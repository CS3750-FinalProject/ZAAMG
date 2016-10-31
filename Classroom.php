<?php

include 'Database.php';

class Classroom {
    private $database;

    private $classroomID;
    private $classroomNum;
    private $classroomCapacity;
    private $buildId;

    # removed types from formal arguments, don't think they're necessary
    public function __construct($classID, $classNum, $classCap, $buildId) {
        $this->classroomID = $classID;
        $this->classroomNum = $classNum;
        $this->classroomCapacity = $classCap;
        $this->buildId = $buildId;

        $this->database = new Database();
    }

    public function getClassroomID(){
        return $this->classroomID;
    }

    public function getClassroomNum(){
        return $this->classroomNum;
    }

    public function getClassroomCap(){
        return $this->classroomCapacity;
    }

    public function getBuildingId(){
        return $this->buildId;
    }

    public function insertNewClassroom(){
        $dbh = $this->database->getdbh();
        $stmtInsert = $dbh->prepare("INSERT INTO ZAAMG.Classroom VALUES (:id, :num, :cap, :buildId)");
        # send NULL for classroom_id because the database auto-increments it
        $stmtInsert->bindValue("id", NULL);
        $stmtInsert->bindValue(":num", $this->classroomNum);
        $stmtInsert->bindValue(":cap", $this->classroomCapacity);
        $stmtInsert->bindValue(":buildId", $this->buildId);

            try {
                $stmtInsert->execute();

            echo "Success executing Insert";
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
}
