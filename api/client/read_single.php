<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
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
 
// set ID property of record to read
$client->_id = isset($_GET['_id']) ? $_GET['_id'] : die();
 
// read the details of client to be edited
$client->readOne();
 
if($client->firstname!=null){
    // create array
    $client_arr = array(
        "client_id" =>  $client->client_id,
        "_id" =>  $client->_id,
        "firstname" => ucfirst($client->firstname),
        "lastname" => ucfirst($client->lastname),
        "fullname" => ucwords($client->firstname ." ".$client->lastname),
        "email" => ucfirst($client->email),
        "created" => $client->created,
        "modified" => $client->modified
    );
 
    // set response code - 200 OK
    http_response_code(200);
 
    // make it json format
    echo json_encode($client_arr);
}
 
else{
    // set response code - 404 Not found
    http_response_code(404);
 
    // tell the user client does not exist
    echo json_encode(array("message" => "Client does not exist."));
}


?>