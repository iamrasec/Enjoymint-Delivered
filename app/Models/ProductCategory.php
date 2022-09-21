<?php 

namespace App\Models;

use CodeIgniter\Model;

class ProductCategory extends Model {
  protected $table = 'product_categories';
  protected $allowedFields = ['pid', 'cid'];

  public function getAllProducts() {
    $this->select('products_categories.*, products.thc_unit, compounds.thc_value, compounds.cbd_unit, compounds.cbd_value, product_categories. pid');
    $this->join('compounds', 'compounds.pid = products.id', 'left');
    $this->join('product_categories', 'product_categories.pid = products.id', 'inner');
    $this->paginate(30);
   
  }
}