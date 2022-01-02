<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Access-Control-Allow-Methods, Authorization, X-Requested-With");
 
// get database connection
include_once '../config/database.php';
 
// instantiate client object
include_once '../objects/client.php';
 
$database = new Database();
$db = $database->connect();
 
$client = new Client($db);
 
// get posted data
$data = json_decode(file_get_contents("php://input"));
 
// make sure data is not empty
if(
    isset($data->firstname) &&
    isset($data->lastname) &&
    isset($data->password) &&
    isset($data->email) 
){
    // set client property values
    $client->_id = md5(uniqid(mt_rand(), true).microtime(true));
    $client->firstname = $data->firstname;
    $client->lastname = $data->lastname;
    $client->password = $data->password;
    $client->email = $data->email;
    $client->created = date('Y-m-d H:i:s');

    if ($client->emailExists()) {
        echo json_encode(
            array("status" => false,
                "message" => "User with this email ".$client->email." already exist.")
        );
    }else{
        if ($client->create()) {
            // set response code - 201 created
            http_response_code(201);

            echo json_encode(
                array('status' => true,
                    'message' => 'User added successfully.')
            );
        } else {
            // set response code - 503 service unavailable
            http_response_code(503);

            echo json_encode(
                array('status' => false,
                    'message' => 'Unable to create user.')
            );
        }
    }
}
 
// tell the user data is incomplete
else{
 
    // set response code - 400 bad request
    http_response_code(400);
 
    // tell the user
    echo json_encode(array("message" => "Unable to create user. Data is incomplete."));
}

?>