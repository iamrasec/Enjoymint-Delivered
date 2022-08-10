<?php 

namespace App\Models;

use CodeIgniter\Model;

class RatingModel extends Model {
  protected $table = 'ratings';
  protected $allowedFields = ['name', 'message', 'rating', 'status'];
}