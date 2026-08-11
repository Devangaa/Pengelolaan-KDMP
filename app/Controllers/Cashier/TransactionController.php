<?php

namespace App\Controllers\Cashier;

use App\Controllers\BaseController;
use App\Models\TransactionDetailModel;
use App\Models\TransactionModel;
use App\Models\UserModel;

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

    public function print(string $transactionId = null)
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
            'printMode' => true,
        ]);
    }
}
