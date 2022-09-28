<?php 

namespace App\Models;

use CodeIgniter\Model;

class ProductExperience extends Model {
  protected $table = 'product_experience';
  protected $allowedFields = ['pid', 'exp_id'];
}