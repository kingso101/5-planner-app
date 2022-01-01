<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Access-Control-Allow-Methods, Authorization, X-Requested-With");
 
// get database connection
include_once '../config/database.php';
// instantiate plan object
include_once '../objects/plan.php';
 
$database = new Database();
$db = $database->connect();
 
$plan = new Plan($db);
// get posted data
$data = json_decode(file_get_contents("php://input"));
 
// make sure data is not empty
if(
    isset($data->goal) &&
    isset($data->plan_type)&&
    isset($data->from_date)
){
    $plan->_id = md5(uniqid(mt_rand(), true).microtime(true));
    $plan->client_id = strtolower($data->client_id);
    $plan->key_id = strtolower($data->key_id);
    $plan->plan_type = strtolower($data->plan_type);
    $plan->goal = strtolower($data->goal);
    $plan->from_date = $data->from_date;
    $plan->to_date = $data->to_date;
    $plan->description = $data->description;
    $plan->resources = strtolower($data->resources);
    $plan->plan_interval = strtolower($data->plan_interval);
    $plan->priority_label = strtolower($data->priority_label);
    $plan->priority_date = $data->priority_date;
    $plan->reward = strtolower($data->reward);
    $plan->created = date('Y-m-d H:i:s');

    if ($plan->create()) {
        // set response code - 201 created
        http_response_code(201);

        echo json_encode(
            array("status" => true,
                "message" => "Plan created successfully.")
        );
    } else {
        // set response code - 503 service unavailable
        http_response_code(503);

        echo json_encode(
            array("status" => false,
                "message" => "Plan could not be created!")
        );
    } 
}
 
// tell the user data is incomplete
else{
 
    // set response code - 400 bad request
    http_response_code(400);
 
    // tell the user
    echo json_encode(array("message" => "Unable to perform plan. Data is incomplete."));
}

?>