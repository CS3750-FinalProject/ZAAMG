<?php

include 'Database.php';

class Course {
    private $database;

    private $courseId;
    private $courseCode;
    private $courseTitle;
    private $courseCapacity;
    private $courseCredits;
    private $deptId;


    public function __construct($courseId, $courseCode, $courseTitle, $courseCap, $courseCred, $deptId) {
        $this->courseId = $courseId;
        $this->courseCode = $courseCode;
        $this->courseTitle = $courseTitle;
        $this->courseCapacity = $courseCap;
        $this->courseCredits = $courseCred;
        $this->deptId = $deptId;

        $this->database = new Database();
    }



    public function getCourseId(){
        return $this->courseId;
    }

    public function getCourseCode(){
        return $this->courseCode;
    }

    public function getCourseTitle(){
        return $this->courseTitle;
    }

    public function getCourseCapacity(){
        return $this->courseCapacity;
    }

    public function getCourseCredits(){
        return $this->courseCredits;
    }

    public function getDeptId(){
        return $this->deptId;
    }

    public function insertNewCourse(){
        $dbh = $this->database->getdbh();
        $stmtInsert = $dbh->prepare(
            "INSERT INTO ZAAMG.Course VALUES (
              :id, :code, :title, :cap, :cred, :deptId)");
        # send NULL for course_id because the database auto-increments it
        $stmtInsert->bindValue("id", NULL);
        $stmtInsert->bindValue(":code", $this->courseCode);
        $stmtInsert->bindValue(":title", $this->courseTitle);
        $stmtInsert->bindValue(":cap", $this->courseCapacity);
        $stmtInsert->bindValue(":cred", $this->courseCredits);
        $stmtInsert->bindValue(":deptId", $this->deptId);

        try {
            $stmtInsert->execute();
            //echo "Success executing Insert";
        } catch (Exception $e) {
            echo $e->getMessage();
            return $e->getMessage();
        }
        return $dbh->lastInsertId();
    }

    public function courseExists($courseCode, $deptId){
        $dbh = $this->database->getdbh();
        $stmtSelect = $dbh->prepare(
            "SELECT course_id FROM ZAAMG.Course
              WHERE course_code = $courseCode
              AND dept_id = $deptId");
        try {
            $stmtSelect->execute();
            $result = $stmtSelect->fetch(PDO::FETCH_ASSOC);
            if ($result != NULL) {
                return "does exist";
            }else{
                return "does not exist";
            }
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }


}
