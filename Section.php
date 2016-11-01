<?php

require_once "Database.php";

class Section
{
    private $sectionID;
    private $courseID;
    private $profID;
    private $classroomID;
    private $block;
    private $days = array();
    private $startTime;
    private $endTime;

    private $database;

    public function __construct($sectionID, $courseID, $profID, $classroomID,
                                $block = 0, $days, $startTime, $endTime) {
        $this->sectionID = $sectionID;
        $this->courseID = $courseID;
        $this->profID = $profID;
        $this->classroomID = $classroomID;
        $this->block = $block;
        $this->days[] = $days;
        $this->startTime = $startTime;
        $this->endTime = $endTime;

        $this->database = new Database();
    }

    public function createSection(Classroom $classroom, Professor $professor,
                                  Course $course){
        $section = new Section(NULL, $course->getCourseId(),
            $professor->getProfId(), $classroom->getClassroomID(), 0, "", "",
            "");
        return $section;
    }

    public function insertNewSection(){
        $dbh = $this->database->getdbh();
        $stmtInsert = $dbh->prepare("INSERT INTO zaamg.Course VALUES 
        (:secID, :courseID, :profID, :classID, :block, :days, :startTime, 
        :endTime)");
        $stmtInsert->bindValue(":secID", NULL);
        $stmtInsert->bindValue(":courseID", $this->courseID);
        $stmtInsert->bindValue(":profID", $this->profID);
        $stmtInsert->bindValue(":classID", $this->classroomID);
        $stmtInsert->bindValue(":block", $this->block);
        $stmtInsert->bindValue(":days", $this->days);
        $stmtInsert->bindValue(":startTime", $this->startTime);
        $stmtInsert->bindValue(":endTime", $this->endTime);
        $stmtInsert->execute();
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
}