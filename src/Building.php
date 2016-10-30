<?php

include 'Database.php';

class Building {
    private $database;

    private $buildingID;
    private $campusID;
    private $buildCode;
    private $buildName;

    # removed types from formal arguments, don't think they're necessary
    //they aren't necessary, but they do help with back-end error checking
    public function __construct($buildingID, $buildingCode, $buildingName, $campusID) {
        $this->buildingID = $buildingID;
        $this->buildCode = $buildingCode;
        $this->buildName = $buildingName;
        $this->campusID = $campusID;

        $this->database = new Database();
    }

    public function getBuildingID() : int {
        return $this->buildingID;
    }

    public function getBuildingName(){
        return $this->buildName;
    }

    public function getBuildingCode(){
        return $this->buildCode;
    }

    public function getCampusID(){
        return $this->campusID;
    }

    public function insertNewBuilding(){
        $dbh = $this->database->getdbh();
        $stmtInsert = $dbh->prepare("INSERT INTO ZAAMG.Building VALUES (:id, :code, :buildName, :campusID)");
        # send NULL for building_id because the database auto-increments it
        //this will create an error, we are now attempting to put a null value into an enforced non-null datafield. 
        //Why did this get changed? The way I had it before was the best way.
        $stmtInsert->bindValue("id", NULL);
        $stmtInsert->bindValue(":code", $this->buildCode);
        $stmtInsert->bindValue(":buildName", $this->buildName);
        $stmtInsert->bindValue(":campusID", $this->campusID);

            try {
                $stmtInsert->execute();

            echo "Success executing Insert";
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
}
