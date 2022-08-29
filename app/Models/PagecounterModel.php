<?php

namespace App\Models;

use CodeIgniter\Model;

class PageCounterModel extends Model {

    protected $table = 'page_counter';
    protected $primarykey = 'id';
    protected $allowedFields = [
        'product_name',
        'ip_views',
    ];

    public function getNumRows(){
        $builder = $this->db->table('page_counter');
        $builder->select("id, ip_views");
        $result = $builder->get();
        if(count($result->getResult())==1)
        {
          return $result->getRowArray();
        }
        else{
          return false;
        }
        
      }
  
}
?>