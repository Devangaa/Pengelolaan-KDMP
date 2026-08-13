<?php

namespace App\Models;

use App\Models\BaseModel;

class TransactionModel extends BaseModel
{
    protected $table            = 'transactions';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;

    protected $allowedFields    = [
        'id',
        'transaction_id',
        'user_id',
        'member_id',
        'total',
        'pay',
        'change',
        'payment_type'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [
        'transaction_id' => 'required|max_length[20]',
        'user_id'        => 'required|is_not_unique[users.id]|max_length[36]',
        'member_id'      => 'permit_empty|is_not_unique[members.id]|max_length[36]',
        'total'          => 'required|integer|greater_than_equal_to[0]',
        'pay'            => 'required|integer|greater_than_equal_to[0]',
        'change'         => 'required|integer|greater_than_equal_to[0]',
        'payment_type'   => 'required|in_list[tunai,nontunai]',
    ];
    protected $validationMessages   = [
        'transaction_id' => [
            'required' => 'ID transaksi wajib diisi.',
            'max_length' => 'ID transaksi maksimal 20 karakter.',
        ],
        'user_id' => [
            'required' => 'ID pengguna wajib diisi.',
            'is_not_unique' => 'ID pengguna tidak valid.',
            'max_length' => 'ID pengguna maksimal 36 karakter.',
        ],
        'member_id' => [
            'permit_empty' => 'ID anggota boleh kosong.',
            'is_not_unique' => 'ID anggota tidak valid.',
            'max_length' => 'ID anggota maksimal 36 karakter.',
        ],
        'total' => [
            'required' => 'Total transaksi wajib diisi.',
            'integer' => 'Total transaksi harus berupa angka.',
            'greater_than_equal_to' => 'Total transaksi harus lebih besar atau sama dengan 0.',
        ],
        'pay' => [
            'required' => 'Jumlah pembayaran wajib diisi.',
            'integer' => 'Jumlah pembayaran harus berupa angka.',
            'greater_than_equal_to' => 'Jumlah pembayaran harus lebih besar atau sama dengan 0.',
        ],
        'change' => [
            'required' => 'Kembalian wajib diisi.',
            'integer' => 'Kembalian harus berupa angka.',
            'greater_than_equal_to' => 'Kembalian harus lebih besar atau sama dengan 0.',
        ],
        'payment_type' => [
            'required' => 'Tipe pembayaran wajib diisi.',
            'in_list' => 'Tipe pembayaran harus bernilai tunai atau nontunai.',
        ],
    ];

    public function getDailyTotal(string $date, ?string $userId = null): int
    {
        $builder = $this->builder()
            ->select('IFNULL(SUM(total), 0) as total')
            ->where('DATE(transactions.created_at)', $date);

        if ($userId) {
            $builder->where('user_id', $userId);
        }

        $row = $builder->get()->getRowArray();
        return isset($row['total']) ? (int) $row['total'] : 0;
    }

    public function getDailyCount(string $date, ?string $userId = null): int
    {
        $builder = $this->builder()->where('DATE(transactions.created_at)', $date);

        if ($userId) {
            $builder->where('user_id', $userId);
        }

        return (int) $builder->countAllResults(false);
    }

    public function getDailyTotalByPaymentType(string $date, string $paymentType, ?string $userId = null): int
    {
        $builder = $this->builder()
            ->select('IFNULL(SUM(total), 0) as total')
            ->where('DATE(created_at)', $date)
            ->where('payment_type', $paymentType);

        if ($userId) {
            $builder->where('user_id', $userId);
        }

        $row = $builder->get()->getRowArray();
        return isset($row['total']) ? (int) $row['total'] : 0;
    }

    public function getTodayTransactionsWithCashier(string $date, int $limit = 8)
    {
        return $this->builder()
            ->select('transactions.transaction_id as no_struk, users.name as nama_kasir, transactions.total as total_harga, transactions.created_at')
            ->join('users', 'users.id = transactions.user_id', 'left')
            ->where('DATE(transactions.created_at)', $date)
            ->orderBy('transactions.created_at', 'DESC')
            ->limit($limit)
            ->get()
            ->getResultArray();
    }

    public function getTodayTransactionsByUser(string $userId, string $date, int $limit = 10)
    {
        return $this->builder()
            ->select('transaction_id as no_struk, total, pay, `change`, transactions.created_at, payment_type')
            ->where('user_id', $userId)
            ->where('DATE(transactions.created_at)', $date)
            ->orderBy('transactions.created_at', 'DESC')
            ->limit($limit)
            ->get()
            ->getResultArray();
    }

    public function getTransactionsByUser(string $userId, ?string $orderBy = 'latest', ?string $startDate = null, ?string $endDate = null, int $perPage = 20, string $pageName = 'transactions')
    {
        $this->select('transactions.id, transactions.transaction_id as no_struk, transactions.transaction_id, transactions.total, transactions.payment_type, transactions.created_at, members.name as member_name')
            ->join('members', 'members.id = transactions.member_id', 'left')
            ->where('transactions.user_id', $userId);

        if (!empty($startDate)) {
            $this->where('DATE(transactions.created_at) >=', $startDate);
        }

        if (!empty($endDate)) {
            $this->where('DATE(transactions.created_at) <=', $endDate);
        }

        $this->orderBy('transactions.created_at', $orderBy === 'oldest' ? 'ASC' : 'DESC');

        return $this->paginate($perPage, $pageName);
    }

    public function getTransactionByTransactionIdAndUser(string $transactionId, string $userId): ?array
    {
        return $this->builder()
            ->select('transactions.id, transactions.transaction_id, transactions.total, transactions.pay, transactions.`change`, transactions.payment_type, transactions.created_at, members.name as member_name')
            ->join('members', 'members.id = transactions.member_id', 'left')
            ->where('transactions.transaction_id', $transactionId)
            ->where('transactions.user_id', $userId)
            ->limit(1)
            ->get()
            ->getRowArray();
    }

    public function getFirstTransactionTimeByUser(string $userId, string $date): ?string
    {
        $row = $this->builder()
            ->select('MIN(transactions.created_at) as start_time')
            ->where('user_id', $userId)
            ->where('DATE(transactions.created_at)', $date)
            ->get()
            ->getRowArray();

        return !empty($row['start_time']) ? $row['start_time'] : null;
    }
}
