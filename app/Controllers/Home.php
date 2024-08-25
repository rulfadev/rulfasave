<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index()
    {
        $data = ['title' => 'Rulfa Saving - Sistem Manajemen Keuangan Pribadi', 'judul' => 'Rulfa Saving'];
        return view('landingpage', $data);
    }

}
