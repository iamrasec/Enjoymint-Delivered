<?php 

namespace App\Models;

use CodeIgniter\Model;

class CompoundModel extends Model {
  protected $table = 'compounds';
  protected $allowedFields = ['pid', 'thc_pct', 'thc_mg', 'cbd_pct', 'cbd_mg'];
}