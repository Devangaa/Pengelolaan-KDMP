<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->group('', ['filter' => 'guest'], static function ($routes) {
    $routes->get('/', 'GuestController::index');
    $routes->get('/products', 'GuestController::products');
    $routes->get('/products/filter', 'GuestController::filterProducts');
    $routes->get('/about', 'GuestController::about');
    
    $routes->get('login', 'AuthController::login');
    $routes->post('login', 'AuthController::loginProcess');
    $routes->get('forgot-password', 'AuthController::forgotPassword');
});

$routes->get('logout', 'AuthController::logout');

$routes->group('', ['filter' => 'role'], static function ($routes) {
    $routes->get('dashboard', 'DashboardController::index');
});