<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// get database connection
include_once '../config/database.php';
 
// instantiate plan object
include_once '../objects/plan.php';

// get database connection
$database = new Database();
$db = $database->connect();
 
// prepare plan object
$plan = new Plan($db);
 
// set ID property of record to read
$plan->_id = isset($_GET['_id']) ? $_GET['_id'] : die();
 
// delete the plan
if($plan->delete()){
    // set response code - 200 ok
    http_response_code(200);
 
    // tell the user
    echo json_encode(array("message" => "Plan was deleted successfully."));
}
 
// if unable to delete the plan
else{
    // set response code - 503 service unavailable
    http_response_code(503);
 
    // tell the user
    echo json_encode(array("message" => "Unable to delete plan."));
}


?>