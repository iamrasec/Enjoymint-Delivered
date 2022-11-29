<?php

namespace App\Models;

use CodeIgniter\Model;

class CheckoutModel extends Model {

    protected $table = 'orders';
    protected $primarykey = 'id';
    protected $allowedFields = [
        'order_key',
        'customer_id',
        'first_name',
        'last_name',
        'email',
        'phone',
        'address',
        'subtotal',
        'tax',
        'total',
        'payment_method',
        'created',
        'modified',
        'order_notes',
        'delivery_schedule',
        'delivery_time',
        'delivery_type',
    ];
    
    public function fetchOrderDetails($key)
    {
        $this->select('order_products.*, products.*, compounds.thc_unit, compounds.thc_value, compounds.cbd_unit, compounds.cbd_value, strains.name AS strain_name, strains.url_slug AS strain_url');
        $this->join('order_products', 'orders.id = order_products.order_id', 'inner');
        $this->join('products', 'order_products.product_id = products.id', 'inner');
        $this->join('strains', 'strains.id = products.strain', 'left');
        $this->join('compounds', 'compounds.pid = products.id', 'left');
        $this->where('orders.order_key', $key);
        return $this->get()->getResult();
    }
}
?>
