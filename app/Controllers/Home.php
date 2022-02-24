<?php

namespace App\Controllers;

class Home extends BaseController
{
    var $view_data = array();

    public function index()
    {
        echo view('home_view');
    }
}
