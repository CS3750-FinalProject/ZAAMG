<!--

Just testing php/mysql wire-up.

-->

<?php

include 'Course.php';


if (isset($_POST['courseCode'])) $courseCode = $_POST['courseCode'];
else $courseCode = "(not entered)";

if (isset($_POST['courseTitle'])) $courseTitle = $_POST['courseTitle'];
else $courseTitle = "(not entered)";

if (isset($_POST['courseCap'])) $courseCap = $_POST['courseCap'];
else $courseCap = "(not entered)";

if (isset($_POST['courseCred'])) $courseCred = $_POST['courseCred'];
else $courseCred = "(not entered)";

if (isset($_POST['deptId'])) $deptId = $_POST['deptId'];
else $deptId = "(not entered)";



$course = new Course(NULL, $courseCode, $courseTitle, $courseCap, $courseCred, $deptId);


echo<<<YO
Course Code: $courseCode <br>
Course Title: $courseTitle <br>
Course Capacity: $courseCap <br>
Course Credits: $courseCred <br>
Department Id: $deptId <br>
YO;

$course->insertNewCourse();





