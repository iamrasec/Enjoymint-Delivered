<?php 

namespace App\Models;

use CodeIgniter\Model;

class DiscountModel extends Model 
{
  protected $table = 'sale';
  protected $allowedFields = ['pid', 'variant_id', 'discount_value', 'discount_attribute', 'start_date', 'end_date', 'status'];
}