<?php

use Config\AppConstants;

if (!function_exists('validate_amount')) {
    /**
     * Validate dan sanitize amount/money value
     * @param mixed $value
     * @param float $minValue = 0
     * @return array ['valid' => bool, 'value' => float, 'error' => string|null]
     */
    function validate_amount($value, float $minValue = 0): array
    {
        if ($value === null || $value === '') {
            return [
                'valid' => false,
                'value' => 0,
                'error' => 'Nominal tidak boleh kosong.',
            ];
        }

        $numericValue = filter_var($value, FILTER_VALIDATE_FLOAT);

        if ($numericValue === false) {
            return [
                'valid' => false,
                'value' => 0,
                'error' => 'Format nominal tidak valid.',
            ];
        }

        if ($numericValue < $minValue) {
            return [
                'valid' => false,
                'value' => 0,
                'error' => 'Nominal tidak boleh kurang dari ' . rupiah($minValue) . '.',
            ];
        }

        return [
            'valid' => true,
            'value' => (float) $numericValue,
            'error' => null,
        ];
    }
}

if (!function_exists('validate_date')) {
    /**
     * Validate date format YYYY-MM-DD
     * @param mixed $value
     * @return array ['valid' => bool, 'value' => string|null, 'error' => string|null]
     */
    function validate_date($value): array
    {
        if ($value === null || $value === '') {
            return [
                'valid' => true,
                'value' => null,
                'error' => null,
            ];
        }

        $value = trim((string) $value);

        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $value)) {
            return [
                'valid' => false,
                'value' => null,
                'error' => 'Format tanggal harus YYYY-MM-DD.',
            ];
        }

        $datetime = \DateTime::createFromFormat('Y-m-d', $value);
        if ($datetime === false || $datetime->format('Y-m-d') !== $value) {
            return [
                'valid' => false,
                'value' => null,
                'error' => 'Tanggal tidak valid.',
            ];
        }

        return [
            'valid' => true,
            'value' => $value,
            'error' => null,
        ];
    }
}

if (!function_exists('validate_payment_type')) {
    /**
     * Validate payment type
     * @param mixed $value
     * @return array ['valid' => bool, 'value' => string|null, 'error' => string|null]
     */
    function validate_payment_type($value): array
    {
        $paymentType = strtolower(trim((string) ($value ?? '')));

        if (empty($paymentType)) {
            return [
                'valid' => false,
                'value' => null,
                'error' => 'Metode pembayaran harus dipilih.',
            ];
        }

        if (!in_array($paymentType, AppConstants::PAYMENT_TYPES, true)) {
            return [
                'valid' => false,
                'value' => null,
                'error' => AppConstants::MSG_INVALID_PAYMENT_TYPE,
            ];
        }

        return [
            'valid' => true,
            'value' => $paymentType,
            'error' => null,
        ];
    }
}

if (!function_exists('validate_cart')) {
    /**
     * Validate cart structure
     * @param mixed $cart
     * @return array ['valid' => bool, 'error' => string|null]
     */
    function validate_cart($cart): array
    {
        if (!is_array($cart) || empty($cart)) {
            return [
                'valid' => false,
                'error' => AppConstants::MSG_CART_EMPTY,
            ];
        }

        foreach ($cart as $item) {
            if (!is_array($item) || empty($item['id']) || empty($item['qty'])) {
                return [
                    'valid' => false,
                    'error' => 'Struktur keranjang tidak valid.',
                ];
            }

            $qty = filter_var($item['qty'], FILTER_VALIDATE_INT);
            if ($qty === false || $qty <= 0) {
                return [
                    'valid' => false,
                    'error' => 'Jumlah produk harus lebih dari 0.',
                ];
            }
        }

        return [
            'valid' => true,
            'error' => null,
        ];
    }
}

if (!function_exists('authorize_user_role')) {
    /**
     * Check if user has required role
     * @param string|array $requiredRoles
     * @return bool
     */
    function authorize_user_role($requiredRoles): bool
    {
        $session = session();
        $userRole = $session->get('role');

        if (empty($userRole)) {
            return false;
        }

        $roles = is_array($requiredRoles) ? $requiredRoles : [$requiredRoles];

        return in_array($userRole, $roles, true);
    }
}

if (!function_exists('log_transaction')) {
    /**
     * Log transaction untuk audit trail
     * @param string $type
     * @param string $message
     * @param array $context
     * @return void
     */
    function log_transaction(string $type = 'info', string $message = '', array $context = []): void
    {
        $session = session();
        $userId = $session->get('id') ?? 'unknown';

        $contextStr = !empty($context) ? ' | ' . json_encode($context) : '';
        $fullMessage = "User:{$userId} | {$message}{$contextStr}";

        log_message($type, $fullMessage);
    }
}
