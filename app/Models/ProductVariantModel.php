<?php 

namespace App\Models;

use CodeIgniter\Model;

class ProductVariantModel extends Model {
  protected $table = 'product_variant';
  protected $allowedFields = ['pid', 'unit', 'unit_value', 'price', 'stock'];
}