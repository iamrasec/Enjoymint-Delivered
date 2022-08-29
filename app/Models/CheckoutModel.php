<?php

namespace App\Models;

use CodeIgniter\Model;

class CheckoutModel extends Model {

    protected $table = 'orders';
    protected $primarykey = 'id';
    protected $allowedFields = [
        'full_name',
        'c_number',
        'address',
        'product',
        'price',
        'qty',
        'total',
    ];
  
}
?>
