<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: PUT");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Access-Control-Allow-Methods, Authorization, X-Requested-With");
 
// get database connection
include_once '../config/database.php';
 
// instantiate analytics object
include_once '../objects/analytics.php';
 
// get database connection
$database = new Database();
$db = $database->connect();
 
// prepare analytics object
$analytics = new Analytics($db);
 
// get id of analytics to be edited
$data = json_decode(file_get_contents("php://input"));
 
// set ID property of analytics to be edited
$analytics->plan_id = $data->plan_id;
$analytics->key_id = $data->key_id;
// set analytics property values
$analytics->time_left = strtolower($data->time_left);
$analytics->time_used = strtolower($data->time_used);

// update the analytics
if($analytics->updatePriorityTime()){
    // set response code - 200 ok
    http_response_code(200);
 
    // tell the user
    echo json_encode(array("message" => "Priority time updated successfully."));
}
// if unable to update the analytics, tell the user
else{
    // set response code - 503 service unavailable
    http_response_code(503);
 
    // tell the user
    echo json_encode(array("message" => "Unable to add priority."));
}


?>