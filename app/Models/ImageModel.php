<?php 

namespace App\Models;

use CodeIgniter\Model;

class ImageModel extends Model {
  protected $table = 'images';
  protected $allowedFields = ['filename', 'mime', 'url'];


  public function getFile($pid) {
    $this->select('images.*');
    $this->join('products', 'images.id = products.images');
    $this->where('products.id', $pid);
    return $this->get()->getResult();
  }
}