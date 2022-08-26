<?php 

namespace App\Models;

use CodeIgniter\Model;

class MeasurementModel extends Model {
  protected $table = 'measurements';
  protected $allowedFields = ['unit'];
}