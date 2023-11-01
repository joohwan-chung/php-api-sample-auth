<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->post('/api/create-users', 'UserApiController::create');
$routes->post('/api/login-users', 'UserApiController::login');
// $routes->get('/api/read-users', 'UserApiController::readUser', ['filter' => 'auth']);
$routes->get('/api/read-users', 'UserApiController::readUser');