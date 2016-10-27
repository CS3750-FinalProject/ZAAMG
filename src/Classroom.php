<?php

class Classroom
{
    private $classroomID;
    private $classroomNum;
    private $capacity;
    private $buildingID;

    private $host = "localhost";
    private $dbname = "ZAAMG";
    private $username = "zaamg";
    private $dbh;

    public function __construct(int $classID, string $classNum, int $capacity = 30, int $buildingID) {
        $this->classroomID = $classID;
        $this->classroomNum = $classNum;
        $this->capacity = $capacity;
        $this->buildingID = $buildingID;
    }

    public function getClassroomID():int {
        return $this->classroomID;
    }

    public function insertNewClassroom(Classroom $classroom){
        try {
            $this->dbh = new PDO("mysql:host=$this->host;dbname:$this->dbname", $this->username);
        } catch(PDOException $e){
            echo "Error creating Database Object";
            return;
        }
        $stmtInsert = $this->dbh->prepare("INSERT INTO `Classroom` (`classroom_number`, `classroom_capacity`, " .
            "`building_id`) VALUES (:classNum, :capacity, :buildID)");
        $stmtInsert->bindValue(":classNum", $classroom->classroomNum); //I do it this way just in case someone is trying to hack the system.
        $stmtInsert->bindValue(":capacity", $classroom->capacity);
        $stmtInsert->bindValue(":buildID", $classroom->buildingID);
        $stmtInsert->execute();
    }

}