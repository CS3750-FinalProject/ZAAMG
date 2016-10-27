<?php

class Campus {
    private $campusID;
    private $campusName;

    private $host = "localhost";
    private $dbname = "ZAAMG";
    private $username = "zaamg";

    private $dbh;

    public function getCampusID(): int {
        return $this->campusID;
    }

    public function __construct(int $campusID, string $campusName) {
        $this->campusID = $campusID;
        $this->campusName = $campusName;
    }

    public function insertNewCampus(){
        try {
            $this->dbh = new PDO("mysql:host=$this->host;dbname:$this->dbname", $this->username);
        } catch(PDOException $e){
            echo "Error creating Database Object";
            return;
        }
        $stmtInsert = $this->dbh->prepare("INSERT INTO `CAMPUS` (`campus_name`) VALUES (:campusName)");
        $stmtInsert->bindValue(":campusName", $this->campusName);
        $stmtInsert->execute();
    }
}
