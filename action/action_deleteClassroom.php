<?php

require_once '../Database.php';
require_once '../Classroom.php';


$roomId = isset($_POST['roomId']) ? $_POST['roomId'] : "not entered";


$database = new Database();
$dbh = $database->getdbh();

$message = "";

$deleteStmt = $dbh->prepare(
    "  DELETE FROM W01143557.Classroom
        WHERE classroom_id = $roomId
    "
);

try{
    $deleteStmt->execute();
    $message = "success";
}catch(Exception $e){
    $message = "action_deleteClassroom: ".$e->getMessage();
}

echo $message;