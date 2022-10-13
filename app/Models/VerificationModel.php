<?php 

namespace App\Models;

use CodeIgniter\Model;

class VerificationModel extends Model {
  protected $table = 'customer_verification';
  protected $allowedFields = ['user_id', 'image_validID', 'image_profile', 'image_MMIC', 'status','denial_message'];

  public function getVerificationData($id){
    $this->select('customer_verification.*, images.filename');
    $this->join('images', 'images.id = customer_verification.images');
    $this->where('customer_verification.id', $id);
    return $this->get()->getResult();
  }
  public function getAllVerification($start = null, $length=null) 
  { 
    $this->select('customer_verification.id AS cv_id, customer_verification.*, users.*');
    $this->join('users', 'customer_verification.user_id = users.id');
    
    // $this->groupBy('customer_verification.user_id');
    // $this->like('first_name',$_POST['search']['value']);
    // $this->orLike('last_name',$_POST['search']['value']);

    if($start != null && $length != null){
      $this->limit($length, $start);
    }

    return $this->get()->getResult();
  }

  public function verifyUserID($id){
    $builder = $this->db->table('customer_verification');
    $builder->select("*");
    $builder->where('id', $id);
    $result = $builder->get();
    if(count($result->getResultArray())==1)
    {
      return $result->getRowArray();
    }
    else{
      return false;
    }
  }

  public function verifyUser($user_id){
    $builder = $this->db->table('customer_verification');
    $builder->select("*");
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