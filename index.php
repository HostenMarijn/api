<?php
require 'vendor/autoload.php';
use Medoo\Medoo;

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

// api home
Flight::route('GET /api', function () {
    echo 'Welcome to the API';
});

// user login to cms
Flight::route('POST /api/login', function () {

    $db = Flight::get("db");
    $postData = Flight::request()->data->getData();

    //TODO: VALIDATION!
    $login = $postData["login"];
    $pw = encrypt_decrypt("encrypt", $postData["password"]);

    $user = $db->get("users", ["id", "login", "password"], ["login" => $login, "password" => $pw]);

    // when logged in, set token that expires, then on cms page, first check for token, if none found, redirect to login
    if($user) {

      // set cookie : HASH: (salt + user name + password + timestamp + ip address)
      $cookie_name = "m-token";
      $token_expiration = date("Y-m-d H:i:s", strtotime('+8 hours'));

      $encrypted = encrypt_decrypt("encrypt", "You in boi!-=-" . $user["login"] . "-=-" . $user["password"] . "-=-" . $token_expiration);
      $cookie_value = $encrypted;

      setcookie($cookie_name, $cookie_value, time() + (86400 * 30 * 8), "/"); //8 days

      // set token
      $db->update("users", ["token_expiration" => $token_expiration], ["id" => $user["id"]]);

      echo json_encode(["success" => 1, "redirect_url" => "/cms"]);

    } else {
      echo json_encode(["error" => "Invalid login or password"]);
    }
});

Flight::route('POST /api/authtoken', function () {

  $db = Flight::get("db");
  $postData = Flight::request()->data->getData();

  $token = $postData["m-token"];
  $decrypted;
  $response = "Token assigned to ";

  if ($token) {

    $decrypted = encrypt_decrypt("decrypt", $token);
    $parts = explode("-=-", $decrypted); // 0:salt, 1:name, 2:pass, 3":expiration_time"

    if ($parts && count($parts) === 4) {
      $login = $parts[1];
      $password = $parts[2];
      $token_expiration = $parts[3];

      $user = $db->get("users", ["id", "login"], ["login" => $login, "password" => $password]);

      if ($user) {

        // check if token expiration date is still valid
        if ($token_expiration > date("Y-m-d H:i:s")) {

          echo json_encode(["success" => 1, "message" => "User found!", "data" => $user]);
          return;
        }
      }
    }
  }

  echo json_encode(["error" => 1, "message" => "Invalid token", "redirect_url" => "/login"]);
});

// get all bears
Flight::route('GET /api/bears', function () {

    $db = Flight::get("db");
    $bears = $db->select('bears', '*', ["deleted" => 0]);
    echo json_encode($bears);
});

// get single bear
Flight::route('GET /api/bears/@id', function ($id) {

    $db = Flight::get("db");
    $bear = $db->get('bears', '*', ["id" => $id, "deleted" => 0]);
    echo json_encode($bear);
});

// create new and send back all after creation
Flight::route('POST /api/bears', function () {

    $db = Flight::get("db");
    $postData = Flight::request()->data->getData(); // get POST data

    $db->insert("bears", [
       "name" => $postData["name"],
       "home" => $postData["home"]
    ]);

    $account_id = $db->id(); // last inserted bear id

    $bear = $db->get("bears", "*", ["id" => $account_id]);

    echo json_encode($bear);
});

// remove bear (soft delete)
Flight::route('PUT /api/bears/@id', function($id) {

    $db = Flight::get("db");

    $request = $db->update("bears", ["deleted" => 1], ["id" => $id]);

    // row changed?
    if ($request->rowCount() > 0 ) {
        echo json_encode(["msg" => "success"]);
    } else {
        echo json_encode(["msg" => "error"]);
    }


});

// fallback when getting something non-existing, just redirect to home
Flight::route('GET /api/*', function () {
    Flight::redirect('/api');
});

Flight::start();

function encrypt_decrypt($action, $string) {
    $output = false;
    $encrypt_method = "AES-256-CBC";
    $secret_key = 'supersecretstuff';
    $secret_iv = 'initializationvectororsomething';
    // hash
    $key = hash('sha256', $secret_key);

    // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
    $iv = substr(hash('sha256', $secret_iv), 0, 16);
    if ( $action == 'encrypt' ) {
        $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
        $output = base64_encode($output);
    } else if( $action == 'decrypt' ) {
        $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
    }
    return $output;
}
?>
