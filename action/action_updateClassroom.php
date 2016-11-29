<?php

require_once '../Database.php';
require_once '../Classroom.php';


$roomId = isset($_POST['roomId']) ? $_POST['roomId'] : "not entered";
$building = isset($_POST['building']) ? $_POST['building'] : "not entered";
$number = isset($_POST['number']) ? $_POST['number'] : "not entered";
$cap = isset($_POST['cap']) ? $_POST['cap'] : "not entered";
$computers = isset($_POST['computers']) ? $_POST['computers'] : "not entered";
$action = isset($_POST['action']) ? $_POST['action'] : "not entered";

$database = new Database();
$dbh = $database->getdbh();

$message = "";


if ($action == "update"){
    $updateStmt = $dbh->prepare(
        "  UPDATE ZAAMG.Classroom
        SET classroom_id            = $roomId,
            classroom_number        = $number,
            classroom_capacity      = $cap,
            classroom_workstations  = $computers,
            building_id             = $building
        WHERE classroom_id = $roomId
    ");

    try{
        $updateStmt->execute();
        $message = "success";
    }catch(Exception $e){
        $message = "action_updateClassroom: ".$e->getMessage();
    }
}else if ($action == 'delete'){
    $deleteStmt = $dbh->prepare(
        "  DELETE FROM ZAAMG.Classroom
        WHERE classroom_id = $roomId
    "
    );

    try{
        $deleteStmt->execute();
        $message = "success";
    }catch(Exception $e){
        $message = "action_deleteClassroom: ".$e->getMessage();
    }
}


$selectRoom = $dbh->prepare(
    "SELECT * FROM ZAAMG.Classroom");
$selectRoom->execute();
$classrooms = $selectRoom->fetchAll();
$classrooms_json = [];

foreach($classrooms as $classroom){
    $classrooms_json[] = array(
        'id'=>$classroom['classroom_id'],
        'number'=>$classroom['classroom_number'],
        'cap'=>$classroom['classroom_capacity'],
        'computers'=>$classroom['classroom_workstations'],
        'building'=>$classroom['building_id']
    );
};



echo json_encode($classrooms_json);  // to use in refreshing dropdown lists inside modals
