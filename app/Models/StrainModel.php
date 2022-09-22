<?php 

namespace App\Models;

use CodeIgniter\Model;

class StrainModel extends Model {
  protected $table = 'strains';
  protected $allowedFields = ['name', 'url_slug'];

  public function strainProducts($id) {
    $this->select('products.*, compounds.thc_unit, compounds.thc_value, compounds.cbd_unit, compounds.cbd_value, strains.url_slug');
    $this->join('strains', 'strains.id = products.strain', 'left');
    $this->join('compounds', 'compounds.pid = products.id', 'left');
    $this->where('strains.id', $id);
    return $this->get()->getResult();
  }
}