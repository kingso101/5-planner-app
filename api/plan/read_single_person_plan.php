<?php
// HEADERS
header('Access-Control-Allow-Origin: *');
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
$plan->client_id = isset($_GET['_id']) ? $_GET['_id'] : die();

// read the details of plan to be edited
$stmt = $plan->readOnePersonPlans($plan->client_id);
$num = $stmt->rowCount();
// check if more than 0 record found
if($num=1 || $num>1){
 
    // plan array
    $plan_arr=array();
    $plan_arr["records"]=array();
 
    // retrieve our table contents
    // fetch() is faster than fetchAll()
    // http://stackoverflow.com/questions/2770630/pdofetchall-vs-pdofetch-in-a-loop
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        // extract row
        // this will make $row['name'] to
        // just $name only
        extract($row);
        $created = strtotime($created);

        $plan_item = array(
            "_id" => $_id,
            "plan_id" => $plan_id,
            "key_id" => $key_id,
            "client_id" => $client_id,
            "plan_type" => ucfirst($plan_type),
            "goal" => ucwords($goal),
            "from_date" => $from_date,
            "to_date" => $to_date,
            "description" => ucfirst($description),
            "resources" => ucfirst($resources),
            "plan_interval" => $plan_interval,
            "reward" => ucfirst($reward),
            "priority_label" => ucfirst($priority_label),
            "priority_date" => $priority_date,
            "isCompleted" => $isCompleted,
            "created" => date('M d Y', $created)
        );
 
        array_push($plan_arr["records"], $plan_item);
    }
 
    // set response code - 200 OK
    http_response_code(200);
 
    // show plan data in json format
    echo json_encode($plan_arr);
}else{
 
    // set response code - 404 Not found
    http_response_code(404);
 
    // tell the user no plan found
    echo json_encode(
        array("message" => "No plan(s) found.")
    );
}