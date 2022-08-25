<?php 

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model {
  protected $table = 'users';
  protected $primarykey = 'id';
  protected $allowedFields = ['guid', 'first_name', 'last_name', 'email', 'role', 'password', 'updated_at'];
  protected $beforeInsert = ['beforeInsert'];
  protected $beforeUpdate = ['beforeUpdate'];

  protected function beforeInsert(array $data) {
    $data = $this->passwordHash($data);

    return $data;
  }

  protected function beforeUpdate(array $data) {
    $data = $this->passwordHash($data);

    return $data;
  }

  protected function passwordHash(array $data) {
    if(isset($data['data']['password'])) {
      $data['data']['password'] = password_hash($data['data']['password'], PASSWORD_DEFAULT);
    }

    return $data;
  }

  public function saveUser($data) {
    
  }

  public function getUser($data) {
    return $this->where('email', $data['email'])->first();
  }

  public function getUserByGuid($guid) {
    return $this->where('guid', $guid)->first();
  }

  public function verifyEmail($email)
  {
    $builder = $this->db->table('users');
    $builder->select("id, guid, first_name, last_name, password");
    $builder->where('email', $email);
    $result = $builder->get();
    if(count($result->getResultArray())==1)
    {
      return $result->getRowArray();
    }
    else{
      return false;
    }
  }

  public function passwordVerify($new_password)
  {
    $builder = $this->db->table('users');
    $builder->select("id, guid, first_name, last_name, password");
    $builder->where('password', $new_password);
    $result = $builder->get();
    if(count($result->getResultArray())==1)
    {
      return $result->getRowArray();
    }
    else{
      return false;
    }
  }

  // public function getLoggedInUserData($id)
  // {
  //   $builder = $this->db->table('users');
  //   $builder->where('id', $id);
  //   $result = $builder->get();
  //   if(count($result->getResultArray())==1)
  //   {
  //     return $result->getRowArray();
  //   }
  //   else{
  //     return false;
  //   }
  // }
  

  // public function updatedAt($id){
  //   $builder = $this->db->table('users');
  //   $builder-> where('guid', $id);
  //   $builder-> update(['updated_at'=>date('Y-m-d h:i:s')]);
  //   if($this->db->affectedRows()==1)
  //   {
  //     return true;
  //   }
  //   else{
  //     return false;
  //   }
  // }
   
 
}