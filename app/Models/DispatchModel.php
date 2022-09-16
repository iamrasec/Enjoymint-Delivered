<?php 

namespace App\Models;

use CodeIgniter\Model;

class DispatchModel extends Model {
  protected $table = 'auto_dispatch';
  protected $allowedFields = ['dispatch_id', 'date'];
}