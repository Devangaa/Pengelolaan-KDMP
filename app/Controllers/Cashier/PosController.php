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
        try {
            $session = session();
            $userId = $session->get('id');

            if (!$userId) {
                return html_error_response(AppConstants::MSG_LOGIN_REQUIRED, base_url('login'));
            }

            // Validate authorization
            if (!authorize_user_role(AppConstants::ROLE_KASIR)) {
                log_transaction('warning', 'Unauthorized startShift attempt', ['userId' => $userId]);
                return html_error_response(AppConstants::MSG_UNAUTHORIZED, base_url('dasbor'));
            }

            // Validate amount
            $amountValidation = validate_amount($this->request->getPost('modal_awal'), 0);
            if (!$amountValidation['valid']) {
                return html_error_response($amountValidation['error'], base_url('pos'));
            }
            $modalAwal = $amountValidation['value'];

            $shiftModel = new CashierShiftModel();
            $latestShift = $shiftModel->getLatestShiftByUser((string) $userId);

            // Check if shift already active (409 Conflict logic)
            if ($latestShift && empty($latestShift['closed_at'])) {
                return html_error_response(AppConstants::MSG_SHIFT_ALREADY_ACTIVE, base_url('pos'));
            }

            $inserted = $shiftModel->insert([
                'user_id' => (string) $userId,
                'status' => AppConstants::SHIFT_STATUS_OPEN,
                'modal_awal' => $modalAwal,
                'uang_fisik' => $modalAwal,
                'opened_at' => date('Y-m-d H:i:s'),
                'closed_at' => null,
            ]);

            if (!$inserted) {
                throw new DatabaseException('Failed to insert shift record');
            }

            log_transaction('info', 'Shift started', ['modalAwal' => $modalAwal]);
            return html_success_response('Shift kasir berhasil dimulai.', base_url('pos'));
        } catch (DatabaseException $e) {
            log_transaction('error', 'Shift start failed', ['error' => $e->getMessage()]);
            return html_error_response(AppConstants::MSG_TRANSACTION_FAILED, base_url('pos'));
        } catch (\Exception $e) {
            log_transaction('error', 'Shift start exception', ['error' => $e->getMessage()]);
            return html_error_response('Terjadi kesalahan yang tidak terduga.', base_url('pos'));
        }
    }

    public function closeShift()
    {
        try {
            $session = session();
            $userId = $session->get('id');

            if (!$userId) {
                return html_error_response(AppConstants::MSG_LOGIN_REQUIRED, base_url('login'));
            }

            // Validate authorization
            if (!authorize_user_role(AppConstants::ROLE_KASIR)) {
                log_transaction('warning', 'Unauthorized closeShift attempt', ['userId' => $userId]);
                return html_error_response(AppConstants::MSG_UNAUTHORIZED, base_url('dasbor'));
            }

            // Validate amount
            $amountValidation = validate_amount($this->request->getPost('uang_fisik'), 0);
            if (!$amountValidation['valid']) {
                return html_error_response($amountValidation['error'], base_url('pos'));
            }
            $uangFisik = $amountValidation['value'];

            $shiftModel = new CashierShiftModel();
            $shift = $shiftModel->getLatestShiftByUser((string) $userId);

            if (!$shift) {
                return html_error_response('Belum ada sesi yang dimulai.', base_url('pos'));
            }

            if (!empty($shift['closed_at'])) {
                return html_error_response('Sesi kasir sudah ditutup sebelumnya.', base_url('pos'));
            }

            $updated = $shiftModel->update($shift['id'], [
                'status' => AppConstants::SHIFT_STATUS_CLOSED,
                'uang_fisik' => $uangFisik,
                'closed_at' => date('Y-m-d H:i:s'),
            ]);

            if ($updated === false) {
                throw new DatabaseException('Failed to update shift record');
            }

            log_transaction('info', 'Shift closed', ['shiftId' => $shift['id'], 'uangFisik' => $uangFisik]);
            return html_success_response('Sesi kasir berhasil ditutup.', base_url('dasbor'));
        } catch (DatabaseException $e) {
            log_transaction('error', 'Shift close failed', ['error' => $e->getMessage()]);
            return html_error_response(AppConstants::MSG_TRANSACTION_FAILED, base_url('pos'));
        } catch (\Exception $e) {
            log_transaction('error', 'Shift close exception', ['error' => $e->getMessage()]);
            return html_error_response('Terjadi kesalahan yang tidak terduga.', base_url('pos'));
        }
    }

    public function checkout()
    {
        try {
            $session = session();
            $userId = $session->get('id');

            if (!$userId) {
                return api_unauthorized(AppConstants::MSG_LOGIN_REQUIRED);
            }

            // Validate authorization
            if (!authorize_user_role(AppConstants::ROLE_KASIR)) {
                log_transaction('warning', 'Unauthorized checkout attempt');
                return api_forbidden(AppConstants::MSG_UNAUTHORIZED);
            }

            // Initialize models
            $shiftModel = new CashierShiftModel();
            $activeShift = $shiftModel->getLatestShiftByUser((string) $userId);

            if (!$activeShift || !empty($activeShift['closed_at'])) {
                return api_conflict(AppConstants::MSG_SHIFT_NOT_ACTIVE);
            }

            // Parse and validate request payload
            $payload = $this->request->getJSON(true);
            $cart = $payload['cart'] ?? [];
            $cashGiven = $payload['cash'] ?? null;
            $paymentTypeInput = $payload['payment_type'] ?? null;

            // Validate cart structure
            $cartValidation = validate_cart($cart);
            if (!$cartValidation['valid']) {
                return api_validation_error([], $cartValidation['error']);
            }

            // Validate payment type
            $paymentValidation = validate_payment_type($paymentTypeInput);
            if (!$paymentValidation['valid']) {
                return api_validation_error([], $paymentValidation['error']);
            }
            $paymentType = $paymentValidation['value'];

            // Validate cash amount for cash payment
            if ($paymentType === AppConstants::PAYMENT_TYPE_CASH) {
                $cashValidation = validate_amount($cashGiven, 0);
                if (!$cashValidation['valid']) {
                    return api_validation_error([], $cashValidation['error']);
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
                    return api_validation_error([], AppConstants::MSG_PRODUCT_NOT_FOUND);
                }

                if ((int) $product['stock'] < $quantity) {
                    log_transaction('warning', 'Insufficient stock', [
                        'productId' => $productId,
                        'required' => $quantity,
                        'available' => $product['stock'],
                    ]);
                    return api_validation_error([], AppConstants::MSG_INSUFFICIENT_STOCK . ' (' . $product['name'] . ')');
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
                return api_validation_error([], AppConstants::MSG_INSUFFICIENT_CASH);
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
                throw new \RuntimeException('Failed to save transaction data');
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
                    throw new \RuntimeException('Failed to save transaction detail');
                }

                // Update stock
                $product = $productModel->find($item['product_id']);
                if (!$product) {
                    throw new \RuntimeException('Product not found during stock update');
                }

                $newStock = max(0, (int) $product['stock'] - $item['qty']);
                $updateResult = $productModel->update($item['product_id'], [
                    'stock' => $newStock,
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);

                if ($updateResult === false) {
                    throw new \RuntimeException('Failed to update product stock');
                }
            }

            // COMMIT TRANSACTION
            $database->transComplete();

            if ($database->transStatus() === false) {
                throw new \RuntimeException('Database transaction failed');
            }

            log_transaction('info', 'Checkout successful', [
                'transactionId' => $transactionNumber,
                'total' => $total,
                'paymentType' => $paymentType,
            ]);

            return api_response(true, 'Transaksi berhasil disimpan.', [
                'invoice' => $transactionNumber,
                'total' => $total,
                'pay' => $payAmount,
                'change' => $change,
                'payment_type' => $paymentType,
            ], 201);
        } catch (\Exception $e) {
            // ROLLBACK ON ERROR
            try {
                $database->transRollback();
            } catch (\Exception $rbExc) {
                log_transaction('error', 'Rollback failed', ['error' => $rbExc->getMessage()]);
            }

            log_transaction('error', 'Checkout failed', [
                'error' => $e->getMessage(),
            ]);

            return api_server_error('Terjadi kesalahan saat memproses transaksi. Silakan hubungi administrator.');
        }
    }

    public function searchByBarcode()
    {
        try {
            if (!$this->request->isAJAX()) {
                return api_error('Invalid request method', 405);
            }

            $session = session();
            $userId = $session->get('id');

            if (!$userId) {
                return api_unauthorized(AppConstants::MSG_LOGIN_REQUIRED);
            }

            // Validate authorization
            if (!authorize_user_role(AppConstants::ROLE_KASIR)) {
                log_transaction('warning', 'Unauthorized searchByBarcode attempt');
                return api_forbidden(AppConstants::MSG_UNAUTHORIZED);
            }

            $barcode = trim((string) $this->request->getPost('barcode'));

            if (empty($barcode)) {
                return api_validation_error([], 'Barcode tidak boleh kosong.');
            }

            $productModel = new ProductModel();
            $product = $productModel->getByBarcode($barcode);

            if (!$product) {
                log_transaction('info', 'Barcode not found', ['barcode' => $barcode]);
                return api_not_found('Produk dengan barcode ini tidak ditemukan.');
            }

            if ((int) $product['stock'] <= 0) {
                return api_validation_error([], 'Stok produk sudah habis.');
            }

            log_transaction('info', 'Product found by barcode', ['barcode' => $barcode, 'productId' => $product['id']]);

            return api_response(true, 'Produk ditemukan.', [
                'id' => $product['id'],
                'name' => $product['name'],
                'price' => (int) $product['sell_price'],
                'stock' => (int) $product['stock'],
                'image' => $product['image'],
                'unit' => $product['unit'],
            ]);
        } catch (\Exception $e) {
            log_transaction('error', 'searchByBarcode exception', ['error' => $e->getMessage()]);
            return api_server_error('Terjadi kesalahan saat mencari produk.');
        }
    }
}
