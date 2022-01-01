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
$plan->key_id = isset($_GET['key_id']) ? $_GET['key_id'] : die();
$plan->client_id = isset($_GET['client_id']) ? $_GET['client_id'] : die();
 
// read the details of plan to be edited
$e = $plan->countPrioritiesForEachPlan($plan->client_id,$plan->key_id);

if (empty($e)) {
    $z = 0;
    // set response code - 404 Not found
    http_response_code(404);
 
    // tell the user plan does not exist
    echo json_encode(array("message" => "No priority found."));
}else{
    $z = count($e);
    $plan_arr = array(
        "count" => $z
    );
    // set response code - 200 OK
    http_response_code(200);
 
    // make it json format
    echo json_encode($plan_arr);
}

// if($e){
//     // create array
//     $plan->created = strtotime($plan->created);
//     $plan_arr = array(
//         "count" => $plan->count
//     );
 
//     // set response code - 200 OK
//     http_response_code(200);
 
//     // make it json format
//     echo json_encode($plan_arr);
// }
 
// else{
//     // set response code - 404 Not found
//     http_response_code(404);
 
//     // tell the user plan does not exist
//     echo json_encode(array("message" => "No plan(s) found."));
// }


?>