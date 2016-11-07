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
        $dbh = $this->database->getdbh();
        $stmtInsert = $dbh->prepare("INSERT INTO ZAAMG.Campus VALUES (:id, :name)");
        # send NULL for campus_id because the database auto-increments it
        $stmtInsert->bindValue(":id", NULL);
        $stmtInsert->bindValue(":name", $this->campusName);
        $stmtInsert->execute();
    }


    public function campusExists($campusName){
        $dbh = $this->database->getdbh();
        $stmtSelect = $dbh->prepare(
            "SELECT campus_id FROM ZAAMG.Campus
              WHERE campus_name = ".$dbh->quote($campusName));
        try {
            $stmtSelect->execute();
            $result = $stmtSelect->fetch(PDO::FETCH_ASSOC);
            if ($result != NULL) {
                return "does exist";
            }else{
                return "does not exist";
            }
        } catch (Exception $e) {
            echo "Here's what went wrong: ".$e->getMessage();
        }
    }


}
