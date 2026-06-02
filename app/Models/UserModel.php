<?php
namespace App\Models;
use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table      = 'users';
    protected $primaryKey = 'ID_USER';
    protected $returnType = 'array';
    
    // Kolom apa saja yang boleh diisi
    protected $allowedFields = ['USERNAME', 'PASSWORD_HASH', 'USER_TYPE', 'ID_KARIAWAN', 'ID_PEMBELI', 'IS_ACTIVE'];

    // Fungsi khusus untuk mengecek login
    public function cekLogin($username)
    {
        return $this->where('USERNAME', $username)->first();
    }
}