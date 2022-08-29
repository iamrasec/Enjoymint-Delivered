<?php

namespace App\Models;

use CodeIgniter\Model;

class ForgotPasswordModel extends Model {

    protected $table = 'forgot_password';
    protected $primarykey = 'id';
    protected $allowedFields = [
        'unique_id',
        'user_id',
    ];
  
}
?>