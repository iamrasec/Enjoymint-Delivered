<?php 

namespace App\Models;

use CodeIgniter\Model;

class ExperienceModel extends Model {
  protected $table = 'experience';
  protected $allowedFields = ['name', 'url'];

}