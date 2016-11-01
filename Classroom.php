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

    public function classroomExists($classNum, $buildId){
        $dbh = $this->database->getdbh();
        $stmtSelect = $dbh->prepare(
            "SELECT classroom_id FROM ZAAMG.Classroom
              WHERE classroom_number = $classNum AND building_id = $buildId");
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
