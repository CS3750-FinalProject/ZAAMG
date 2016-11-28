<?php

require_once '../Database.php';
require_once '../Professor.php';


$profId = isset($_POST['profId']) ? $_POST['profId'] : "not entered";
$first = isset($_POST['first']) ? $_POST['first'] : "not entered";
$last = isset($_POST['last']) ? $_POST['last'] : "not entered";
$email = isset($_POST['email']) ? $_POST['email'] : "not entered";
$dept = isset($_POST['dept']) ? $_POST['dept'] : "not entered";
$reqHrs = isset($_POST['reqHrs']) ? $_POST['reqHrs'] : "not entered";
$relHrs = isset($_POST['relHrs']) ? $_POST['relHrs'] : "not entered";

$database = new Database();
$dbh = $database->getdbh();

$message = "";

$updateStmt = $dbh->prepare(
    "  UPDATE ZAAMG.Professor
        SET prof_id           = $profId,
            prof_first        = '$first',
            prof_last         = '$last',
            prof_email        = '$email',
            dept_id           = $dept,
            prof_req_hours    = $reqHrs,
            prof_rel_hours    = $relHrs
        WHERE prof_id = $profId
    "
);

try{
    $updateStmt->execute();
    $message = "success";
}catch(Exception $e){
    $message = "action_updateProfessor: ".$e->getMessage();
}

echo $message;