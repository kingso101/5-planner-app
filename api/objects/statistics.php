<?php
ob_start();
// 'statistics' object
class Statistics{
 
    // database connection and table name
    private $conn;
    private $table_name = "statistics";
    private $client_table = "clients";

    // object properties
    public $statistics_id;
    public $_id;
    public $client_id;
    public $month;
    public $total_gain;
    public $win_rate;
    public $average_gain;
    public $no_of_trade;
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
                client_id = :client_id,
                month = :month,
                total_gain = :total_gain,
                win_rate = :win_rate,
                average_gain = :average_gain,
                no_of_trade = :no_of_trade,
                created = :created";
     
        // prepare the query
        $stmt = $this->conn->prepare($query);
     
        // sanitize
        $this->_id=htmlspecialchars(strip_tags($this->_id));
        $this->client_id=htmlspecialchars(strip_tags($this->client_id));
        $this->month=htmlspecialchars(strip_tags($this->month));
        $this->total_gain=htmlspecialchars(strip_tags($this->total_gain));
        $this->win_rate=htmlspecialchars(strip_tags($this->win_rate));
        $this->average_gain=htmlspecialchars(strip_tags($this->average_gain));
        $this->no_of_trade=htmlspecialchars(strip_tags($this->no_of_trade));
        $this->created=htmlspecialchars(strip_tags($this->created));
     
        // bind the values
        $stmt->bindParam(':_id', $this->_id);
        $stmt->bindParam(':client_id', $this->client_id);
        $stmt->bindParam(':month', $this->month);
        $stmt->bindParam(':total_gain', $this->total_gain);
        $stmt->bindParam(':win_rate', $this->win_rate);
        $stmt->bindParam(':average_gain', $this->average_gain);
        $stmt->bindParam(':no_of_trade', $this->no_of_trade);
        $stmt->bindParam(':created', $this->created);
     
        // execute the query, also check if query was successful
        if($stmt->execute()){
            // $query2 = "UPDATE " . $this->client_table . "
            //     SET
            //         balance = (balance - {$this->win_rate})
            //     WHERE client_id = :client_id";

            // // prepare the query2
            // $stmt2 = $this->conn->prepare($query2);
         
            // // unique ID of record to be edited
            // $stmt2->bindParam(':client_id', $this->client_id);
         
            // // execute the query2
            // if($stmt2->execute()){
            //     return true;
            // }

            return true;    
        }
        else{
            $this->showError($stmt);
            return false;
        }
    }

    // check if given email exist in the database
    function statisticsExists(){
     
        // query to check if email exists
        $query = "SELECT statistics_id, _id, client_id,
                FROM " . $this->table_name . "
                WHERE client_id = ?
                LIMIT 0,1";
     
        // prepare the query
        $stmt = $this->conn->prepare( $query );
     
        // sanitize
        $this->client_id=htmlspecialchars(strip_tags($this->client_id));
     
        // bind given statistics value
        $stmt->bindParam(1, $this->client_id);
     
        // execute the query
        $stmt->execute();
     
        // get number of rows
        $num = $stmt->rowCount();
     
        // if statistics exists, assign values to object properties for easy access and use for php sessions
        if($num>0){
     
            // get record details / values
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            // var_dump($row);
     
            // assign values to object properties
            $this->id = $row['statistics_id'];
            $this->_id = $row['_id'];
            $this->client_id = $row['client_id'];
     
            // return true because statistics exists in the database
            return true;
        }
     
        // return false if statistics does not exist in the database
        return false;
    }

    // read total_gain sheets
    function read(){
     
        // query to read single record
        $query = "SELECT
                    statistics_id, _id, client_id, month, total_gain, win_rate, average_gain, no_of_trade, created
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

    // read only one statistics sheet
    function readOne(){
     
        // query to read single record
        $query = "SELECT
                    statistics_id, _id, client_id, month, total_gain, win_rate, average_gain, no_of_trade, created
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
        $this->statistics_id = $row['statistics_id'];
        $this->_id = $row['_id'];
        $this->client_id = $row['client_id'];
        $this->month = $row['month'];
        $this->win_rate = $row['win_rate'];
        $this->total_gain = $row['total_gain'];
        $this->average_gain = $row['average_gain'];
        $this->no_of_trade = $row['no_of_trade'];
        $this->created = $row['created'];
    }

    // read only one person's statisticss
    function readOnePersonStatistics($client_id){
     
        // query to read single record
        $query = "SELECT
                    statistics_id, _id, client_id, month, total_gain, win_rate, average_gain, no_of_trade, created
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
                    client_id = :client_id,
                    month = :month,
                    win_rate = :win_rate,
                    total_gain = :total_gain,
                    average_gain = :average_gain,
                    no_of_trade = :no_of_trade
                WHERE _id = :_id";

     
        // prepare the query
        $stmt = $this->conn->prepare($query);
     
        // sanitize
        $this->client_id=htmlspecialchars(strip_tags($this->client_id));
        $this->month=htmlspecialchars(strip_tags($this->month));
        $this->win_rate=htmlspecialchars(strip_tags($this->win_rate));
        $this->total_gain=htmlspecialchars(strip_tags($this->total_gain));
        $this->average_gain=htmlspecialchars(strip_tags($this->average_gain));
        $this->no_of_trade=htmlspecialchars(strip_tags($this->no_of_trade));

        // bind the values from the form
        $stmt->bindParam(':client_id', $this->client_id);
        $stmt->bindParam(':month', $this->month);
        $stmt->bindParam(':win_rate', $this->win_rate);
        $stmt->bindParam(':total_gain', $this->total_gain);
        $stmt->bindParam(':average_gain', $this->average_gain);
        $stmt->bindParam(':no_of_trade', $this->no_of_trade);
     
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

    // delete the total_gain sheet
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

    public function count_total_expenses($client_id){
       try
       {    
            // query to check if email exists
            $query = "SELECT SUM(win_rate) AS win_rate FROM " . $this->table_name. " 
                WHERE client_id = :client_id";
         
            // prepare the query
            $stmt = $this->conn->prepare( $query );

            $stmt->execute(array(':client_id'=>$client_id));
            $sum = 0;
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if($stmt->rowCount() > 0){
                // set values to object properties
                $value = $row['win_rate'];
                $sum += $value;
                return $sum;
                // var_dump($sum);
            }
        }
        catch(PDOException $e){
            echo $e->getMessage();
        }
    }

    public function count_total_gain(){   
        $sql = "SELECT total_gain FROM " . $this->table_name;
        // echo $sql;
        $stmt = $this->conn->prepare($sql);
        // BIND COLUMN
        if ($stmt->execute()) {
            $sum = 0;
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $value = $row['total_gain'];
                $sum = $value;
                // var_dump($row);
            }
            return $sum;
        }
    }

    // read the first mail
    function countstatisticss(){
     
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
        $stmt = $stmt->fetchAll(PDO::FETCH_ASSOC);
     
        return $stmt;
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

