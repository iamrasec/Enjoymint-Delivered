<?php

namespace App\Models;

use CodeIgniter\Model;

class BlogModel extends Model {

    protected $table = 'blogs';
    protected $primarykey = 'id';
    protected $allowedFields = ['title', 'url', 'description', 'content', 'author', 'images'];
  
    public function getBlogbyID($id) 
      {
        $this->select('blogs.*, images.url');
        $this->join('images', 'blogs.images = images.id');
        $this->where('blogs.id', $id);
        return $this->get()->getResult();
      }
} 
?>
