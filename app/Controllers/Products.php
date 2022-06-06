<?php

namespace App\Controllers;

class Products extends BaseController
{
    var $view_data = array();

    public function index()
    {
        echo view('product_view');
    }
}
