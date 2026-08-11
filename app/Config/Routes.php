<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->group('', ['filter' => 'guest'], static function ($routes) {
    $routes->get('/', 'GuestController::index');
    $routes->get('/produk', 'GuestController::products');
    $routes->get('/produk/saring', 'GuestController::filterProducts');
    $routes->get('/tentang-kami', 'GuestController::about');
    
    $routes->get('login', 'AuthController::login');
    $routes->post('login', 'AuthController::loginProcess');
    $routes->get('/lupa-kata-sandi', 'AuthController::forgotPassword');
});

$routes->get('logout', 'AuthController::logout');

$routes->group('', ['filter' => 'role'], static function ($routes) {
    $routes->get('dasbor', 'DashboardController::index');
});

$routes->group('', ['filter' => 'role:kasir'], static function ($routes) {
    $routes->get('katalog', 'Cashier\ProductCatalogController::index');
    $routes->get('katalog/saring', 'Cashier\ProductCatalogController::filterProducts');
});