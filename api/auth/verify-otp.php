<?php
// required headers
header("Access-Control-Allow-Origin: http://phpajaxcrud/");
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
$client->otp = $data->otp;
$opt_exists = $client->otpExists();
$verifyLogin = $client->verify_login($data->otp);
 
// generate json web token]
include_once '../libs/php-jwt-master/src/BeforeValidException.php';
include_once '../libs/php-jwt-master/src/ExpiredException.php';
include_once '../libs/php-jwt-master/src/SignatureInvalidException.php';
include_once '../libs/php-jwt-master/src/JWT.php';

use \Firebase\JWT\JWT;
// check if email exists and if password is correct
if($opt_exists){
  if($verifyLogin) {
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
        "email" => $client->email,
        "account_num" => $client->account_num,
        "profile_img" => $client->profile_img
      )
    );
    
    // set response code
    http_response_code(200);
    // generate jwt
    $jwt = JWT::encode($token, $key);
    // $_SESSION['jwt'] = $jwt;
    echo json_encode(
      array(
        "message" => "OTP verified successfully.",
        "status"  => "Done",
        "jwt" => $jwt
      )
    );
  }
}else{
  // set response code
  http_response_code(401);

  // tell the client login failed
  echo json_encode(
    array(
      "message" => "OTP verification failed. Code is incorrect.",
      "status" => "Failed"
    )
  );
}

?>  