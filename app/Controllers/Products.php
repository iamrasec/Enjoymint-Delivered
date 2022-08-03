<?php

namespace App\Controllers;

class Products extends BaseController
{
    var $view_data = array();

    public function index($url)
    {
        echo view('product_view');
    }
}
