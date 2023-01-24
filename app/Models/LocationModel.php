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

    public function getLocation($id, $user_id) 
    {
      $this->select('location.address');
      $this->where('location.user_id', $user_id);
      $this->limit('1');
      return $this->get()->getResult();
    }
  
    public function verifyUser($user_id)
    {
      $builder = $this->db->table('location');
      $builder->select("user_id");
      $builder->where('user_id', $user_id);
      $result = $builder->get();
      if(count($result->getResultArray())==1)
      {
        return $result->getRowArray();
      }
      else{
        return false;
    }
  }
}
?>