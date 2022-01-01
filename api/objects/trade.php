<?php
ob_start();
// 'trade' object
class Trade{
 
    // database connection and table name
    private $conn;
    private $table_name = "trades";
    private $stats_table = "statistics";
    private $client_table = "clients";

    // object properties
    public $trade_id;
    public $_id;
    public $client_id;
    public $ticker;
    public $position_type;
    public $leverage;
    public $trade_type;
    public $entry;
    public $exit_at;
    public $percentage;
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
                ticker = :ticker,
                position_type = :position_type,
                leverage = :leverage,
                trade_type = :trade_type,
                entry = :entry,
                exit_at = :exit_at,
                percentage = :percentage,
                created = :created";
     
        // prepare the query
        $stmt = $this->conn->prepare($query);
     
        // sanitize
        $this->_id=htmlspecialchars(strip_tags($this->_id));
        $this->client_id=htmlspecialchars(strip_tags($this->client_id));
        $this->ticker=htmlspecialchars(strip_tags($this->ticker));
        $this->position_type=htmlspecialchars(strip_tags($this->position_type));
        $this->leverage=htmlspecialchars(strip_tags($this->leverage));
        $this->trade_type=htmlspecialchars(strip_tags($this->trade_type));
        $this->entry=htmlspecialchars(strip_tags($this->entry));
        $this->exit_at=htmlspecialchars(strip_tags($this->exit_at));
        $this->percentage=htmlspecialchars(strip_tags($this->percentage));
        $this->created=htmlspecialchars(strip_tags($this->created));
     
        // bind the values
        $stmt->bindParam(':_id', $this->_id);
        $stmt->bindParam(':client_id', $this->client_id);
        $stmt->bindParam(':ticker', $this->ticker);
        $stmt->bindParam(':position_type', $this->position_type);
        $stmt->bindParam(':leverage', $this->leverage);
        $stmt->bindParam(':trade_type', $this->trade_type);
        $stmt->bindParam(':entry', $this->entry);
        $stmt->bindParam(':exit_at', $this->exit_at);
        $stmt->bindParam(':percentage', $this->percentage);
        $stmt->bindParam(':created', $this->created);
     
        // execute the query, also check if query was successful
        if($stmt->execute()){

            // if ($this->action == "credit") {
            //     $query2 = "UPDATE " . $this->client_table . "
            //         SET
            //             balance = (balance + {$this->amount})
            //         WHERE _id = :_id OR client_id = :client_id";

            //     // prepare the query2
            //     $stmt2 = $this->conn->prepare($query2);
            //     // unique ID of record to be edited
            //     $stmt2->bindParam(':_id', $this->_id);
            //     $stmt2->bindParam(':client_id', $this->client_id);
            //     // execute the query2
            //     if($stmt2->execute()){
            //         return true;
            //     }

            //     return true;
            // }

            // if ($this->action == "debit") {
            //     $query2 = "UPDATE " . $this->client_table . "
            //         SET
            //             balance = (balance - {$this->amount})
            //         WHERE _id = :_id OR client_id = :client_id";

            //     // prepare the query2
            //     $stmt2 = $this->conn->prepare($query2);
            //     // unique ID of record to be edited
            //     $stmt2->bindParam(':_id', $this->_id);
            //     $stmt2->bindParam(':client_id', $this->client_id);
            //     // execute the query2
            //     if($stmt2->execute()){
            //         return true;
            //     }

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
    function tradeExists(){
     
        // query to check if email exists
        $query = "SELECT trade_id, _id, client_id,
                FROM " . $this->table_name . "
                WHERE client_id = ?
                LIMIT 0,1";
     
        // prepare the query
        $stmt = $this->conn->prepare( $query );
     
        // sanitize
        $this->client_id=htmlspecialchars(strip_tags($this->client_id));
     
        // bind given trade value
        $stmt->bindParam(1, $this->client_id);
     
        // execute the query
        $stmt->execute();
     
        // get number of rows
        $num = $stmt->rowCount();
     
        // if trade exists, assign values to object properties for easy access and use for php sessions
        if($num>0){
     
            // get record details / values
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            // var_dump($row);
     
            // assign values to object properties
            $this->id = $row['trade_id'];
            $this->_id = $row['_id'];
            $this->client_id = $row['client_id'];
     
            // return true because trade exists in the database
            return true;
        }
     
        // return false if trade does not exist in the database
        return false;
    }

    // read trade sheets
    function read(){
     
        // query to read single record
        $query = "SELECT
                    trade_id, _id, client_id, ticker, position_type, leverage, trade_type, entry, exit_at, percentage, created
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

    // read only one position_type sheet
    function readOne(){
     
        // query to read single record
        $query = "SELECT
                    trade_id, _id, client_id, ticker, position_type, leverage, trade_type, entry, exit_at, percentage, created
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
        $this->trade_id = $row['trade_id'];
        $this->_id = $row['_id'];
        $this->client_id = $row['client_id'];
        $this->ticker = $row['ticker'];
        $this->position_type = $row['position_type'];
        $this->leverage = $row['leverage'];
        $this->trade_type = $row['trade_type'];
        $this->entry = $row['entry'];
        $this->exit_at = $row['exit_at'];
        $this->percentage = $row['percentage'];
        $this->created = $row['created'];
    }
     
    // update a user record
    public function update(){
     
        // if password needs to be updated
        $query = "UPDATE " . $this->table_name . "
                SET
                    client_id = :client_id,
                    ticker = :ticker,
                    position_type = :position_type,
                    leverage = :leverage,
                    trade_type = :trade_type,
                    entry = :entry,
                    exit_at = :exit_at,
                    percentage = :percentage
                WHERE _id = :_id";

     
        // prepare the query
        $stmt = $this->conn->prepare($query);
     
        // sanitize
        $this->client_id=htmlspecialchars(strip_tags($this->client_id));
        $this->ticker=htmlspecialchars(strip_tags($this->ticker));
        $this->position_type=htmlspecialchars(strip_tags($this->position_type));
        $this->leverage=htmlspecialchars(strip_tags($this->leverage));
        $this->trade_type=htmlspecialchars(strip_tags($this->trade_type));
        $this->entry=htmlspecialchars(strip_tags($this->entry));
        $this->exit_at=htmlspecialchars(strip_tags($this->exit_at));
        $this->percentage=htmlspecialchars(strip_tags($this->percentage));

        // bind the values from the form
        $stmt->bindParam(':client_id', $this->client_id);
        $stmt->bindParam(':ticker', $this->ticker);
        $stmt->bindParam(':position_type', $this->position_type);
        $stmt->bindParam(':leverage', $this->leverage);
        $stmt->bindParam(':trade_type', $this->trade_type);
        $stmt->bindParam(':entry', $this->entry);
        $stmt->bindParam(':exit_at', $this->exit_at);
        $stmt->bindParam(':percentage', $this->percentage);
     
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

    // delete the amount sheet
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

    // read one person trades
    function readOnePersonTradeReport($client_id){
     
        // query to read single record
        $query = "SELECT
                    trade_id, _id, client_id, ticker, position_type, leverage, trade_type, entry, exit_at, percentage, created
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

    // read one person transactions
    function readTradesAndStats($client_id){
     
        // query to read single record
        $query = "SELECT *
                FROM
                    " . $this->table_name . " 
                INNER JOIN " . $this->stats_table . " 
                ON " . $this->table_name . ".client_id = " . $this->stats_table . ".client_id
                ORDER BY ".$this->table_name .".created DESC";
     
        // prepare query statement
        $stmt = $this->conn->prepare( $query );
     
        // bind id of product to be updated
        $stmt->bindParam(':client_id', $this->client_id);
     
        // execute query
        $stmt->execute();
     
        // get retrieved row
        return $stmt;
    }

    public function countTrade(){
     
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

    public function count_total_expenses(){   
        $sql = "SELECT SUM(amount) AS amount FROM " . $this->table_name;
        // echo $sql;
        $stmt = $this->conn->prepare($sql);
        // BIND COLUMN
        if ($stmt->execute()) {
            $sum = 0;
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $value = $row['amount'];
                $sum += $value;
                // var_dump($row);
            }
            return $sum;
        }
    }

    public function count_amount(){   
        $sql = "SELECT amount FROM " . $this->table_name;
        // echo $sql;
        $stmt = $this->conn->prepare($sql);
        // BIND COLUMN
        if ($stmt->execute()) {
            $sum = 0;
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $value = $row['amount'];
                $sum = $value;
                // var_dump($row);
            }
            return $sum;
        }
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

