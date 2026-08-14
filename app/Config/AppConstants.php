<?php

namespace Config;

class AppConstants
{
    // Payment Types
    public const PAYMENT_TYPE_CASH = 'tunai';
    public const PAYMENT_TYPE_NON_CASH = 'nontunai';

    public const PAYMENT_TYPES = [
        self::PAYMENT_TYPE_CASH,
        self::PAYMENT_TYPE_NON_CASH,
    ];

    // Shift Status
    public const SHIFT_STATUS_OPEN = 'open';
    public const SHIFT_STATUS_CLOSED = 'closed';

    public const SHIFT_STATUSES = [
        self::SHIFT_STATUS_OPEN,
        self::SHIFT_STATUS_CLOSED,
    ];

    // User Roles
    public const ROLE_ADMIN = 'admin';
    public const ROLE_KASIR = 'kasir';

    public const ROLES = [
        self::ROLE_ADMIN,
        self::ROLE_KASIR,
    ];

    // Error Messages
    public const MSG_UNAUTHORIZED = 'Anda tidak memiliki akses ke fitur ini.';
    public const MSG_LOGIN_REQUIRED = 'Silakan login terlebih dahulu.';
    public const MSG_SHIFT_NOT_ACTIVE = 'Sesi kasir belum aktif. Silakan mulai sesi terlebih dahulu.';
    public const MSG_SHIFT_ALREADY_ACTIVE = 'Shift kasir masih aktif. Silakan lanjutkan transaksi di POS.';
    public const MSG_CART_EMPTY = 'Keranjang masih kosong.';
    public const MSG_INVALID_PAYMENT_TYPE = 'Metode pembayaran tidak valid.';
    public const MSG_INSUFFICIENT_STOCK = 'Stok produk tidak mencukupi.';
    public const MSG_PRODUCT_NOT_FOUND = 'Produk tidak ditemukan.';
    public const MSG_INSUFFICIENT_CASH = 'Uang pembayaran customer kurang dari total transaksi.';
    public const MSG_TRANSACTION_FAILED = 'Gagal menyimpan transaksi. Silakan coba lagi.';
    public const MSG_INVALID_AMOUNT = 'Nominal uang tidak valid.';
}
