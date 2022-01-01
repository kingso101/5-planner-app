<?php
ob_start();
// 'transaction' object
class Transaction{
 
    // database connection and table name
    private $conn;
    private $table_name = "transactions";
    private $transfer_table = "transfer";
    private $client_table = "clients";

    // object properties
    public $transaction_id;
    public $_id;
    public $client_id;
    public $action;
    public $amount;
    public $balance;
    public $transaction_note;
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
                action = :action,
                amount = :amount,
                transaction_note = :transaction_note,
                created = :created";
     
        // prepare the query
        $stmt = $this->conn->prepare($query);
     
        // sanitize
        $this->_id=htmlspecialchars(strip_tags($this->_id));
        $this->client_id=htmlspecialchars(strip_tags($this->client_id));
        $this->action=htmlspecialchars(strip_tags($this->action));
        $this->amount=htmlspecialchars(strip_tags($this->amount));
        $this->transaction_note=htmlspecialchars(strip_tags($this->transaction_note));
        $this->created=htmlspecialchars(strip_tags($this->created));
     
        // bind the values
        $stmt->bindParam(':_id', $this->_id);
        $stmt->bindParam(':client_id', $this->client_id);
        $stmt->bindParam(':action', $this->action);
        $stmt->bindParam(':amount', $this->amount);
        $stmt->bindParam(':transaction_note', $this->transaction_note);
        $stmt->bindParam(':created', $this->created);
     
        // execute the query, also check if query was successful
        if($stmt->execute()){

            if ($this->action == "credit") {
                $query2 = "UPDATE " . $this->client_table . "
                    SET
                        balance = (balance + {$this->amount})
                    WHERE _id = :_id OR client_id = :client_id";

                // prepare the query2
                $stmt2 = $this->conn->prepare($query2);
                // unique ID of record to be edited
                $stmt2->bindParam(':_id', $this->_id);
                $stmt2->bindParam(':client_id', $this->client_id);
                // execute the query2
                if($stmt2->execute()){
                    return true;
                }

                return true;
            }

            if ($this->action == "debit") {
                $query2 = "UPDATE " . $this->client_table . "
                    SET
                        balance = (balance - {$this->amount})
                    WHERE _id = :_id OR client_id = :client_id";

                // prepare the query2
                $stmt2 = $this->conn->prepare($query2);
                // unique ID of record to be edited
                $stmt2->bindParam(':_id', $this->_id);
                $stmt2->bindParam(':client_id', $this->client_id);
                // execute the query2
                if($stmt2->execute()){
                    return true;
                }

                return true;
            }    
        }
        else{
            $this->showError($stmt);
            return false;
        }
    }

    // check if given email exist in the database
    function transactionExists(){
     
        // query to check if email exists
        $query = "SELECT transaction_id, _id, client_id,
                FROM " . $this->table_name . "
                WHERE client_id = ?
                LIMIT 0,1";
     
        // prepare the query
        $stmt = $this->conn->prepare( $query );
     
        // sanitize
        $this->client_id=htmlspecialchars(strip_tags($this->client_id));
     
        // bind given transaction value
        $stmt->bindParam(1, $this->client_id);
     
        // execute the query
        $stmt->execute();
     
        // get number of rows
        $num = $stmt->rowCount();
     
        // if transaction exists, assign values to object properties for easy access and use for php sessions
        if($num>0){
     
            // get record details / values
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            // var_dump($row);
     
            // assign values to object properties
            $this->id = $row['transaction_id'];
            $this->_id = $row['_id'];
            $this->client_id = $row['client_id'];
     
            // return true because transaction exists in the database
            return true;
        }
     
        // return false if transaction does not exist in the database
        return false;
    }

    // read amount sheets
    function read(){
     
        // query to read single record
        $query = "SELECT
                    transaction_id, _id, client_id, action, amount, transaction_note, created
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

    // read only one amount sheet
    function readOne(){
     
        // query to read single record
        $query = "SELECT
                    transaction_id, _id, client_id, action, amount, transaction_note, created
                FROM
                    " . $this->table_name . " 
                WHERE _id = ?
                LIMIT 0,1";
     
        // prepare query statement
        $stmt = $this->conn->prepare( $query );
     
        // bind id of amount sheet to be updated
        $stmt->bindParam(1, $this->_id);
     
        // execute query
        $stmt->execute();
     
        // get retrieved row
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
     
        // set values to object properties
        $this->transaction_id = $row['transaction_id'];
        $this->_id = $row['_id'];
        $this->client_id = $row['client_id'];
        $this->action = $row['action'];
        $this->amount = $row['amount'];
        $this->transaction_note = $row['transaction_note'];
        $this->created = $row['created'];
    }
     
    // update a user record
    public function update(){
     
        // if password needs to be updated
        $query = "UPDATE " . $this->table_name . "
                SET
                    client_id = :client_id,
                    action = :action,
                    amount = :amount,
                    transaction_note = :transaction_note
                WHERE _id = :_id";

     
        // prepare the query
        $stmt = $this->conn->prepare($query);
     
        // sanitize
        $this->client_id=htmlspecialchars(strip_tags($this->client_id));
        $this->action=htmlspecialchars(strip_tags($this->action));
        $this->amount=htmlspecialchars(strip_tags($this->amount));
        $this->transaction_note=htmlspecialchars(strip_tags($this->transaction_note));

        // bind the values from the form
        $stmt->bindParam(':client_id', $this->client_id);
        $stmt->bindParam(':action', $this->action);
        $stmt->bindParam(':amount', $this->amount);
        $stmt->bindParam(':transaction_note', $this->transaction_note);
     
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

    // read alltime transactions
    function readTransactionsAndTransfers(){
     
        // query to read single record
        $query = "SELECT *
                FROM
                    " . $this->table_name . " 
                INNER JOIN " . $this->transfer_table . " 
                ON " . $this->table_name . ".client_id = " . $this->transfer_table . ".client_id
                ORDER BY " . $this->transfer_table . ".created DESC";
     
        // prepare query statement
        $stmt = $this->conn->prepare($query);
     
        // execute query
        $stmt->execute();
     
        return $stmt;
    }

    // read one person transactions
    function readOnePersonTransactions($client_id){
     
        // query to read single record
        $query = "SELECT *
                FROM
                    " . $this->table_name . " 
                INNER JOIN " . $this->transfer_table . " 
                ON " . $this->table_name . ".client_id = " . $this->transfer_table . ".client_id
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

    public function countTransaction(){
     
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

