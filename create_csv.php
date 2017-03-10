<?php

require_once "Database.php";
require_once "Professor.php";

$db = new Database();

$semester = "Spring_2017";
$semesterID = 2;

if (isset($_SESSION['mainSemesterLabel'])){
    $semester = $_SESSION['mainSemesterLabel'];
}
if (isset($_SESSION['mainSemesterId'])){
    $semesterId = $_SESSION['mainSemesterId'];
}

$File = fopen("php://output", "xb");
if($File == false){
    $File = fopen("php://output", "wb") or die("Unable to create file!");
}
header('Content-Type: text/csv; charset=utf-8');
header("Content-Disposition: attachment; filename=$semester.csv");


fputcsv($File, array("Instructor", "Course", "Hours", "Days", "Room", "MAX", "HRS", "CAMP", "Hrs Required"));

$professors = $db->getAllProfessors("prof_id");
foreach ($professors as $professor){
    if($professor instanceof Professor) {
        $dbh = $db->getdbh();
        $stmtSelect = $dbh->prepare("SELECT concat(W01143557.Professor.prof_last,', ',W01143557.Professor.prof_first) AS 
            'Professor', concat(W01143557.Course.course_prefix,' ',W01143557.Course.course_number) AS 'Course', W01143557.Section.section_start_time, 
            W01143557.Section.section_end_time, W01143557.Section.section_days, W01143557.Classroom.classroom_number, W01143557.Section.section_capacity, 
            W01143557.Campus.campus_name, W01143557.Course.course_credits, W01143557.Professor.prof_req_hours FROM W01143557.Section JOIN W01143557.Professor ON 
            W01143557.Section.prof_id = W01143557.Professor.prof_id JOIN W01143557.Course ON W01143557.Section.course_id = W01143557.Course.course_id 
            JOIN (W01143557.Classroom JOIN (W01143557.Building JOIN W01143557.Campus ON W01143557.Building.campus_id = W01143557.Campus.campus_id)
            ON W01143557.Classroom.building_id = W01143557.Building.building_id) ON W01143557.Section.classroom_id = W01143557.Classroom.classroom_id
            WHERE Professor.prof_id = :profID AND sem_id = :semID");
        $stmtSelect->bindValue(":profID", $professor->getProfId());
        $stmtSelect->bindValue(":semID", $semesterID);
        $stmtSelect->execute();
        $profSections = $stmtSelect->fetchAll(PDO::FETCH_ASSOC);
        foreach ($profSections as $section){
            fputcsv($File, $section);
        }
    }
}

fclose($File);
