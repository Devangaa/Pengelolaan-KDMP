<?php

namespace App\Controllers\Cashier;

use App\Controllers\BaseController;
use App\Models\CashierShiftModel;
use App\Models\ProductModel;
use App\Models\TransactionDetailModel;
use App\Models\TransactionModel;
use Config\AppConstants;
use CodeIgniter\Database\Exceptions\DatabaseException;


class PosController extends BaseController
{
    public function index(): string
    {
        $session = session();
        $userId = $session->get('id');

        $shiftModel = new CashierShiftModel();
        $shiftData = $shiftModel->getCurrentShiftDataByUser((string) $userId);
        $products = (new ProductModel())
            ->where('deleted_at', null)
            ->orderBy('name', 'ASC')
            ->findAll();

        return view('cashier/pos', [
            'cashier' => current_cashier_data(),
            'products' => $products,
            'shiftActive' => $shiftData['shiftActive'],
            'shiftStartedAt' => $shiftData['shiftStartedAt'],
            'openingBalance' => $shiftData['openingBalance'],
            'shift' => $shiftData['latestShift'],
            'title' => page_title('POS Kasir'),
        ]);
    }

    public function startShift()
    {
        $session = session();
        $userId = $session->get('id');

        if (!$userId) {
            return redirect()->to(base_url('login'))->with('error', AppConstants::MSG_LOGIN_REQUIRED);
        }

        // Validate authorization
        if (!authorize_user_role(AppConstants::ROLE_KASIR)) {
            log_transaction('warning', 'Unauthorized startShift attempt', ['userId' => $userId]);
            return redirect()->to(base_url('dasbor'))->with('error', AppConstants::MSG_UNAUTHORIZED);
        }

        $shiftModel = new CashierShiftModel();
        $latestShift = $shiftModel->getLatestShiftByUser((string) $userId);

        if ($latestShift && empty($latestShift['closed_at'])) {
            return redirect()->to(base_url('pos'))->with('error', AppConstants::MSG_SHIFT_ALREADY_ACTIVE);
        }

        // Validate amount
        $amountValidation = validate_amount($this->request->getPost('modal_awal'), 0);
        if (!$amountValidation['valid']) {
            return redirect()->back()->with('error', $amountValidation['error']);
        }

        $modalAwal = $amountValidation['value'];

        try {
            $shiftModel->insert([
                'user_id' => (string) $userId,
                'status' => AppConstants::SHIFT_STATUS_OPEN,
                'modal_awal' => $modalAwal,
                'uang_fisik' => $modalAwal,
                'opened_at' => date('Y-m-d H:i:s'),
                'closed_at' => null,
            ]);

            log_transaction('info', 'Shift started', ['modalAwal' => $modalAwal]);
            return redirect()->to(base_url('pos'))->with('success', 'Shift kasir berhasil dimulai.');
        } catch (DatabaseException $e) {
            log_transaction('error', 'Shift start failed', ['error' => $e->getMessage()]);
            return redirect()->to(base_url('pos'))->with('error', AppConstants::MSG_TRANSACTION_FAILED);
        }
    }

    public function closeShift()
    {
        $session = session();
        $userId = $session->get('id');

        if (!$userId) {
            return redirect()->to(base_url('login'))->with('error', AppConstants::MSG_LOGIN_REQUIRED);
        }

        // Validate authorization
        if (!authorize_user_role(AppConstants::ROLE_KASIR)) {
            log_transaction('warning', 'Unauthorized closeShift attempt', ['userId' => $userId]);
            return redirect()->to(base_url('dasbor'))->with('error', AppConstants::MSG_UNAUTHORIZED);
        }

        $shiftModel = new CashierShiftModel();
        $shift = $shiftModel->getLatestShiftByUser((string) $userId);

        if (!$shift) {
            return redirect()->to(base_url('pos'))->with('error', 'Belum ada sesi yang dimulai.');
        }

        if (!empty($shift['closed_at'])) {
            return redirect()->to(base_url('pos'))->with('error', 'Sesi kasir sudah ditutup sebelumnya.');
        }

        // Validate amount
        $amountValidation = validate_amount($this->request->getPost('uang_fisik'), 0);
        if (!$amountValidation['valid']) {
            return redirect()->back()->with('error', $amountValidation['error']);
        }

        $uangFisik = $amountValidation['value'];

        try {
            $shiftModel->update($shift['id'], [
                'status' => AppConstants::SHIFT_STATUS_CLOSED,
                'uang_fisik' => $uangFisik,
                'closed_at' => date('Y-m-d H:i:s'),
            ]);

            log_transaction('info', 'Shift closed', ['shiftId' => $shift['id'], 'uangFisik' => $uangFisik]);
            return redirect()->to(base_url('dasbor'))->with('success', 'Sesi kasir berhasil ditutup.');
        } catch (DatabaseException $e) {
            log_transaction('error', 'Shift close failed', ['error' => $e->getMessage()]);
            return redirect()->to(base_url('pos'))->with('error', AppConstants::MSG_TRANSACTION_FAILED);
        }
    }

    public function checkout()
    {
        $session = session();
        $userId = $session->get('id');

        if (!$userId) {
            return $this->response->setJSON([
                'success' => false,
                'message' => AppConstants::MSG_LOGIN_REQUIRED,
            ])->setStatusCode(401);
        }

        // Validate authorization
        if (!authorize_user_role(AppConstants::ROLE_KASIR)) {
            log_transaction('warning', 'Unauthorized checkout attempt');
            return $this->response->setJSON([
                'success' => false,
                'message' => AppConstants::MSG_UNAUTHORIZED,
            ])->setStatusCode(403);
        }

        // Initialize models
        $shiftModel = new CashierShiftModel();
        $activeShift = $shiftModel->getLatestShiftByUser((string) $userId);

        if (!$activeShift || !empty($activeShift['closed_at'])) {
            return $this->response->setJSON([
                'success' => false,
                'message' => AppConstants::MSG_SHIFT_NOT_ACTIVE,
            ])->setStatusCode(403);
        }

        // Parse and validate request payload
        $payload = $this->request->getJSON(true);
        $cart = $payload['cart'] ?? [];
        $cashGiven = $payload['cash'] ?? null;
        $paymentTypeInput = $payload['payment_type'] ?? null;

        // Validate cart structure
        $cartValidation = validate_cart($cart);
        if (!$cartValidation['valid']) {
            return $this->response->setJSON([
                'success' => false,
                'message' => $cartValidation['error'],
            ])->setStatusCode(422);
        }

        // Validate payment type
        $paymentValidation = validate_payment_type($paymentTypeInput);
        if (!$paymentValidation['valid']) {
            return $this->response->setJSON([
                'success' => false,
                'message' => $paymentValidation['error'],
            ])->setStatusCode(422);
        }
        $paymentType = $paymentValidation['value'];

        // Validate cash amount for cash payment
        if ($paymentType === AppConstants::PAYMENT_TYPE_CASH) {
            $cashValidation = validate_amount($cashGiven, 0);
            if (!$cashValidation['valid']) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => $cashValidation['error'],
                ])->setStatusCode(422);
            }
            $cashGiven = $cashValidation['value'];
        } else {
            $cashGiven = 0;
        }

        // Initialize models
        $productModel = new ProductModel();
        $transactionModel = new TransactionModel();
        $transactionDetailModel = new TransactionDetailModel();

        $total = 0;
        $preparedItems = [];

        // Validate all cart items and calculate total
        foreach ($cart as $item) {
            $productId = $item['id'] ?? null;
            $quantity = max(1, (int) ($item['qty'] ?? 0));
            $product = $productModel->find($productId);

            if (!$product) {
                log_transaction('warning', 'Product not found', ['productId' => $productId]);
                return $this->response->setJSON([
                    'success' => false,
                    'message' => AppConstants::MSG_PRODUCT_NOT_FOUND,
                ])->setStatusCode(422);
            }

            if ((int) $product['stock'] < $quantity) {
                log_transaction('warning', 'Insufficient stock', [
                    'productId' => $productId,
                    'required' => $quantity,
                    'available' => $product['stock'],
                ]);
                return $this->response->setJSON([
                    'success' => false,
                    'message' => AppConstants::MSG_INSUFFICIENT_STOCK . ' (' . $product['name'] . ')',
                ])->setStatusCode(422);
            }

            $price = (int) ($product['sell_price'] ?? 0);
            $subtotal = $price * $quantity;
            $total += $subtotal;

            $preparedItems[] = [
                'product_id' => $productId,
                'qty' => $quantity,
                'price' => $price,
                'subtotal' => $subtotal,
            ];
        }

        // Validate cash sufficiency for cash payment
        if ($paymentType === AppConstants::PAYMENT_TYPE_CASH && $cashGiven < $total) {
            return $this->response->setJSON([
                'success' => false,
                'message' => AppConstants::MSG_INSUFFICIENT_CASH,
            ])->setStatusCode(422);
        }

        // Generate transaction number
        $countToday = $transactionModel
            ->where('DATE(created_at)', date('Y-m-d'))
            ->countAllResults();

        $transactionNumber = 'TRX-' . date('Ymd') . '-' . str_pad((string) ($countToday + 1), 4, '0', STR_PAD_LEFT);
        $payAmount = $paymentType === AppConstants::PAYMENT_TYPE_CASH ? (int) round($cashGiven) : $total;
        $change = max(0, $payAmount - $total);

        // START DATABASE TRANSACTION
        $database = \Config\Database::connect();
        $database->transStart();

        try {
            // Insert main transaction
            $transactionInsert = [
                'transaction_id' => $transactionNumber,
                'user_id' => (string) $userId,
                'member_id' => null,
                'total' => $total,
                'pay' => $payAmount,
                'change' => $change,
                'payment_type' => $paymentType,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ];

            $inserted = $transactionModel->insert($transactionInsert);
            if ($inserted === false) {
                throw new \RuntimeException('Gagal menyimpan data transaksi.');
            }

            // Insert transaction details dan update stock
            foreach ($preparedItems as $item) {
                $detailInserted = $transactionDetailModel->insert([
                    'transaction_id' => $inserted,
                    'product_id' => $item['product_id'],
                    'price' => $item['price'],
                    'quantity' => $item['qty'],
                    'subtotal' => $item['subtotal'],
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);

                if ($detailInserted === false) {
                    throw new \RuntimeException('Gagal menyimpan detail transaksi.');
                }

                // Update stock
                $product = $productModel->find($item['product_id']);
                if (!$product) {
                    throw new \RuntimeException('Produk tidak ditemukan saat update stok.');
                }

                $newStock = max(0, (int) $product['stock'] - $item['qty']);
                $updateResult = $productModel->update($item['product_id'], [
                    'stock' => $newStock,
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);

                if ($updateResult === false) {
                    throw new \RuntimeException('Gagal update stok produk.');
                }
            }

            // COMMIT TRANSACTION
            $database->transComplete();

            if ($database->transStatus() === false) {
                throw new \RuntimeException('Transaksi database gagal.');
            }

            log_transaction('info', 'Checkout successful', [
                'transactionId' => $transactionNumber,
                'total' => $total,
                'paymentType' => $paymentType,
            ]);

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Transaksi berhasil disimpan.',
                'invoice' => $transactionNumber,
                'total' => $total,
                'pay' => $payAmount,
                'change' => $change,
                'payment_type' => $paymentType,
            ]);
        } catch (\Exception $e) {
            // ROLLBACK ON ERROR
            $database->transRollback();

            log_transaction('error', 'Checkout failed', [
                'error' => $e->getMessage(),
                'total' => $total,
            ]);

            return $this->response->setJSON([
                'success' => false,
                'message' => AppConstants::MSG_TRANSACTION_FAILED,
            ])->setStatusCode(500);
        }
    }

    public function searchByBarcode()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Invalid request method.',
            ])->setStatusCode(405);
        }

        $session = session();
        $userId = $session->get('id');

        if (!$userId) {
            return $this->response->setJSON([
                'success' => false,
                'message' => AppConstants::MSG_LOGIN_REQUIRED,
            ])->setStatusCode(401);
        }

        // Validate authorization
        if (!authorize_user_role(AppConstants::ROLE_KASIR)) {
            log_transaction('warning', 'Unauthorized searchByBarcode attempt');
            return $this->response->setJSON([
                'success' => false,
                'message' => AppConstants::MSG_UNAUTHORIZED,
            ])->setStatusCode(403);
        }

        $barcode = trim((string) $this->request->getPost('barcode'));

        if (empty($barcode)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Barcode tidak boleh kosong.',
            ])->setStatusCode(422);
        }

        try {
            $productModel = new ProductModel();
            $product = $productModel->getByBarcode($barcode);

            if (!$product) {
                log_transaction('info', 'Barcode not found', ['barcode' => $barcode]);
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Produk dengan barcode ini tidak ditemukan.',
                ])->setStatusCode(404);
            }

            if ((int) $product['stock'] <= 0) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Stok produk sudah habis.',
                ])->setStatusCode(422);
            }

            log_transaction('info', 'Product found by barcode', ['barcode' => $barcode, 'productId' => $product['id']]);

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Produk ditemukan.',
                'data' => [
                    'id' => $product['id'],
                    'name' => $product['name'],
                    'price' => (int) $product['sell_price'],
                    'stock' => (int) $product['stock'],
                    'image' => $product['image'],
                    'unit' => $product['unit'],
                ],
            ]);
        } catch (\Exception $e) {
            log_transaction('error', 'searchByBarcode exception', ['error' => $e->getMessage()]);
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mencari produk.',
            ])->setStatusCode(500);
        }
    }
}
