<?php
ob_start();
// session_start();
// 'contact' object
class Contact{
 
    // database connection and table name
    private $conn;
    private $table_name = "contacts";

    // object properties
    public $contact_id;
    public $_id;
    public $name;
    public $email;
    public $phone;
    public $message;
    public $subject;
    public $client_id;
    public $created;
    // public $modified;

 
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
                name = :name,
                email = :email,
                phone = :phone,
                message = :message,
                subject = :subject,
                client_id = :client_id,
                created = :created";
     
        // prepare the query
        $stmt = $this->conn->prepare($query);
     
        // sanitize
        $this->_id=htmlspecialchars(strip_tags($this->_id));
        $this->name=htmlspecialchars(strip_tags($this->name));
        $this->email=htmlspecialchars(strip_tags($this->email));
        $this->phone=htmlspecialchars(strip_tags($this->phone));
        $this->message=htmlspecialchars(strip_tags($this->message));
        $this->subject=htmlspecialchars(strip_tags($this->subject));
        $this->client_id=htmlspecialchars(strip_tags($this->client_id));
        $this->created=htmlspecialchars(strip_tags($this->created));
     
        // bind the values
        $stmt->bindParam(':_id', $this->_id);
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':phone', $this->phone);
        $stmt->bindParam(':message', $this->message);
        $stmt->bindParam(':subject', $this->subject);
        $stmt->bindParam(':client_id', $this->client_id);
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

    // read products
    function read(){
     
        // query to read single record
        $query = "SELECT
                    contact_id, _id, name, email, phone, message, subject, reply, seen, client_id, created
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
                    contact_id, _id, name, email, phone, message, subject, reply, seen, client_id, created
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
        $this->contact_id = $row['contact_id'];
        $this->_id = $row['_id'];
        $this->name = $row['name'];
        $this->email = $row['email'];
        $this->phone = $row['phone'];
        $this->message = $row['message'];
        $this->subject = $row['subject'];
        $this->reply = $row['reply'];
        $this->seen = $row['seen'];
        $this->client_id = $row['client_id'];
        $this->created = $row['created'];
    }
     
    // update a user record
    public function update(){
     
        // if password needs to be updated
        $query = "UPDATE " . $this->table_name . "
                SET
                    name = :name,
                    email = :email,
                    phone = :phone,
                    message = :message,
                    subject = :subject,
                    client_id = :client_id,
                WHERE _id = :_id";

     
        // prepare the query
        $stmt = $this->conn->prepare($query);
     
        // sanitize
        $this->name=htmlspecialchars(strip_tags($this->name));
        $this->email=htmlspecialchars(strip_tags($this->email));
        $this->phone=htmlspecialchars(strip_tags($this->phone));
        $this->message=htmlspecialchars(strip_tags($this->message));
        $this->subject=htmlspecialchars(strip_tags($this->subject));
        $this->client_id=htmlspecialchars(strip_tags($this->client_id));

        // bind the values from the form
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':phone', $this->phone);
        $stmt->bindParam(':message', $this->message);
        $stmt->bindParam(':subject', $this->subject);
        $stmt->bindParam(':client_id', $this->client_id);
     
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

    // read the first mail
    function countMessages(){
     
        // query to read single record
        $query = "SELECT *
                FROM
                    " . $this->table_name . " 
                WHERE seen = 0";
     
        // prepare query statement
        $stmt = $this->conn->prepare($query);
     
        // execute query
        $stmt->execute();
        $stmt = $stmt->fetchAll(PDO::FETCH_ASSOC);
     
        return $stmt;
    }

    // read the first mail
    function readTheFirstThread(){
     
        // query to read single record
        $query = "SELECT
                    contact_id, _id, name, email, phone, message, subject, reply, seen, client_id, created
                FROM
                    " . $this->table_name . " 
                WHERE seen = 1";
     
        // prepare query statement
        $stmt = $this->conn->prepare($query);
     
        // execute query
        $stmt->execute();
     
        return $stmt;
    }

    // mark record as read
    public function markAsRead(){
     
        // if password needs to be updated
        $query = "UPDATE " . $this->table_name . "
                SET
                    seen = :seen
                WHERE _id = :_id";

     
        // prepare the query
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(':seen', $this->seen);
        $stmt->bindParam(':_id', $this->_id);
     
        // execute the query
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

