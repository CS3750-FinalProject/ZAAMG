

<?php

include 'Department.php';


if (isset($_POST['deptCode'])) $deptCode = $_POST['deptCode'];
else $deptCode = "(not entered)";

if (isset($_POST['deptName'])) $deptName = $_POST['deptName'];
else $deptName = "(not entered)";



$department = new Department(NULL, $deptName, $deptCode);


echo<<<YO
Department Code: $deptCode <br>
Department Name: $deptName <br>
YO;

$department->insertNewDepartment();





