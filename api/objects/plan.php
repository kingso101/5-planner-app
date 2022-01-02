<?php
ob_start();
// 'plan' object
class Plan{
 
    // database connection and table name
    private $conn;
    private $table_name = "plan";
    private $priority_table = "priority";
    private $analytics_table = "analytics";

    // object properties
    public $_id;
    public $plan_id;
    public $key_id;
    public $client_id;
    public $plan_type;
    public $goal;
    public $from_date;
    public $to_date;
    public $description;
    public $resources;
    public $plan_interval;
    public $reward;
    public $isCompleted;
    public $priority_label;
    public $priority_date;
    public $created;

    public $no_of_intervals;       
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
                key_id = :key_id,
                client_id = :client_id,
                plan_type = :plan_type,
                goal = :goal,
                from_date = :from_date,
                to_date = :to_date,
                description = :description,
                resources = :resources,
                plan_interval = :plan_interval,
                reward = :reward,
                priority_label = :priority_label,
                priority_date = :priority_date,
                created = :created";
     
        // prepare the query
        $stmt = $this->conn->prepare($query);
     
        // sanitize
        $this->_id=htmlspecialchars(strip_tags($this->_id));
        $this->key_id=htmlspecialchars(strip_tags($this->key_id));
        $this->client_id=htmlspecialchars(strip_tags($this->client_id));
        $this->plan_type=htmlspecialchars(strip_tags($this->plan_type));
        $this->goal=htmlspecialchars(strip_tags($this->goal));
        $this->from_date=htmlspecialchars(strip_tags($this->from_date));
        $this->to_date=htmlspecialchars(strip_tags($this->to_date));
        $this->description=htmlspecialchars(strip_tags($this->description));
        $this->resources=htmlspecialchars(strip_tags($this->resources));
        $this->plan_interval=htmlspecialchars(strip_tags($this->plan_interval));
        $this->reward=htmlspecialchars(strip_tags($this->reward));
        $this->priority_label=htmlspecialchars(strip_tags($this->priority_label));
        $this->priority_date=htmlspecialchars(strip_tags($this->priority_date));
        $this->created=htmlspecialchars(strip_tags($this->created));
     
        // bind the values
        $stmt->bindParam(':_id', $this->_id);
        $stmt->bindParam(':key_id', $this->key_id);
        $stmt->bindParam(':client_id', $this->client_id);
        $stmt->bindParam(':plan_type', $this->plan_type);
        $stmt->bindParam(':goal', $this->goal);
        $stmt->bindParam(':from_date', $this->from_date);
        $stmt->bindParam(':to_date', $this->to_date);
        $stmt->bindParam(':description', $this->description);
        $stmt->bindParam(':resources', $this->resources);
        $stmt->bindParam(':plan_interval', $this->plan_interval);
        $stmt->bindParam(':reward', $this->reward);
        $stmt->bindParam(':priority_label', $this->priority_label);
        $stmt->bindParam(':priority_date', $this->priority_date);
        $stmt->bindParam(':created', $this->created);
     
        // execute the query, also check if query was successful
        if($stmt->execute()){
            $this->plan_id = $this->conn->lastInsertId();
            $this->no_of_intervals .=1;
            //query the db
            $query2 = "UPDATE " . $this->analytics_table . "
                SET
                    plan_id = :plan_id,
                    key_id = :key_id,
                    no_of_intervals = :no_of_intervals
                WHERE key_id = :key_id";
                

            // prepare the query2
            $stmt2 = $this->conn->prepare($query2);
            
            // unique ID of record to be edited
            $stmt2->bindParam(':plan_id', $this->plan_id);
            $stmt2->bindParam(':key_id', $this->key_id);
            $stmt2->bindParam(':no_of_intervals', $this->no_of_intervals);
         
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

    // check if given email exist in the database
    function planExists(){
     
        // query to check if email exists
        $query = "SELECT _id, goal, plan_type,
                FROM " . $this->table_name . "
                WHERE _id = ?
                LIMIT 0,1";
     
        // prepare the query
        $stmt = $this->conn->prepare( $query );
     
        // sanitize
        $this->_id=htmlspecialchars(strip_tags($this->_id));
     
        // bind given transfer value
        $stmt->bindParam(1, $this->_id);
     
        // execute the query
        $stmt->execute();
     
        // get number of rows
        $num = $stmt->rowCount();
     
        // if transfer exists, assign values to object properties for easy access and use for php sessions
        if($num>0){
     
            // get record details / values
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            // var_dump($row);
     
            // assign values to object properties
            $this->_id = $row['_id'];
            $this->goal = $row['goal'];
            $this->plan_type = $row['plan_type'];
     
            // return true because transfer exists in the database
            return true;
        }
     
        // return false if transfer does not exist in the database
        return false;
    }

    // read to_account sheets
    function read(){
     
        // query to read single record
        $query = "SELECT
                    _id, plan_id, key_id, client_id, plan_type, goal, from_date, to_date, description, resources, plan_interval, reward, priority_label, priority_date, isCompleted, created
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
                    _id, plan_id, key_id, client_id, plan_type, goal, from_date, to_date, description, resources, plan_interval, reward, priority_label, priority_date, isCompleted, created
                FROM
                    " . $this->table_name . " 
                WHERE _id = ?
                LIMIT 0,1";
     
        // prepare query statement
        $stmt = $this->conn->prepare( $query );
     
        // bind id of to_account sheet to be updated
        $stmt->bindParam(1, $this->_id);
     
        // execute query
        $stmt->execute();
     
        // get retrieved row
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
     
        // set values to object properties
        $this->_id = $row['_id'];
        $this->plan_id = $row['plan_id'];
        $this->key_id = $row['key_id'];
        $this->client_id = $row['client_id'];
        $this->plan_type = $row['plan_type'];
        $this->goal = $row['goal'];
        $this->from_date = $row['from_date'];
        $this->to_date = $row['to_date'];
        $this->description = $row['description'];
        $this->resources = $row['resources'];
        $this->plan_interval = $row['plan_interval'];
        $this->reward = $row['reward'];
        $this->priority_label = $row['priority_label'];
        $this->priority_date = $row['priority_date'];
        $this->isCompleted = $row['isCompleted'];
        $this->created = $row['created'];
    }

    function readOnePersonanalytics(){
     
        // query to read single record
        $query = "SELECT * FROM " . $this->table_name . " INNER JOIN " . $this->analytics_table . " 
            ON " . $this->table_name . ".plan_id = " . $this->analytics_table . ".plan_id
            WHERE ".$this->table_name .".plan_id = ".$this->analytics_table .".plan_id 
            AND ".$this->table_name.".client_id = ?";
     
        // prepare query statement
        $stmt = $this->conn->prepare( $query );
     
        // bind id of product to be updated
        $stmt->bindParam(1, $this->client_id);
     
        // execute query
        $stmt->execute();
     
        // get retrieved row
        return $stmt;
    }

    // read only one person's transfers
    function readOnePersonPlans($client_id){
        // query to read single record
        $query = "SELECT
                     _id, plan_id, key_id, client_id, plan_type, goal, from_date, to_date, description, resources, plan_interval, reward, priority_label, priority_date, isCompleted, created
                FROM
                    " . $this->table_name . " 
                WHERE client_id = :client_id
                ORDER BY 
                    created DESC";
     
        // prepare query statement
        $stmt = $this->conn->prepare( $query );
     
        // bind id of product to be updated
        $stmt->bindParam(':client_id', $this->client_id);
     
        // execute query
        $stmt->execute();
     
        // get retrieved row
        return $stmt;
    }

    // update a user record
    public function update(){
     
        // if password needs to be updated
        $query = "UPDATE " . $this->table_name . "
                SET
                    plan_type = :plan_type,
                    goal = :goal,
                    from_date = :from_date,
                    to_date = :to_date,
                    description = :description,
                    resources = :resources,
                    plan_interval = :plan_interval,
                    reward = :reward
                WHERE _id = :_id";

     
        // prepare the query
        $stmt = $this->conn->prepare($query);
     
        // sanitize
        $this->plan_type=htmlspecialchars(strip_tags($this->plan_type));
        $this->goal=htmlspecialchars(strip_tags($this->goal));
        $this->from_date=htmlspecialchars(strip_tags($this->from_date));
        $this->to_date=htmlspecialchars(strip_tags($this->to_date));
        $this->description=htmlspecialchars(strip_tags($this->description));
        $this->resources=htmlspecialchars(strip_tags($this->resources));
        $this->plan_interval=htmlspecialchars(strip_tags($this->plan_interval));
        $this->reward=htmlspecialchars(strip_tags($this->reward));

        // bind the values from the form
        $stmt->bindParam(':plan_type', $this->plan_type);
        $stmt->bindParam(':goal', $this->goal);
        $stmt->bindParam(':from_date', $this->from_date);
        $stmt->bindParam(':to_date', $this->to_date);
        $stmt->bindParam(':description', $this->description);
        $stmt->bindParam(':resources', $this->resources);
        $stmt->bindParam(':plan_interval', $this->plan_interval);
        $stmt->bindParam(':reward', $this->reward);
     
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

    // delete the to_account sheet
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

    // count plans
    public function countPlans($client_id){
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

    // count priority for each plans
    public function countPrioritiesForEachPlan($client_id,$key_id){
       try
       {    
            // query to check if email exists
            $query = "SELECT *
                    FROM " . $this->table_name . "
                    WHERE client_id = :client_id AND key_id = :key_id";

            // $query = "SELECT *
            //     FROM
            //         " . $this->table_name . " 
            //     INNER JOIN " . $this->priority_table . " 
            //     ON " . $this->table_name . ".client_id = " . $this->priority_table . ".client_id
            //     ORDER BY " . $this->priority_table . ".created DESC";
         
            // prepare the query
            $stmt = $this->conn->prepare( $query );

            $stmt->execute(array(':key_id'=>$key_id, ':client_id'=>$client_id));
            $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if($stmt->rowCount() > 0){

                return $row;
            }
        }
        catch(PDOException $e){
            echo $e->getMessage();
        }
    }

    // Count completed plans
    public function countCompletedPlans($client_id){
       try
       {    
            // query to check if email exists
            $query = "SELECT *
                    FROM " . $this->table_name . "
                    WHERE isCompleted = 1 && client_id = :client_id";
         
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

    // Count ongoing plans
    public function countOngoingPlans($client_id){
       try
       {    
            // query to check if email exists
            $query = "SELECT *
                    FROM " . $this->table_name . "
                    WHERE isCompleted = 0 && client_id = :client_id";
         
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


    // Count priorities 
    public function countPriorities($client_id){
       try
       {    
            // query to check if email exists
            $query = "SELECT *
                    FROM " . $this->table_name . "
                    WHERE isCompleted = 1 && client_id = :client_id";
         
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

    // update a part of plan record
    public function updatePlanInt(){
        
        $query = "UPDATE " . $this->table_name . "
                SET
                    plan_interval = :plan_interval,
                    modified = :modified
                WHERE _id = :_id";
        // prepare the query
        $stmt = $this->conn->prepare($query);
        // sanitize
        $this->plan_interval=htmlspecialchars(strip_tags($this->plan_interval));
        $this->modified=htmlspecialchars(strip_tags($this->modified));
        // bind the values
        $stmt->bindParam(':plan_interval', $this->plan_interval);
        $stmt->bindParam(':modified', $this->modified);
        // unique ID of record to be edited
        $stmt->bindParam(':_id', $this->_id);
        // execute the query
        if($stmt->execute()){
            return true;
        }
     
        return false;
    }

    // update a part of plan record
    public function updatePriorityLabel(){
        
        $query = "UPDATE " . $this->table_name . "
                SET
                    priority_label = :priority_label,
                    modified = :modified
                WHERE _id = :_id";
        // prepare the query
        $stmt = $this->conn->prepare($query);
        // sanitize
        $this->priority_label=htmlspecialchars(strip_tags($this->priority_label));
        $this->modified=htmlspecialchars(strip_tags($this->modified));
        // bind the values
        $stmt->bindParam(':priority_label', $this->priority_label);
        $stmt->bindParam(':modified', $this->modified);
        // unique ID of record to be edited
        $stmt->bindParam(':_id', $this->_id);
        // execute the query
        if($stmt->execute()){
            return true;
        }
     
        return false;
    }

    // update a part of plan record
    public function updatePriorityDate(){
        
        $query = "UPDATE " . $this->table_name . "
                SET
                    priority_date = :priority_date,
                    modified = :modified
                WHERE _id = :_id";
        // prepare the query
        $stmt = $this->conn->prepare($query);
        // sanitize
        $this->priority_date=htmlspecialchars(strip_tags($this->priority_date));
        $this->modified=htmlspecialchars(strip_tags($this->modified));
        // bind the values
        $stmt->bindParam(':priority_date', $this->priority_date);
        $stmt->bindParam(':modified', $this->modified);
        // unique ID of record to be edited
        $stmt->bindParam(':_id', $this->_id);
        // execute the query
        if($stmt->execute()){
            return true;
        }
     
        return false;
    }

    // mark record as read
    public function markAsRead(){
     
        // if password needs to be updated
        $query = "UPDATE " . $this->table_name . "
                SET
                    status = :status
                WHERE _id = :_id";

     
        // prepare the query
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(':status', $this->status);
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

