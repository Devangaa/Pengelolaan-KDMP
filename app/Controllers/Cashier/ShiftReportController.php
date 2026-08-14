<?php

namespace App\Controllers\Cashier;

use App\Controllers\BaseController;
use App\Models\CashierShiftModel;
use App\Models\TransactionModel;
use Config\AppConstants;


class ShiftReportController extends BaseController
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
            log_transaction('warning', 'Unauthorized shift report access');
            return redirect()->to(base_url('dasbor'))->with('error', AppConstants::MSG_UNAUTHORIZED);
        }

        $orderBy = $this->request->getGet('orderBy') ?? 'latest';
        $startDate = $this->request->getGet('startDate');
        $endDate = $this->request->getGet('endDate');

        // Validate dates
        if (!empty($startDate)) {
            $startDateValidation = validate_date($startDate);
            if (!$startDateValidation['valid']) {
                return redirect()->back()->with('error', $startDateValidation['error']);
            }
            $startDate = $startDateValidation['value'];
        }

        if (!empty($endDate)) {
            $endDateValidation = validate_date($endDate);
            if (!$endDateValidation['valid']) {
                return redirect()->back()->with('error', $endDateValidation['error']);
            }
            $endDate = $endDateValidation['value'];
        }

        if (!empty($startDate) && empty($endDate)) {
            $endDate = date('Y-m-d');
        }

        $shiftModel = new CashierShiftModel();
        $transactionModel = new TransactionModel();
        $today = date('Y-m-d');

        $latestShift = $shiftModel->getLatestShiftByUser((string) $userId);
        $shifts = $shiftModel->getShiftsByUser((string) $userId, $orderBy, $startDate, $endDate, 20, 'transactions');
        $totalPenjualan = $transactionModel->getDailyTotal($today, (string) $userId);
        $totalTunai = $transactionModel->getDailyTotalByPaymentType($today, AppConstants::PAYMENT_TYPE_CASH, (string) $userId);
        $totalNonTunai = $transactionModel->getDailyTotalByPaymentType($today, AppConstants::PAYMENT_TYPE_NON_CASH, (string) $userId);
        $jumlahTransaksi = $transactionModel->getDailyCount($today, (string) $userId);

        $data = [
            'cashier' => current_cashier_data(),
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
            'title' => page_title('Rekap Shift'),
        ];

        if ($this->request->isAJAX()) {
            return view('cashier/partials/shift_table', $data);
        }

        return view('cashier/shift_report', $data);
    }
}
