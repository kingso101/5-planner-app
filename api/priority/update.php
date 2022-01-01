<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: PUT");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Access-Control-Allow-Methods, Authorization, X-Requested-With");
 
// get database connection
include_once '../config/database.php';
 
// instantiate priority object
include_once '../objects/priority.php';
 
// get database connection
$database = new Database();
$db = $database->connect();
 
// prepare priority object
$priority = new Priority($db);
 
// get id of priority to be edited
$data = json_decode(file_get_contents("php://input"));
 
// set ID property of priority to be edited
$priority->key_id = $data->key_id;
// set priority property values
$priority->priority_label = strtolower($data->add_priority_label);
$priority->priority_date = strtolower($data->add_priority_date);

// update the priority
if($priority->update()){
    // set response code - 200 ok
    http_response_code(200);
 
    // tell the user
    echo json_encode(array("message" => "Priority was updated successfully."));
}
 
// if unable to update the priority, tell the user
else{
    // set response code - 503 service unavailable
    http_response_code(503);
 
    // tell the user
    echo json_encode(array("message" => "Unable to update priority."));
}


?>