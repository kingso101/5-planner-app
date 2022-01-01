<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');
 
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
 
// read the details of plan to be edited
$plan->readOne();
 
if($plan->goal!=null){
    // create array
    // $plan_date = $plan->plan_date;
    // $plan_date = date('d/m/Y', strtotime($plan_date));
    // echo $plan_date;
    $plan->created = strtotime($plan->created);
    $plan_arr = array(
        "_id" => $plan->_id,
        "plan_id" => $plan->plan_id,
        "key_id" => $plan->key_id,
        "client_id" => $plan->client_id,
        "plan_type" => ucfirst($plan->plan_type),
        "goal" => ucwords($plan->goal),
        "from_date" => $plan->from_date,
        "to_date" => $plan->to_date,
        "description" => ucfirst($plan->description),
        "resources" => ucfirst($plan->resources),
        "plan_interval" => $plan->plan_interval,
        "reward" => ucfirst($plan->reward),
        "priority_label" => ucfirst($plan->priority_label),
        "priority_date" => $plan->priority_date,
        "isCompleted" => $plan->isCompleted,
        "created" => date('M d Y', $plan->created)
    );
 
    // set response code - 200 OK
    http_response_code(200);
 
    // make it json format
    echo json_encode($plan_arr);
}
 
else{
    // set response code - 404 Not found
    http_response_code(404);
 
    // tell the user plan does not exist
    echo json_encode(array("message" => "No plan(s) found."));
}


?>