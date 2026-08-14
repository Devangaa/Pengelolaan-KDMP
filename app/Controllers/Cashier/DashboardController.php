<?php

namespace App\Controllers\Cashier;

use App\Controllers\BaseController;
use Config\AppConstants;
use CodeIgniter\HTTP\ResponseInterface;

class DashboardController extends BaseController
{
    public function index()
    {
        $session = session();
        $userId = $session->get('id');

        if (!$userId) {
            return redirect()->to(base_url('login'))->with('error', AppConstants::MSG_LOGIN_REQUIRED);
        }

        // Validate authorization
        if (!authorize_user_role(AppConstants::ROLE_KASIR)) {
            log_transaction('warning', 'Unauthorized cashier dashboard access');
            return redirect()->to(base_url('/'))->with('error', AppConstants::MSG_UNAUTHORIZED);
        }

        $transactionModel = new \App\Models\TransactionModel();
        $shiftModel = new \App\Models\CashierShiftModel();
        $today = date('Y-m-d');

        $totalOmset = $transactionModel->getDailyTotal($today, $userId);
        $cashTotal = $transactionModel->getDailyTotalByPaymentType($today, AppConstants::PAYMENT_TYPE_CASH, $userId);
        $nonCashTotal = $transactionModel->getDailyTotalByPaymentType($today, AppConstants::PAYMENT_TYPE_NON_CASH, $userId);
        $transaksiSayaHariIni = $transactionModel->getDailyCount($today, $userId);
        $transactionsToday = $transactionModel->getTodayTransactionsByUser($userId, $today, 10);
        $shiftData = $shiftModel->getCurrentShiftDataByUser($userId);

        $data = [
            'title' => page_title('Dashboard'),
            'cashier' => current_cashier_data(),
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
