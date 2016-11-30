<?php

require_once '../Database.php';
require_once '../Department.php';


$deptId = isset($_POST['deptId']) ? $_POST['deptId'] : "not entered";
$deptCode = isset($_POST['deptCode']) ? $_POST['deptCode'] : "not entered";
$deptName = isset($_POST['deptName']) ? $_POST['deptName'] : "not entered";
$action = isset($_POST['action']) ? $_POST['action'] : "not entered";


$database = new Database();
$dbh = $database->getdbh();

$message = "";


if ($action == "update"){
    $updateStmt = $dbh->prepare(
        "UPDATE ZAAMG.Department
        SET dept_name  = {$dbh->quote($deptName)},
          dept_code = {$dbh->quote($deptCode)}
        WHERE dept_id = $deptId");

    try{
        $updateStmt->execute();
        $message = "success";
    }catch(Exception $e){
        $message = "action_updateDepartment: ".$e->getMessage();
        echo $message;
    }
}else if ($action == 'delete'){
    $deleteStmt = $dbh->prepare(
        "  DELETE FROM ZAAMG.Department
        WHERE dept_id = $deptId
    "
    );

    try{
        $deleteStmt->execute();
        $message = "success";
    }catch(Exception $e){
        $message = "action_deleteDepartment: ".$e->getMessage();
    }
}





