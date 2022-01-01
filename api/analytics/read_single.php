<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');
 
// get database connection
include_once '../config/database.php';
 
// instantiate analytics object
include_once '../objects/analytics.php';
 
// get database connection
$database = new Database();
$db = $database->connect();
 
// prepare analytics object
$analytics = new Analytics($db);
 
// set ID property of record to read
$analytics->_id = isset($_GET['_id']) ? $_GET['_id'] : die();
 
// read the details of analytics to be edited
$analytics->readOne();
 
if($analytics->goal!=null){
    // create array
    // $analytics_date = $analytics->analytics_date;
    // $analytics_date = date('d/m/Y', strtotime($analytics_date));
    // echo $analytics_date;
    $analytics->created = strtotime($analytics->created);
    $analytics_arr = array(
        "_id" => $analytics->_id,
        "analytics_id" => $analytics->analytics_id,
        "key_id" => $analytics->key_id,
        "client_id" => $analytics->client_id,
        "plan_type" => ucfirst($analytics->plan_type),
        "goal" => ucwords($analytics->goal),
        "from_date" => $analytics->from_date,
        "to_date" => $analytics->to_date,
        "description" => ucfirst($analytics->description),
        "resources" => ucfirst($analytics->resources),
        "analytics_interval" => $analytics->analytics_interval,
        "reward" => ucfirst($analytics->reward),
        "priority_label" => ucfirst($analytics->priority_label),
        "priority_date" => $analytics->priority_date,
        "isCompleted" => $analytics->isCompleted,
        "created" => date('M d Y', $analytics->created)
    );
 
    // set response code - 200 OK
    http_response_code(200);
 
    // make it json format
    echo json_encode($analytics_arr);
}
 
else{
    // set response code - 404 Not found
    http_response_code(404);
 
    // tell the user analytics does not exist
    echo json_encode(array("message" => "No analytics(s) found."));
}


?>