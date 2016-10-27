<?php

class Building {
    private $buildingID;
    private $campusID;
    private $buildCode = "TE";
    private $buildName;

    private $host = "localhost";
    private $dbname = "ZAAMG";
    private $username = "zaamg";
    private $dbh;

    public function __construct(int $buildingID, int $campusID, string $buildingCode, string $buildingName) {
        $this->buildingID = $buildingID;
        $this->campusID = $campusID;
        $this->buildCode = $buildingCode;
        $this->buildName = $buildingName;
    }

    public function getBuildingID() : int {
        return $this->buildingID;
    }

    public function insertNewBuilding(Building $building){
        try {
            $this->dbh = new PDO("mysql:host=$this->host;dbname:$this->dbname", $this->username);
        } catch(PDOException $e){
            echo "Error creating Database Object";
            return;
        }
        $stmtInsert = $this->dbh->prepare("INSERT INTO `Building` (`campus_id`, `building_code`, `building_name`)
            VALUES (:campusID, :code, :buildName)");
        $stmtInsert->bindValue(":campusID", $building->campusID);
        $stmtInsert->bindValue(":code", $building->buildCode);
        $stmtInsert->bindValue(":buildName", $building->buildName);
        $stmtInsert->execute();
    }
}