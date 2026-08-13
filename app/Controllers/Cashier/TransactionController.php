<?php

namespace App\Controllers\Cashier;

use App\Controllers\BaseController;
use App\Models\TransactionDetailModel;
use App\Models\TransactionModel;
use App\Models\UserModel;
use Dompdf\Dompdf;
use Dompdf\Options;

class TransactionController extends BaseController
{
    private function getCashierData(): array
    {
        $session = session();
        $userId = $session->get('id');
        $cashier = [];

        if ($userId) {
            $userModel = new UserModel();
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

        return $cashier;
    }

    public function index()
    {
        $session = session();
        $orderBy = $this->request->getGet('orderBy') ?? 'latest';
        $startDate = $this->request->getGet('startDate');
        $endDate = $this->request->getGet('endDate');

        if (!empty($startDate) && empty($endDate)) {
            $endDate = date('Y-m-d');
        }

        $transactionModel = new TransactionModel();
        $transactions = $transactionModel->getTransactionsByUser(
            $session->get('id'),
            $orderBy,
            $startDate,
            $endDate,
            20,
            'transactions'
        );

        $data = [
            'cashier' => $this->getCashierData(),
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
    }

    public function detail(string $transactionId = null)
    {
        $session = session();

        if (empty($transactionId)) {
            return redirect()->to(base_url('cashier/reports'));
        }

        $transactionModel = new TransactionModel();
        $transaction = $transactionModel->getTransactionByTransactionIdAndUser($transactionId, $session->get('id'));

        if (empty($transaction)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Transaksi tidak ditemukan.');
        }

        $detailModel = new TransactionDetailModel();
        $items = $detailModel->getItemsByTransactionUuid($transaction['id']);

        return view('cashier/transaction_detail', [
            'cashier' => $this->getCashierData(),
            'transaction' => $transaction,
            'items' => $items,
        ]);
    }

    public function downloadNota(string $transactionId = null)
    {
        $session = session();

        if (empty($transactionId)) {
            return redirect()->to(base_url('transaksi'));
        }

        $transactionModel = new TransactionModel();
        $transaction = $transactionModel->getTransactionByTransactionIdAndUser($transactionId, $session->get('id'));

        if (empty($transaction)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Transaksi tidak ditemukan.');
        }

        $detailModel = new TransactionDetailModel();
        $items = $detailModel->getItemsByTransactionUuid($transaction['id']);

        $html = view('cashier/transaction_nota_pdf', [
            'cashier' => $this->getCashierData(),
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
    }
}