<?php 

namespace App\Models;

use CodeIgniter\Model;

class RatingModel extends Model {
  protected $table = 'rating';
  protected $allowedFields = [ 'product_id','customer_id','message', 'star','is_rated','created'];

  
//   public function count_total_rating($id = NULL) {
//     $this->select('star, AVG(`star`) As avg_r',FALSE); 
//     $this->where('product_id', $id);
//     $this->from('rating');
//     return $this->get()->getResult();

// }

public function getUserId($id = null){
  $this->select('rating.*, users.first_name, last_name');
  $this->join('users', 'users.id = rating.customer_id', 'inner');
  // $this->join('rating', 'rating.customer_id = users.id', 'left');
  $this->where('rating.product_id', $id);
  
  return $this->get()->getResult();
}

  // public function getAvgRatings($id = null){
  //   $this->select('product_id, COUNT(star) AS total_ratings, AVG(star) AS average_ratings');
  //   $this->where('product_id', $id);
  //   $this->groupBy('product_id');
  //   return $this->get()->getResult();
  // }

}