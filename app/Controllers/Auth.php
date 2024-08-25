<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\UsersModel;

class Auth extends Controller
{
    public function __construct()
    {
        // Memuat helper cookie di seluruh controller
        helper(['form', 'cookie']);
    }
    public function index()
    {
        return redirect()->to('/auth/login');
    }

    public function login()
    {
        if ($this->isLoggedIn()) {
            return redirect()->to('/dashboard'); // Redirect ke halaman dashboard jika sudah login
        }

        $data = ['title' => 'Login - Rulfa Saving', 'judul' => 'Rulfa Saving'];

        return view('auth/login', $data);
    }

    public function proseslogin()
    {
        $login = $this->request->getVar('login');
        $password = $this->request->getVar('password');
        $rememberMe = $this->request->getVar('remember_me');
        // Ambil data pengguna berdasarkan email atau username
        $session = session();
        $model = new UsersModel();
        $user = $model->where('email', $login)
            ->orWhere('username', $login)
            ->first();
        if ($user && password_verify($password, $user['password'])) {
            $this->setUserSession($user);

            // Set Remember Me token if checked
            if ($rememberMe) {
                $this->setRememberMe($user['id']);
            }

            // Dapatkan IP pengguna dan waktu login
            $ipAddress = $this->request->getIPAddress();
            $loginTime = date('Y-m-d H:i:s');

            // Update IP and Login Time
            $model->update($user['id'], [
                'last_ip' => $ipAddress,
                'last_time' => $loginTime,
            ]);

            if ($user['role'] == "admin") {
                return redirect()->to('admin/dashboard');
            } else {
                return redirect()->to('/dashboard');
            }
        } else {
            $session->setFlashdata('error', 'Login gagal. Cek email atau username dan password.');
            return redirect()->back()->withInput();
        }
    }

    public function register()
    {
        if ($this->isLoggedIn()) {
            return redirect()->to('/dashboard'); // Redirect ke halaman dashboard jika sudah login
        }

        $data = ['title' => 'Register - Rulfa Saving', 'judul' => 'Rulfa Saving'];

        return view('auth/register', $data);
    }

    public function prosesregister()
    {
        $username = $this->request->getVar('username');
        $email = $this->request->getVar('email');
        $password = $this->request->getVar('password');
        $confirmpassword = $this->request->getVar('confirmpassword');
        $tor = $this->request->getVar('tor');

        // Ambil data pengguna berdasarkan email atau username
        $session = session();
        $model = new UsersModel();
        $user = $model->where('email', $email)
            ->orWhere('username', $username)
            ->first();
        if (!isset($user)) {
            if ($password !== $confirmpassword) {
                $session->setFlashdata('error', 'Password yang anda ulangi tidak sama.');
                return redirect()->back()->withInput();
            } elseif (!isset($tor)) {
                $session->setFlashdata('error', 'Tolong checklis Kebijakan dan Layanan.');
                return redirect()->back()->withInput();
            }

            // Validasi data input
            if (
                !$this->validate([
                    'username' => 'required|min_length[6]|max_length[50]|is_unique[users.username]',
                    'email' => 'required|valid_email|is_unique[users.email]',
                    'password' => 'required|min_length[6]',
                ])
            ) {
                $session->setFlashdata('errors', json_encode($this->validator->getErrors()));
                return redirect()->back()->withInput();
            }

            // Simpan data pengguna
            $model->save([
                'username' => $username,
                'email' => $email,
                'password' => password_hash($password, PASSWORD_BCRYPT),
            ]);

            $session->setFlashdata('success', 'Registration successful. You can now log in.');
            return redirect()->to('auth/login');
        } else {
            $session->setFlashdata('error', 'Pendaftaran gagal. Username atau Email telah terdaftar.');
            return redirect()->back()->withInput();
        }
    }

    // Private Function
    private function isLoggedIn()
    {
        // Ambil instance session
        $session = session();

        // Periksa apakah data sesi yang menandakan pengguna sudah login ada
        return $session->has('isLoggedIn') && $session->get('isLoggedIn') === true;
    }

    private function setUserSession($user)
    {
        $data = [
            'id' => $user['id'],
            'nama' => $user['nama'],
            'email' => $user['email'],
            'role' => $user['role'],
            'isLoggedIn' => true,
        ];

        session()->set($data);
        return true;
    }

    private function setRememberMe($userId)
    {
        // Hasilkan token acak dan salt jika diperlukan
        $token = bin2hex(random_bytes(16));

        // Simpan token di cookie dengan HttpOnly dan Secure flag
        $cookieOptions = [
            'name' => 'remember_token',
            'value' => $token,
            'expire' => 86400 * 30, // 30 hari
            'httponly' => true,
            'secure' => isset($_SERVER['HTTPS']), // Hanya secure jika HTTPS
        ];

        set_cookie($cookieOptions);

        // Simpan token dalam database
        $model = new UsersModel();
        $model->update($userId, ['remember_token' => $token]);
    }

    public function logout()
    {
        helper('cookie');

        // Hapus token dari database
        $userId = session()->get('id');
        $model = new UsersModel();
        $model->update($userId, ['remember_token' => null]);

        // Hapus cookie
        delete_cookie('remember_token');

        // Hancurkan sesi
        session()->destroy();

        return redirect()->to('/auth/login');
    }

    public function checkRememberMe()
    {
        $rememberToken = get_cookie('remember_token');

        if ($rememberToken) {
            $model = new UsersModel();
            $user = $model->where('remember_token', $rememberToken)->first();

            if ($user) {
                $this->setUserSession($user);
                return redirect()->to('/dashboard');
            }
        }

        return redirect()->to('/auth/login');
    }
}
