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

    public function getProductData($pid)
    {
        $this->select('v_all_products.*');
        $this->join('v_all_products', 'order_products.product_id = v_all_products.id', 'inner');
        $this->where('order_products.product_id', $pid);
        return $this->get()->getResult();
    }
}
?>
