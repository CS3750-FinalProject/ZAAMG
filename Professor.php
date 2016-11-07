<?php

include 'Database.php';

class Professor{
    private $database;

    private $profId;
    private $profFirst;
    private $profLast;
    private $profEmail;
    private $profHours;
    private $profRelease;
    private $deptId;


    public function __construct($profId, $profFirst, $profLast, $profEmail, $profHours, $profRelease, $deptId) {

        /*$index_atSymbol = strpos($profEmail, "@");
        $fixed_profEmail = substr_replace($profEmail, "\\@", $index_atSymbol, 1);
        echo $fixed_profEmail;*/

        $this->profId = $profId;
        $this->profFirst = $profFirst;
        $this->profLast = $profLast;
        $this->profEmail = $profEmail;
        $this->profHours = $profHours;
        $this->profRelease = $profRelease;
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


    public function getProfHours()
    {
        return $this->profHours;
    }


    public function getProfRelease()
    {
        return $this->profRelease;
    }


    public function getDeptId(){
        return $this->deptId;
    }

    public function insertNewProfessor(){
        $dbh = $this->database->getdbh();
        $stmtInsert = $dbh->prepare(
            "INSERT INTO ZAAMG.Professor VALUES (
              :id, :first, :last, :email, :hours, :release, :deptId)");
        # send NULL for course_id because the database auto-increments it
        $stmtInsert->bindValue("id", NULL);
        $stmtInsert->bindValue(":first", $this->profFirst);
        $stmtInsert->bindValue(":last", $this->profLast);
        $stmtInsert->bindValue(":email", strtolower($this->profEmail));
        $stmtInsert->bindValue(":hours", $this->profHours);
        $stmtInsert->bindValue(":release", $this->profRelease);
        $stmtInsert->bindValue(":deptId", $this->deptId);
        try {
            $stmtInsert->execute();
            echo "Success executing Insert";
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }


    public function professorExists($profEmail){
        $profEmail_lower = strtolower($profEmail);
        $dbh = $this->database->getdbh();
        $stmtSelect = $dbh->prepare(
            "SELECT prof_id FROM ZAAMG.Professor
              WHERE prof_email = '$profEmail_lower'");
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