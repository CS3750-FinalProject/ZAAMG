<?php

require_once '../Course.php';



$coursePrefix = isset($_POST['coursePrefix']) ? $_POST['coursePrefix'] : "not entered";
$courseNumber = isset($_POST['courseNumber']) ? $_POST['courseNumber'] : "not entered";
$courseTitle = isset($_POST['courseTitle']) ? $_POST['courseTitle'] : "not entered";
$courseCred = isset($_POST['courseCred']) ? $_POST['courseCred'] : "not entered";
$deptId = isset($_POST['deptId']) ? $_POST['deptId'] : "not entered";


$course = new Course(NULL, $coursePrefix, $courseNumber, $courseTitle,  $courseCred, $deptId);


$result = $course->courseExists($coursePrefix, $courseNumber, $deptId);


if ($result == "does not exist"){
    $course->insertNewCourse();
}








