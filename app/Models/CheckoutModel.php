<?php

namespace App\Models;

use CodeIgniter\Model;

class CheckoutModel extends Model {

    protected $table = 'orders';
    protected $primarykey = 'id';
    protected $allowedFields = [
        'customer_id',
        'first_name',
        'last_name',
        'address',
        'subtotal',
        'tax',
        'total',
        'payment_method',
        'created',
        'modified',
    ];
    
    public function save_order_products($data)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('order_products');

        
    }
}
?>
