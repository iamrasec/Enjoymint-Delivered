<?php 

namespace App\Models;

use CodeIgniter\Model;

class StrainModel extends Model {
  protected $table = 'strains';
  protected $allowedFields = ['name', 'url_slug'];
}