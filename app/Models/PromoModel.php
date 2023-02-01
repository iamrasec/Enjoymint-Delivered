<?php 

namespace App\Models;

use CodeIgniter\Model;

class PromoModel extends Model 
{
  protected $table = 'promotion';
  protected $allowedFields = ['id', 'title', 'url', 'description', 'promo_type', 'mechanics', 'start_date', 'end_date', 'status'];
}