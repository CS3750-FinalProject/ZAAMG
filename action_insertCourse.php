<?php

include 'Course.php';



//$courseCode = isset($_POST['courseCode']) ? $_POST['courseCode'] : "not entered";
$coursePrefix = isset($_POST['coursePrefix']) ? $_POST['coursePrefix'] : "not entered";
$courseNumber = isset($_POST['courseNumber']) ? $_POST['courseNumber'] : "not entered";
$courseTitle = isset($_POST['courseTitle']) ? $_POST['courseTitle'] : "not entered";
$courseCap = isset($_POST['courseCap']) ? $_POST['courseCap'] : "not entered";
$courseCred = isset($_POST['courseCred']) ? $_POST['courseCred'] : "not entered";
$deptId = isset($_POST['deptId']) ? $_POST['deptId'] : "not entered";


$course = new Course(NULL, $coursePrefix, $courseNumber, $courseTitle, $courseCap, $courseCred, $deptId);

echo "deptId = ".$deptId;

$result = $course->courseExists($coursePrefix, $courseNumber, $deptId);
echo $result;

//echo "Last Insert Id =".$course->insertNewCourse();

if ($result == "does not exist"){
    $course->insertNewCourse();
}








/*
echo<<<YO

Course Title: $courseTitle <br>
Course Capacity: $courseCap <br>
Course Credits: $courseCred <br>
Department Id: $deptId <br>
YO;*/