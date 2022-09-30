<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (file_exists(SYSTEMPATH . 'Config/Routes.php')) {
    require SYSTEMPATH . 'Config/Routes.php';
}

/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
//$routes->setAutoRoute(true);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
$routes->get('/', 'Home::index');

$routes->get('categories/(:any)', 'Categories::index/$1');
$routes->get('experience/(:any)', 'Experience::index/$1');
$routes->get('products/images/(:any)', 'Products::images/$1'); // Show uploaded product images
$routes->get('products/(:any)', 'Products::index/$1');

$routes->get('blogs/images/(:any)', 'Blogs::images/$1');

$routes->get('user', 'User::index', ['filter' => 'noauth']);
$routes->post('save_data', 'Products::save');
$routes->post('save_data', 'Blogs::save');
$routes->get('checkout', 'Users::checkout');
$routes->get('password-reset/(:any)', 'Users::reset_password/$1');
$routes->post('counter', 'Products::index');
$routes->get('search', 'Shop::index');
  

// $routes->get('logout', 'User::logout');
// $routes->match(['get', 'post'], 'register', 'User::register', ['filter' => 'noauth']);
// $routes->match(['get', 'post'], 'profile', 'User::profile', ['filter' => 'auth']);
// $routes->get('dashboard', 'Dashboard::index', ['filter' => 'auth']);

/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (file_exists(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
