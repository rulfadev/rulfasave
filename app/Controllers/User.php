<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\UsersModel;

class User extends Controller
{
    public function index()
    {
        return redirect()->to('/dashboard');
    }

    public function dashboard()
    {
        if (!$this->isLoggedIn()) {
            return redirect()->to('/auth/login'); // Redirect ke halaman login jika belum login
        }

        $session = session();
        $model = new UsersModel();
        $user = $model->where('id', $session->get('id'))->first();

        if($user['role'] == 'admin') {
            return redirect()->to('admin/dashboard');
        }

        $data = ['title' => 'Login Admin - Rulfa Saving', 'judul' => 'Rulfa Saving'];

        return view('user/dashboard', $data);
    }

    // Private Function
    private function isLoggedIn()
    {
        // Ambil instance session
        $session = session();

        // Periksa apakah data sesi yang menandakan pengguna sudah login ada
        return $session->has('isLoggedIn') && $session->get('isLoggedIn') === true;
    }
}