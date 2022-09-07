<?php 

namespace App\Models;

use CodeIgniter\Model;

class CartModel extends Model {
  protected $table = 'cart_items';
  protected $allowedFields = ['uid', 'pid', 'qty', 'date_added'];

  public function checkProductExists($uid, $pid) 
  {
    $this->select('id');
    $this->where('uid', $uid);
    $this->where('pid', $pid);
    $this->orderBy('id', 'desc');
    $this->limit(1);
    return $this->get()->getResult();
  }

  public function updateCartProduct($uid, $pid, $qty)
  {
    $this->set('qty', 'qty+'.$qty, false);
    $this->where('uid', $uid);
    $this->where('pid', $pid);
    return $this->update();
  }

  public function cartProductsCount($uid)
  {
    $this->select('count(id) AS count');
    $this->where('uid', $uid);
    return $this->get()->getResult();
  }

  public function delete_cart_item($uid, $pid)
  {
    $this->where('uid', $uid);
    $this->where('pid', $pid);
    return $this->delete();
  }
}