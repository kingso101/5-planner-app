<?php
ob_start();
// session_start();
// 'notification' object
class Notification{
 
    // database connection and table name
    private $conn;
    private $table_name = "notifications";

    // object properties
    public $notification_id;
    public $_id;
    public $admin_id;
    public $client_id;
    public $subject;
    public $message;
    public $reply;
    public $seen;
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
                message = :message,
                subject = :subject,
                client_id = :client_id,
                admin_id = :admin_id,
                created = :created";
     
        // prepare the query
        $stmt = $this->conn->prepare($query);
     
        // sanitize
        $this->_id=htmlspecialchars(strip_tags($this->_id));
        $this->message=htmlspecialchars(strip_tags($this->message));
        $this->subject=htmlspecialchars(strip_tags($this->subject));
        $this->client_id=htmlspecialchars(strip_tags($this->client_id));
        $this->admin_id=htmlspecialchars(strip_tags($this->admin_id));
        $this->created=htmlspecialchars(strip_tags($this->created));
     
        // bind the values
        $stmt->bindParam(':_id', $this->_id);   
        $stmt->bindParam(':message', $this->message);
        $stmt->bindParam(':subject', $this->subject);
        $stmt->bindParam(':client_id', $this->client_id);
        $stmt->bindParam(':admin_id', $this->admin_id);
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
                    notification_id, _id, client_id, admin_id, subject, message, reply, seen, created
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
                    notification_id, _id, client_id, admin_id, subject, message, reply, seen, created
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
        $this->notification_id = $row['notification_id'];
        $this->_id = $row['_id'];
        $this->client_id = $row['client_id'];
        $this->admin_id = $row['admin_id'];
        $this->subject = $row['subject'];
        $this->message = $row['message'];
        $this->reply = $row['reply'];
        $this->seen = $row['seen'];
        $this->created = $row['created'];
    }
     
    // update a user record
    public function update(){
     
        // if password needs to be updated
        $query = "UPDATE " . $this->table_name . "
                SET
                    subject = :subject,
                    message = :message,
                WHERE _id = :_id";

     
        // prepare the query
        $stmt = $this->conn->prepare($query);
     
        // sanitize
        $this->subject=htmlspecialchars(strip_tags($this->subject));
        $this->message=htmlspecialchars(strip_tags($this->message));
        $this->admin_id=htmlspecialchars(strip_tags($this->admin_id));

        // bind the values from the form
        $stmt->bindParam(':message', $this->message);
        $stmt->bindParam(':subject', $this->subject);
        $stmt->bindParam(':client_id', $this->client_id);
        $stmt->bindParam(':admin_id', $this->admin_id);

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
    function countNotifications(){
     
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
                    notification_id, _id, client_id, admin_id, subject, message, reply, seen, created
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

}

