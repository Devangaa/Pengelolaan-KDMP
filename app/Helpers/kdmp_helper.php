<?php

use App\Models\UserModel;

if (!function_exists('page_title')) {
    function page_title(string $pageName = ''): string
    {
        $pageName = trim($pageName);

        return $pageName === ''
            ? 'Koperasi Desa Merah Putih'
            : $pageName . ' - Koperasi Desa Merah Putih';
    }
}

if (!function_exists('current_cashier_data')) {
    function current_cashier_data(): array
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
}

if (!function_exists('rupiah')) {
    function rupiah($value = 0, int $decimals = 0): string
    {
        $numericValue = is_numeric($value) ? (float) $value : 0;

        return 'Rp ' . number_format($numericValue, $decimals, ',', '.');
    }
}

if (!function_exists('product_image_url')) {
    function product_image_url($image = null): string
    {
        $imagePath = trim((string) ($image ?? ''));

        if ($imagePath !== '') {
            return base_url('uploads/products/' . ltrim($imagePath, '/'));
        }

        return base_url('assets/images/product-placeholder.webp');
    }
}
