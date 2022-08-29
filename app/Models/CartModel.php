<?php 

namespace App\Models;

use CodeIgniter\Model;

class CartModel extends Model {
  protected $table = 'cart_items';
  protected $allowedFields = ['uid', 'pid', 'qty', 'date_added'];

  
}