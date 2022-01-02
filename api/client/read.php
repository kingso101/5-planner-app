<?php
// HEADERS
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

// get database connection
include_once '../config/database.php';
 
// instantiate client object
include_once '../objects/client.php';
 
// get database connection
$database = new Database();
$db = $database->connect();
 
// prepare client object
$client = new Client($db);
 
// query client
$stmt = $client->read();
$num = $stmt->rowCount();
// check if more than 0 record found
if($num>0){
 
    // client array
    $client_arr=array();
    $client_arr["records"]=array();
 
    // retrieve our table contents
    // fetch() is faster than fetchAll()
    // http://stackoverflow.com/questions/2770630/pdofetchall-vs-pdofetch-in-a-loop
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        // extract row
        // this will make $row['name'] to
        // just $name only
        extract($row);
        $created = strtotime($created);
        
        $contact_number = $contact_number;
 
        $client_item = array(
            "client_id" =>  $client_id,
            "_id" =>  $_id,
            "firstname" => ucfirst($firstname),
            "lastname" => ucfirst($lastname),
            "fullname" => ucwords($firstname ." ".$lastname),
            "email" => ucfirst($email),
            "created" => date('M d Y', $created),
            "modified" => $modified
        );
 
        array_push($client_arr["records"], $client_item);
    }
 
    // set response code - 200 OK
    http_response_code(200);
 
    // show client data in json format
    echo json_encode($client_arr);
}else{
 
    // set response code - 404 Not found
    http_response_code(404);
 
    // tell the user no client found
    echo json_encode(
        array("message" => "No client found.")
    );
}