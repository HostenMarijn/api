<?php
require 'vendor/autoload.php';
use Medoo\Medoo;

include 'helpers.php';
include 'api-routes.php';

$database = new Medoo([
    'database_type' => 'mysql',
    'database_name' => 'bears',
    'server' => 'localhost',
    'username' => 'root',
    'password' => 'root'
]);


// make database globally accessible in Flight framework
Flight::set("db", $database);

/* ROUTES */

// website home page
Flight::route('GET /', function () {
    Flight::render('public/home.php');
});

/* API ROUTES */
Flight::route('GET /api', function () {
    echo 'Welcome to the API';
});

apiUserRoutes(); // login, token, ...

apiBearRoutes(); // CRUD

apiCmsRoutes(); // content of website

// fallback when getting something non-existing, just redirect to home
Flight::route('GET /api/*', function () {
    Flight::redirect('/api');
});

Flight::start();


?>
