<?php

require_once '../Department.php';

$deptCode = isset($_POST['deptCode']) ? $_POST['deptCode'] : "not entered";
$deptName = isset($_POST['deptName']) ? $_POST['deptName'] : "not entered";

foreach ($_POST as $item){
    strip_tags($item);
}

$department = new Department(NULL, $deptName, $deptCode);

//$result = $department->departmentExists($deptCode, $deptName);
//echo $result;
if(assert("does not exist", $department->departmentExists($deptCode, $deptName))){
    $department->insertNewDepartment();
}
/*if ($result == "does not exist"){
    $department->insertNewDepartment();
}*/


