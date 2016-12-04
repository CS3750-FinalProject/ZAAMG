<?php
require_once '../Database.php';
session_start();

$database = new Database();
$dbh = $database->getdbh();

$checkCapacity = $dbh->prepare(
    "SELECT s.section_id, c.classroom_id, s.section_capacity, c.classroom_capacity
    FROM W01143557.Section s JOIN W01143557.Classroom c
    ON s.classroom_id = c.classroom_id
    AND s.section_capacity > c.classroom_capacity
    WHERE s.sem_id = :semId");
    $checkCapacity->bindValue(":semId", $_SESSION['mainSemesterId']);
try{
    $checkCapacity->execute();

    $capOvers = $checkCapacity->fetchAll();
    $capOvers_json = [];
    $secId_1 = 0;

    foreach($capOvers as $index=>$capOver){
        $capOvers_json[$index] = array(
            'secId'=>$capOver['section_id'],
            'roomId'=>$capOver['classroom_id'],
            'secCap'=>$capOver['section_capacity'],
            'roomCap'=>$capOver['classroom_capacity']
        );
    }
    echo json_encode($capOvers_json);
}catch(Exception $e){
    $message = "action_checkCapacity: ".$e->getMessage();
    echo $message;
}



