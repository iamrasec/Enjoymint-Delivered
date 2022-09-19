<?php

namespace App\Models;

use CodeIgniter\Model;

class Drivers extends Model {

    protected $table = 'drivers';
    protected $primarykey = 'id';
    protected $allowedFields = [
        'order_id',
        'name',
      
    ];
  
}
?>
