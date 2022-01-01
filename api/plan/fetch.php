<?php
// HEADERS
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

// get database connection
include_once '../config/database.php';
 
// instantiate transfer object
include_once '../objects/transfer.php';
 
// get database connection
$database = new Database();
$db = $database->connect();
 
// prepare transfer object
$transfer = new Transfer($db);
 
// function to count total notifactions



// query transfer


// check if more than 0 record found
if($notifNum = $transfer->countTransfers()){
	$notifNum = count($notifNum);
    // transfer array
    $transfer_arr=array();
    $transfer_arr["records"]=array();
 
    // retrieve our table contents
    // fetch() is faster than fetchAll()
    // http://stackoverflow.com/questions/2770630/pdofetchall-vs-pdofetch-in-a-loop
   	$transfer_arr = array(
        "transfer_count" =>  $notifNum
    );
 
    // set response code - 200 OK
    http_response_code(200);
 
    // make it json format
    echo json_encode($transfer_arr);
}else{
 
    // set response code - 404 Not found
    http_response_code(404);
 
    // tell the user no transfer found
    echo json_encode(
        array("message" => "No transfer(s) found.")
    );
}