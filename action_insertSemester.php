

<?php

include 'Semester.php';


if (isset($_POST['semYear'])) $semYear = $_POST['semYear'];
else $semYear = "(not entered)";

if (isset($_POST['semSeason'])) $semSeason = $_POST['semSeason'];
else $semSeason = "(not entered)";

if (isset($_POST['semWeeks'])) $semWeeks = $_POST['semWeeks'];
else $semWeeks = "(not entered)";

if (isset($_POST['semStart'])) $semStart = $_POST['semStart'];
else $semStart = "(not entered)";

if (isset($_POST['firstBlockStart'])) $firstBlockStart = $_POST['firstBlockStart'];
#else $firstBlockStart = NULL;
if ($firstBlockStart=="") {
    $firstBlockStart = NULL;
}

if (isset($_POST['secondBlockStart'])) $secondBlockStart = $_POST['secondBlockStart'];
if ($secondBlockStart=="") {
    $secondBlockStart = NULL;
}

$semester = new Semester(NULL, $semYear, $semSeason, $semWeeks, $semStart, $firstBlockStart, $secondBlockStart);


echo<<<YO
Year: $semYear <br>
Season: $semSeason <br>
Number Weeks: $semWeeks <br>
Start Date: $semStart <br>
First Block Start Date: $firstBlockStart <br>
Second Block Start Date: $secondBlockStart <br>
YO;

$semester->insertNewSemester();





