<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');
header("Content-type: image/jpg");
header("Content-type: image/gif");
header("Content-type: image/png");
 
// get database connection
include_once '../config/database.php';
 
// instantiate priority object
include_once '../objects/priority.php';
 
// get database connection
$database = new Database();
$db = $database->connect();
 
// prepare priority object
$priority = new Priority($db);
 
// set ID property of record to read
$priority->priority_id = isset($_GET['_id']) ? $_GET['_id'] : die();
 
// read the details of priority to be edited
$priority->readOne();
 
if($priority->n_priority_label!=null){
    // create array
    // $n_priority_date = $priority->n_priority_date;
    // $n_priority_date = date('d/m/Y', strtotime($n_priority_date));
    // echo $n_priority_date;
    $priority->created = strtotime($priority->created);
    $priority_arr = array(
        "priority_id" => $priority->priority_id,
        "key_id" => $priority->key_id,
        "n_priority_label" => ucfirst($priority->n_priority_label),
        "n_priority_date" => $priority->n_priority_date,
        "isDone" => $priority->isDone,
        "created" => date('M d Y', $priority->created)
    );
 
    // set response code - 200 OK
    http_response_code(200);
 
    // make it json format
    echo json_encode($priority_arr);
}
 
else{
    // set response code - 404 Not found
    http_response_code(404);
 
    // tell the user priority does not exist
    echo json_encode(array("message" => "No priority(s) found."));
}


?>