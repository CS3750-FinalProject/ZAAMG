<!--

Just testing php/mysql wire-up.

-->

<?php

include 'Classroom.php';


if (isset($_POST['classNum'])) $classNum = $_POST['classNum'];
else $classNum = "(not entered)";

if (isset($_POST['classCapacity'])) $classCap = $_POST['classCapacity'];
else $classCap = "(not entered)";

if (isset($_POST['buildId'])) $buildId = $_POST['buildId'];
else $buildId = "(not entered)";

$classroom = new Classroom(NULL, $classNum, $classCap, $buildId);


echo<<<YO
Classroom Number: $classNum <br>
Classroom Capacity: $classCap <br>
Building Id: $buildId <br>
YO;

$classroom->insertNewClassroom();





