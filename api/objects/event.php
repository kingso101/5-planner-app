<?php
ob_start();
// session_start();
// 'event' object
class Event{
 
    // database connection and table name
    private $conn;
    private $table_name = "events";

    // object properties
    public $event_id;
    public $_id;
    public $event_title;
    public $event_date;
    public $event_time;
    public $event_location;
    public $event_desc;
    public $event_img;
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
                event_title = :event_title,
                event_date = :event_date,
                event_time = :event_time,
                event_location = :event_location,
                event_desc = :event_desc,
                event_img = :event_img;
                created = :created";
     
        // prepare the query
        $stmt = $this->conn->prepare($query);
     
        // sanitize
        $this->_id=htmlspecialchars(strip_tags($this->_id));
        $this->event_title=htmlspecialchars(strip_tags($this->event_title));
        $this->event_date=htmlspecialchars(strip_tags($this->event_date));
        $this->event_time=htmlspecialchars(strip_tags($this->event_time));
        $this->event_location=htmlspecialchars(strip_tags($this->event_location));
        $this->event_desc=htmlspecialchars(strip_tags($this->event_desc));
        $this->event_img=htmlspecialchars(strip_tags($this->event_img));
        $this->created=htmlspecialchars(strip_tags($this->created));
     
        // bind the values
        $stmt->bindParam(':_id', $this->_id);
        $stmt->bindParam(':event_title', $this->event_title);
        $stmt->bindParam(':event_date', $this->event_date);
        $stmt->bindParam(':event_time', $this->event_time);
        $stmt->bindParam(':event_location', $this->event_location);
        $stmt->bindParam(':event_desc', $this->event_desc);
        $stmt->bindParam(':event_img', $this->event_img);
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
    function eventExists(){
     
        // query to check if email exists
        $query = "SELECT event_id, _id, event_title, event_date, event_time, event_location, event_desc
                FROM " . $this->table_name . "
                WHERE event_title = ?
                LIMIT 0,1";
     
        // prepare the query
        $stmt = $this->conn->prepare( $query );
     
        // sanitize
        $this->event_title=htmlspecialchars(strip_tags($this->event_title));
     
        // bind given event value
        $stmt->bindParam(1, $this->event_title);
     
        // execute the query
        $stmt->execute();
     
        // get number of rows
        $num = $stmt->rowCount();
     
        // if event exists, assign values to object properties for easy access and use for php sessions
        if($num>0){
     
            // get record details / values
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            // var_dump($row);
     
            // assign values to object properties
            $this->id = $row['event_id'];
            $this->_id = $row['_id'];
            $this->event_title = $row['event_title'];
            $this->event_date = $row['event_date'];
            $this->event_time = $row['event_time'];
            $this->event_location = $row['event_location'];
            $this->event_desc = $row['event_desc'];
     
            // return true because event exists in the database
            return true;
        }
     
        // return false if event does not exist in the database
        return false;
    }

    // read products
    function read(){
     
        // query to read single record
        $query = "SELECT
                    event_id, _id, event_title, event_date, event_time, event_location, event_desc, event_img, created
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
                    event_id, _id, event_title, event_date, event_time, event_location, event_desc, event_img, created
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
        $this->id = $row['event_id'];
        $this->_id = $row['_id'];
        $this->event_title = $row['event_title'];
        $this->event_date = $row['event_date'];
        $this->event_time = $row['event_time'];
        $this->event_location = $row['event_location'];
        $this->event_desc = $row['event_desc'];
        $this->event_img = $row['event_img'];
        $this->created = $row['created'];
    }
     
    // update a user record
    public function update(){
     
        // if password needs to be updated
        $query = "UPDATE " . $this->table_name . "
                SET
                    event_title = :event_title,
                    event_date = :event_date,
                    event_time = :event_time,
                    event_location = :event_location,
                    event_desc = :event_desc,
                    event_img = :event_img
                WHERE _id = :_id";

     
        // prepare the query
        $stmt = $this->conn->prepare($query);
     
        // sanitize
        $this->event_title=htmlspecialchars(strip_tags($this->event_title));
        $this->event_date=htmlspecialchars(strip_tags($this->event_date));
        $this->event_time=htmlspecialchars(strip_tags($this->event_time));
        $this->event_location=htmlspecialchars(strip_tags($this->event_location));
        $this->event_desc=htmlspecialchars(strip_tags($this->event_desc));
        $this->event_img=htmlspecialchars(strip_tags($this->event_img));

        // bind the values from the form
        $stmt->bindParam(':event_title', $this->event_title);
        $stmt->bindParam(':event_date', $this->event_date);
        $stmt->bindParam(':event_time', $this->event_time);
        $stmt->bindParam(':event_location', $this->event_location);
        $stmt->bindParam(':event_desc', $this->event_desc);
        $stmt->bindParam(':event_img', $this->event_img);
     
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

}

