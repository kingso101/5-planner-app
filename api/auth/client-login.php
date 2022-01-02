<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
// files needed to connect to database
include_once '../config/core.php';
include_once '../config/database.php';
include_once '../objects/client.php';
// Required if your environment does not handle autoloading

// get database connection
$database = new Database();
$db = $database->connect();
// instantiate client object
$client = new Client($db);

// get database connection
$database = new Database();
$db = $database->connect();
 
// get posted data
$data = json_decode(file_get_contents("php://input"));
// print_r($data);
 
// set product property values
$client->email = $data->email;
$client->password = $data->password;
$email_exists = $client->emailExists();
$login = $client->login($data->email, $data->password);
 
// generate json web token]
include_once '../libs/php-jwt-master/src/BeforeValidException.php';
include_once '../libs/php-jwt-master/src/ExpiredException.php';
include_once '../libs/php-jwt-master/src/SignatureInvalidException.php';
include_once '../libs/php-jwt-master/src/JWT.php';

use \Firebase\JWT\JWT;
// check if email exists and if password is correct
if($email_exists && password_verify($data->password, $client->password)){

  if($login) {
    $token = array(
      "iss" => $iss,
      "aud" => $aud,
      "iat" => $iat,
      "nbf" => $nbf,
      "data" => array(
        "client_id" => $client->client_id,
        "_id" => $client->_id,
        "firstname" => $client->firstname,
        "lastname" => $client->lastname,
        "email" => $client->email
      )
    );
    
    // set response code
    http_response_code(200);
    // generate jwt
    $jwt = JWT::encode($token, $key);
    // $_SESSION['jwt'] = $jwt;
    echo json_encode(
      array(
        "message" => "Login was successful.",
        "status"  => "Done",
        "jwt" => $jwt,
        "email" => $client->email
      )
    );
  }
}else{
  // set response code
  http_response_code(401);

  // tell the client login failed
  echo json_encode(
    array(
      "message" => "Login failed.",
      "status" => "Failed"
    )
  );
}

?>  