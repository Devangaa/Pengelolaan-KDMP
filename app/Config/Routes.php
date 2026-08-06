<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('/', 'GuestController::index');
$routes->get('/products', 'GuestController::products');
$routes->get('/products/filter', 'GuestController::filterProducts');
$routes->get('/about', 'GuestController::about');

