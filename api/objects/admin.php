<?php
if(!isset($_SESSION)){
    session_start();
}
// 'admin' object
class Admin{
 
    // database connection and table name
    private $conn;
    private $table_name = "admin";

    // object properties
    public $id;
    public $_id;
    public $firstname;
    public $lastname;
    public $email;
    public $contact_number;
    public $gender;
    public $address;
    public $password;
    public $access_level;
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
                gender = :gender,
                contact_number = :contact_number,
                address = :address,
                password = :password,
                access_level = :access_level,
                profile_img = :profile_img,
                created = :created";
     
        // prepare the query
        $stmt = $this->conn->prepare($query);
     
        // sanitize
        $this->_id=htmlspecialchars(strip_tags($this->_id));
        $this->firstname=htmlspecialchars(strip_tags($this->firstname));
        $this->lastname=htmlspecialchars(strip_tags($this->lastname));
        $this->email=htmlspecialchars(strip_tags($this->email));
        $this->gender=htmlspecialchars(strip_tags($this->gender));
        $this->contact_number=htmlspecialchars(strip_tags($this->contact_number));
        $this->address=htmlspecialchars(strip_tags($this->address));
        $this->password=htmlspecialchars(strip_tags($this->password));
        $this->access_level=htmlspecialchars(strip_tags($this->access_level));
        $this->profile_img=htmlspecialchars(strip_tags($this->profile_img));
        $this->created=htmlspecialchars(strip_tags($this->created));
     
        // bind the values
        $stmt->bindParam(':_id', $this->_id);
        $stmt->bindParam(':firstname', $this->firstname);
        $stmt->bindParam(':lastname', $this->lastname);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':gender', $this->gender);
        $stmt->bindParam(':contact_number', $this->contact_number);
        $stmt->bindParam(':address', $this->address);
     
        // hash the password before saving to database
        $password_hash = password_hash($this->password, PASSWORD_BCRYPT);
        $stmt->bindParam(':password', $password_hash);
     
        $stmt->bindParam(':access_level', $this->access_level);
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
        $query = "SELECT id, _id, firstname, lastname, password, access_level
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
            $this->id = $row['id'];
            $this->_id = $row['_id'];
            $this->firstname = $row['firstname'];
            $this->lastname = $row['lastname'];
            $this->access_level = $row['access_level'];
            $this->password = $row['password'];
     
            // return true because email exists in the database
            return true;
        }
     
        // return false if email does not exist in the database
        return false;
    }

    public function login($email,$password){
       try
       {    
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
                    $_SESSION['_id'] = $row['_id'];
                    $_SESSION['id'] = $row['id'];
                    $_SESSION['firstname'] = $row['firstname'];
                    $_SESSION['lastname'] = $row['lastname'];
                    $_SESSION['email'] = $row['email'];
                    $_SESSION['access_level'] = $row['access_level'];
                    $_SESSION['profile_img'] = $row['profile_img'];
                    return true;
                }
                else{
                    return false;
                }
            }
        }
        catch(PDOException $e){
            echo $e->getMessage();
        }
    }

    // read products
    function read(){
     
        // query to read single record
        $query = "SELECT
                    id, _id, firstname, lastname, email, gender, contact_number, address, access_level, password, profile_img, created, modified
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
                    id, _id, firstname, lastname, email, gender, contact_number, address, access_level, password, profile_img, created, modified
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
        $this->id = $row['id'];
        $this->_id = $row['_id'];
        $this->firstname = $row['firstname'];
        $this->lastname = $row['lastname'];
        $this->email = $row['email'];
        $this->gender = $row['gender'];
        $this->contact_number = $row['contact_number'];
        $this->address = $row['address'];
        $this->password = $row['password'];
        $this->access_level = $row['access_level'];
        $this->profile_img = $row['profile_img'];
        $this->created = $row['created'];
        $this->modified = $row['modified'];
    }
     
    // update a user record
    public function update(){
     
        // if password needs to be updated
        // $password_set = !empty($this->password) ? ", password = :password" : "";
     
        // if no posted password, do not update the password
        // $query = "UPDATE " . $this->table_name . "
        //         SET
        //             firstname = :firstname,
        //             lastname = :lastname,
        //             email = :email,
        //             gender = :gender,
        //             address = :address,
        //             contact_number = :contact_number,
        //             access_level = :access_level,
        //             profile_img = :profile_img,
        //             modified = :modified,
        //             {$password_set}
        //         WHERE _id = :_id";
        $query = "UPDATE " . $this->table_name . "
                SET
                    firstname = :firstname,
                    lastname = :lastname,
                    email = :email,
                    gender = :gender,
                    address = :address,
                    contact_number = :contact_number,
                    access_level = :access_level,
                    profile_img = :profile_img,
                    modified = :modified
                WHERE _id = :_id";

     
        // prepare the query
        $stmt = $this->conn->prepare($query);
     
        // sanitize
        $this->firstname=htmlspecialchars(strip_tags($this->firstname));
        $this->lastname=htmlspecialchars(strip_tags($this->lastname));
        $this->email=htmlspecialchars(strip_tags($this->email));
        $this->gender=htmlspecialchars(strip_tags($this->gender));
        $this->address=htmlspecialchars(strip_tags($this->address));
        $this->contact_number=htmlspecialchars(strip_tags($this->contact_number));
        $this->access_level=htmlspecialchars(strip_tags($this->access_level));
        $this->profile_img=htmlspecialchars(strip_tags($this->profile_img));
        $this->modified=htmlspecialchars(strip_tags($this->modified));

        // bind the values from the form
        $stmt->bindParam(':firstname', $this->firstname);
        $stmt->bindParam(':lastname', $this->lastname);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':gender', $this->gender);
        $stmt->bindParam(':address', $this->address);
        $stmt->bindParam(':contact_number', $this->contact_number);
        $stmt->bindParam(':access_level', $this->access_level);
        $stmt->bindParam(':profile_img', $this->profile_img);
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

    // read the count newsletter
    function countAdmin(){
     
        // query to read single record
        $query = "SELECT *
                FROM
                    " . $this->table_name;
     
        // prepare query statement
        $stmt = $this->conn->prepare($query);
     
        // execute query
        $stmt->execute();
        $stmt = $stmt->fetchAll(PDO::FETCH_ASSOC);
     
        return $stmt;
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

