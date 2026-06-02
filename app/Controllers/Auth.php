<?php
namespace App\Controllers;
use App\Models\UserModel;

class Auth extends BaseController
{
    public function index()
    {
        return view('auth/login');
    }

    public function proses_login()
    {
        $userModel = new UserModel();
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');
        
        $user = $userModel->cekLogin($username);

        if ($user) {
            // 1. PAKSA UBAH KE ARRAY (Jika model return tipe Object)
            if (is_object($user)) {
                $user = (array) $user;
            }

            // 2. AMBIL PASSWORD (Deteksi otomatis kapital/kecil)
            $db_password = $user['PASSWORD'] ?? $user['password'] ?? null;

            if ($db_password === $password) {
                
                // 3. AMBIL ROLE DARI KOLOM 'USER_TYPE' LALU KECILKAN HURUFNYA
                // Contoh: 'Admin' dari database akan diubah otomatis menjadi 'admin'
                $rawRole = $user['USER_TYPE'] ?? $user['user_type'] ?? $user['role'] ?? '';
                $cleanRole = strtolower(trim($rawRole));

                // 4. SIMPAN SESSION DENGAN BENAR
                session()->set([
                    'id_user'   => $user['ID_USER'] ?? $user['id_user'] ?? '',
                    'username'  => $user['USERNAME'] ?? $user['username'] ?? '',
                    'role'      => $cleanRole, // Pasti terisi huruf kecil
                    'logged_in' => TRUE
                ]);

                return redirect()->to('/dashboard');
            }
        }
        
        return redirect()->back()->with('error', 'Username atau Password salah!');
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/auth');
    }
}