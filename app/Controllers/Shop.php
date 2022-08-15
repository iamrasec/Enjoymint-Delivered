<?php

namespace App\Controllers;

class Shop extends BaseController
{
    var $view_data = array();


    public function index()
    {
        $session = session();
		$data = [
			'product' => 'Minntz Indoor Flowers - Big Apple (3.5g Indica) - Cookies',
			'price'  => '33.50',
			'qty'  => '32',
			'total'  => '100',
		];
		$session->set('userorder', $data);
        echo view('shop_view');
    }
}
