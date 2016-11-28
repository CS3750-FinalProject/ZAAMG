<?php

require_once '../Database.php';
require_once '../Section.php';


$sectionId = isset($_POST['sectionId']) ? $_POST['sectionId'] : "not entered";
$sectionCourse = isset($_POST['sectionCourse']) ? $_POST['sectionCourse'] : "not entered";
$sectionProfessor = isset($_POST['sectionProfessor']) ? $_POST['sectionProfessor'] : "not entered";
$sectionClassroom = isset($_POST['sectionClassroom']) ? $_POST['sectionClassroom'] : "not entered";
$sectionDays = isset($_POST['sectionDays']) ? $_POST['sectionDays'] : "not entered";
$sectionStartTime = isset($_POST['sectionStartTime']) ? $_POST['sectionStartTime'] : "not entered";
$sectionEndTime = isset($_POST['sectionEndTime']) ? $_POST['sectionEndTime'] : "not entered";
$sectionIsOnline = isset($_POST['sectionIsOnline']) ? $_POST['sectionIsOnline'] : "not entered";
$sectionBlock = isset($_POST['sectionBlock']) ? $_POST['sectionBlock'] : "not entered";
$sectionCapacity = isset($_POST['sectionCapacity']) ? $_POST['sectionCapacity'] : "not entered";
$sectionSemester = isset($_POST['sectionSemester']) ? $_POST['sectionSemester'] : "not entered";

$database = new Database();
$dbh = $database->getdbh();

$message = "";

$updateStmt = $dbh->prepare(
    "  UPDATE ZAAMG.Section
        SET course_id           = $sectionCourse,
            prof_id             = $sectionProfessor,
            classroom_id        = $sectionClassroom,
            sem_id              = $sectionSemester,
            section_days        = '$sectionDays',
            section_start_time  = '$sectionStartTime',
            section_end_time    = '$sectionEndTime',
            section_is_online   = $sectionIsOnline,
            section_block       = $sectionBlock,
            section_capacity    = $sectionCapacity
        WHERE section_id = $sectionId
    "
);

try{
    $updateStmt->execute();
    $message = "success";
}catch(Exception $e){
    $message = "action_updateSection: ".$e->getMessage();
}

echo $message;