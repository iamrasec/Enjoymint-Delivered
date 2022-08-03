<?php 

namespace App\Models;

use CodeIgniter\Model;

class CategoryModel extends Model {
  protected $table = 'categories';
  protected $allowedFields = ['name', 'url', 'parent', 'weight'];

  public function categoryGetProducts($cid) {
    $this->select('products.*, compounds.thc_pct, compounds.thc_pct, compounds.thc_mg, compounds.cbd_pct, compounds.cbd_mg');
    $this->join('product_categories', 'product_categories.cid = categories.id', 'inner');
    $this->join('products', 'products.id = product_categories.pid', 'inner');
    $this->join('compounds', 'compounds.pid = products.id', 'left');
    return $this->get()->getResult();
  }
}