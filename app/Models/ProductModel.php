<?php 

namespace App\Models;

use CodeIgniter\Model;

class ProductModel extends Model {
  protected $table = 'products';
  protected $allowedFields = ['name', 'url', 'description', 'price', 'stocks', 'strain', 'brands', 'sku', 'unit_measure', 'unit_value', 'images'];
 
  public function getAllProducts() {
    $this->select('products.*, compounds.thc_unit, compounds.thc_value, compounds.cbd_unit, compounds.cbd_value');
    $this->join('compounds', 'compounds.pid = products.id', 'left');
    return $this->get()->getResult();
  }

  public function getProductData($pid) {
    $this->select('products.*, compounds.thc_unit, compounds.thc_value, compounds.cbd_unit, compounds.cbd_value, strains.name, strains.url_slug');
    $this->join('strains', 'strains.id = products.strain', 'left');
    $this->join('compounds', 'compounds.pid = products.id', 'left');
    $this->where('products.id', $pid);
    return $this->get()->getResult()[0];
  }

  public function getProductFromUrl($url) {
    $this->select('products.*, compounds.thc_unit, compounds.thc_value, compounds.cbd_unit, compounds.cbd_value, strains.name AS strain_name, strains.url_slug AS strain_url');
    $this->join('strains', 'strains.id = products.strain', 'left');
    $this->join('compounds', 'compounds.pid = products.id', 'left');
    $this->where('products.url', $url);
    return $this->get()->getResult();
  }
}