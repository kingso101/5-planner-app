<?php
// HEADERS
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');

// get database connection
include_once '../config/database.php';
 
// instantiate priority object
include_once '../objects/priority.php';
 
// get database connection
$database = new Database();
$db = $database->connect();
 
// prepare priority object
$priority = new Priority($db);
 
// query priority
$stmt = $priority->read();
$num = $stmt->rowCount();
// check if more than 0 record found
if($num=1 || $num>1){
 
    // priority array
    $priority_arr=array();
    $priority_arr["records"]=array();
 
    // retrieve our table contents
    // fetch() is faster than fetchAll()
    // http://stackoverflow.com/questions/2770630/pdofetchall-vs-pdofetch-in-a-loop
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        // extract row
        // this will make $row['name'] to
        // just $name only
        extract($row);
        $created = strtotime($created);

        $priority_item = array(
            "priority_id" => $priority_id,
            "key_id" => $key_id,
            "n_priority_label" => ucfirst($n_priority_label),
            "n_priority_date" => $n_priority_date,
            "isDone" => $isDone,
            "created" => date('M d Y', $created)
        );
 
        array_push($priority_arr["records"], $priority_item);
    }
 
    // set response code - 200 OK
    http_response_code(200);
 
    // show priority data in json format
    echo json_encode($priority_arr);
}else{
 
    // set response code - 404 Not found
    http_response_code(404);
 
    // tell the user no priority found
    echo json_encode(
        array("message" => "No Priority(s) found.")
    );
}