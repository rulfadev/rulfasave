<?php

namespace App\Models;

use CodeIgniter\Model;

class UsersModel extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = [
        'nama', 'username', 'email', 'password', 'role', 'remember_token', 'last_ip', 'last_time', 'created_at', 'updated_at'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $validationRules = [
        'username' => 'required|min_length[6]|max_length[50]|is_unique[users.username]',
        'email'    => 'required|valid_email|is_unique[users.email]',
        'password' => 'required|min_length[6]',
    ];

    protected $validationMessages = [
        'username' => [
            'checkBannedWords' => 'Username tidak boleh mengandung kata yang dilarang.'
        ],
    ];

    protected $skipValidation     = false;

    // Fungsi validasi kustom
    public function checkBannedWords(string $str, string $fields, array $data)
    {
        $bannedWords = ['admin', 'root', 'superuser']; // Daftar kata yang dilarang
        foreach ($bannedWords as $word) {
            if (stripos($str, $word) !== false) {
                return false;
            }
        }
        return true;
    }

    protected $beforeInsert = ['hashPassword'];
    protected $beforeUpdate = ['hashPassword'];

    protected function hashPassword(array $data)
    {
        if (isset($data['data']['password'])) {
            $data['data']['password'] = password_hash($data['data']['password'], PASSWORD_DEFAULT);
        }

        return $data;
    }
}
