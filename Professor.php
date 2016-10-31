<?php

include 'Database.php';

class Professor{
    private $database;

    private $profId;
    private $profFirst;
    private $profLast;
    private $profEmail;
    private $deptId;


    public function __construct($profId, $profFirst, $profLast, $profEmail, $deptId) {
        $this->profId = $profId;
        $this->profFirst = $profFirst;
        $this->profLast = $profLast;
        $this->profEmail = $profEmail;
        $this->deptId = $deptId;

        $this->database = new Database();
    }

    public function getProfId(){
        return $this->profId;
    }

    public function getProfFirst(){
        return $this->profFirst;
    }

    public function getProfLast(){
        return $this->profLast;
    }

    public function getProfEmail(){
        return $this->profEmail;
    }

    public function getDeptId(){
        return $this->deptId;
    }

    public function insertNewProfessor(){
        $dbh = $this->database->getdbh();
        $stmtInsert = $dbh->prepare(
            "INSERT INTO ZAAMG.Professor VALUES (
              :id, :first, :last, :email, :deptId)");
        # send NULL for course_id because the database auto-increments it
        $stmtInsert->bindValue("id", NULL);
        $stmtInsert->bindValue(":first", $this->profFirst);
        $stmtInsert->bindValue(":last", $this->profLast);
        $stmtInsert->bindValue(":email", $this->profEmail);
        $stmtInsert->bindValue(":deptId", $this->deptId);

        try {
            $stmtInsert->execute();
            echo "Success executing Insert";
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
}