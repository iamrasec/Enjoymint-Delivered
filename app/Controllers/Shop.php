<?php

namespace App\Controllers;

class Shop extends BaseController
{
    var $view_data = array();

    public function index()
    {
        echo view('shop_view');
    }
}
