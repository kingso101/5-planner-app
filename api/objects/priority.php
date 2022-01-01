<?php
ob_start();
// 'priority' object
class Priority{
 
    // database connection and table name
    private $conn;
    private $table_name = "priority";
    private $plan_table = "plan";
    private $plan_analytics_table = "analytics";

    // object properties
    public $priority_id;
    public $priority_label;
    public $priority_date;
    public $client_id;
    public $key_id;
    public $created;
    public $total_priorities;

 
    // constructor
    public function __construct($db){
        $this->conn = $db;
    }
 
    // create new user record
    function create(){
        // insert query
        $query = "INSERT INTO " . $this->table_name . "
            SET
                key_id = :key_id,
                client_id = :client_id,
                priority_label = :priority_label,
                priority_date = :priority_date,
                created = :created";
     
        // prepare the query
        $stmt = $this->conn->prepare($query);
     
        // sanitize
        $this->key_id=htmlspecialchars(strip_tags($this->key_id));
        $this->client_id=htmlspecialchars(strip_tags($this->client_id));
        $this->priority_label=htmlspecialchars(strip_tags($this->priority_label));
        $this->priority_date=htmlspecialchars(strip_tags($this->priority_date));
        $this->created=htmlspecialchars(strip_tags($this->created));
     
        // bind the values
        $stmt->bindParam(':key_id', $this->key_id);
        $stmt->bindParam(':client_id', $this->client_id);
        $stmt->bindParam(':priority_label', $this->priority_label);
        $stmt->bindParam(':priority_date', $this->priority_date);
        $stmt->bindParam(':created', $this->created);
     
        // execute the query, also check if query was successful
        if($stmt->execute()){
            $this->priority_id = $this->conn->lastInsertId();
            $this->total_priorities .=1;

            $query2 = "UPDATE " . $this->plan_table . "
                SET
                    priority_label = :priority_label,
                    priority_date = :priority_date
                WHERE key_id = :key_id";

            // prepare the query2
            $stmt2 = $this->conn->prepare($query2);
         
            // unique ID of record to be edited
            $stmt2->bindParam(':priority_label', $this->priority_label);
            $stmt2->bindParam(':priority_date', $this->priority_date);
            $stmt2->bindParam(':key_id', $this->key_id);
         
            // execute the query2
            if($stmt2->execute()){
                //query the db
                $query3 = "INSERT INTO " . $this->plan_analytics_table . "
                    SET
                        priority_id = :priority_id,
                        key_id = :key_id,
                        total_priorities = :total_priorities";

                // prepare the query3
                $stmt3 = $this->conn->prepare($query3);
                
                // unique ID of record to be edited
                $stmt3->bindParam(':priority_id', $this->priority_id);
                $stmt3->bindParam(':key_id', $this->key_id);
                $stmt3->bindParam(':total_priorities', $this->total_priorities);
             
                // execute the query3
                if($stmt3->execute()){
                    return true;
                }
                return true;
            }
            return true;    
        }
        else{
            $this->showError($stmt);
            return false;
        }
    }

    // check if given email exist in the database
    function priorityExists(){
     
        // query to check if email exists
        $query = "SELECT priority_id, priority_date, created
                FROM " . $this->table_name . "
                WHERE priority_label = ?
                LIMIT 0,1";
     
        // prepare the query
        $stmt = $this->conn->prepare( $query );
     
        // sanitize
        $this->priority_label=htmlspecialchars(strip_tags($this->priority_label));
     
        // bind given priority_label value
        $stmt->bindParam(1, $this->priority_label);
     
        // execute the query
        $stmt->execute();
     
        // get number of rows
        $num = $stmt->rowCount();
     
        // if priority label exists, assign values to object properties for easy access and use for php sessions
        if($num>0){
     
            // get record details / values
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            // var_dump($row);
     
            // assign values to object properties
            $this->priority_id = $row['priority_id'];
            $this->priority_date = $row['priority_date'];
            $this->created = $row['created'];
     
            // return true because priority label exists in the database
            return true;
        }
     
        // return false if priority label does not exist in the database
        return false;
    }

    // read to_account sheets
    function read(){
     
        // query to read single record
        $query = "SELECT
                    priority_id, key_id, priority_label, priority_date, isDone, created
                FROM
                    " . $this->table_name . " 
                ORDER BY
                    created ASC";
     
        // prepare query statement
        $stmt = $this->conn->prepare($query);
     
        // execute query
        $stmt->execute();
     
        return $stmt;
    }

    // read only one to_account sheet
    function readOne(){
     
        // query to read single record
        $query = "SELECT
                    priority_id, key_id, priority_label, priority_date, isDone, created
                FROM
                    " . $this->table_name . " 
                WHERE priority_id = ?
                LIMIT 0,1";
     
        // prepare query statement
        $stmt = $this->conn->prepare( $query );
     
        // bind id of to_account sheet to be updated
        $stmt->bindParam(1, $this->priority_id);
     
        // execute query
        $stmt->execute();
     
        // get retrieved row
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
     
        // set values to object properties
        $this->priority_id = $row['priority_id'];
        $this->key_id = $row['key_id'];
        $this->priority_label = $row['priority_label'];
        $this->priority_date = $row['priority_date'];
        $this->isDone = $row['isDone'];
        $this->created = $row['created'];
    }
     
    // update a user record
    public function update(){
        
        $query = "UPDATE " . $this->table_name . "
                SET
                    priority_label = :priority_label,
                    priority_date = :priority_date
                WHERE key_id = :key_id";
        // prepare the query
        $stmt = $this->conn->prepare($query);
        // sanitize
        $this->priority_label=htmlspecialchars(strip_tags($this->priority_label));
        $this->priority_date=htmlspecialchars(strip_tags($this->priority_date));
        // bind the values
        $stmt->bindParam(':priority_label', $this->priority_label);
        $stmt->bindParam(':priority_date', $this->priority_date);
        // unique ID of record to be edited
        $stmt->bindParam(':key_id', $this->key_id);
        // execute the query
        if($stmt->execute()){
            $query2 = "UPDATE " . $this->plan_table . "
                SET
                    priority_label = :priority_label,
                    priority_date = :priority_date
                WHERE key_id = :key_id";

            // prepare the query2
            $stmt2 = $this->conn->prepare($query2);
         
            // unique ID of record to be edited
            $stmt2->bindParam(':priority_label', $this->priority_label);
            $stmt2->bindParam(':priority_date', $this->priority_date);
            $stmt2->bindParam(':key_id', $this->key_id);
         
            // execute the query2
            if($stmt2->execute()){
                return true;
            }
            return true;    
        }
        else{
            $this->showError($stmt);
            return false;
        }
    }

    public function showError($stmt){
        echo "<pre>";
            print_r($stmt->errorInfo());
        echo "</pre>";
    }

    // delete the to_account sheet
    function delete(){
     
        // delete query
        $query = "DELETE FROM " . $this->table_name . " WHERE priority_id = ?";
     
        // prepare query
        $stmt = $this->conn->prepare($query);
     
        // sanitize
        $this->priority_id = htmlspecialchars(strip_tags($this->priority_id));
     
        // bind id of record to delete
        $stmt->bindParam(1, $this->priority_id);
     
        // execute query
        if($stmt->execute()){
            return true;
        }
     
        return false;
    }

    // count priorities
    public function countPriorities($client_id){
       try
       {    
            // query to check if email exists
            $query = "SELECT *
                    FROM " . $this->table_name . "
                    WHERE client_id = :client_id";
         
            // prepare the query
            $stmt = $this->conn->prepare( $query );

            $stmt->execute(array(':client_id'=>$client_id));
            $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if($stmt->rowCount() > 0){

                return $row;
            }
        }
        catch(PDOException $e){
            echo $e->getMessage();
        }
    }

    // Count completed priorities
    public function countCompletedPriorities($client_id, $key_id, $priority_label){
       try
       {    
            // query to check if email exists
            $query = "SELECT *
                    FROM " . $this->table_name . "
                    WHERE isDone = 1 AND client_id = :client_id AND key_id = :key_id AND priority_label = :priority_label";
         
            // prepare the query
            $stmt = $this->conn->prepare( $query );

            $stmt->execute(array(':key_id'=>$key_id, ':client_id'=>$client_id, ':priority_label'=>$priority_label));
            $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if($stmt->rowCount() > 0){

                return $row;
            }
        }
        catch(PDOException $e){
            echo $e->getMessage();
        }
    }

    // Count completed priorities
    public function countOngoingPriorities($priority_id){
       try
       {    
            // query to check if email exists
            $query = "SELECT *
                    FROM " . $this->table_name . "
                    WHERE isDone = 0 && priority_id = :priority_id";
         
            // prepare the query
            $stmt = $this->conn->prepare( $query );

            $stmt->execute(array(':priority_id'=>$priority_id));
            $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if($stmt->rowCount() > 0){

                return $row;
            }
        }
        catch(PDOException $e){
            echo $e->getMessage();
        }
    }

    // update a part of priority record
    public function updatepriorityInt(){
        
        $query = "UPDATE " . $this->table_name . "
                SET
                    priority_interval = :priority_interval,
                    modified = :modified
                WHERE priority_id = :priority_id";
        // prepare the query
        $stmt = $this->conn->prepare($query);
        // sanitize
        $this->priority_interval=htmlspecialchars(strip_tags($this->priority_interval));
        $this->modified=htmlspecialchars(strip_tags($this->modified));
        // bind the values
        $stmt->bindParam(':priority_interval', $this->priority_interval);
        $stmt->bindParam(':modified', $this->modified);
        // unique ID of record to be edited
        $stmt->bindParam(':priority_id', $this->priority_id);
        // execute the query
        if($stmt->execute()){
            return true;
        }
     
        return false;
    }

    // update a part of priority record
    public function updatePriorityLabel(){
        
        $query = "UPDATE " . $this->table_name . "
                SET
                    priority_label = :priority_label,
                    modified = :modified
                WHERE priority_id = :priority_id";
        // prepare the query
        $stmt = $this->conn->prepare($query);
        // sanitize
        $this->priority_label=htmlspecialchars(strip_tags($this->priority_label));
        $this->modified=htmlspecialchars(strip_tags($this->modified));
        // bind the values
        $stmt->bindParam(':priority_label', $this->priority_label);
        $stmt->bindParam(':modified', $this->modified);
        // unique ID of record to be edited
        $stmt->bindParam(':priority_id', $this->priority_id);
        // execute the query
        if($stmt->execute()){
            return true;
        }
     
        return false;
    }

    // update a part of priority record
    public function addPriorityLabel(){
        
        $query = "UPDATE " . $this->table_name . "
                SET
                    priority_label = :priority_label,
                    priority_date = :priority_date,
                    modified = :modified
                WHERE priority_id = :priority_id";
        // prepare the query
        $stmt = $this->conn->prepare($query);
        // sanitize
        $this->priority_label=htmlspecialchars(strip_tags($this->priority_label));
        $this->priority_date=htmlspecialchars(strip_tags($this->priority_date));
        $this->modified=htmlspecialchars(strip_tags($this->modified));
        // bind the values
        $stmt->bindParam(':priority_label', $this->priority_label);
        $stmt->bindParam(':priority_date', $this->priority_date);
        $stmt->bindParam(':modified', $this->modified);
        // unique ID of record to be edited
        $stmt->bindParam(':priority_id', $this->priority_id);
        // execute the query
        if($stmt->execute()){
            return true;
        }
     
        return false;
    }

    // update a part of priority record
    public function updatePriorityDate(){
        
        $query = "UPDATE " . $this->table_name . "
                SET
                    priority_date = :priority_date,
                    modified = :modified
                WHERE priority_id = :priority_id";
        // prepare the query
        $stmt = $this->conn->prepare($query);
        // sanitize
        $this->priority_date=htmlspecialchars(strip_tags($this->priority_date));
        $this->modified=htmlspecialchars(strip_tags($this->modified));
        // bind the values
        $stmt->bindParam(':priority_date', $this->priority_date);
        $stmt->bindParam(':modified', $this->modified);
        // unique ID of record to be edited
        $stmt->bindParam(':priority_id', $this->priority_id);
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

