<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class DashboardController extends BaseController
{
    public function index()
    {
        $session = session();
        $role    = $session->get('role');

        switch ($role) {
            case 'admin':
                return (new \App\Controllers\Admin\DashboardController())->index();

            case 'kasir':
                return (new \App\Controllers\Cashier\DashboardController())->index();

            default:
                return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu.');
        }
    }
}