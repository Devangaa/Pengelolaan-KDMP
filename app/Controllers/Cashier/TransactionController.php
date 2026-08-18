<?php

namespace App\Controllers\Cashier;

use App\Controllers\BaseController;
use App\Models\TransactionDetailModel;
use App\Models\TransactionModel;
use Config\AppConstants;
use Dompdf\Dompdf;
use Dompdf\Options;

class TransactionController extends BaseController
{
    public function index()
    {
        try {
            $session = session();
            $userId = $session->get('id');

            if (!$userId) {
                return redirect()->to(base_url('login'))->with('error', AppConstants::MSG_LOGIN_REQUIRED);
            }

            // Validate authorization
            if (!authorize_user_role(AppConstants::ROLE_KASIR)) {
                log_transaction('warning', 'Unauthorized transaction history access');
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

            $transactionModel = new TransactionModel();
            $transactions = $transactionModel->getTransactionsByUser(
                $userId,
                $orderBy,
                $startDate,
                $endDate,
                20,
                'transactions'
            );

            $data = [
                'title' => page_title('Riwayat Transaksi'),
                'cashier' => current_cashier_data(),
                'transactions' => $transactions,
                'orderBy' => $orderBy,
                'startDate' => $startDate,
                'endDate' => $endDate,
                'pager' => $transactionModel->pager,
            ];

            if ($this->request->isAJAX()) {
                return view('cashier/partials/transaction_table', $data);
            }

            return view('cashier/transactions', $data);
        } catch (\Throwable $e) {
            log_message('error', 'Transaction index failed: ' . $e->getMessage());
            return redirect()->to(base_url('dasbor'))->with('error', 'Gagal memuat riwayat transaksi. Silakan coba lagi.');
        }
    }

    public function detail(string $transactionId = null)
    {
        try {
            $session = session();
            $userId = $session->get('id');

            if (!$userId) {
                return redirect()->to(base_url('login'))->with('error', AppConstants::MSG_LOGIN_REQUIRED);
            }

            // Validate authorization
            if (!authorize_user_role(AppConstants::ROLE_KASIR)) {
                log_transaction('warning', 'Unauthorized transaction detail access');
                return redirect()->to(base_url('dasbor'))->with('error', AppConstants::MSG_UNAUTHORIZED);
            }

            if (empty($transactionId)) {
                return redirect()->to(base_url('transaksi'))->with('error', 'ID transaksi tidak valid.');
            }

            $transactionModel = new TransactionModel();
            $transaction = $transactionModel->getTransactionByTransactionIdAndUser($transactionId, $userId);

            if (empty($transaction)) {
                log_transaction('warning', 'Transaction not found', ['transactionId' => $transactionId]);
                return redirect()->to(base_url('transaksi'))->with('error', 'Transaksi tidak ditemukan.');
            }

            $detailModel = new TransactionDetailModel();
            $items = $detailModel->getItemsByTransactionUuid($transaction['id']);

            return view('cashier/transaction_detail', [
                'title' => page_title('Detail Transaksi'),
                'cashier' => current_cashier_data(),
                'transaction' => $transaction,
                'items' => $items,
            ]);
        } catch (\Throwable $e) {
            log_message('error', 'Transaction detail failed: ' . $e->getMessage());
            return redirect()->to(base_url('transaksi'))->with('error', 'Gagal memuat detail transaksi. Silakan coba lagi.');
        }
    }

    public function downloadNota(string $transactionId = null)
    {
        try {
            $session = session();
            $userId = $session->get('id');

            if (!$userId) {
                return redirect()->to(base_url('login'))->with('error', AppConstants::MSG_LOGIN_REQUIRED);
            }

            // Validate authorization
            if (!authorize_user_role(AppConstants::ROLE_KASIR)) {
                log_transaction('warning', 'Unauthorized nota download attempt');
                return redirect()->to(base_url('dasbor'))->with('error', AppConstants::MSG_UNAUTHORIZED);
            }

            if (empty($transactionId)) {
                return redirect()->to(base_url('transaksi'))->with('error', 'ID transaksi tidak valid.');
            }

            $transactionModel = new TransactionModel();
            $transaction = $transactionModel->getTransactionByTransactionIdAndUser($transactionId, $userId);

            if (empty($transaction)) {
                log_transaction('warning', 'Transaction not found for nota download', ['transactionId' => $transactionId]);
                return redirect()->to(base_url('transaksi'))->with('error', 'Transaksi tidak ditemukan.');
            }

            $detailModel = new TransactionDetailModel();
            $items = $detailModel->getItemsByTransactionUuid($transaction['id']);

            $html = view('cashier/transaction_nota_pdf', [
                'cashier' => current_cashier_data(),
                'transaction' => $transaction,
                'items' => $items,
            ]);

            $options = new Options();
            $options->set('isRemoteEnabled', false);
            $options->set('defaultFont', 'Helvetica');

            $dompdf = new Dompdf($options);
            $dompdf->loadHtml($html);

            $widthPt = 226.77;
            $baseHeightPt = 300;
            $perItemHeightPt = 24;
            $heightPt = $baseHeightPt + (count($items) * $perItemHeightPt);

            $dompdf->setPaper([0, 0, $widthPt, $heightPt], 'portrait');
            $dompdf->render();

            $fileName = 'nota-' . $transaction['transaction_id'] . '.pdf';

            $dompdf->stream($fileName, ['Attachment' => true]);
            exit;
        } catch (\Throwable $e) {
            log_message('error', 'Nota download failed: ' . $e->getMessage());
            return redirect()->to(base_url('transaksi'))->with('error', 'Gagal mengunduh nota. Silakan coba lagi.');
        }
    }
}