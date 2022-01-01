<?php
// required headers
header("Access-Control-Allow-Origin: http://phpajaxcrud/");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
// files needed to connect to database
include_once '../config/database.php';
include_once '../objects/admin.php';
 
// get database connection
$database = new Database();
$db = $database->connect();
 
// instantiate admin object
$admin = new Admin($db);
// if (isset($_POST['submit'])) {
//   var_dump($_POST);
// }
// get posted data
$data = json_decode(file_get_contents("php://input"));
// print_r($data);
 
// set product property values
$admin->email = $data->email;
$admin->password = $data->password;
$email_exists = $admin->emailExists();
$login = $admin->login($data->email, $data->password);
 
// generate json web token
include_once '../config/core.php';
include_once '../libs/php-jwt-master/src/BeforeValidException.php';
include_once '../libs/php-jwt-master/src/ExpiredException.php';
include_once '../libs/php-jwt-master/src/SignatureInvalidException.php';
include_once '../libs/php-jwt-master/src/JWT.php';
use \Firebase\JWT\JWT;
 
// check if email exists and if password is correct
if($email_exists && password_verify($data->password, $admin->password)){

  if($login) {
    $token = array(
      "iss" => $iss,
      "aud" => $aud,
      "iat" => $iat,
      "nbf" => $nbf,
      "data" => array(
        "id" => $admin->id,
        "_id" => $admin->_id,
        "firstname" => $admin->firstname,
        "lastname" => $admin->lastname,
        "email" => $admin->email,
        "access_level" => $admin->access_level,
        "profile_img" => $admin->profile_img
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
        "jwt" => $jwt
      )
    );
  }
}else{
  // set response code
  http_response_code(401);

  // tell the admin login failed
  echo json_encode(
    array(
      "message" => "Login failed.",
      "status" => ""
    )
  );
}

?>