<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; multipart/form-data; boundary=MultipartBoundry; charset=UTF-8");
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
    isset($data->email) &&
    isset($data->gender) &&
    isset($data->contact_number) &&
    isset($data->password) &&
    isset($data->country) &&
    isset($data->state) &&
    isset($data->dob) &&
    isset($data->address) &&
    isset($data->profile_img)
    // !isset($data->$_FILES['file']) 
){
    // set client property values
    $client->_id = md5(uniqid(mt_rand(), true).microtime(true));
    $client->firstname = $data->firstname;
    $client->lastname = $data->lastname;
    $client->email = $data->email;
    $client->password = $data->password;
    $client->country = $data->country;
    $client->state = $data->state;
    $client->contact_number = $data->contact_number;
    $client->gender = $data->gender;
    $client->dob = $data->dob;
    $client->address = $data->address;
    $client->profile_img = $data->profile_img;
    $client->created = date('Y-m-d H:i:s');

    // $filename = $client->profile_img;
    // salt for clarity in file name
    list($type, $data) = explode(';', $client->profile_img); // exploding data for later checking and validating 

    if (preg_match('/^data:image\/(\w+);base64,/', $client->profile_img, $type)) {
        $data = substr($data, strpos($data, ',') + 1);
        $type = strtolower($type[1]); // jpg, png, gif

        if (!in_array($type, [ 'jpg', 'jpeg', 'gif', 'png' ])) {
            throw new \Exception('invalid image type');
        }

        $data = base64_decode($data);

        if ($data === false) {
            throw new \Exception('base64_decode failed');
        }
    } else {
        throw new \Exception('did not match data URI with image data');
    }

    $filepath = "../../uploads/image_"; // or image.jpg
    // salt for clarity in file name
    $salt = time();
    // destination of the file on the server
    $destination = $filepath .$salt.".".$type;
    $client->profile_img = $destination;

    if(file_put_contents($destination, $data)){
        // move the uploaded (temporary) file to the specified destination
        if ($client->create()) {
            // set response code - 201 created
            http_response_code(201);

            echo json_encode(
                array('status' => true,
                    'message' => 'Client added successfully.')
            );
        } else {
            // set response code - 503 service unavailable
            http_response_code(503);

            echo json_encode(
                array('status' => false,
                    'message' => 'Unable to create client.')
            );
        }
    }else{
        $result =  "error";
    }
}
 
// tell the user data is incomplete
else{
 
    // set response code - 400 bad request
    http_response_code(400);
 
    // tell the user
    echo json_encode(array("message" => "Unable to create client. Data is incomplete."));
}

?>