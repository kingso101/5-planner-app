<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Access-Control-Allow-Methods, Authorization, X-Requested-With");
 
// get database connection
include_once '../config/database.php';
// instantiate priority object
include_once '../objects/priority.php';
 
$database = new Database();
$db = $database->connect();
 
$priority = new Priority($db);
// get posted data
$data = json_decode(file_get_contents("php://input"));
 
// make sure data is not empty
if(
    isset($data->priority_label) &&
    isset($data->priority_date) &&
    isset($data->client_id) &&
    isset($data->key_id)
){
    $priority->priority_label = strtolower($data->priority_label);
    $priority->priority_date = $data->priority_date;
    $priority->client_id = $data->client_id;
    $priority->key_id = $data->key_id;
    $priority->created = date('Y-m-d H:i:s');

    if ($priority_exists = $priority->priorityExists()) {
        echo json_encode(
            array("status" => false,
                "message" => "Priority already exist.")
        );
    }else{
        if ($priority->create()) {
            // set response code - 201 created
            http_response_code(201);

            echo json_encode(
                array("status" => true,
                    "message" => "Priority created successfully.")
            );
        } else {
            // set response code - 503 service unavailable
            http_response_code(503);

            echo json_encode(
                array("status" => false,
                    "message" => "Priority could not be created!")
            );
        }
    } 
}
 
// tell the user data is incomplete
else{
 
    // set response code - 400 bad request
    http_response_code(400);
 
    // tell the user
    echo json_encode(array("message" => "Unable to create priority. Data is incomplete."));
}

?>