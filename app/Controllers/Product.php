<?php

namespace App\Controllers;

class Product extends BaseController
{
    var $view_data = array();

    public function index()
    {
        echo view('product_view');
    }
}
