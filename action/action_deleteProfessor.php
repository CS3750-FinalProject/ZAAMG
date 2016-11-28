<?php

require_once '../Database.php';
require_once '../Professor.php';


$profId = isset($_POST['profId']) ? $_POST['profId'] : "not entered";


$database = new Database();
$dbh = $database->getdbh();

$message = "";

$deleteStmt = $dbh->prepare(
    "  DELETE FROM ZAAMG.Professor
        WHERE prof_id = $profId
    "
);

try{
    $deleteStmt->execute();
    $message = "success";
}catch(Exception $e){
    $message = "action_deleteProfessor: ".$e->getMessage();
}

echo $message;