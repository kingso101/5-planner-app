<?php
ob_start();
// session_start();
// 'artist' object
class Artist{
 
    // database connection and table name
    private $conn;
    private $table_name = "artist";

    // object properties
    public $artist_id;
    public $_id;
    public $firstname;
    public $lastname;
    public $fullname;
    public $stage_name;
    public $artist_gender;
    public $age;
    public $location;
    public $social_media_link;
    public $genre;
    public $info;
    public $artist_img;
    public $created;
    public $modified;

 
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
                firstname = :firstname,
                lastname = :lastname,
                artist_gender = :artist_gender,
                stage_name = :stage_name,
                age = :age,
                location = :location,
                social_media_link = :social_media_link,
                genre = :genre,
                info = :info,
                artist_img = :artist_img,
                created = :created";
     
        // prepare the query
        $stmt = $this->conn->prepare($query);
     
        // sanitize
        $this->_id=htmlspecialchars(strip_tags($this->_id));
        $this->firstname=htmlspecialchars(strip_tags($this->firstname));
        $this->lastname=htmlspecialchars(strip_tags($this->lastname));
        $this->artist_gender=htmlspecialchars(strip_tags($this->artist_gender));
        $this->stage_name=htmlspecialchars(strip_tags($this->stage_name));
        $this->age=htmlspecialchars(strip_tags($this->age));
        $this->location=htmlspecialchars(strip_tags($this->location));
        $this->social_media_link=htmlspecialchars(strip_tags($this->social_media_link));

        $this->genre = implode(', ', $this->genre);

        $this->genre=htmlspecialchars(strip_tags($this->genre));
        $this->info=htmlspecialchars(strip_tags($this->info));
        $this->artist_img=htmlspecialchars(strip_tags($this->artist_img));
        $this->created=htmlspecialchars(strip_tags($this->created));
     
        // bind the values
        $stmt->bindParam(':_id', $this->_id);
        $stmt->bindParam(':firstname', $this->firstname);
        $stmt->bindParam(':lastname', $this->lastname);
        $stmt->bindParam(':stage_name', $this->stage_name);
        $stmt->bindParam(':artist_gender', $this->artist_gender);
        $stmt->bindParam(':stage_name', $this->stage_name);
        $stmt->bindParam(':age', $this->age);
        $stmt->bindParam(':location', $this->location);
        $stmt->bindParam(':social_media_link', $this->social_media_link);
        $stmt->bindParam(':genre', $this->genre);
        $stmt->bindParam(':info', $this->info);
        $stmt->bindParam(':artist_img', $this->artist_img);
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

    // read artists
    function read(){
     
        // query to read single record
        $query = "SELECT
                    artist_id, _id, firstname, lastname, artist_gender, stage_name, age, location, social_media_link, genre, info, artist_img, created, modified
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

    // read only one artist
    function readOne(){
     
        // query to read single record
        $query = "SELECT
                    artist_id, _id, firstname, lastname, artist_gender, stage_name, age, location, social_media_link, genre, info, artist_img, created, modified
                FROM
                    " . $this->table_name . " 
                WHERE _id = ?
                LIMIT 0,1";
     
        // prepare query statement
        $stmt = $this->conn->prepare( $query );
     
        // bind id of artist to be updated
        $stmt->bindParam(1, $this->_id);
     
        // execute query
        $stmt->execute();
     
        // get retrieved row
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        header("Content-type: image/jpg");
        header("Content-type: image/gif");
        header("Content-type: image/png");
     
        // set values to object properties
        $this->artist_id = $row['artist_id'];
        $this->_id = $row['_id'];
        $this->firstname = $row['firstname'];
        $this->lastname = $row['lastname'];
        $this->artist_gender = $row['artist_gender'];
        $this->stage_name = $row['stage_name'];
        $this->age = $row['age'];
        $this->location = $row['location'];
        $this->social_media_link = $row['social_media_link'];
        $this->genre = $row['genre'];
        $this->info = $row['info'];
        $this->artist_img = $row['artist_img'];
        $this->created = $row['created'];
        $this->modified = $row['modified'];
        $this->fullname = $row['lastname'] ." ". $row['firstname'];
    }
     
    // update a user record
    public function update(){
        // if no posted password, do not update the password
        $query = "UPDATE " . $this->table_name . "
            SET
                firstname = :firstname,
                lastname = :lastname,
                artist_gender = :artist_gender,
                stage_name = :stage_name,
                age = :age,
                stage_name = :stage_name,
                location = :location,
                social_media_link = :social_media_link,
                genre = :genre,
                info = :info,
                artist_img = :artist_img,
                modified = :modified
            WHERE _id = :_id";
     
        // prepare the query
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->_id=htmlspecialchars(strip_tags($this->_id));
        $this->firstname=htmlspecialchars(strip_tags($this->firstname));
        $this->lastname=htmlspecialchars(strip_tags($this->lastname));
        $this->artist_gender=htmlspecialchars(strip_tags($this->artist_gender));
        $this->stage_name=htmlspecialchars(strip_tags($this->stage_name));
        $this->age=htmlspecialchars(strip_tags($this->age));
        $this->location=htmlspecialchars(strip_tags($this->location));
        $this->social_media_link=htmlspecialchars(strip_tags($this->social_media_link));

        $this->genre = implode(', ', $this->genre);
        
        $this->genre=htmlspecialchars(strip_tags($this->genre));
        $this->info=htmlspecialchars(strip_tags($this->info));
        $this->artist_img=htmlspecialchars(strip_tags($this->artist_img));
        $this->modified=htmlspecialchars(strip_tags($this->modified));
     
        // bind the values
        $stmt->bindParam(':_id', $this->_id);
        $stmt->bindParam(':firstname', $this->firstname);
        $stmt->bindParam(':lastname', $this->lastname);
        $stmt->bindParam(':stage_name', $this->stage_name);
        $stmt->bindParam(':artist_gender', $this->artist_gender);
        $stmt->bindParam(':age', $this->age);
        $stmt->bindParam(':location', $this->location);
        $stmt->bindParam(':social_media_link', $this->social_media_link);
        $stmt->bindParam(':genre', $this->genre);
        $stmt->bindParam(':info', $this->info);
        $stmt->bindParam(':artist_img', $this->artist_img);
        $stmt->bindParam(':modified', $this->modified);
     
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

    // delete the artist
    function delete(){
        // query to read single record
        $query = "SELECT
                    artist_img
                FROM
                    " . $this->table_name . " 
                WHERE _id = ?
                LIMIT 0,1";
     
        // prepare query statement
        $stmt = $this->conn->prepare( $query );
     
        // bind id of artist to be updated
        $stmt->bindParam(1, $this->_id);
     
        // execute query
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        // set values to object properties
        $this->artist_img = $row['artist_img'];
        // delete image path in uploads directory
        unlink($this->artist_img);
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

