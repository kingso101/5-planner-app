<?php
ob_start();
// session_start();
// 'release' object
class Release{
 
    // database connection and table name
    private $conn;
    private $table_name = "releases";

    // object properties
    public $release_id;
    public $_id;
    public $release_title;
    public $artist;
    public $release_date;
    public $producer;
    public $genre;
    public $release_info;
    public $media_link;
    public $release_audio;
    public $release_img;
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
                release_title = :release_title,
                artist = :artist,
                producer = :producer,
                release_date = :release_date,
                genre = :genre,
                release_info = :release_info,
                media_link = :media_link,
                release_audio = :release_audio,
                release_img = :release_img,
                created = :created";
     
        // prepare the query
        $stmt = $this->conn->prepare($query);
     
        // sanitize
        $this->_id=htmlspecialchars(strip_tags($this->_id));
        $this->release_title=htmlspecialchars(strip_tags($this->release_title));
        $this->artist = implode(', ', $this->artist);

        $this->artist=htmlspecialchars(strip_tags($this->artist));
        $this->producer=htmlspecialchars(strip_tags($this->producer));
        $this->release_date=htmlspecialchars(strip_tags($this->release_date));

        $this->genre = implode(', ', $this->genre);
        $this->genre=htmlspecialchars(strip_tags($this->genre));

        $this->release_info=htmlspecialchars(strip_tags($this->release_info));

        $this->media_link=htmlspecialchars(strip_tags($this->media_link));
        $this->release_audio=htmlspecialchars(strip_tags($this->release_audio));
        $this->release_img=htmlspecialchars(strip_tags($this->release_img));
        $this->created=htmlspecialchars(strip_tags($this->created));
     
        // bind the values
        $stmt->bindParam(':_id', $this->_id);
        $stmt->bindParam(':release_title', $this->release_title);
        $stmt->bindParam(':artist', $this->artist);
        $stmt->bindParam(':release_date', $this->release_date);
        $stmt->bindParam(':producer', $this->producer);
        $stmt->bindParam(':release_date', $this->release_date);
        $stmt->bindParam(':genre', $this->genre);
        $stmt->bindParam(':release_info', $this->release_info);
        $stmt->bindParam(':media_link', $this->media_link);
        $stmt->bindParam(':release_audio', $this->release_audio);
        $stmt->bindParam(':release_img', $this->release_img);
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
                    release_id, _id, release_title, artist, producer, release_date, genre, release_info, media_link, release_audio, release_img, created, modified
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
                    release_id, _id, release_title, artist, producer, release_date, genre, release_info, media_link, release_audio, release_img, created, modified
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
        $this->release_id = $row['release_id'];
        $this->_id = $row['_id'];
        $this->release_title = $row['release_title'];
        $this->artist = $row['artist'];
        $this->producer = $row['producer'];
        $this->release_date = $row['release_date'];
        $this->genre = $row['genre'];
        $this->release_info = $row['release_info'];
        $this->media_link = $row['media_link'];
        $this->release_audio = $row['release_audio'];
        $this->release_img = $row['release_img'];
        $this->created = $row['created'];
        $this->modified = $row['modified'];
    }
     
    // update a user record
    public function update(){
        // if no posted password, do not update the password
        $query = "UPDATE " . $this->table_name . "
            SET
                release_title = :release_title,
                artist = :artist,
                producer = :producer,
                release_date = :release_date,
                genre = :genre,
                release_info = :release_info,
                media_link = :media_link,
                release_audio = :release_audio,
                release_img = :release_img,
                modified = :modified
            WHERE _id = :_id";
     
        // prepare the query
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->_id=htmlspecialchars(strip_tags($this->_id));
        $this->release_title=htmlspecialchars(strip_tags($this->release_title));
        $this->artist = implode(', ', $this->artist);
        
        $this->artist=htmlspecialchars(strip_tags($this->artist));
        $this->producer=htmlspecialchars(strip_tags($this->producer));
        $this->release_date=htmlspecialchars(strip_tags($this->release_date));

        $this->genre = implode(', ', $this->genre);
        $this->genre=htmlspecialchars(strip_tags($this->genre));

        $this->release_info=htmlspecialchars(strip_tags($this->release_info));

        $this->media_link=htmlspecialchars(strip_tags($this->media_link));
        $this->release_audio=htmlspecialchars(strip_tags($this->release_audio));
        $this->release_img=htmlspecialchars(strip_tags($this->release_img));
        $this->modified=htmlspecialchars(strip_tags($this->modified));
     
        // bind the values
        $stmt->bindParam(':_id', $this->_id);
        $stmt->bindParam(':release_title', $this->release_title);
        $stmt->bindParam(':artist', $this->artist);
        $stmt->bindParam(':release_date', $this->release_date);
        $stmt->bindParam(':producer', $this->producer);
        $stmt->bindParam(':genre', $this->genre);
        $stmt->bindParam(':release_info', $this->release_info);
        $stmt->bindParam(':media_link', $this->media_link);
        $stmt->bindParam(':release_audio', $this->release_audio);
        $stmt->bindParam(':release_img', $this->release_img);
        $stmt->bindParam(':modified', $this->modified);
     
        // execute the query
        if($stmt->execute()){
            return true;
        }
     
        return false;
    }

    public function showError($stmt){
        echo "<pre>";
            print_r($stmt->errorrelease_Info());
        echo "</pre>";
    }

    // delete the product
    function delete(){
        // query to read single record
        $query = "SELECT
                    release_img, release_audio
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
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        // set values to object properties
        $this->release_audio = $row['release_audio'];
        $this->release_img = $row['release_img'];
        // delete image path in uploads directory
        unlink($this->release_audio);
        unlink($this->release_img);
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

    // read only one artist with album/track
    function readOneWithTrack($artist){
     
        // query to read single record
        $query = "SELECT
                    release_id, _id, release_title, artist, producer, release_date, genre, release_info, media_link, release_audio, release_img, created, modified
                FROM
                    " . $this->table_name . " 
                WHERE artist = :artist";
     
        // prepare query statement
        $stmt = $this->conn->prepare( $query );
     
        // bind id of product to be updated
        $stmt->bindParam(':artist', $this->artist);
     
        // execute query
        $stmt->execute();
     
        // get retrieved row
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
     
        // set values to object properties
        $this->release_id = $row['release_id'];
        $this->_id = $row['_id'];
        $this->release_title = $row['release_title'];
        $this->artist = $row['artist'];
        $this->producer = $row['producer'];
        $this->release_date = $row['release_date'];
        $this->genre = $row['genre'];
        $this->release_info = $row['release_info'];
        $this->media_link = $row['media_link'];
        $this->release_audio = $row['release_audio'];
        $this->release_img = $row['release_img'];
        $this->created = $row['created'];
        $this->modified = $row['modified'];
    }

}

