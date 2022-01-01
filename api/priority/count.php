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
// $data = json_decode(file_get_contents("php://input"));
// set ID property of priority to be edited
$priority->key_id = isset($_GET['key_id']) ? $_GET['key_id'] : die();
$priority->priority_label = isset($_GET['priority_label']) ? $_GET['priority_label'] : die();
$priority->client_id = isset($_GET['client_id']) ? $_GET['client_id'] : die();
$priority->request = isset($_GET['count_priorities_done']) ? $_GET['count_priorities_done'] : die();
// set priority property values
switch ($priority->request) {
    case 'count_priorities_done':
        $pro = $priority->countCompletedPriorities($priority->client_id, $priority->key_id, $priority->priority_label);     
        if (empty($pro)) {
            $result = 0;
            // set response code - 404 Not found
            http_response_code(503);
         
            // tell the user plan does not exist
            echo json_encode(array("message" => "No priority found."));
        }else{
            $result = count($pro);
            $plan_arr = array(
                "count" => $result,
                "priority_label" => $pro->priority_label
            );
            // set response code - 200 ok
            http_response_code(200);
            // tell the user
            echo json_encode($plan_arr);
        }
        break;
    case 'add_priority_label':
        $priority->priority_label = $value;
        if($priority->updatePriorityLabel()){
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
        $priority->priority_date = $value;
        if($priority->updatePriorityDate()){
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


?>