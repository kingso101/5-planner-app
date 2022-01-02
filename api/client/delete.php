<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// get database connection
include_once '../config/database.php';
 
// instantiate client object
include_once '../objects/client.php';

// get database connection
$database = new Database();
$db = $database->connect();
 
// prepare client object
$client = new Client($db);
 
// set ID property of record to read
$client->_id = isset($_GET['_id']) ? $_GET['_id'] : die();
 
// delete the client
if($client->delete()){
    // set response code - 200 ok
    http_response_code(200);
 
    // tell the user
    echo json_encode(array("message" => "User deleted successfully."));
}
 
// if unable to delete the client
else{
    // set response code - 503 service unavailable
    http_response_code(503);
 
    // tell the user
    echo json_encode(array("message" => "Unable to delete user."));
}


?>