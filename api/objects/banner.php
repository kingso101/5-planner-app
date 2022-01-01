<?php
ob_start();
// session_start();
// 'banner' object
class Banner{
 
    // database connection and table name
    private $conn;
    private $table_name = "banner";

    // object properties
    public $banner_id;
    public $_id;
    public $banner_title;
    public $banner_img;
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
                banner_title = :banner_title,
                banner_img = :banner_img;
                created = :created";
     
        // prepare the query
        $stmt = $this->conn->prepare($query);
     
        // sanitize
        $this->_id=htmlspecialchars(strip_tags($this->_id));
        $this->banner_title=htmlspecialchars(strip_tags($this->banner_title));
        $this->created=htmlspecialchars(strip_tags($this->created));
     
        // bind the values
        $stmt->bindParam(':_id', $this->_id);
        $stmt->bindParam(':banner_title', $this->banner_title);
        $stmt->bindParam(':banner_img', $this->banner_img);
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
    function bannerExists(){
     
        // query to check if email exists
        $query = "SELECT banner_id, _id, banner_title,
                FROM " . $this->table_name . "
                WHERE banner_title = ?
                LIMIT 0,1";
     
        // prepare the query
        $stmt = $this->conn->prepare( $query );
     
        // sanitize
        $this->banner_title=htmlspecialchars(strip_tags($this->banner_title));
     
        // bind given banner value
        $stmt->bindParam(1, $this->banner_title);
     
        // execute the query
        $stmt->execute();
     
        // get number of rows
        $num = $stmt->rowCount();
     
        // if banner exists, assign values to object properties for easy access and use for php sessions
        if($num>0){
     
            // get record details / values
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            // var_dump($row);
     
            // assign values to object properties
            $this->id = $row['banner_id'];
            $this->_id = $row['_id'];
            $this->banner_title = $row['banner_title'];
     
            // return true because banner exists in the database
            return true;
        }
     
        // return false if banner does not exist in the database
        return false;
    }

    // read products
    function read(){
     
        // query to read single record
        $query = "SELECT
                    banner_id, _id, banner_title, banner_img, created
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
                    banner_id, _id, banner_title, banner_img, created
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
        $this->id = $row['banner_id'];
        $this->_id = $row['_id'];
        $this->banner_title = $row['banner_title'];
        $this->banner_img = $row['banner_img'];
        $this->created = $row['created'];
    }
     
    // update a user record
    public function update(){
     
        // if password needs to be updated
        $query = "UPDATE " . $this->table_name . "
                SET
                    banner_title = :banner_title,
                    banner_img = :banner_img
                WHERE _id = :_id";

     
        // prepare the query
        $stmt = $this->conn->prepare($query);
     
        // sanitize
        $this->banner_title=htmlspecialchars(strip_tags($this->banner_title));
        $this->banner_img=htmlspecialchars(strip_tags($this->banner_img));

        // bind the values from the form
        $stmt->bindParam(':banner_title', $this->banner_title);
        $stmt->bindParam(':banner_img', $this->banner_img);
     
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

