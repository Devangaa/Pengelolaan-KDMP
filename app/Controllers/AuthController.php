<?php

namespace App\Controllers;

use App\Models\UserModel;

class AuthController extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function login()
    {
        return view('auth/login', [
            'title' => 'Masuk - Koperasi Merah Putih'
        ]);
    }

    public function loginProcess()
    {
        $rules = [
            'email'    => 'required|valid_email',
            'password' => 'required',
        ];

        $messages = [
            'email' => [
                'required'    => 'Email wajib diisi.',
                'valid_email' => 'Format email tidak valid.',
            ],
            'password' => [
                'required' => 'Password wajib diisi.',
            ],
        ];

        if (!$this->validate($rules, $messages)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $email    = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        $user = $this->userModel->where('email', $email)->first();

        if (!$user || !password_verify($password, $user->password)) {
            return redirect()->back()->withInput()->with('error', 'Email atau password yang Anda masukkan salah.');
        }

        $sessionData = [
            'id'         => $user->id,
            'name'       => $user->name,
            'email'      => $user->email,
            'role'       => $user->role,
            'isLoggedIn' => true,
        ];

        session()->set($sessionData);

        return redirect()->to('/dasbor')->with('success', 'Selamat datang kembali, ' . $user->name . '!');
    }

    public function logout()
    {
        $session = session();
        $session->remove(['id', 'name', 'email', 'role', 'isLoggedIn']);
        $session->regenerate(true);
        $session->setFlashdata('success', 'Anda telah berhasil keluar dari sistem.');

        return redirect()->to('/login');
    }
}