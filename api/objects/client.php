<?php
include_once __DIR__ . '/../../vendor/autoload.php';
include_once __DIR__ . '/../config/core.php';
use Twilio\Rest\Client as TwilioClient;
use telesign\sdk\messaging\MessagingClient;
use function telesign\sdk\util\randomWithNDigits;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__.'./../../');
$dotenv->load();

if(!isset($_SESSION)){
    session_start();
}
// 'Client' object
class Client{
 
    // database connection and table name
    private $conn;
    private $table_name = "clients";

    // object properties
    public $client_id;
    public $_id;
    public $firstname;
    public $lastname;
    public $email;
    public $password;
    public $description;
    public $country;
    public $state;
    public $address;
    public $contact_number;
    public $gender;
    public $dob;
    public $otp;
    public $customer_id;
    public $api_key;
    public $twilio_number;
    public $app_name;
    public $isVerified;
    public $profile_img;
    public $created;
    public $modified;

 
    // constructor
    public function __construct($db){
        $this->conn = $db;
    }
 
    // create new user record
    function create(){
     
        // insert query
        $query = "INSERT INTO " . $this->table_name . "
            SET
                _id = :_id,
                firstname = :firstname,
                lastname = :lastname,
                email = :email,
                password = :password,
                description = :description,
                country = :country,
                state = :state,
                address = :address,
                contact_number = :contact_number,
                gender = :gender,
                dob = :dob,
                profile_img = :profile_img,
                created = :created";
     
        // prepare the query
        $stmt = $this->conn->prepare($query);
     
        // sanitize
        $this->_id=htmlspecialchars(strip_tags($this->_id));
        $this->firstname=htmlspecialchars(strip_tags($this->firstname));
        $this->lastname=htmlspecialchars(strip_tags($this->lastname));
        $this->email=htmlspecialchars(strip_tags($this->email));
        $this->password=htmlspecialchars(strip_tags($this->password));
        $this->description=htmlspecialchars(strip_tags($this->description));
        $this->country=htmlspecialchars(strip_tags($this->country));
        $this->state=htmlspecialchars(strip_tags($this->state));
        $this->address=htmlspecialchars(strip_tags($this->address));
        $this->contact_number=htmlspecialchars(strip_tags($this->contact_number));
        $this->gender=htmlspecialchars(strip_tags($this->gender));
        $this->dob=htmlspecialchars(strip_tags($this->dob));
        $this->profile_img=htmlspecialchars(strip_tags($this->profile_img));
        $this->created=htmlspecialchars(strip_tags($this->created));
     
        // bind the values
        $stmt->bindParam(':_id', $this->_id);
        $stmt->bindParam(':firstname', $this->firstname);
        $stmt->bindParam(':lastname', $this->lastname);
        $stmt->bindParam(':email', $this->email);

        // hash the password before saving to database
        $password_hash = password_hash($this->password, PASSWORD_BCRYPT);
        $stmt->bindParam(':password', $password_hash);

        $stmt->bindParam(':description', $this->description);
        $stmt->bindParam(':country', $this->country);
        $stmt->bindParam(':state', $this->state);
        $stmt->bindParam(':address', $this->address);
        $stmt->bindParam(':contact_number', $this->contact_number);
        $stmt->bindParam(':gender', $this->gender);
        $stmt->bindParam(':dob', $this->dob);
        $stmt->bindParam(':profile_img', $this->profile_img);
        $stmt->bindParam(':created', $this->created);
     
        // execute the query, also check if query was successful
        if($stmt->execute()){
            return true;
        }
        else{
            $this->showError($stmt);
            return false;
        }
     
        return false;
    }

    // check if given email exist in the database
    function emailExists(){
     
        // query to check if email exists
        $query = "SELECT client_id, _id, firstname, lastname, password, country
                FROM " . $this->table_name . "
                WHERE email = ?
                LIMIT 0,1";
     
        // prepare the query
        $stmt = $this->conn->prepare( $query );
     
        // sanitize
        $this->email=htmlspecialchars(strip_tags($this->email));
     
        // bind given email value
        $stmt->bindParam(1, $this->email);
     
        // execute the query
        $stmt->execute();
     
        // get number of rows
        $num = $stmt->rowCount();
     
        // if email exists, assign values to object properties for easy access and use for php sessions
        if($num>0){
     
            // get record details / values
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            // var_dump($row);
     
            // assign values to object properties
            $this->client_id = $row['client_id'];
            $this->_id = $row['_id'];
            $this->firstname = $row['firstname'];
            $this->lastname = $row['lastname'];
            $this->country = $row['country'];
            $this->password = $row['password'];
     
            // return true because email exists in the database
            return true;
        }
     
        // return false if email does not exist in the database
        return false;
    }

    public function login($email,$password){
        $this->otp = substr(str_shuffle('123456789'),1,6); 
        // query to check if email exists
        $query = "SELECT *
            FROM " . $this->table_name . "
            WHERE email = :email OR password = :password
            LIMIT 0,1";
     
        // prepare the query
        $stmt = $this->conn->prepare( $query );

        $stmt->execute(array(':email'=>$email, ':password'=>$password));
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if($stmt->rowCount() > 0){
            if(password_verify($password, $row['password'])){
                $query2 = "UPDATE " . $this->table_name . "
                    SET
                        otp = " . $this->otp . "
                    WHERE email = :email OR password = :password LIMIT 1";

                // prepare the query2
                $stmt2 = $this->conn->prepare($query2);

                // unique ID of record to be edited
                $stmt2->bindParam(':email', $this->email);
                $stmt2->bindParam(':password', $this->password);
                // execute the query2
                if($stmt2->execute()){
                    $this->customer_id = $_ENV['CUSTOMER_ID'];
                    $this->api_key = $_ENV['API_KEY'];
                    $this->app_name = $_ENV['APP_NAME'];
                    $this->contact_number = $row['contact_number'];
                    $message_type = $_ENV['MESSAGE_TYPE'];
                    // try{
                    //     // $twilioClient = new TwilioClient($this->sid, $this->auth_token);
                    //     $messaging = new MessagingClient($this->customer_id, $this->api_key);
                    //     $response = $messaging->message(
                    //         $this->contact_number, 
                    //         $this->app_name.' OTP code is ' . $this->otp, 
                    //         $message_type
                    //     );
                    //     return true;
                    // }catch (Exception $e) {
                    //     echo 'Error: ' . $e->getMessage();
                    // }
                    $_SESSION['_id'] = $row['_id'];
                    $_SESSION['client_id'] = $row['client_id'];
                    $_SESSION['firstname'] = $row['firstname'];
                    $_SESSION['lastname'] = $row['lastname'];
                    $_SESSION['email'] = $row['email'];
                    return true;
                }
            }
            else{
                return false;
            }
        }
    }

    // check if given otp exist in the database
    function otpExists(){
     
        // query to check if otp exists
        $query = "SELECT client_id, _id, firstname, lastname, password, country
                FROM " . $this->table_name . "
                WHERE otp = ?
                LIMIT 0,1";
     
        // prepare the query
        $stmt = $this->conn->prepare( $query );
     
        // sanitize
        $this->otp=htmlspecialchars(strip_tags($this->otp));
     
        // bind given otp value
        $stmt->bindParam(1, $this->otp);
     
        // execute the query
        $stmt->execute();
     
        // get number of rows
        $num = $stmt->rowCount();
     
        // if otp exists, assign values to object properties for easy access and use for php sessions
        if($num>0){
     
            // get record details / values
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            // var_dump($row);
     
            // assign values to object properties
            $this->client_id = $row['client_id'];
            $this->_id = $row['_id'];
            $this->firstname = $row['firstname'];
            $this->lastname = $row['lastname'];
            $this->country = $row['country'];
            $this->password = $row['password'];
     
            // return true because otp exists in the database
            return true;
        }
     
        // return false if otp does not exist in the database
        return false;
    }

    public function verify_login($otp){
       try
       {    
            // query to check if otp exists
            $query = "SELECT *
                    FROM " . $this->table_name . "
                    WHERE otp = :otp 
                    LIMIT 0,1";
         
            // prepare the query
            $stmt = $this->conn->prepare( $query );

            $stmt->execute(array(':otp'=>$otp));
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if($stmt->rowCount() > 0){
                $_SESSION['_id'] = $row['_id'];
                $_SESSION['client_id'] = $row['client_id'];
                $_SESSION['firstname'] = $row['firstname'];
                $_SESSION['lastname'] = $row['lastname'];
                $_SESSION['email'] = $row['email'];
                return true;
            }
            else{
                return false;
            }
        }
        catch(PDOException $e){
            echo $e->getMessage();
        }
    }

    // read clients
    function read(){
     
        // query to read single record
        $query = "SELECT *
                FROM
                    " . $this->table_name . " 
                ORDER BY
                    created DESC";
     
        // prepare query statement
        $stmt = $this->conn->prepare($query);
     
        // execute query
        $stmt->execute();
     
        return $stmt;
    }

    // read only one product
    function readOne(){
     
        // query to read single record
        $query = "SELECT
                    client_id, _id, firstname, lastname, email, password, description, country, state, address, contact_number, gender, dob, isVerified, profile_img, created, modified
                FROM
                    " . $this->table_name . " 
                WHERE _id = ?
                LIMIT 0,1";
     
        // prepare query statement
        $stmt = $this->conn->prepare( $query );
     
        // bind id of product to be updated
        $stmt->bindParam(1, $this->_id);
     
        // execute query
        $stmt->execute();
     
        // get retrieved row
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
     
        // set values to object properties
        $this->client_id = $row['client_id'];
        $this->_id = $row['_id'];
        $this->firstname = $row['firstname'];
        $this->lastname = $row['lastname'];
        $this->email = $row['email'];
        $this->password = $row['password'];
        $this->description = $row['description'];
        $this->country = $row['country'];
        $this->state = $row['state'];
        $this->address = $row['address'];
        $this->contact_number = $row['contact_number'];
        $this->gender = $row['gender'];
        $this->dob = $row['dob'];
        $this->isVerified = $row['isVerified'];
        $this->profile_img = $row['profile_img'];
        $this->created = $row['created'];
        $this->modified = $row['modified'];
    }

    public function getdescription($client_id){
       try
       {    
            // query to check if email exists
            $query = "SELECT description
                    FROM " . $this->table_name . "
                    WHERE client_id = :client_id
                    LIMIT 0,1";
         
            // prepare the query
            $stmt = $this->conn->prepare( $query );

            $stmt->execute(array(':client_id'=>$client_id));
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if($stmt->rowCount() > 0){
                // set values to object properties
                $this->description = $row['description'];
                return $this->description;
                // return $row;
            }
        }
        catch(PDOException $e){
            echo $e->getMessage();
        }
    }

    public function readOneClient($_id){
       try
       {    
            // query to check if email exists
            $query = "SELECT *
                    FROM " . $this->table_name . "
                    WHERE _id = :_id
                    LIMIT 0,1";
         
            // prepare the query
            $stmt = $this->conn->prepare( $query );

            $stmt->execute(array(':_id'=>$_id));
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if($stmt->rowCount() > 0){
                // set values to object properties
                $this->client_id = $row['client_id'];
                $this->_id = $row['_id'];
                $this->firstname = $row['firstname'];
                $this->lastname = $row['lastname'];
                $this->email = $row['email'];
                $this->password = $row['password'];
                $this->description = $row['description'];
                $this->country = $row['country'];
                $this->state = $row['state'];
                $this->address = $row['address'];
                $this->contact_number = $row['contact_number'];
                $this->gender = $row['gender'];
                $this->isVerified = $row['isVerified'];
                $this->profile_img = $row['profile_img'];
                $this->created = $row['created'];
                $this->modified = $row['modified'];

                return $row;
            }
        }
        catch(PDOException $e){
            echo $e->getMessage();
        }
    }
    
    public function update(){
         $query = "UPDATE " . $this->table_name . "
                SET
                    firstname = :firstname,
                    lastname = :lastname,
                    email = :email,
                    country = :country,
                    state = :state,
                    contact_number = :contact_number,
                    gender = :gender,
                    dob = :dob,
                    address = :address,
                    description = :description,
                    modified = :modified
                WHERE _id = :_id";

        // prepare the query
        $stmt = $this->conn->prepare($query);
     
        // sanitize
        $this->firstname=htmlspecialchars(strip_tags($this->firstname));
        $this->lastname=htmlspecialchars(strip_tags($this->lastname));
        $this->email=htmlspecialchars(strip_tags($this->email));
        $this->country=htmlspecialchars(strip_tags($this->country));
        $this->state=htmlspecialchars(strip_tags($this->state));
        $this->contact_number=htmlspecialchars(strip_tags($this->contact_number));
        $this->gender=htmlspecialchars(strip_tags($this->gender));
        $this->dob=htmlspecialchars(strip_tags($this->dob));
        $this->address=htmlspecialchars(strip_tags($this->address));
        $this->description=htmlspecialchars(strip_tags($this->description));
        $this->modified=htmlspecialchars(strip_tags($this->modified));

        // bind the values from the form
        $stmt->bindParam(':firstname', $this->firstname);
        $stmt->bindParam(':lastname', $this->lastname);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':country', $this->country);
        $stmt->bindParam(':state', $this->state);
        $stmt->bindParam(':contact_number', $this->contact_number);
        $stmt->bindParam(':gender', $this->gender);
        $stmt->bindParam(':dob', $this->dob);
        $stmt->bindParam(':address', $this->address);
        $stmt->bindParam(':description', $this->description);
        $stmt->bindParam(':modified', $this->modified);

        // hash the password before saving to database
        // if(!empty($this->password)){
        //     $this->password=htmlspecialchars(strip_tags($this->password));
        //     $password_hash = password_hash($this->password, PASSWORD_BCRYPT);
        //     $stmt->bindParam(':password', $password_hash);
        // }
     
        // unique ID of record to be edited
        $stmt->bindParam(':_id', $this->_id);
     
        // execute the query
        if($stmt->execute()){
            return true;
        }
     
        return false;
    }

    public function updatePassword(){
        
        $query = "UPDATE " . $this->table_name . "
                SET
                    password = :password,
                    modified = :modified
                WHERE _id = :_id";
        // prepare the query
        $stmt = $this->conn->prepare($query);
        // sanitize
        $this->password=htmlspecialchars(strip_tags($this->password));
        $this->modified=htmlspecialchars(strip_tags($this->modified));
        // bind the values
        $password_hash = password_hash($this->password, PASSWORD_BCRYPT);

        $stmt->bindParam(':password', $this->password_hash);
        $stmt->bindParam(':modified', $this->modified);
        // unique ID of record to be edited
        $stmt->bindParam(':_id', $this->_id);
        // execute the query
        if($stmt->execute()){
            return true;
        }
     
        return false;
    }
    
    public function updateImg(){
        $query = "UPDATE " . $this->table_name . "
                SET
                    profile_img = :profile_img,
                    modified = :modified
                WHERE _id = :_id";
        // prepare the query
        $stmt = $this->conn->prepare($query);
        // sanitize
        $this->profile_img=htmlspecialchars(strip_tags($this->profile_img));
        $this->modified=htmlspecialchars(strip_tags($this->modified));
        // bind the values
        $stmt->bindParam(':profile_img', $this->profile_img);
        $stmt->bindParam(':modified', $this->modified);
        // unique ID of record to be edited
        $stmt->bindParam(':_id', $this->_id);
        // execute the query
        if($stmt->execute()){
            return true;
        }
     
        return false;
    }

    public function showError($stmt){
        echo "<pre>";
            print_r($stmt->errorInfo());
        echo "</pre>";
    }

    // delete the product
    function delete(){
     
        // delete query
        $query = "DELETE FROM " . $this->table_name . " WHERE _id = ?";
     
        // prepare query
        $stmt = $this->conn->prepare($query);
     
        // sanitize
        $this->_id = htmlspecialchars(strip_tags($this->_id));
     
        // bind id of record to delete
        $stmt->bindParam(1, $this->_id);
     
        // execute query
        if($stmt->execute()){
            return true;
        }
     
        return false;
    }

    public function generate_file(){   
        $sql = "SELECT * FROM ".$this->table_name;
        // echo $sql;
        $stmt = $this->conn->prepare($sql);
        // BIND COLUMN
        $stmt->execute();
        $row = $stmt->fetchAll(PDO::FETCH_OBJ);
        return $row;
    }

}

