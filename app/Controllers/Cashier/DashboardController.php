<?php

namespace App\Controllers\Cashier;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class DashboardController extends BaseController
{
    public function index()
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
