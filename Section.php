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

    /*public function getSectionCourseName(){
        $dbh = $this->database->getdbh();
        $stmtSelect = $dbh->prepare(
            "SELECT course_title FROM ZAAMG.Course
              WHERE course_id = ".$dbh->quote($this->courseID));
        try{
            $stmtSelect->execute();
            $result = $stmtSelect->fetch();
            return $result[0];
        }catch (Exception $e){
            echo "getSectionCourseName: ".$e->getMessage();
        }
    }*/

    public function getSectionProperty($sql_property, $table, $id, $object_property){
        $dbh = $this->database->getdbh();
        $stmtSelect = $dbh->prepare(
            "SELECT {$sql_property} FROM ZAAMG.{$table}
              WHERE {$id} = ".$dbh->quote($this->{$object_property}));
        try{
            $stmtSelect->execute();
            $result = $stmtSelect->fetch();
            return $result[0];
        }catch (Exception $e){
            echo "getSectionProperty: ".$e->getMessage();
        }
    }

    public function getSectionProperty_Join_3($sql_property, $table1, $table2, $id1, $id2, $object_property){
        $dbh = $this->database->getdbh();
        $stmtSelect = $dbh->prepare(
            "SELECT {$sql_property} FROM ZAAMG.Section S
              JOIN ZAAMG.{$table1} A
              ON S.{$id1} = A.{$id1}
              JOIN ZAAMG.{$table2} B
              ON A.{$id2} = B.{$id2}
              WHERE S.{$id1} = ".$dbh->quote($this->{$object_property}));
        try{
            $stmtSelect->execute();
            $result = $stmtSelect->fetch();
            return $result[0];
        }catch (Exception $e){
            echo "getSectionProperty: ".$e->getMessage();
        }
    }

    public function getSectionProperty_Join_4($sql_property, $table1, $table2, $table3, $id1, $id2, $id3, $object_property){
        $dbh = $this->database->getdbh();
        $stmtSelect = $dbh->prepare(
            "SELECT {$sql_property} FROM ZAAMG.Section S
              JOIN ZAAMG.{$table1} A
              ON S.{$id1} = A.{$id1}
              JOIN ZAAMG.{$table2} B
              ON A.{$id2} = B.{$id2}
              JOIN ZAAMG.{$table3} C
              ON B.{$id3} = C.{$id3}
              WHERE S.{$id1} = ".$dbh->quote($this->{$object_property}));
        try{
            $stmtSelect->execute();
            $result = $stmtSelect->fetch();
            return $result[0];
        }catch (Exception $e){
            echo "getSectionProperty: ".$e->getMessage();
        }
    }


    /*public function getBuildingCode(){
        $dbh = $this->database->getdbh();
        $stmtSelect = $dbh->prepare(
            "SELECT building_code FROM ZAAMG.Section s
              JOIN ZAAMG.Classroom c
              ON s.classroom_id = c.classroom_id
              JOIN ZAAMG.Building b
              ON c.building_id = b.building_id
              WHERE s.classroom_id = ".$dbh->quote($this->classroomID));
        try{
            $stmtSelect->execute();
            $result = $stmtSelect->fetch();
            return $result[0];
        }catch (Exception $e){
            echo "getSectionProperty: ".$e->getMessage();
        }
    }*/

    public function getDayString(){
        $theDays = explode( "day", $this->days);
        $theLetters = "";

        foreach($theDays as $day){
            $theLetters .= $day != "Thurs" ? substr($day,0,1) : substr($day,0,2);
        }

        return $theLetters;
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

    public function getBlock(): string
    {
        if ($this->block == 0)
            return "Full Semester";
        elseif ($this->block == 1)
            return "First Block";
        else
            return "Second Block";
    }

    public function getDays(): array
    {
        return $this->days;
    }

    public function getStartTime(): string
    {
        $theTime = strtotime($this->startTime);
        return date("h:i A", $theTime);
    }

    public function getEndTime(): string
    {
        $theTime = strtotime($this->endTime);
        return date("h:i A", $theTime);
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
