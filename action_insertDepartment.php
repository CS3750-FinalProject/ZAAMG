<?php

include 'Department.php';

$deptCode = isset($_POST['deptCode']) ? $_POST['deptCode'] : "not entered";
$deptName = isset($_POST['deptName']) ? $_POST['deptName'] : "not entered";

$department = new Department(NULL, $deptName, $deptCode);


echo<<<YO
Department Code: $deptCode <br>
Department Name: $deptName <br>
YO;


$result = $department->departmentExists($deptCode, $deptName);
echo $result;

if ($result == "does not exist"){
    $department->insertNewDepartment();
}


