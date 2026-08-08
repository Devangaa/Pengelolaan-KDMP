<?php

namespace App\Controllers;

use App\Controllers\BaseController;

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

    private function kasirDashboard()
    {
        $transactionModel = new \App\Models\TransactionModel();
        $shiftModel = new \App\Models\CashierShiftModel();
        $userModel = new \App\Models\UserModel();
        $session = session();
        $cashier = [];
        $userId = $session->get('id');
    
        if ($userId) {
            $user = $userModel->find($userId);
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
    
        $today = date('Y-m-d');

        $totalOmset = $transactionModel->getDailyTotal($today, $userId);
        $cashTotal = $transactionModel->getDailyTotalByPaymentType($today, 'tunai', $userId);
        $nonCashTotal = $transactionModel->getDailyTotalByPaymentType($today, 'nontunai', $userId);

        $transaksiSayaHariIni = $transactionModel->getDailyCount($today, $userId);
        $transactionsToday = $transactionModel->getTodayTransactionsByUser($userId, $today, 10);

        $shiftData = $shiftModel->getCurrentShiftDataByUser($userId);

        $data = [
            'cashier' => $cashier,
            'shiftActive' => $shiftData['shiftActive'],
            'shiftStartedAt' => $shiftData['shiftStartedAt'],
            'openingBalance' => $shiftData['openingBalance'],
            'totalOmset' => $totalOmset,
            'receiptCount' => $transaksiSayaHariIni,
            'cashTotal' => $cashTotal,
            'nonCashTotal' => $nonCashTotal,
            'transactionsToday' => $transactionsToday,
        ];
    
        return view('cashier/dashboard', $data);
    }
}