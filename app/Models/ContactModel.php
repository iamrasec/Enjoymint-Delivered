<?php 

namespace App\Models;

use CodeIgniter\Model;

class ContactModel extends Model {
  protected $table = 'contact';
  protected $allowedFields = ['first_name', 'last_name', 'phone_number', 'email', 'message'];
}