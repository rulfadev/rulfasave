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

        $session = session();
        $model = new UsersModel();
        $user = $model->where('email', $login)
            ->orWhere('username', $login)
            ->first();

        if ($user && password_verify($password, $user['password'])) {
            if (!$user['is_active']) {
                $session->setFlashdata('error', 'Akun Anda belum diaktifkan. Silakan cek email Anda.');
                return redirect()->back()->withInput();
            }

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
        $session = session();
        $model = new UsersModel();

        // Ambil input data
        $username = $this->request->getVar('username');
        $email = $this->request->getVar('email');
        $password = $this->request->getVar('password');
        $confirmpassword = $this->request->getVar('confirmpassword');
        $tor = $this->request->getVar('tor');

        // Aturan validasi
        $validationRules = [
            'username' => [
                'label' => 'Username',
                'rules' => 'required|min_length[6]|max_length[50]|is_unique[users.username]',
                'errors' => [
                    'is_unique' => 'Username sudah terdaftar.',
                    'min_length' => 'Username minimal 6 karakter.',
                    'max_length' => 'Username maksimal 50 karakter.',
                    'required' => 'Username wajib diisi.'
                ]
            ],
            'email' => [
                'label' => 'Email',
                'rules' => 'required|valid_email|is_unique[users.email]',
                'errors' => [
                    'required' => 'Email wajib diisi.',
                    'valid_email' => 'Format email tidak valid.',
                    'is_unique' => 'Email sudah terdaftar.'
                ]
            ],
            'password' => [
                'label' => 'Password',
                'rules' => 'required|min_length[6]',
                'errors' => [
                    'required' => 'Password wajib diisi.',
                    'min_length' => 'Password minimal 6 karakter.'
                ]
            ],
        ];

        // Validasi input
        if (!$this->validate($validationRules)) {
            $session->setFlashdata('errors', $this->validator->getErrors());
            return redirect()->back()->withInput();
        }

        // Validasi kata yang dilarang
        if (!$this->checkBannedWords($username)) {
            $session->setFlashdata('error', 'Username tidak boleh mengandung kata yang dilarang.');
            return redirect()->back()->withInput();
        }

        // Cek kesamaan password
        if ($password !== $confirmpassword) {
            $session->setFlashdata('error', 'Password yang Anda ulangi tidak sama.');
            return redirect()->back()->withInput();
        }

        // Cek apakah ToR dicentang
        if (!isset($tor)) {
            $session->setFlashdata('error', 'Tolong checklis Kebijakan dan Layanan.');
            return redirect()->back()->withInput();
        }

        // Buat kode aktivasi
        $activationCode = bin2hex(random_bytes(16));
        $model->save([
            'username' => $username,
            'email' => $email,
            'password' => password_hash($password, PASSWORD_BCRYPT),
            'is_active' => false, // Set is_active ke false
            'activation_code' => $activationCode,
        ]);

        // Kirim email aktivasi
        $this->sendActivationEmail($email, $activationCode);

        $session->setFlashdata('success', 'Pendaftaran berhasil. Anda sekarang bisa login.');
        return redirect()->to('auth/login');
    }

    // Private Function
    private function isLoggedIn()
    {
        // Ambil instance session
        $session = session();

        // Periksa apakah data sesi yang menandakan pengguna sudah login ada
        return $session->has('isLoggedIn') && $session->get('isLoggedIn') === true;
    }

    private $bannedWords = [
        'admin',      // Kata umum untuk administrator
        'root',       // Pengguna dengan akses tertinggi di sistem
        'superuser',  // Pengguna dengan hak istimewa lebih tinggi
        'support',    // Kata yang biasanya digunakan untuk tim dukungan
        'staff',      // Digunakan untuk anggota staf
        'moderator',  // Kata yang umum digunakan untuk peran moderator
        'administrator', // Variasi lain dari admin
        'webmaster',  // Digunakan untuk pengelola situs web
        'test',       // Umum digunakan dalam akun pengujian
        'guest',      // Digunakan untuk akun tamu
        'system',     // Digunakan untuk pengguna sistem
        'null',       // Nilai kosong atau tidak valid
        'god',        // Untuk menghindari nama yang religius atau menyinggung
        'hack',       // Untuk menghindari nama yang mengandung maksud tidak baik
        'password',   // Kata yang tidak masuk akal untuk username
        'user',       // Kata umum untuk pengguna
        'sysadmin',   // Digunakan untuk administrator sistem
        'operator',   // Digunakan untuk operator sistem
        'anon',       // Untuk mencegah akun anonim
        'anonymous',  // Nama yang terkait dengan akun anonim
        'master',     // Kata yang bisa memiliki makna istimewa atau menyinggung
        'admin1',     // Variasi dari admin dengan angka
        'qwerty',     // Kata acak yang umum digunakan
        'owner',      // Digunakan oleh pemilik situs atau sistem
    ];

    private function checkBannedWords(string $username)
    {
        foreach ($this->bannedWords as $word) {
            if (stripos($username, $word) !== false) {
                return false;
            }
        }
        return true;
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

    private function sendActivationEmail($email, $activationCode)
    {
        $emailService = \Config\Services::email();

        $emailService->setTo($email);
        $emailService->setSubject('Aktivasi Akun Anda');
        $message = "Klik link berikut untuk mengaktifkan akun Anda:\n\n";
        $message .= base_url("auth/activate/$activationCode");

        $emailService->setMessage($message);

        if ($emailService->send()) {
            return true;
        } else {
            log_message('error', 'Email gagal dikirim ke: ' . $email);
            return false;
        }
    }

    public function activate($activationCode)
    {
        $model = new UsersModel();

        $user = $model->where('activation_code', $activationCode)
            ->where('is_active', false)
            ->first();

        if ($user) {
            $model->update($user['id'], ['is_active' => true, 'activation_code' => null]);

            session()->setFlashdata('success', 'Akun Anda telah diaktifkan. Silakan login.');
            return redirect()->to('auth/login');
        } else {
            session()->setFlashdata('error', 'Kode aktivasi tidak valid atau akun sudah aktif.');
            return redirect()->to('auth/login');
        }
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
