<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class RoleFilter implements FilterInterface
{
    /**
     * Filter yang dijalankan SEBELUM controller mengeksekusi rute halaman.
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        // 1. PASTIKAN USER SUDAH LOGIN
        // Memeriksa status session 'logged_in', jika kosong maka langsung dilempar ke login
        if (!session()->get('logged_in')) {
            return redirect()->to('/auth')->with('error', 'Silakan login terlebih dahulu.');
        }

        // 2. EVALUASI HAK AKSES (ROLE)
        // Jika rute yang dituju memiliki parameter batasan (contoh: filter=role[admin,owner])
        if ($arguments !== null) {
            
            // Ambil data role dari session, hilangkan spasi, dan paksa ke huruf kecil
            $userRole = strtolower(trim(session()->get('role') ?? ''));

            // Paksa semua parameter role yang dikirim dari rute (Routes.php) menjadi huruf kecil
            $allowedRoles = array_map('strtolower', $arguments);

            // Periksa apakah role user saat ini terdaftar di dalam array rute yang diizinkan
            if (!in_array($userRole, $allowedRoles)) {
                
                // Jika tidak diizinkan, kembalikan ke dashboard dengan pesan peringatan
                return redirect()->to('/dashboard')->with('error', 'Akses Ditolak: Role Anda tidak diizinkan mengakses halaman ini.');
            }
        }
    }

    /**
     * Filter yang dijalankan SESUDAH controller memproses request.
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Dibiarkan kosong karena pengamanan hanya dilakukan di awal sebelum halaman dimuat
    }
}