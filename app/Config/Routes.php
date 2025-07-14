<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */


/*** LOGIN ***/
$routes->get( '/admin/login', 'Admin\Admin::login' );
/*** SERVER REQUESTS ***/
// User data API endpoints
$routes->post( '/get_user_data', 'Admin\Admin::get_user_data' );

/*** PUBLIC PAGES ***/
$routes->get( '/', 'Public\Home::index' );
$routes->get( '/public/home', 'Public\Home::home' );

$routes->get( '/public/sentRequests', 'Public\Home::sentRequests' );
$routes->get( '/public/profile', 'Public\Home::profile' );
$routes->post( '/public/checker', 'Public\ProfileController::checker' );
$routes->post( '/public/updateFullname', 'Public\ProfileController::updateFullname' );
$routes->post( '/public/updateGender', 'Public\ProfileController::updateGender' );
$routes->post( '/public/updateBirthdate', 'Public\ProfileController::updateBirthdate' );
$routes->post( '/public/updateBarangay', 'Public\ProfileController::updateBarangay' );
$routes->post( '/public/updateFarmArea', 'Public\ProfileController::updateFarmArea' );
$routes->post( '/public/updateLandOwner', 'Public\ProfileController::updateLandOwner' );
$routes->post( '/public/updateRSBSA', 'Public\ProfileController::updateRSBSA' );
$routes->post( '/public/updateContactNo', 'Public\ProfileController::updateContactNo' );
$routes->post( '/public/updateEmail', 'Public\ProfileController::updateEmail' );
$routes->post( '/public/updateUsername', 'Public\ProfileController::updateUsername' );
$routes->post( '/public/checkCurrentPassword', 'Public\ProfileController::checkCurrentPassword' );
$routes->post( '/public/changePassword', 'Public\ProfileController::changePassword' );



$routes->post( '/public/downloadVoucher', 'Public\SentRequestsController::downloadVoucher' );
$routes->post( '/public/sentRequest/edit', 'Public\SentRequestsController::editSentRequest' );
$routes->post( 'public/sentRequests/cancel', 'Public\SentRequestsController::cancelRequest' );


$routes->get( '/public/signUp', 'Public\Home::signUp' );
$routes->post( '/public/login', 'Public\LoginController::login' );
/* login credentials */
$routes->post( '/public/login/check_credentials', 'Public\LoginController::check_credentials' );
$routes->get( '/public/logout', 'Public\Home::logout' );

$routes->post( '/public/signUp/submitSignUp', 'Public\SignUpController::submitSignUp' );
$routes->post( '/public/signUp/checker', 'Public\SignUpController::checker' );
$routes->post( '/public/request_seed/submit', 'Public\RequestSeedController::requestSeedSubmit' );




/*** ADMIN PAGES ***/
// Admin authentication and dashboard routes
$routes->get( '/admin', 'Admin\Admin::index' );
$routes->get( '/admin/dashboard', 'Admin\Admin::dashboard' );
// Admin management pages
$routes->get( '/admin/publicPage', 'Admin\Admin::publicPage' );
$routes->get( '/admin/inventory', 'Admin\Admin::inventory' );
$routes->get( '/admin/seedsRequests', 'Admin\Admin::seedsRequests' );
$routes->get( '/admin/beneficiaries', 'Admin\Admin::beneficiaries' );
$routes->get( '/admin/reports', 'Admin\Admin::reports' );
$routes->get( '/admin/logs', 'Admin\Admin::logs' );
$routes->get( '/admin/logout', 'Admin\Admin::logout' );

/*** PUBLIC PAGE CONTROLLER ACTIONS ***/
$routes->post( '/admin/uploadPost', 'Admin\PublicPageController::uploadPost' );
$routes->get( '/admin/deletePost/(:num)', 'Admin\PublicPageController::deletePost/$1' );
$routes->post( '/admin/updatePost', 'Admin\PublicPageController::updatePost' );



/*** INVENTORY CONTROLLER ACTIONS ***/
// Save, update, and delete inventory items
$routes->post( '/admin/inventory/save', 'Admin\InventoryController::saveInventory' );
$routes->get( '/admin/inventory/delete/(:num)', 'Admin\InventoryController::deleteInventory/$1' );
$routes->post( '/admin/inventory/update/(:num)', 'Admin\InventoryController::updateInventory/$1' );


/*** SEED REQUESTS CONTROLLER ACTIONS ***/
$routes->post( '/admin/seedrequests/setBarangayView', 'Admin\SeedRequestsController::setBarangayView' );
// Approve / Undo Approve
$routes->post( '/admin/seedrequests/approve/(:num)', 'Admin\SeedRequestsController::approve/$1' );
$routes->post( '/admin/seedrequests/undoApproved/(:num)', 'Admin\SeedRequestsController::undoApproved/$1' );
// Reject / Undo Reject
$routes->post( '/admin/seedrequests/reject/(:num)', 'Admin\SeedRequestsController::reject/$1' );
$routes->post( '/admin/seedrequests/undoRejected/(:num)', 'Admin\SeedRequestsController::undoRejected/$1' );


/*** BENEFICIARIES CONTROLLER ACTIONS ***/
$routes->post( '/admin/beneficiaries/setBarangayView', 'Admin\BeneficiariesController::setBarangayView' );

// Route to mark a beneficiary as received
$routes->post( '/admin/beneficiaries/markReceived/(:num)', 'Admin\BeneficiariesController::markReceived/$1' );
// Route to undo a received beneficiary
$routes->post( '/admin/beneficiaries/undoReceive/(:num)', 'Admin\BeneficiariesController::undoReceive/$1' );

/*** REPORTS CONTROLLER ACTIONS ***/
$routes->post( '/admin/reports/setReportBarangayView', 'Admin\ReportsController::setReportBarangayView' );

$routes->get( '/admin/reports/setListView/(:segment)', 'Admin\ReportsController::setListView/$1' );
$routes->post( '/admin/reports/setSeasonView', 'Admin\ReportsController::setSeasonView' );



$routes->post( '/admin/reports/exportToExcel', 'Admin\ReportsController::exportToExcel' );
$routes->post( '/admin/reports/seedRequestExportToPDF', 'Admin\ReportsController::seedRequestExportToPDF' );
$routes->post( '/admin/reports/beneficiariesExportToPDF', 'Admin\ReportsController::beneficiariesExportToPDF' );

/*** LOGS CONTROLLER ACTIONS ***/
$routes->post( '/admin/logs/clearLogs', 'Admin\LogsController::clearLogs' );


