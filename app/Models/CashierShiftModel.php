<?php

namespace App\Models;

use App\Models\BaseModel;

class CashierShiftModel extends BaseModel
{
    protected $table            = 'cashier_shifts';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    
    protected $allowedFields    = [
        'user_id',
        'status',
        'modal_awal',
        'uang_fisik',
        'opened_at',
        'closed_at',
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function getLatestShiftByUser(string $userId): ?array
    {
        return $this->builder()
            ->where('user_id', $userId)
            ->orderBy('opened_at', 'DESC')
            ->limit(1)
            ->get()
            ->getRowArray() ?: null;
    }

    public function getCurrentShiftDataByUser(string $userId): array
    {
        $shift = $this->getLatestShiftByUser($userId);

        if (!$shift) {
            return [
                'shiftActive' => false,
                'shiftStartedAt' => null,
                'openingBalance' => 0.0,
                'latestShift' => null,
            ];
        }

        $shiftActive = empty($shift['closed_at']);
        $shiftStartedAt = $shiftActive ? date('H:i', strtotime($shift['opened_at'])) : null;

        return [
            'shiftActive' => $shiftActive,
            'shiftStartedAt' => $shiftStartedAt,
            'openingBalance' => (float) $shift['modal_awal'],
            'latestShift' => $shift,
        ];
    }
}
