<?php 

namespace App\Models;

use CodeIgniter\Model;

class PromoModel extends Model 
{
  protected $table = 'promotion';
  protected $allowedFields = 
  ['id', 'title', 'url', 'description', 'promo_type', 'discount_value', 'promo_code', 'usage_limit', 'show_products', 'max_prod_discounted', 'mechanics', 'start_date', 'end_date', 'status'];

  public function getPromo($promo) {
    $this->select('promotion.*, promo_products.*');
    $this->join('promo_products', 'promo_products.promo_id = promotion.id', 'left');
    $this->where('promotion.promo_code', $promo);
    return $this->get()->getResult();
  }
  public function getAllPromo() {
    $this->select('promotion.*');
    return $this->paginate(28);
  }

  public function getProductFromUrl($url) {
    $this->select('promotion.*');
    $this->where('promotion.url', $url);
    return $this->get()->getResult();
  }
    
}