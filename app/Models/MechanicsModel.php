<?php 

namespace App\Models;

use CodeIgniter\Model;

class MechanicsModel extends Model 
{
  protected $table = 'mechanics';
  protected $allowedFields = ['id','promo_product', 'promo_products_specific_id', 'promo_products_category_id', 'require_purchase', 'require_all_products', 'require_specific_products_id', 'require_category_products_id', 'required_qty_purchase', 'product_discounted_condition'];
}