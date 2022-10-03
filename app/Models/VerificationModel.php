<?php 

namespace App\Models;

use CodeIgniter\Model;

class VerificationModel extends Model {
  protected $table = 'customer_verification';
  protected $allowedFields = ['user_id', 'images', 'status'];


}