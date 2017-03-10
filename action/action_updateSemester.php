<?php

require_once '../Database.php';
require_once '../Semester.php';


$semId = isset($_POST['semId']) ? $_POST['semId'] : "not entered";
$semYear = isset($_POST['semYear']) ? $_POST['semYear'] : "not entered";
$semSeason = isset($_POST['semSeason']) ? $_POST['semSeason'] : "not entered";
$semWeeks = isset($_POST['semWeeks']) ? $_POST['semWeeks'] : "not entered";
$semStart = isset($_POST['semStart']) ? $_POST['semStart'] : "not entered";
$semFirstStart = isset($_POST['semFirstStart']) ? $_POST['semFirstStart'] : "not entered";
$semSecondStart = isset($_POST['semSecondStart']) ? $_POST['semSecondStart'] : "not entered";

$action = isset($_POST['action']) ? $_POST['action'] : "not entered";

foreach ($_POST as $item){
    strip_tags($item);
}

$database = new Database();
$dbh = $database->getdbh();

$message = "";


if ($action == "update"){
    $updateStmt = $dbh->prepare(
        "UPDATE W01143557.Semester
        SET sem_year  = {$dbh->quote($semYear)},
            sem_season = {$dbh->quote($semSeason)},
            sem_num_weeks = {$dbh->quote($semWeeks)},
            sem_start_date = {$dbh->quote($semStart)},
            sem_first_block_start_date = {$dbh->quote($semFirstStart)},
            sem_second_block_start_date = {$dbh->quote($semSecondStart)}
        WHERE sem_id = $semId");

    try{
        $updateStmt->execute();
        $message = "success";
    }catch(Exception $e){
        $message = "action_updateSemester: ".$e->getMessage();
        echo $message;
    }
}else if ($action == 'delete'){
    $deleteStmt = $dbh->prepare(
        "  DELETE FROM W01143557.Semester
        WHERE sem_id = $semId");

    try{
        $deleteStmt->execute();
        $message = "success";
    }catch(Exception $e){
        $message = "action_deleteSemester: ".$e->getMessage();
    }
}





