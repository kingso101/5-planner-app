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
$plan->modified = date('Y-m-d H:i:s');
foreach ($data as $key => $value) {
    switch ($key) {
        case 'up_plan_interval':
            $plan->plan_interval = $value;
            if($plan->updatePlanInt()){
                // set response code - 200 ok
                http_response_code(200);
                // tell the user
                echo json_encode(array("message" => "Plan interval was updated successfully."));
            }
            else{
                // set response code - 503 service unavailable
                http_response_code(503);
                // tell the user
                echo json_encode(array("message" => "Unable to update plan interval."));
            }
            break;
        case 'add_priority_label':
            $plan->priority_label = $value;
            if($plan->updatePriorityLabel()){
                // set response code - 200 ok
                http_response_code(200);
                // tell the user
                echo json_encode(array("message" => "Priority label was updated successfully."));
            }
            else{
                // set response code - 503 service unavailable
                http_response_code(503);
                // tell the user
                echo json_encode(array("message" => "Unable to update priority label."));
            }
            break;
        case 'add_priority_date':
            $plan->priority_date = $value;
            if($plan->updatePriorityDate()){
                // set response code - 200 ok
                http_response_code(200);
                // tell the user
                echo json_encode(array("message" => "Priority label was updated successfully."));
            }
            else{
                // set response code - 503 service unavailable
                http_response_code(503);
                // tell the user
                echo json_encode(array("message" => "Unable to update priority label."));
            }
            break;
        default:
            // code...
            break;
    }
}


?>