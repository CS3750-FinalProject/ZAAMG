<?php

require_once '../Section.php';

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

$sectionStartTime = $sectionIsOnline == 1 ? "00:00:00" : $sectionStartTime;
$sectionEndTime = $sectionIsOnline == 1 ? "00:00:00" : $sectionEndTime;
$sectionDays = $sectionIsOnline == 1 ? "Online" : $sectionEndTime;

$section = new Section(NULL, $sectionCourse, $sectionProfessor, $sectionClassroom,
    $sectionBlock, $sectionDays, $sectionStartTime, $sectionEndTime, $sectionIsOnline,
    $sectionSemester, $sectionCapacity);




$result = $section->sectionExists($sectionCourse, $sectionProfessor, $sectionClassroom,
    $sectionSemester, $sectionDays, $sectionStartTime);

if ($result == "does not exist"){
    $section->insertNewSection();
}




