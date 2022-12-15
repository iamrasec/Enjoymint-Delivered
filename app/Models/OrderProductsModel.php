<?php

namespace App\Models;

use CodeIgniter\Model;

class OrderProductsModel extends Model {

    protected $table = 'order_products';
    protected $primarykey = 'id';
    protected $allowedFields = [
        'order_id',
        'product_id',
        'product_name',
        'qty',
        'unit_price',
        'is_sale',
        'regular_price',
        'total',
        
    ];

}
?>
