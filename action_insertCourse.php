<?php

include 'Course.php';



$courseCode = isset($_POST['courseCode']) ? $_POST['courseCode'] : "not entered";
$courseTitle = isset($_POST['courseTitle']) ? $_POST['courseTitle'] : "not entered";
$courseCap = isset($_POST['courseCap']) ? $_POST['courseCap'] : "not entered";
$courseCred = isset($_POST['courseCred']) ? $_POST['courseCred'] : "not entered";
$deptId = isset($_POST['deptId']) ? $_POST['deptId'] : "not entered";


$course = new Course(NULL, $courseCode, $courseTitle, $courseCap, $courseCred, $deptId);


echo<<<YO
Course Code: $courseCode <br>
Course Title: $courseTitle <br>
Course Capacity: $courseCap <br>
Course Credits: $courseCred <br>
Department Id: $deptId <br>
YO;

$result = $course->courseExists($courseCode, $deptId);
echo $result;

//echo "Last Insert Id =".$course->insertNewCourse();

if ($result == "does not exist"){
    $course->insertNewCourse();
}



