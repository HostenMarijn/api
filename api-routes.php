<?php


function apiUserRoutes() {

  // user login to cms
  Flight::route('POST /api/login', function () {

      $db = Flight::get("db");
      $postData = Flight::request()->data->getData();

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

  // check token for validation
  Flight::route('POST /api/authtoken', function () {

    $db = Flight::get("db");
    $postData = Flight::request()->data->getData();

    $token = $postData["m-token"];

    $tokenData = validateToken($token);

    if ($tokenData["valid"]) {

      echo json_encode(["success" => 1, "message" => "User found!", "data" => $tokenData["user"]]);
    }

    else {
      echo json_encode(["error" => 1, "message" => "Invalid token", "redirect_url" => "/login"]);
    }
  });
}

function apiBearRoutes() {
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
}

function apiCmsRoutes() {

  // get content by pageId
  Flight::route('GET /api/cms/page/@id', function($id) {

    $token = $_COOKIE["m-token"];

    $tokenData = validateToken($token);

    if (!$tokenData["valid"]) {
      echo json_encode("No valid token provided");
      return;
    }

    $db = Flight::get("db");
    $pageContent = $db->select('page_content', '*', ["pageId" => $id]);

    echo json_encode($pageContent);
  });

  // get all content
  Flight::route('GET /api/cms/pages', function() {

    $token = $_COOKIE["m-token"];

    $tokenData = validateToken($token);

    if (!$tokenData["valid"]) {
      echo json_encode("No valid token provided");
      return;
    }

    $db = Flight::get("db");
    $pageContent = $db->select('page_content', '*', ['pageId[!]' => 0]);

    echo json_encode($pageContent);
  });

  // update content of a single piece of text
  Flight::route('POST /api/cms/page/@id', function($id) {

    $token = $_COOKIE["m-token"];
    $tokenData = validateToken($token);
    $db = Flight::get("db");

    if (!$tokenData["valid"]) {
      echo json_encode("No valid token provided");
      return;
    }

    $postData = Flight::request()->data->getData();
    $id = $id + 0; // to int

    $request = $db->update("page_content", ["text" => $postData["text"]], ["id" => $id]);

    // row changed?
    if ($request->rowCount() > 0 ) {
        echo json_encode(["msg" => "success"]);
    } else {
        echo json_encode(["msg" => "error"]);
    }
  });
}

function validateToken($pToken) {

  $db = Flight::get("db");
  $token = $pToken;
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

          return ["valid" => true, "user" => $user];
        }
      }
    }
  }

  return ["valid" => false];
}
