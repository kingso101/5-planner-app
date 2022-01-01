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
// $client->_id = isset($_GET['_id']) ? $_GET['_id'] : die();
// get id of client to be edited
$data = json_decode(file_get_contents("php://input"));
// set ID property of client to be edited
// $client->_id = isset($_POST['_id']) ? $_POST['_id'] : die();
$client->_id = $data->_id;
// set client property values
$client->isVerified = $data->isVerified;
$client->modified = date('Y-m-d H:i:s');
// block the client
if($client->verified()){
    // set response code - 200 ok
    http_response_code(200);
 
    // tell the user
    echo json_encode(array("message" => "Client updated successfully."));
}
 
// if unable to block the client
else{
    // set response code - 503 service unavailable
    http_response_code(503);
 
    // tell the user
    echo json_encode(array("message" => "Unable to update client verified status."));
}


?>