<?php

class Building {
    private $buildingID;
    private $campusID;
    private $buildCode;
    private $buildName;



    private $host = "localhost";
    private $dbname = "zaamg";
    private $username = "zaamg";
    private $dbh;

    # removed types from formal arguments,
    # made buildingID optional (should be produced automatically by mysql)
    public function __construct($buildingID, $buildingCode, $buildingName, $campusID) {
        $this->buildingID = $buildingID;
        $this->buildCode = $buildingCode;
        $this->buildName = $buildingName;
        $this->campusID = $campusID;
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
        try {
            $this->dbh = new PDO("mysql:host=$this->host;dbname:$this->dbname", $this->username);
            $this->dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            echo "<br>Success creating Database Object<br>";
        } catch(PDOException $e){
            echo "Error creating Database Object";
            return;
        }
        $stmtInsert = $this->dbh->prepare("INSERT INTO ZAAMG.Building VALUES (:id, :code, :buildName, :campusID)");
        # send NULL for building_id because the database auto-increments it
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