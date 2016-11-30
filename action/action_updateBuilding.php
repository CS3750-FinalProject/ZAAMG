<?php

require_once '../Database.php';
require_once '../Building.php';


$buildingId = isset($_POST['buildingId']) ? $_POST['buildingId'] : "not entered";
$buildingCode = isset($_POST['buildingCode']) ? $_POST['buildingCode'] : "not entered";
$buildingName = isset($_POST['buildingName']) ? $_POST['buildingName'] : "not entered";
$campusId = isset($_POST['campusId']) ? $_POST['campusId'] : "not entered";

$action = isset($_POST['action']) ? $_POST['action'] : "not entered";


$database = new Database();
$dbh = $database->getdbh();

$message = "";


if ($action == "update"){
    $updateStmt = $dbh->prepare(
        "UPDATE ZAAMG.Building
        SET building_code  = {$dbh->quote($buildingCode)},
            building_name = {$dbh->quote($buildingName)},
            campus_id = {$dbh->quote($campusId)}
        WHERE building_id = $buildingId");

    try{
        $updateStmt->execute();
        $message = "success";
    }catch(Exception $e){
        $message = "action_updateBuilding: ".$e->getMessage();
        echo $message;
    }
}else if ($action == 'delete'){
    $deleteStmt = $dbh->prepare(
        "  DELETE FROM ZAAMG.Building
        WHERE building_id = $buildingId");

    try{
        $deleteStmt->execute();
        $message = "success";
    }catch(Exception $e){
        $message = "action_deleteBuilding: ".$e->getMessage();
    }
}





