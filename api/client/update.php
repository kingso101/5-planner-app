<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: PUT");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Access-Control-Allow-Methods, Authorization, X-Requested-With");
 
// get database connection
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
$client->_id = $data->_id;
// set client property values
// $client->data = strtolower($data->data);
$client->modified = date('Y-m-d H:i:s');
foreach ($data as $key => $value) {
    switch ($key) {
        case 'firstname':
            $client->firstname = $value;
            if($client->updateFname()){
                // set response code - 200 ok
                http_response_code(200);
                // tell the user
                echo json_encode(array("message" => "Firstname was updated successfully."));
            }
            else{
                // set response code - 503 service unavailable
                http_response_code(503);
                // tell the user
                echo json_encode(array("message" => "Unable to update firstname."));
            }
            break;
        case 'lastname':
            $client->lastname = $value;
            if($client->updateLname()){
                // set response code - 200 ok
                http_response_code(200);
                // tell the user
                echo json_encode(array("message" => "Lastname was updated successfully."));
            }
            else{
                // set response code - 503 service unavailable
                http_response_code(503);
                // tell the user
                echo json_encode(array("message" => "Unable to update lastname."));
            }
            break;
        case 'email':
            $client->email = $value;
            if($client->updateEmail()){
                // set response code - 200 ok
                http_response_code(200);
                // tell the user
                echo json_encode(array("message" => "Email was updated successfully."));
            }
            else{
                // set response code - 503 service unavailable
                http_response_code(503);
                // tell the user
                echo json_encode(array("message" => "Unable to update email."));
            }
            break;
        case 'address':
            $client->address = $value;
            if($client->updateAddress()){
                // set response code - 200 ok
                http_response_code(200);
                // tell the user
                echo json_encode(array("message" => "Address was updated successfully."));
            }
            else{
                // set response code - 503 service unavailable
                http_response_code(503);
                // tell the user
                echo json_encode(array("message" => "Unable to update address."));
            }
            break;
        case 'gender':
            $client->gender = $value;
            if($client->updateGender()){
                // set response code - 200 ok
                http_response_code(200);
                // tell the user
                echo json_encode(array("message" => "Gender was updated successfully."));
            }
            else{
                // set response code - 503 service unavailable
                http_response_code(503);
                // tell the user
                echo json_encode(array("message" => "Unable to update gender."));
            }
            break;
        case 'contact_number':
            $client->contact_number = $value;
            if($client->updateContactNumber()){
                // set response code - 200 ok
                http_response_code(200);
                // tell the user
                echo json_encode(array("message" => "Contact number was updated successfully."));
            }
            else{
                // set response code - 503 service unavailable
                http_response_code(503);
                // tell the user
                echo json_encode(array("message" => "Unable to update contact number."));
            }
            break;
        case 'contact_number_foreign':
            $client->contact_number_foreign = $value;
            if($client->updateContactNumberForeign()){
                // set response code - 200 ok
                http_response_code(200);
                // tell the user
                echo json_encode(array("message" => "Contact number was updated successfully."));
            }
            else{
                // set response code - 503 service unavailable
                http_response_code(503);
                // tell the user
                echo json_encode(array("message" => "Unable to update foreign contact number."));
            }
            break;
        case 'dob':
            $client->dob = $value;
            if($client->updateBirthday()){
                // set response code - 200 ok
                http_response_code(200);
                // tell the user
                echo json_encode(array("message" => "Birthday was updated successfully."));
            }
            else{
                // set response code - 503 service unavailable
                http_response_code(503);
                // tell the user
                echo json_encode(array("message" => "Unable to update birthday."));
            }
            break;
        case 'profit':
            $client->profit = $value;
            if($client->updateProfit()){
                // set response code - 200 ok
                http_response_code(200);
                // tell the user
                echo json_encode(array("message" => "Profit was updated successfully."));
            }
            else{
                // set response code - 503 service unavailable
                http_response_code(503);
                // tell the user
                echo json_encode(array("message" => "Unable to update profit."));
            }
            break;
        case 'capital':
            $client->capital = $value;
            if($client->updateCapital()){
                // set response code - 200 ok
                http_response_code(200);
                // tell the user
                echo json_encode(array("message" => "Capital was updated successfully."));
            }
            else{
                // set response code - 503 service unavailable
                http_response_code(503);
                // tell the user
                echo json_encode(array("message" => "Unable to update capital."));
            }
            break;
        case 'total_investment':
            $client->total_investment = $value;
            if($client->updateTotalInvestment()){
                // set response code - 200 ok
                http_response_code(200);
                // tell the user
                echo json_encode(array("message" => "Total investment was updated successfully."));
            }
            else{
                // set response code - 503 service unavailable
                http_response_code(503);
                // tell the user
                echo json_encode(array("message" => "Unable to update total investment."));
            }
            break;
        case 'password':
            $client->password = $value;
            if($client->updatePassword()){
                // set response code - 200 ok
                http_response_code(200);
                // tell the user
                echo json_encode(array("message" => "Password was updated successfully."));
            }
            else{
                // set response code - 503 service unavailable
                http_response_code(503);
                // tell the user
                echo json_encode(array("message" => "Unable to update password."));
            }
            break;
        case 'up_profile_img':
            $client->profile_img = $value;

            if (preg_match('/uploads\/image_/', $client->profile_img)) {
                // update the client
                if($client->updateImg()){
                    // set response code - 200 ok
                    http_response_code(200);
                    // tell the user
                    echo json_encode(array("message" => "Profile image was updated successfully."));
                }
                else{
                    // set response code - 503 service unavailable
                    http_response_code(503);
                    // tell the user
                    echo json_encode(array("message" => "Unable to update profile image."));
                }

            }else{
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

                    $filepath = "../../uploads/image_"; // or image.jpg
                    // salt for clarity in file name
                    $salt = time();
                    // destination of the file on the server
                    $destination = $filepath .$salt.".".$type;
                    $client->profile_img = $destination;
                    if(file_put_contents($destination, $data)){
                        // move the uploaded (temporary) file to the specified destination
                        // update the client
                        if($client->updateImg()){
                            // set response code - 200 ok
                            http_response_code(200);
                            // tell the user
                            echo json_encode(array("message" => "Profile image was updated successfully."));
                        }
                        else{
                            // set response code - 503 service unavailable
                            http_response_code(503);
                            // tell the user
                            echo json_encode(array("message" => "Unable to update profile image."));
                        }
                    }else{
                        $result =  "error";
                    }
                }
            }
            break;
        default:
            // code...
            break;
    }
}


?>