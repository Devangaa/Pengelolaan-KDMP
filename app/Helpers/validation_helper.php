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

if (!function_exists('validate_id')) {
    /**
     * Validate generic ID value (UUID-like or integer-like string)
     * @param mixed $value
     * @param string $fieldName
     * @param bool $allowEmpty
     * @return array ['valid' => bool, 'value' => string|null, 'error' => string|null]
     */
    function validate_id($value, string $fieldName = 'ID', bool $allowEmpty = false): array
    {
        if ($value === null || $value === '') {
            if ($allowEmpty) {
                return ['valid' => true, 'value' => null, 'error' => null];
            }

            return ['valid' => false, 'value' => null, 'error' => ucfirst($fieldName) . ' tidak boleh kosong.'];
        }

        $filtered = trim((string) $value);
        $filtered = preg_replace('/\s+/', '', $filtered) ?: $filtered;

        if ($filtered === '') {
            return ['valid' => false, 'value' => null, 'error' => ucfirst($fieldName) . ' tidak valid.'];
        }

        $isNumericId = preg_match('/^\d+$/', $filtered) === 1;
        $isUuidId = preg_match('/^[0-9a-fA-F-]{8,36}$/', $filtered) === 1;

        if (!$isNumericId && !$isUuidId) {
            return ['valid' => false, 'value' => null, 'error' => ucfirst($fieldName) . ' tidak valid.'];
        }

        return ['valid' => true, 'value' => $filtered, 'error' => null];
    }
}

if (!function_exists('validate_product_id')) {
    function validate_product_id($value): array
    {
        return validate_id($value, 'Product ID');
    }
}

if (!function_exists('validate_category_id')) {
    function validate_category_id($value): array
    {
        return validate_id($value, 'Kategori', true);
    }
}

if (!function_exists('validate_member_id')) {
    function validate_member_id($value): array
    {
        return validate_id($value, 'Member ID', true);
    }
}

if (!function_exists('validate_search_keyword')) {
    /**
     * Sanitize keyword search from query string
     * @param mixed $value
     * @param int $maxLength
     * @return array ['valid' => bool, 'value' => string|null, 'error' => string|null]
     */
    function validate_search_keyword($value, int $maxLength = 100): array
    {
        if ($value === null || $value === '') {
            return ['valid' => true, 'value' => null, 'error' => null];
        }

        $sanitized = trim((string) $value);
        $sanitized = preg_replace('/[\x00-\x1F\x7F]/u', '', $sanitized) ?? $sanitized;
        $sanitized = strip_tags($sanitized);

        if ($sanitized === '') {
            return ['valid' => true, 'value' => null, 'error' => null];
        }

        if (mb_strlen($sanitized, 'UTF-8') > $maxLength) {
            return ['valid' => false, 'value' => null, 'error' => 'Pencarian terlalu panjang. Maksimal ' . $maxLength . ' karakter.'];
        }

        return ['valid' => true, 'value' => $sanitized, 'error' => null];
    }
}

if (!function_exists('validate_sort')) {
    /**
     * Validate sort parameter against allowed values
     * @param mixed $value
     * @param array $allowed
     * @return array ['valid' => bool, 'value' => string|null, 'error' => string|null]
     */
    function validate_sort($value, array $allowed = ['popular', 'newest', 'name_asc', 'name_desc', 'price_asc', 'price_desc']): array
    {
        $sort = strtolower(trim((string) ($value ?? '')));

        if ($sort === '') {
            return ['valid' => true, 'value' => $allowed[0], 'error' => null];
        }

        if (!in_array($sort, $allowed, true)) {
            return ['valid' => false, 'value' => null, 'error' => 'Pilihan urutan tidak valid.'];
        }

        return ['valid' => true, 'value' => $sort, 'error' => null];
    }
}

if (!function_exists('validate_date_range')) {
    /**
     * Validate date range based on YYYY-MM-DD
     * @param mixed $startDate
     * @param mixed $endDate
     * @return array ['valid' => bool, 'startDate' => string|null, 'endDate' => string|null, 'error' => string|null]
     */
    function validate_date_range($startDate, $endDate): array
    {
        $startValidation = validate_date($startDate);
        if (!$startValidation['valid']) {
            return ['valid' => false, 'startDate' => null, 'endDate' => null, 'error' => $startValidation['error']];
        }

        $endValidation = validate_date($endDate);
        if (!$endValidation['valid']) {
            return ['valid' => false, 'startDate' => null, 'endDate' => null, 'error' => $endValidation['error']];
        }

        $normalizedStart = $startValidation['value'];
        $normalizedEnd = $endValidation['value'];

        if ($normalizedStart !== null && $normalizedEnd !== null && strtotime($normalizedStart) > strtotime($normalizedEnd)) {
            return ['valid' => false, 'startDate' => null, 'endDate' => null, 'error' => 'Rentang tanggal tidak valid. Tanggal mulai tidak boleh lebih besar dari tanggal akhir.'];
        }

        return ['valid' => true, 'startDate' => $normalizedStart, 'endDate' => $normalizedEnd, 'error' => null];
    }
}

if (!function_exists('validate_quantity')) {
    /**
     * Validate quantity for cart items or stock updates
     * @param mixed $value
     * @param int $min
     * @param int $max
     * @return array ['valid' => bool, 'value' => int, 'error' => string|null]
     */
    function validate_quantity($value, int $min = 1, int $max = 9999): array
    {
        if ($value === null || $value === '') {
            return ['valid' => false, 'value' => 0, 'error' => 'Jumlah produk tidak boleh kosong.'];
        }

        $quantity = filter_var($value, FILTER_VALIDATE_INT);

        if ($quantity === false) {
            return ['valid' => false, 'value' => 0, 'error' => 'Jumlah produk harus berupa angka bulat.'];
        }

        if ($quantity < $min || $quantity > $max) {
            return ['valid' => false, 'value' => 0, 'error' => 'Jumlah produk harus berada di rentang ' . $min . ' sampai ' . $max . '.'];
        }

        return ['valid' => true, 'value' => (int) $quantity, 'error' => null];
    }
}

if (!function_exists('validate_user_access')) {
    /**
     * Validate whether role is allowed for the current operation
     * @param mixed $actualRole
     * @param array $allowedRoles
     * @return array ['valid' => bool, 'role' => string|null, 'error' => string|null]
     */
    function validate_user_access($actualRole, array $allowedRoles): array
    {
        $role = strtolower(trim((string) ($actualRole ?? '')));

        if ($role === '' || !in_array($role, $allowedRoles, true)) {
            return ['valid' => false, 'role' => null, 'error' => AppConstants::MSG_UNAUTHORIZED];
        }

        return ['valid' => true, 'role' => $role, 'error' => null];
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
