<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class DashboardController extends BaseController
{
    public function index()
    {
        $session = session();
        $role    = $session->get('role');

        switch ($role) {
            case 'admin':
                return $this->adminDashboard();

            case 'kasir':
                return $this->kasirDashboard();

            default:
                return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu.');
        }
    }

    private function adminDashboard()
    {
        $productModel = new \App\Models\ProductModel();
        $transactionModel = new \App\Models\TransactionModel();
        $memberModel = new \App\Models\MemberModel();

        $today = date('Y-m-d');

        // Total sales today
        $row = $transactionModel->select('IFNULL(SUM(total), 0) as total')->where('DATE(created_at)', $today)->first();
        $penjualanHariIni = isset($row['total']) ? (int) $row['total'] : 0;

        // Total transactions today
        $totalTransaksiHariIni = $transactionModel->where('DATE(created_at)', $today)->countAllResults();

        // Products running low (threshold: 5)
        $stokMenipisList = $productModel
            ->select('products.id, products.name as nama_produk, product_categories.name as kategori, products.stock as stok')
            ->join('product_categories', 'product_categories.id = products.category_id', 'left')
            ->where('products.stock <', 5)
            ->orderBy('products.stock', 'ASC')
            ->findAll(10);
        $stokMenipisCount = is_array($stokMenipisList) ? count($stokMenipisList) : 0;

        // Total members
        $totalAnggota = $memberModel->where('deleted_at', null)->countAllResults();

        // Recent transactions today
        $transaksiTerbaru = $transactionModel
            ->select('transactions.transaction_id as no_struk, users.name as nama_kasir, transactions.total as total_harga, transactions.created_at')
            ->join('users', 'users.id = transactions.user_id', 'left')
            ->where('DATE(transactions.created_at)', $today)
            ->orderBy('transactions.created_at', 'DESC')
            ->findAll(8);

        $data = [
            'penjualanHariIni' => $penjualanHariIni,
            'totalTransaksiHariIni' => $totalTransaksiHariIni,
            'stokMenipisCount' => $stokMenipisCount,
            'listStokMenipis' => $stokMenipisList,
            'totalAnggota' => $totalAnggota,
            'transaksiTerbaru' => $transaksiTerbaru,
        ];

        return view('admin/dashboard', $data);
    }

    private function kasirDashboard()
    {
        $data = [
            'transaksiSayaHariIni' => 0,
        ];

        return view('cashier/dashboard', $data);
    }
}