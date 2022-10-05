<?php 

namespace App\Models;

use CodeIgniter\Model;

class VerificationModel extends Model {
  protected $table = 'customer_verification';
  protected $allowedFields = ['user_id', 'images', 'status'];

   
  public function getAllVerification($start = null, $length=null) 
  { 
    $this->select('customer_verification.id AS cv_id, customer_verification.*, users.*');
    $this->join('users', 'customer_verification.user_id = users.id');
    $this->groupBy('customer_verification.user_id');
    // $this->like('first_name',$_POST['search']['value']);
    // $this->orLike('last_name',$_POST['search']['value']);

    if($start != null && $length != null){
      $this->limit($length, $start);
    }

    return $this->get()->getResult();
  }

}