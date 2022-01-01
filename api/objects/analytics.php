<?php
ob_start();
// 'plan' object
class Analytics{
    // database connection and table name
    private $conn;
    private $table_name = "analytics";
    private $plan_table = "plan";

    // object properties
    public $plan_analytics_id;
    public $plan_id;
    public $key_id;
    public $client_id;
    public $priority_id;
    public $plan_type;
    public $goal;
    public $from_date;
    public $to_date;
    public $no_of_intervals;
    public $total_priorities;
    public $intervals_completed;
    public $total_priorities_done;
    public $time_used;
    public $time_left;
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
                key_id = :key_id,
                client_id = :client_id,
                no_of_intervals = :no_of_intervals,
                total_priorities = :total_priorities,
                intervals_completed = :intervals_completed,
                total_priorities_done = :total_priorities_done,
                time_used = :time_used,
                time_left = :time_left,
                plan_interval = :plan_interval,
                reward = :reward,
                priority_label = :priority_label,
                priority_date = :priority_date,
                created = :created";
     
        // prepare the query
        $stmt = $this->conn->prepare($query);
     
        // sanitize
        $this->key_id=htmlspecialchars(strip_tags($this->key_id));
        $this->client_id=htmlspecialchars(strip_tags($this->client_id));
        $this->no_of_intervals=htmlspecialchars(strip_tags($this->no_of_intervals));
        $this->total_priorities=htmlspecialchars(strip_tags($this->total_priorities));
        $this->intervals_completed=htmlspecialchars(strip_tags($this->intervals_completed));
        $this->total_priorities_done=htmlspecialchars(strip_tags($this->total_priorities_done));
        $this->time_used=htmlspecialchars(strip_tags($this->time_used));
        $this->time_left=htmlspecialchars(strip_tags($this->time_left));
        $this->plan_interval=htmlspecialchars(strip_tags($this->plan_interval));
        $this->reward=htmlspecialchars(strip_tags($this->reward));
        $this->priority_label=htmlspecialchars(strip_tags($this->priority_label));
        $this->priority_date=htmlspecialchars(strip_tags($this->priority_date));
        $this->created=htmlspecialchars(strip_tags($this->created));
     
        // bind the values
        $stmt->bindParam(':key_id', $this->key_id);
        $stmt->bindParam(':client_id', $this->client_id);
        $stmt->bindParam(':no_of_intervals', $this->no_of_intervals);
        $stmt->bindParam(':total_priorities', $this->total_priorities);
        $stmt->bindParam(':intervals_completed', $this->intervals_completed);
        $stmt->bindParam(':total_priorities_done', $this->total_priorities_done);
        $stmt->bindParam(':time_used', $this->time_used);
        $stmt->bindParam(':time_left', $this->time_left);
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
            $query2 = "UPDATE " . $this->plan_analytics_table . "
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
        $query = "SELECT plan_analytics_id, total_priorities, no_of_intervals,
                FROM " . $this->table_name . "
                WHERE plan_analytics_id = ?
                LIMIT 0,1";
     
        // prepare the query
        $stmt = $this->conn->prepare( $query );
     
        // sanitize
        $this->plan_analytics_id=htmlspecialchars(strip_tags($this->plan_analytics_id));
     
        // bind given transfer value
        $stmt->bindParam(1, $this->plan_analytics_id);
     
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
            $this->plan_analytics_id = $row['plan_analytics_id'];
            $this->total_priorities = $row['total_priorities'];
            $this->no_of_intervals = $row['no_of_intervals'];
     
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
                    plan_analytics_id, plan_id, key_id, client_id, plan_type, goal, from_date, to_date, no_of_intervals, total_priorities, intervals_completed, total_priorities_done, time_used, time_left, plan_interval, reward, priority_label, priority_date, isCompleted, created
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
                    plan_analytics_id, plan_id, key_id, client_id, plan_type, goal, from_date, to_date, no_of_intervals, total_priorities, intervals_completed, total_priorities_done, time_used, time_left, plan_interval, reward, priority_label, priority_date, isCompleted, created
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
        $this->plan_analytics_id = $row['plan_analytics_id'];
        $this->plan_id = $row['plan_id'];
        $this->key_id = $row['key_id'];
        $this->client_id = $row['client_id'];
        $this->plan_type = $row['plan_type'];
        $this->goal = $row['goal'];
        $this->from_date = $row['from_date'];
        $this->to_date = $row['to_date'];
        $this->no_of_intervals = $row['no_of_intervals'];
        $this->total_priorities = $row['total_priorities'];
        $this->intervals_completed = $row['intervals_completed'];
        $this->total_priorities_done = $row['total_priorities_done'];
        $this->time_used = $row['time_used'];
        $this->time_left = $row['time_left'];
        $this->plan_interval = $row['plan_interval'];
        $this->reward = $row['reward'];
        $this->priority_label = $row['priority_label'];
        $this->priority_date = $row['priority_date'];
        $this->isCompleted = $row['isCompleted'];
        $this->created = $row['created'];
    }

    function readOnePersonanalytics(){
     
        // query to read single record
        $query = "SELECT * FROM " . $this->plan_table . " INNER JOIN " . $this->table_name . " 
            ON " . $this->plan_table . ".plan_id = " . $this->table_name . ".plan_id
            WHERE ".$this->plan_table .".plan_id = ".$this->table_name .".plan_id 
            AND ".$this->plan_table.".client_id = ?";
     
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
    function readOnePersonPlans($plan_analytics_id){
     
        // query to read single record
        $query = "SELECT
                    plan_analytics_id, plan_id, key_id, client_id, plan_type, goal, from_date, to_date, no_of_intervals, total_priorities, intervals_completed, total_priorities_done, time_used, time_left, plan_interval, reward, priority_label, priority_date, isCompleted, created
                FROM
                    " . $this->table_name . " 
                WHERE plan_analytics_id = :plan_analytics_id
                ORDER BY 
                    created DESC";
     
        // prepare query statement
        $stmt = $this->conn->prepare( $query );
     
        // bind id of product to be updated
        $stmt->bindParam(':plan_analytics_id', $this->plan_analytics_id);
     
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
                    no_of_intervals = :no_of_intervals,
                    total_priorities = :total_priorities,
                    intervals_completed = :intervals_completed,
                    total_priorities_done = :total_priorities_done,
                    time_used = :time_used,
                    time_left = :time_left,
                    plan_interval = :plan_interval,
                    reward = :reward
                WHERE plan_analytics_id = :plan_analytics_id";

     
        // prepare the query
        $stmt = $this->conn->prepare($query);
     
        // sanitize
        $this->no_of_intervals=htmlspecialchars(strip_tags($this->no_of_intervals));
        $this->total_priorities=htmlspecialchars(strip_tags($this->total_priorities));
        $this->intervals_completed=htmlspecialchars(strip_tags($this->intervals_completed));
        $this->total_priorities_done=htmlspecialchars(strip_tags($this->total_priorities_done));
        $this->time_used=htmlspecialchars(strip_tags($this->time_used));
        $this->time_left=htmlspecialchars(strip_tags($this->time_left));
        $this->plan_interval=htmlspecialchars(strip_tags($this->plan_interval));
        $this->reward=htmlspecialchars(strip_tags($this->reward));

        // bind the values from the form
        $stmt->bindParam(':no_of_intervals', $this->no_of_intervals);
        $stmt->bindParam(':total_priorities', $this->total_priorities);
        $stmt->bindParam(':intervals_completed', $this->intervals_completed);
        $stmt->bindParam(':total_priorities_done', $this->total_priorities_done);
        $stmt->bindParam(':time_used', $this->time_used);
        $stmt->bindParam(':time_left', $this->time_left);
        $stmt->bindParam(':plan_interval', $this->plan_interval);
        $stmt->bindParam(':reward', $this->reward);
     
        // unique ID of record to be edited
        $stmt->bindParam(':plan_analytics_id', $this->plan_analytics_id);
     
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
        $query = "DELETE FROM " . $this->table_name . " WHERE plan_analytics_id = ?";
     
        // prepare query
        $stmt = $this->conn->prepare($query);
     
        // sanitize
        $this->plan_analytics_id = htmlspecialchars(strip_tags($this->plan_analytics_id));
     
        // bind id of record to delete
        $stmt->bindParam(1, $this->plan_analytics_id);
     
        // execute query
        if($stmt->execute()){
            return true;
        }
     
        return false;
    }

    public function updatePriorityTime(){
        
        $query = "UPDATE " . $this->table_name . "
                SET
                    time_left = :time_left,
                    time_used = :time_used
                WHERE plan_id = :plan_id AND key_id = :key_id";
        // prepare the query
        $stmt = $this->conn->prepare($query);
        // sanitize
        $this->time_left=htmlspecialchars(strip_tags($this->time_left));
        $this->time_used=htmlspecialchars(strip_tags($this->time_used));
        // bind the values
        $stmt->bindParam(':time_left', $this->time_left);
        $stmt->bindParam(':time_used', $this->time_used);
        // unique ID of record to be edited
        $stmt->bindParam(':plan_id', $this->plan_id);
        $stmt->bindParam(':key_id', $this->key_id);
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

