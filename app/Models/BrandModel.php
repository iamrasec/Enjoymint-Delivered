<?php 

namespace App\Models;

use CodeIgniter\Model;

class BrandModel extends Model {
  protected $table = 'brands';
  protected $allowedFields = ['name', 'url'];
}