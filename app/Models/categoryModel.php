<?php 

namespace App\Models;

use CodeIgniter\Model;

class CategoryModel extends Model {
  protected $table = 'categories';
  protected $allowedFields = ['name', 'url', 'parent', 'weight'];
}