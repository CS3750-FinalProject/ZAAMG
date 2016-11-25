<?php

//include 'Database.php';

class Semester {
    //private $database;

    private $semId;
    private $semYear;
    private $semSeason;
    private $semNumWeeks;
    private $semStartDate;
    private $semFirstBlockStartDate;
    private $semSecondBlockStartDate;


    public function __construct($semId, $semYear, $semSeason, $semNumWeeks, $semStartDate,
                                $semFirstBlockStartDate, $semSecondBlockStartDate) {
        $this->semId = $semId;
        $this->semYear = $semYear;
        $this->semSeason = $semSeason;
        $this->semNumWeeks = $semNumWeeks;
        $this->semStartDate = $semStartDate;
        $this->semFirstBlockStartDate = $semFirstBlockStartDate;
        $this->semSecondBlockStartDate = $semSecondBlockStartDate;

        //$this->database = new Database();
    }


    public function getSemId()
    {
        return $this->semId;
    }


    public function getSemYear()
    {
        return $this->semYear;
    }


    public function getSemSeason()
    {
        return $this->semSeason;
    }


    public function getSemNumWeeks()
    {
        return $this->semNumWeeks;
    }


    public function getSemStartDate()
    {
        return $this->semStartDate;
    }

    public function getSemFirstBlockStartDate()
    {
        return $this->semFirstBlockStartDate;
    }

    public function getSemSecondBlockStartDate()
    {
        return $this->semSecondBlockStartDate;
    }




    public function insertNewSemester(){
        $dbh = $this->database->getdbh();
        $stmtInsert = $dbh->prepare(
            "INSERT INTO W01143557.Semester VALUES (
              :id, :year, :season, :weeks, :start, :first_block, :second_block)");
        # send NULL for course_id because the database auto-increments it
        $stmtInsert->bindValue("id", NULL);
        $stmtInsert->bindValue(":year", $this->semYear);
        $stmtInsert->bindValue(":season", $this->semSeason);
        $stmtInsert->bindValue(":weeks", $this->semNumWeeks);
        $stmtInsert->bindValue(":start", $this->semStartDate);
        $stmtInsert->bindValue(":first_block", $this->semFirstBlockStartDate);
        $stmtInsert->bindValue(":second_block", $this->semSecondBlockStartDate);

        try {
            $stmtInsert->execute();
            echo "Success executing Insert";
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    public function semesterExists($semYear, $semSeason){
        $dbh = $this->database->getdbh();
        $stmtSelect = $dbh->prepare(
            "SELECT sem_id FROM W01143557.Semester
              WHERE sem_year = {$dbh->quote($semYear)} AND sem_season = {$dbh->quote($semSeason)}");
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