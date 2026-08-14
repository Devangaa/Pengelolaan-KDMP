<?php

namespace App\Controllers\Cashier;

use App\Controllers\BaseController;
use App\Models\CashierShiftModel;
use App\Models\ProductModel;
use App\Models\TransactionDetailModel;
use App\Models\TransactionModel;
use App\Models\UserModel;

class PosController extends BaseController
{
    public function index(): string
    {
        $session = session();
        $userId = $session->get('id');

        $cashier = [];
        if ($userId) {
            $user = (new UserModel())->find($userId);
            if ($user) {
                $cashier = [
                    'name' => $user->name ?? $session->get('name'),
                    'email' => $user->email ?? $session->get('email'),
                    'avatar' => $user->avatar ?? null,
                ];
            }
        }

        if (empty($cashier)) {
            $cashier = [
                'name' => $session->get('name'),
                'email' => $session->get('email'),
                'avatar' => null,
            ];
        }

        $shiftModel = new CashierShiftModel();
        $shiftData = $shiftModel->getCurrentShiftDataByUser((string) $userId);
        $products = (new ProductModel())
            ->where('deleted_at', null)
            ->orderBy('name', 'ASC')
            ->findAll();

        return view('cashier/pos', [
            'cashier' => $cashier,
            'products' => $products,
            'shiftActive' => $shiftData['shiftActive'],
            'shiftStartedAt' => $shiftData['shiftStartedAt'],
            'openingBalance' => $shiftData['openingBalance'],
            'shift' => $shiftData['latestShift'],
            'title' => 'POS Kasir',
        ]);
    }

    public function startShift()
    {
        $session = session();
        $userId = $session->get('id');

        if (!$userId) {
            return redirect()->to(base_url('login'))->with('error', 'Silakan login terlebih dahulu.');
        }

        $shiftModel = new CashierShiftModel();
        $latestShift = $shiftModel->getLatestShiftByUser((string) $userId);

        if ($latestShift && empty($latestShift['closed_at'])) {
            return redirect()->to(base_url('pos'))->with('error', 'Shift kasir masih aktif. Silakan lanjutkan transaksi di POS.');
        }

        $modalAwal = (float) ($this->request->getPost('modal_awal') ?? 0);

        if ($modalAwal < 0) {
            return redirect()->to(base_url('pos'))->with('error', 'Uang awal kasir tidak boleh negatif.');
        }

        $openedAt = date('Y-m-d H:i:s');

        $shiftModel->insert([
            'user_id' => (string) $userId,
            'status' => 'open',
            'modal_awal' => $modalAwal,
            'uang_fisik' => $modalAwal,
            'opened_at' => $openedAt,
            'closed_at' => null,
        ]);

        return redirect()->to(base_url('pos'))->with('success', 'Shift kasir berhasil dimulai.');
    }

    public function closeShift()
    {
        $session = session();
        $userId = $session->get('id');

        if (!$userId) {
            return redirect()->to(base_url('login'))->with('error', 'Silakan login terlebih dahulu.');
        }

        $shiftModel = new CashierShiftModel();
        $shift = $shiftModel->getLatestShiftByUser((string) $userId);

        if (!$shift) {
            return redirect()->to(base_url('pos'))->with('error', 'Belum ada sesi yang dimulai.');
        }

        if (!empty($shift['closed_at'])) {
            return redirect()->to(base_url('pos'))->with('error', 'Sesi kasir sudah ditutup sebelumnya.');
        }

        $uangFisik = (float) ($this->request->getPost('uang_fisik') ?? 0);

        $shiftModel->update($shift['id'], [
            'status' => 'closed',
            'uang_fisik' => $uangFisik,
            'closed_at' => date('Y-m-d H:i:s'),
        ]);

        return redirect()->to(base_url('dasbor'))->with('success', 'Sesi kasir berhasil ditutup.');
    }

    public function checkout()
    {
        $session = session();
        $userId = $session->get('id');

        if (!$userId) {
            return $this->response->setJSON(['success' => false, 'message' => 'Silakan login terlebih dahulu.'])->setStatusCode(401);
        }

        $shiftModel = new CashierShiftModel();
        $activeShift = $shiftModel->getLatestShiftByUser((string) $userId);

        if (!$activeShift || !empty($activeShift['closed_at'])) {
            return $this->response->setJSON(['success' => false, 'message' => 'Sesi kasir belum aktif. Silakan mulai sesi terlebih dahulu.'])->setStatusCode(403);
        }

        $payload = $this->request->getJSON(true);
        $cart = $payload['cart'] ?? [];
        $paymentType = strtolower((string) ($payload['payment_type'] ?? 'tunai'));
        $cashGiven = (float) ($payload['cash'] ?? 0);

        if (!is_array($cart) || empty($cart)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Keranjang masih kosong.'])->setStatusCode(422);
        }

        if (!in_array($paymentType, ['tunai', 'nontunai'], true)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Metode pembayaran tidak valid.'])->setStatusCode(422);
        }

        $productModel = new ProductModel();
        $transactionModel = new TransactionModel();
        $transactionDetailModel = new TransactionDetailModel();

        $total = 0;
        $preparedItems = [];

        foreach ($cart as $item) {
            $productId = $item['id'] ?? null;
            $quantity = max(1, (int) ($item['qty'] ?? 0));
            $product = $productModel->find($productId);

            if (!$product) {
                return $this->response->setJSON(['success' => false, 'message' => 'Ada produk yang tidak tersedia di stok.'])->setStatusCode(422);
            }

            if ((int) $product['stock'] < $quantity) {
                return $this->response->setJSON(['success' => false, 'message' => 'Stok produk ' . $product['name'] . ' tidak mencukupi.'])->setStatusCode(422);
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

        if ($paymentType === 'tunai' && $cashGiven < $total) {
            return $this->response->setJSON(['success' => false, 'message' => 'Uang pembayaran customer kurang dari total transaksi.'])->setStatusCode(422);
        }

        $countToday = $transactionModel
            ->where('DATE(created_at)', date('Y-m-d'))
            ->countAllResults();

        $transactionNumber = 'TRX-' . date('Ymd') . '-' . str_pad((string) ($countToday + 1), 4, '0', STR_PAD_LEFT);
        $payAmount = $paymentType === 'tunai' ? (int) round($cashGiven) : $total;
        $change = max(0, $payAmount - $total);

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
            return $this->response->setJSON(['success' => false, 'message' => 'Gagal menyimpan transaksi.'])->setStatusCode(500);
        }

        foreach ($preparedItems as $item) {
            $transactionDetailModel->insert([
                'transaction_id' => $inserted,
                'product_id' => $item['product_id'],
                'price' => $item['price'],
                'quantity' => $item['qty'],
                'subtotal' => $item['subtotal'],
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);

            $product = $productModel->find($item['product_id']);
            if ($product) {
                $productModel->update($item['product_id'], [
                    'stock' => max(0, (int) $product['stock'] - $item['qty']),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
            }
        }

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Transaksi berhasil disimpan.',
            'invoice' => $transactionNumber,
            'total' => $total,
            'pay' => $payAmount,
            'change' => $change,
            'payment_type' => $paymentType,
        ]);
    }

    public function shiftReport()
    {
        $session = session();
        $userId = $session->get('id');

        if (!$userId) {
            return redirect()->to(base_url('login'))->with('error', 'Silakan login terlebih dahulu.');
        }

        $orderBy = $this->request->getGet('orderBy') ?? 'latest';
        $startDate = $this->request->getGet('startDate');
        $endDate = $this->request->getGet('endDate');

        if (!empty($startDate) && empty($endDate)) {
            $endDate = date('Y-m-d');
        }

        $shiftModel = new CashierShiftModel();
        $transactionModel = new TransactionModel();
        $today = date('Y-m-d');

        $latestShift = $shiftModel->getLatestShiftByUser((string) $userId);
        $shifts = $shiftModel->getShiftsByUser((string) $userId, $orderBy, $startDate, $endDate, 20, 'transactions');
        $totalPenjualan = $transactionModel->getDailyTotal($today, (string) $userId);
        $totalTunai = $transactionModel->getDailyTotalByPaymentType($today, 'tunai', (string) $userId);
        $totalNonTunai = $transactionModel->getDailyTotalByPaymentType($today, 'nontunai', (string) $userId);
        $jumlahTransaksi = $transactionModel->getDailyCount($today, (string) $userId);

        $data = [
            'cashier' => [
                'name' => $session->get('name'),
                'email' => $session->get('email'),
            ],
            'shift' => $latestShift,
            'shifts' => $shifts,
            'todaySales' => $totalPenjualan,
            'cashSales' => $totalTunai,
            'nonCashSales' => $totalNonTunai,
            'transactionCount' => $jumlahTransaksi,
            'orderBy' => $orderBy,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'pager' => $shiftModel->pager,
            'title' => 'Rekap Shift Kasir',
        ];

        if ($this->request->isAJAX()) {
            return view('cashier/partials/shift_table', $data);
        }

        return view('cashier/shift_report', $data);
    }

    public function searchByBarcode()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid request method.'])->setStatusCode(405);
        }

        $session = session();
        $userId = $session->get('id');

        if (!$userId) {
            return $this->response->setJSON(['success' => false, 'message' => 'Silakan login terlebih dahulu.'])->setStatusCode(401);
        }

        $barcode = trim((string) $this->request->getPost('barcode'));

        if (empty($barcode)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Barcode tidak boleh kosong.'])->setStatusCode(422);
        }

        $productModel = new ProductModel();
        
        // Debug: Log barcode yang dicari
        log_message('info', 'Searching barcode: ' . $barcode);
        
        $product = $productModel->getByBarcode($barcode);
        
        // Debug: Log hasil pencarian
        log_message('info', 'Product found: ' . json_encode($product));

        if (!$product) {
            // Tambahan debugging: cek apakah ada di database tapi deleted
            $allProducts = $productModel->builder()
                ->where('barcode', $barcode)
                ->get()
                ->getResultArray();
            
            log_message('warning', 'Barcode ' . $barcode . ' not found (even in deleted). All matches: ' . json_encode($allProducts));
            
            return $this->response->setJSON(['success' => false, 'message' => 'Produk dengan barcode ini tidak ditemukan.'])->setStatusCode(404);
        }

        if ((int) $product['stock'] <= 0) {
            return $this->response->setJSON(['success' => false, 'message' => 'Stok produk sudah habis.'])->setStatusCode(422);
        }

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
    }
}
