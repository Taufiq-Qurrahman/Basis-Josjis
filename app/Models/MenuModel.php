<?php
namespace App\Models;
use CodeIgniter\Model;

class MenuModel extends Model
{
    protected $table = 'menu';
    protected $primaryKey = 'ID_MENU';
    protected $returnType = 'array';
    
    // Ambil semua menu yang tersedia
    public function getSemuaMenu()
    {
        return $this->findAll();
    }
}