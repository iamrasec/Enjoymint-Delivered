<?php

namespace App\Models;

use CodeIgniter\Model;

class Location extends Model {

    protected $table = 'location';
    protected $primarykey = 'id';
    protected $allowedFields = [
        'address',
        'user_id',
      
    ];

    public function getLocation($user_id) 
    {
      $this->select('location.address');
      $this->where('location.user_id', $user_id);
      $this->limit('1');
      return $this->get()->getResult();
    }
  
}
?>