<?php

class Classroom {
    private $classroomID;
    private $classroomNum;
    private $classroomCapacity;
    private $buildId;



    private $host = "localhost";
    private $dbname = "zaamg";
    private $username = "zaamg";
    private $dbh;

    # removed types from formal arguments, don't think they're necessary
    public function __construct($classID, $classNum, $classCap, $buildId) {
        $this->classroomID = $classID;
        $this->classroomNum = $classNum;
        $this->classroomCapacity = $classCap;
        $this->buildId = $buildId;
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
        try {
            $this->dbh = new PDO("mysql:host=$this->host;dbname:$this->dbname", $this->username);
            $this->dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            echo "<br>Success creating Database Object<br>";
        } catch(PDOException $e){
            echo "Error creating Database Object";
            return;
        }
        $stmtInsert = $this->dbh->prepare("INSERT INTO ZAAMG.Classroom VALUES (:id, :num, :cap, :buildId)");
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