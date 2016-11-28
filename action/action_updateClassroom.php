<?php

require_once '../Database.php';
require_once '../Classroom.php';


$roomId = isset($_POST['roomId']) ? $_POST['roomId'] : "not entered";
$building = isset($_POST['building']) ? $_POST['building'] : "not entered";
$number = isset($_POST['number']) ? $_POST['number'] : "not entered";
$cap = isset($_POST['cap']) ? $_POST['cap'] : "not entered";
$computers = isset($_POST['computers']) ? $_POST['computers'] : "not entered";

$database = new Database();
$dbh = $database->getdbh();

$message = "";

$updateStmt = $dbh->prepare(
    "  UPDATE ZAAMG.Classroom
        SET classroom_id            = $roomId,
            classroom_number        = $number,
            classroom_capacity      = $cap,
            classroom_workstations  = $computers,
            building_id             = $building
        WHERE classroom_id = $roomId
    "
);

try{
    $updateStmt->execute();
    $message = "success";
}catch(Exception $e){
    $message = "action_updateClassroom: ".$e->getMessage();
}

echo $message;