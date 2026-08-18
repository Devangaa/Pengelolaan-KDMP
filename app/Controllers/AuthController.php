<?php

namespace App\Controllers;

use App\Models\UserModel;

class AuthController extends BaseController
{
    protected $userModel;

    // Rate limiting constants
    private const MAX_LOGIN_ATTEMPTS = 5;
    private const LOCKOUT_DURATION = 900; // 15 minutes in seconds

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function login()
    {
        return view('auth/login', [
            'title' => page_title('Masuk')
        ]);
    }

    private function getAttemptKey(): string
    {
        $ipAddress = $this->request->getIPAddress() ?? '0.0.0.0';
        return 'login_attempts_' . md5($ipAddress);
    }

    private function checkRateLimit(): ?string
    {
        $cache = cache();
        $key = $this->getAttemptKey();
        $attempts = $cache->get($key) ?? 0;

        if ($attempts >= self::MAX_LOGIN_ATTEMPTS) {
            return 'Terlalu banyak percobaan login yang gagal. Silakan coba lagi dalam 15 menit.';
        }

        return null;
    }

    private function incrementFailedAttempts(): void
    {
        $cache = cache();
        $key = $this->getAttemptKey();
        $attempts = ($cache->get($key) ?? 0) + 1;

        $cache->save($key, $attempts, self::LOCKOUT_DURATION);
    }

    private function resetFailedAttempts(): void
    {
        $cache = cache();
        $cache->delete($this->getAttemptKey());
    }

    public function loginProcess()
    {
        try {
            // Check rate limiting
            $rateLimitError = $this->checkRateLimit();
            if ($rateLimitError) {
                return redirect()->back()->withInput()->with('error', $rateLimitError);
            }

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
                $this->incrementFailedAttempts();
                return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
            }

            $email    = trim((string) $this->request->getPost('email'));
            $password = (string) $this->request->getPost('password');

            $user = $this->userModel->where('email', $email)->first();

            if (!$user || !password_verify($password, $user->password)) {
                $this->incrementFailedAttempts();
                return redirect()->back()->withInput()->with('error', 'Email atau password yang Anda masukkan salah.');
            }

            // Reset failed attempts on successful login
            $this->resetFailedAttempts();

            $sessionData = [
                'id'         => $user->id,
                'name'       => $user->name,
                'email'      => $user->email,
                'role'       => $user->role,
                'isLoggedIn' => true,
            ];

            session()->set($sessionData);

            return redirect()->to('/dasbor')->with('success', 'Selamat datang kembali, ' . $user->name . '!');
        } catch (\Throwable $e) {
            log_message('error', 'Login process failed: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan saat masuk. Silakan coba lagi.');
        }
    }

    public function logout()
    {
        try {
            $session = session();

            if (!$session->get('isLoggedIn')) {
                return redirect()->to('/login')->with('error', 'Sesi Anda sudah berakhir.');
            }

            $message = 'Anda telah berhasil keluar dari sistem.';
            $session->destroy();

            if (!headers_sent()) {
                setcookie('logout_success', $message, time() + 60, '/');
            }

            return redirect()->to('/login');
        } catch (\Throwable $e) {
            log_message('error', 'Logout failed: ' . $e->getMessage());
            return redirect()->to('/login')->with('error', 'Gagal keluar dari sistem. Silakan coba lagi.');
        }
    }
}