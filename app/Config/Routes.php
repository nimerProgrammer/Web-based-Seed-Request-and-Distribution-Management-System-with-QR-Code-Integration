<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

/*** PUBLIC PAGES ***/
$routes->get( '/', 'Home::index' );
// $routes->get('/home', 'Home::index');
// $routes->get('/public', 'Home::index');

/*** ADMIN PAGES ***/
// Admin authentication and dashboard routes
$routes->get( '/admin', 'Admin::index' );
$routes->get( '/admin/login', 'Admin::login' );
$routes->get( '/admin/dashboard', 'Admin::dashboard' );

// Admin management pages
$routes->get( '/admin/inventory', 'Admin::inventory' );
$routes->get( '/admin/seedsRequests', 'Admin::seedsRequests' );
$routes->get( '/admin/beneficiaries', 'Admin::beneficiaries' );
$routes->get( '/admin/reports', 'Admin::reports' );
$routes->get( '/admin/logs', 'Admin::logs' );
$routes->get( '/admin/logout', 'Admin::logout' );

/*** SERVER REQUESTS ***/
// User data API endpoints
$routes->post( '/get_user_data', 'Admin::get_user_data' );
$routes->post( '/get_user_data_by_id', 'Admin::get_user_data_by_id' );

/*** INVENTORY CONTROLLER ACTIONS ***/
// Save, update, and delete inventory items
$routes->post( '/admin/inventory/save', 'Admin\InventoryController::saveInventory' );
$routes->get( '/admin/inventory/delete/(:num)', 'Admin\InventoryController::deleteInventory/$1' );
$routes->post( '/admin/inventory/update/(:num)', 'Admin\InventoryController::updateInventory/$1' );

/*** SEED REQUESTS CONTROLLER ACTIONS ***/
// Approve / Undo Approve
$routes->post( 'admin/seedrequests/approve/(:num)', 'Admin\SeedRequestsController::approve/$1' );
$routes->post( 'admin/seedrequests/undoApproved/(:num)', 'Admin\SeedRequestsController::undoApproved/$1' );
// Reject / Undo Reject
$routes->post( 'admin/seedrequests/reject/(:num)', 'Admin\SeedRequestsController::reject/$1' );
$routes->post( 'admin/seedrequests/undoRejected/(:num)', 'Admin\SeedRequestsController::undoRejected/$1' );

/*** BENEFICIARIES CONTROLLER ACTIONS ***/
// Route to mark a beneficiary as received
$routes->post( 'admin/beneficiaries/markReceived/(:num)', 'Admin\BeneficiariesController::markReceived/$1' );
// Route to undo a received beneficiary
$routes->post( 'admin/beneficiaries/undoReceive/(:num)', 'Admin\BeneficiariesController::undoReceive/$1' );

