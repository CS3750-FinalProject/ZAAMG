

<?php

include '../Semester.php';


$semYear = isset($_POST['semYear']) ? $_POST['semYear'] : "not entered";
$semSeason = isset($_POST['semSeason']) ? $_POST['semSeason'] : "not entered";
$semNumWeeks = isset($_POST['semNumWeeks']) ? $_POST['semNumWeeks'] : "not entered";
$semStart = isset($_POST['semStart']) ? $_POST['semStart'] : "not entered";
$firstBlockStart = isset($_POST['firstBlockStart']) ? $_POST['firstBlockStart'] : null;
$secondBlockStart = isset($_POST['secondBlockStart']) ? $_POST['secondBlockStart'] : null;

foreach ($_POST as $item){
    strip_tags($item);
}

$semester = new Semester(NULL, $semYear, $semSeason, $semNumWeeks, $semStart, $firstBlockStart, $secondBlockStart);



$result = $semester->semesterExists($semYear, $semSeason);
echo $result;

if ($result == "does not exist"){
    $semester->insertNewSemester();
}





