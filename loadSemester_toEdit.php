<?php

require_once 'Database.php';
require_once 'Semester.php';

$database = new Database();
$dbh = $database->getdbh();


$semId = isset($_POST['semId']) ? $_POST['semId'] : "not entered";

$semester = $database->getSemester($semId);


$semester_json = json_encode(
    array(
        'id'=>$semester->getSemId(),
        'year'=>$semester->getSemYear(),
        'season'=>$semester->getSemSeason(),
        'weeks'=>$semester->getSemNumWeeks(),
        'start'=>$semester->getSemFirstBlockStartDate(),
        'firstBlock'=>$semester->getSemFirstBlockStartDate(),
        'secondBlock'=>$semester->getSemSecondBlockStartDate(),
    ));

echo $semester_json;