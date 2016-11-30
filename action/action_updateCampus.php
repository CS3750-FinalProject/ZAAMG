<?php

require_once '../Database.php';
require_once '../Campus.php';


$campusId = isset($_POST['campusId']) ? $_POST['campusId'] : "not entered";
$campusName = isset($_POST['campusName']) ? $_POST['campusName'] : "not entered";
$action = isset($_POST['action']) ? $_POST['action'] : "not entered";


$database = new Database();
$dbh = $database->getdbh();

$message = "";


if ($action == "update"){
    $updateStmt = $dbh->prepare(
        "UPDATE ZAAMG.Campus
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
        "  DELETE FROM ZAAMG.Campus
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





