<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; multipart/form-data; boundary=MultipartBoundry; charset=UTF-8");
header("Access-Control-Allow-Methods: PUT");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Access-Control-Allow-Methods, Authorization, X-Requested-With");
 
// get database connection
include_once '../config/core.php';
include_once '../config/database.php';
// instantiate client object
include_once '../objects/client.php';
 
// get database connection
$database = new Database();
$db = $database->connect();
 
// prepare client object
$client = new Client($db);
 
// get id of client to be edited
$data = json_decode(file_get_contents("php://input"));
 
// set ID property of client to be edited
// $client->_id = isset($_POST['_id']) ? $_POST['_id'] : die();
$client->_id = $data->_id;
 
// set client property values
$client->firstname = $data->up_fname;
$client->lastname = $data->up_lname;
$client->email = $data->up_email;
$client->country = $data->up_country;
$client->state = $data->up_state;
$client->contact_number = $data->up_contact_number;
$client->gender = $data->up_gender;
$client->dob = $data->up_dob;
$client->address = $data->up_address;
$client->description = $data->up_description;
$client->modified = date('Y-m-d H:i:s');

if($client->update()){
    // set response code - 200 ok
    http_response_code(200);
    // tell the user
    echo json_encode(array("message" => "Client details updated successfully."));
}
else{
    // set response code - 503 service unavailable
    http_response_code(503);
    // tell the user
    echo json_encode(array("message" => "Unable to update client details."));
}

?>