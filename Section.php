<?php

require_once "Database.php";

class Section
{
    private $sectionID;
    private $courseID;
    private $profID;
    private $classroomID;
    private $block;
    //private $days = array();
    private $days;
    private $startTime;
    private $endTime;
    private $semester;
    private $capacity;
    //adding block definitions
    public static $FULL_BLOCK = 0;
    public static $FIRST_BLOCK = 1;
    public static $SECOND_BLOCK = 2;

    private $database;

    public function __construct($sectionID, $courseID, $profID, $classroomID,
                                $block = 0, /*$days = array(),*/ $days, $startTime = "", $endTime = "",
                                $semester, $capacity = 30) {
        $this->sectionID = $sectionID;
        $this->courseID = $courseID;
        $this->profID = $profID;
        $this->classroomID = $classroomID;
        $this->block = $block;
        //$this->days[] = $days;
        $this->days = $days;
        $this->startTime = $startTime;
        $this->endTime = $endTime;
        $this->semester = $semester;
        $this->capacity = $capacity;

        $this->database = new Database();
    }

    public function createSection(Classroom $classroom, Professor $professor,
                                  Course $course){
        $section = new Section(NULL, $course->getCourseId(),
            $professor->getProfId(), $classroom->getClassroomID());
        return $section;
    }

    public function insertNewSection(){
        $dbh = $this->database->getdbh();
        $stmtInsert = $dbh->prepare("INSERT INTO zaamg.Section VALUES
        (:secID, :courseID, :profID, :classID, :semester, :days, :startTime,
        :endTime, :block, :capacity)");
        $stmtInsert->bindValue(":secID", NULL);
        $stmtInsert->bindValue(":courseID", $this->courseID);
        $stmtInsert->bindValue(":profID", $this->profID);
        $stmtInsert->bindValue(":classID", $this->classroomID);
        $stmtInsert->bindValue(":block", $this->block);
        $stmtInsert->bindValue(":days", $this->days);
        $stmtInsert->bindValue(":startTime", $this->startTime);
        $stmtInsert->bindValue(":endTime", $this->endTime);
        $stmtInsert->bindValue(":capacity", $this->capacity);
        $stmtInsert->bindValue(":semester", $this->semester);
        $stmtInsert->execute();
        $this->sectionID = (int) $dbh->lastInsertId();
    }

    public function sectionExists($courseID, $profID, $roomID, $semID, $days, $startTime){
        $dbh = $this->database->getdbh();
        $stmtSelect = $dbh->prepare(
            "SELECT section_id FROM ZAAMG.Section
              WHERE course_id = ".$dbh->quote($courseID)."
              AND prof_id = ".$dbh->quote($profID)."
              AND classroom_id = ".$dbh->quote($roomID)."
              AND sem_id = ".$dbh->quote($semID)."
              AND section_days = ".$dbh->quote($days)."
              AND section_start_time = ".$dbh->quote($startTime));
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


    #adding getters, most of them auto-generated, so fix things as needed.
    public function getSectionID(): int
    {
        return $this->sectionID;
    }

    public function getCourseID(): int
    {
        return $this->courseID;
    }

    public function getProfID()
    {
        return $this->profID;
    }

    public function getClassroomID(): int
    {
        return $this->classroomID;
    }

    public function getBlock(): int
    {
        return $this->block;
    }

    public function getDays(): array
    {
        return $this->days;
    }

    public function getStartTime(): string
    {
        return $this->startTime;
    }

    public function getEndTime(): string
    {
        return $this->endTime;
    }

    public function getCapacity(): int
    {
        return $this->capacity;
    }

    /*setters auto-generated for block, days, start time, and end time
    setters for the others would be done before with the
    create section function(if that's the way we're doing it)
    Also, I don't think we want anyone being able to mess with foreign keys directly*/
    public function setBlock(int $block)
    {
        $this->block = $block;
    }

    public function setDays(array $days)
    {
        $this->days = $days;
    }

    public function setStartTime(DateTime $startTime)
    {
        $this->startTime = $startTime;
    }

    public function setEndTime(DateTime $endTime)
    {
        $this->endTime = $endTime;
    }

    public function setCapacity(int $capacity)
    {
        $this->capacity = $capacity;
    }

}
