<?php
include 'Database.php';

class Section {
    private $database;

    private $sectionID;
    private $courseID$;
    private $classroomID;
    private $professorID;

    public function getSectionID(){
        return $this->sectionID;
    }

    public function getCourseID(){
        return $this->courseID;
    }

    public function getClassroomID(){
        return $this->classroomID;
    }

    public function getProfessorID(){
        return $this->professorID;
    }

/*
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
*/
}
