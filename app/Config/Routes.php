<?php

use CodeIgniter\Router\RouteCollection;
$routes->setDefaultController('Home');
$routes->setAutoRoute(true);

/**
 * @var RouteCollection $routes
 */

// Auth Area
$routes->group('auth', function($routes) {
    $routes->post('login', 'Auth::proseslogin');
    $routes->post('register', 'Auth::prosesregister');
});

$routes->get('dashboard', 'User::dashboard');
