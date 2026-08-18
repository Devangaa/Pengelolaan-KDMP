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
        try {
            $session = session();
            $userId = $session->get('id');

            if (!$userId) {
                return html_error_response(AppConstants::MSG_LOGIN_REQUIRED, base_url('login'));
            }

            // Validate authorization
            if (!authorize_user_role(AppConstants::ROLE_KASIR)) {
                log_transaction('warning', 'Unauthorized shift report access');
                return html_error_response(AppConstants::MSG_UNAUTHORIZED, base_url('dasbor'));
            }

            $orderBy = $this->request->getGet('orderBy') ?? 'latest';
            $startDate = $this->request->getGet('startDate');
            $endDate = $this->request->getGet('endDate');

            // Validate dates
            if (!empty($startDate)) {
                $startDateValidation = validate_date($startDate);
                if (!$startDateValidation['valid']) {
                    return html_error_response($startDateValidation['error'], base_url('shift-report'));
                }
                $startDate = $startDateValidation['value'];
            }

            if (!empty($endDate)) {
                $endDateValidation = validate_date($endDate);
                if (!$endDateValidation['valid']) {
                    return html_error_response($endDateValidation['error'], base_url('shift-report'));
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
        } catch (\Exception $e) {
            log_transaction('error', 'Shift report failed', ['error' => $e->getMessage()]);
            return html_error_response('Terjadi kesalahan saat memuat laporan shift.', base_url('dasbor'));
        }
    }
}
