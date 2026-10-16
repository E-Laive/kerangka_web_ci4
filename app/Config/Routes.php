<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('/', 'Home::index');
$routes->get('setup', 'Auth::setup');
$routes->post('setup', 'Auth::createFirstUser');
$routes->get('login', 'Auth::login');
$routes->post('login', 'Auth::authenticate');
$routes->post('logout', 'Auth::logout', ['filter' => 'auth']);

$routes->group('', ['filter' => 'auth'], static function ($routes): void {
    $routes->get('dashboard', 'Dashboard::index', ['filter' => 'permission:catalog.view']);
    $routes->get('ekspor/(:segment)/(:segment)', 'Export::download/$1/$2');
    $routes->get('katalog/(:segment)', 'Showcase::page/$1');
    $routes->get('pratinjau-error', 'ErrorPreview::index', ['filter' => 'permission:catalog.view']);
    $routes->get('dokumen/(:segment)/pdf', 'Documents::pdf/$1', ['filter' => 'permission:documents.view']);
    $routes->get('dokumen/(:segment)', 'Documents::preview/$1', ['filter' => 'permission:documents.view']);
});
