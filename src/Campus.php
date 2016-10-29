<?php
include 'Database.php';

class Campus {
    private $database;

    private $campusID;
    private $campusName;


    public function getCampusID(){
        return $this->campusID;
    }

    public function getCampusName(){
        return $this->campusName;
    }

    public function __construct($campusID, $campusName) {
        $this->database = new Database();

        $this->campusID = $campusID;
        $this->campusName = $campusName;
    }

    public function insertNewCampus(){
        $stmtInsert = $this->database->dbh->prepare("INSERT INTO ZAAMG.Campus VALUES (:id, :name)");
        # send NULL for campus_id because the database auto-increments it
        $stmtInsert->bindValue(":id", NULL);
        $stmtInsert->bindValue(":name", $this->campusName);
        $stmtInsert->execute();
    }
}