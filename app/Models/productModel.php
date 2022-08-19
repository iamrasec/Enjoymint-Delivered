<?php 

namespace App\Models;

use CodeIgniter\Model;

class ProductModel extends Model {
  protected $table = 'products';
  protected $primarykey = 'id';
  protected $returnType = 'array';
  protected $allowedFields = ['name', 'url', 'description', 'stocks', 'strain', 'brands', 'sku', 'images'];
  
}