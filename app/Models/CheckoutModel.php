<?php

namespace App\Models;

use CodeIgniter\Model;

class CheckoutModel extends Model {

    protected $table = 'orders';
    protected $primarykey = 'id';
    protected $allowedFields = [
        'user_id',
        'full_name',
        'c_number',
        'address',
        'product',
        'price',
        'qty',
        'total',
        'status',
    ];
  
}
?>
