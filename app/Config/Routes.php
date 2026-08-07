<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('/', 'GuestController::index');
$routes->get('/products', 'GuestController::products');
$routes->get('/products/filter', 'GuestController::filterProducts');
$routes->get('/about', 'GuestController::about');

$routes->group('', ['filter' => 'guest'], static function ($routes) {
    $routes->get('login', 'AuthController::login');
    $routes->post('login', 'AuthController::loginProcess');
    $routes->get('forgot-password', 'AuthController::forgotPassword');
});

$routes->get('logout', 'AuthController::logout');

