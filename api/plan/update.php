<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: PUT");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Access-Control-Allow-Methods, Authorization, X-Requested-With");
 
// get database connection
include_once '../config/database.php';
 
// instantiate plan object
include_once '../objects/plan.php';
 
// get database connection
$database = new Database();
$db = $database->connect();
 
// prepare plan object
$plan = new Plan($db);
 
// get id of plan to be edited
$data = json_decode(file_get_contents("php://input"));
 
// set ID property of plan to be edited
$plan->_id = $data->_id;
 
// set plan property values
$plan->plan_type = strtolower($data->up_plan_type);
$plan->goal = strtolower($data->up_goal);
$plan->from_date = $data->up_from_date;
$plan->to_date = $data->up_to_date;
$plan->description = $data->up_description;
$plan->resources = strtolower($data->up_resources);
$plan->plan_interval = strtolower($data->up_plan_int);
$plan->reward = strtolower($data->up_reward);

// update the plan
if($plan->update()){
    // set response code - 200 ok
    http_response_code(200);
 
    // tell the user
    echo json_encode(array("message" => "Plan was updated successfully."));
}
 
// if unable to update the plan, tell the user
else{
    // set response code - 503 service unavailable
    http_response_code(503);
 
    // tell the user
    echo json_encode(array("message" => "Unable to update plan."));
}


?>