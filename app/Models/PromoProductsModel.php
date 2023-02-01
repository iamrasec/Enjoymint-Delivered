<?php 

namespace App\Models;

use CodeIgniter\Model;

class PromoProductsModel extends Model 
{
  protected $table = 'promo_products';
  protected $allowedFields = ['id', 'promo_id', 'product_id'];
}