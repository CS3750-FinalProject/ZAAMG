<?php

include 'Classroom.php';

$classNum = isset($_POST['classNum']) ? $_POST['classNum'] : "not entered";
$classCapacity = isset($_POST['classCapacity']) ? $_POST['classCapacity'] : "not entered";
$numWorkstations = isset($_POST['roomWorkstations']) ? $_POST['roomWorkstations'] : "not entered";
$buildId = isset($_POST['buildId']) ? $_POST['buildId'] : "not entered";


$classroom = new Classroom(NULL, $classNum, $classCapacity, $numWorkstations, $buildId);


echo<<<YO
Classroom Number: $classNum <br>
Classroom Capacity: $classCapacity <br>
Building Id: $buildId <br>
YO;

$result = $classroom->classroomExists($classNum, $buildId);
echo $result;

if ($result == "does not exist"){
    $classroom->insertNewClassroom();
}





