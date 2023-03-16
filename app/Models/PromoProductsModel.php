<?php 

namespace App\Models;

use CodeIgniter\Model;

class PromoProductsModel extends Model 
{
  protected $table = 'promo_products';
  protected $allowedFields = ['id', 'promo_id','promo_product', 'discounted_specific_product', 'discounted_category_id','require_purchase',
                              'required_product_id','required_category_id','required_qty_purchase','qty_product_discounted' ];

  
public function getPromo($promo)
 {
           $this->select('promo_products.*','promotion.*');
           $this->join('promotion', 'promotion.id = promo_products.promo_id  ', 'left');
           $this->where('promotion.promo_code', $promo);
            return $this->get()->getResult();
                             
 }
}
