<?php
// HEADERS
header('Access-Control-Allow-Origin: *');
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
$analytics->client_id = isset($_GET['_id']) ? $_GET['_id'] : die();

// read the details of analytics to be edited
$stmt = $analytics->readOnePersonanalytics();
$num = $stmt->rowCount();
// check if more than 0 record found
if($num>1){
 
    // analytics array
    $analytics_arr=array();
    $analytics_arr["records"]=array();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        // extract row
        // this will make $row['name'] to
        // just $name only
        extract($row);
        $created = strtotime($created);

        $analytics_item = array(
            "analytics_id" => $analytics_id,
            "key_id" => $key_id,
            "plan_id" => $plan_id,
            "client_id" => $client_id,
            "plan_type" => ucfirst($plan_type),
            "goal" => ucwords($goal),
            "from_date" => $from_date,
            "to_date" => $to_date,
            "no_of_intervals" => ucfirst($no_of_intervals),
            "total_priorities" => ucfirst($total_priorities),
            "intervals_completed" => ucfirst($intervals_completed),
            "total_priorities_done" => ucfirst($total_priorities_done),
            "time_used" => $time_used,
            "time_left" => $time_left,
            "created" => date('M d Y', $created)
        );
 
        array_push($analytics_arr["records"], $analytics_item);
    }
 
    // set response code - 200 OK
    http_response_code(200);
 
    // show analytics data in json format
    echo json_encode($analytics_arr);
}else{
 
    // set response code - 404 Not found
    http_response_code(404);
 
    // tell the user no analytics found
    echo json_encode(
        array("message" => "No analytics(s) found.")
    );
}