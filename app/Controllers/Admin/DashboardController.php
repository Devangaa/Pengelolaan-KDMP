<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class DashboardController extends BaseController
{
    public function index()
    {
        $productModel = new \App\Models\ProductModel();
        $transactionModel = new \App\Models\TransactionModel();
        $memberModel = new \App\Models\MemberModel();
        $userModel = new \App\Models\UserModel();

        $session = session();
        $admin = [];
        $userId = $session->get('id');
        if ($userId) {
            $user = $userModel->find($userId);
            if ($user) {
                $admin = [
                    'name' => $user->name ?? $session->get('name'),
                    'email' => $user->email ?? $session->get('email'),
                    'avatar' => $user->avatar ?? null,
                ];
            }
        }
        if (empty($admin)) {
            $admin = [
                'name' => $session->get('name'),
                'email' => $session->get('email'),
                'avatar' => null,
            ];
        }

        $today = date('Y-m-d');

        $penjualanHariIni = $transactionModel->getDailyTotal($today);
        $totalTransaksiHariIni = $transactionModel->getDailyCount($today);
        $stokMenipisList = $productModel->getLowStockProducts();
        $stokMenipisCount = count($stokMenipisList);
        $totalAnggota = $memberModel->countActiveMembers();
        $transaksiTerbaru = $transactionModel->getTodayTransactionsWithCashier($today, 8);

        $data = [
            'title' => page_title('Dashboard'),
            'penjualanHariIni' => $penjualanHariIni,
            'totalTransaksiHariIni' => $totalTransaksiHariIni,
            'stokMenipisCount' => $stokMenipisCount,
            'listStokMenipis' => $stokMenipisList,
            'totalAnggota' => $totalAnggota,
            'transaksiTerbaru' => $transaksiTerbaru,
            'admin' => $admin,
        ];

        return view('admin/dashboard', $data);
    }
}
