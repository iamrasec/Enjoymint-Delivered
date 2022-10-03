<?php

use App\Models\ImageModel;
use App\Models\ProductModel;

function getProductImage($pid)
{
  $productModel = new ProductModel();
  $product = $productModel->where('id', $pid)->first();

  // return $product;
  
  $imageModel = new ImageModel();

  $images = array();

  $imageIds = [];
  if($product['images']) {
      $imageIds = explode(',',$product['images']);
      $images = $imageModel->whereIn('id', $imageIds)->get()->getResult();
  }

  return $images;  
}