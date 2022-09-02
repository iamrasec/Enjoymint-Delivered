<?php 

namespace App\Models;

use CodeIgniter\Model;

class RatingModel extends Model {
  protected $table = 'rating';
  protected $allowedFields = [ 'message', 'star'];
}