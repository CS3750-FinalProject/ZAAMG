<?php

require_once '../Database.php';
require_once '../Campus.php';


$campusId = isset($_POST['campusId']) ? $_POST['campusId'] : "not entered";
$campusName = isset($_POST['campusName']) ? $_POST['campusName'] : "not entered";
$action = isset($_POST['action']) ? $_POST['action'] : "not entered";

foreach ($_POST as $item){
    strip_tags($item);
}

$database = new Database();
$dbh = $database->getdbh();

$message = "";


if ($action == "update"){
    $updateStmt = $dbh->prepare(
        "UPDATE W01143557.Campus
        SET campus_name  = {$dbh->quote($campusName)}
        WHERE campus_id = $campusId");

    try{
        $updateStmt->execute();
        $message = "success";
    }catch(Exception $e){
        $message = "action_updateCampus: ".$e->getMessage();
        echo $message;
    }
}else if ($action == 'delete'){
    $deleteStmt = $dbh->prepare(
        "  DELETE FROM W01143557.Campus
        WHERE campus_id = $campusId
    "
    );

    try{
        $deleteStmt->execute();
        $message = "success";
    }catch(Exception $e){
        $message = "action_deleteCampus: ".$e->getMessage();
    }
}





