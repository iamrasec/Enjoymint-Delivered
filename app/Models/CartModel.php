<?php 

namespace App\Models;

use CodeIgniter\Model;

class CartModel extends Model {
  protected $table = 'cart_items';
  protected $allowedFields = ['uid', 'pid', 'qty', 'date_added', 'vid'];

  /**
   * Checks Cart if Product is already added.
   *
   * @param integer $uid   User ID
   * @param integer $pid   Product ID
   * @param integer $vid  Variant ID.  vid=0 is base product
   * @return array
   */
  public function checkProductExists($uid, $pid, $vid=0) 
  {
    $this->select('id');
    $this->where('uid', $uid);
    $this->where('pid', $pid);
    $this->where('vid', $vid);
    $this->orderBy('id', 'desc');
    $this->limit(1);
    return $this->get()->getResult();
  }

  /**
   * Update Cart if Product is already added.
   *
   * @param integer $uid  User ID
   * @param integer $pid  Product ID
   * @param integer $qty  Quantity
   * @param integer $vid  Variant ID.  vid=0 is base product
   * @return bool
   */
  public function updateCartProduct($uid, $pid, $qty, $vid=0)
  {
    $this->set('qty', 'qty+'.$qty, false);
    $this->where('uid', $uid);
    $this->where('pid', $pid);
    $this->where('vid', $vid);
    return $this->update();
  }

  /**
   * Count number of products in the Cart
   *
   * @param integer $uid  User ID
   * @return array
   */
  public function cartProductsCount($uid)
  {
    $this->select('count(id) AS count');
    $this->where('uid', $uid);
    return $this->get()->getResult();
  }

  /**
   * Delete Product from Cart
   *
   * @param integer $uid  User ID
   * @param integer $pid  Product ID
   * @param integer $vid  Variant ID.  vid=0 is base product
   * @return bool
   */
  public function delete_cart_item($uid, $pid,)
  {
    $this->where('uid', $uid);
    $this->where('pid', $pid);
    return $this->delete();
  }
}