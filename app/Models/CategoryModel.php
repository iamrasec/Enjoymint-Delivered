<?php 

namespace App\Models;

use CodeIgniter\Model;

class CategoryModel extends Model {
  protected $table = 'categories';
  protected $allowedFields = ['name', 'url', 'parent', 'weight'];

  /** 
   * Get Products in a Particular Category
   * 
   */
  public function categoryGetProducts($cid) {
    $this->select('products.*, compounds.thc_unit, compounds.thc_value, compounds.cbd_unit, compounds.cbd_value');
    $this->join('product_categories', 'product_categories.cid = categories.id', 'inner');
    $this->join('products', 'products.id = product_categories.pid', 'inner');
    $this->join('compounds', 'compounds.pid = products.id', 'left');
    $this->where('categories.id', $cid);
    return $this->get()->getResult();
  }
}