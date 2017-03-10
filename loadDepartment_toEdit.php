<?php

require_once 'Database.php';
require_once 'Department.php';

$database = new Database();
$dbh = $database->getdbh();


$deptId = isset($_POST['deptId']) ? $_POST['deptId'] : "not entered";


$department = $database->getDepartment($deptId);


$department_json = json_encode(
    array(
        'id'=>$department->getDepartmentID(),
        'code'=>$department->getDepartmentCode(),
        'name'=>$department->getDepartmentName()
    ));

echo $department_json;