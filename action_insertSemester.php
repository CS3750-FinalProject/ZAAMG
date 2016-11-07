

<?php

include 'Semester.php';


$semYear = isset($_POST['semYear']) ? $_POST['semYear'] : "not entered";
$semSeason = isset($_POST['semSeason']) ? $_POST['semSeason'] : "not entered";
$semNumWeeks = isset($_POST['semNumWeeks']) ? $_POST['semNumWeeks'] : "not entered";
$semStart = isset($_POST['semStart']) ? $_POST['semStart'] : "not entered";
$firstBlockStart = isset($_POST['firstBlockStart']) ? $_POST['firstBlockStart'] : null;
$secondBlockStart = isset($_POST['secondBlockStart']) ? $_POST['secondBlockStart'] : null;

$semester = new Semester(NULL, $semYear, $semSeason, $semNumWeeks, $semStart, $firstBlockStart, $secondBlockStart);


echo<<<YO
Year: $semYear <br>
Season: $semSeason <br>
Number Weeks: $semNumWeeks <br>
Start Date: $semStart <br>
First Block Start Date: $firstBlockStart <br>
Second Block Start Date: $secondBlockStart <br>
YO;

$result = $semester->semesterExists($semYear, $semSeason);
echo $result;

if ($result == "does not exist"){
    $semester->insertNewSemester();
}





