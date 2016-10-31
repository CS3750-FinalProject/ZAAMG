

<?php

include 'Campus.php';


if (isset($_POST['campusName'])) $campusName = $_POST['campusName'];
else $campusName = "(not entered)";





$campus = new Campus(NULL, $campusName);


echo<<<YO
Campus Name: $campusName <br>
YO;

$campus->insertNewCampus();





