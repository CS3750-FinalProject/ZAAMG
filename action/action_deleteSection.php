<?php

require_once '../Database.php';
require_once '../Section.php';


$sectionId = isset($_POST['sectionId']) ? $_POST['sectionId'] : "not entered";


$database = new Database();
$dbh = $database->getdbh();

$message = "";

$deleteStmt = $dbh->prepare(
    "  DELETE FROM W01143557.Section
        WHERE section_id = $sectionId
    ");

try{
    $deleteStmt->execute();
    $message = "success";
}catch(Exception $e){
    $message = "action_deleteSection: ".$e->getMessage();
}

echo $message;