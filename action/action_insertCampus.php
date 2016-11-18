

<?php

include '../Campus.php';

$campusName = isset($_POST['campusName']) ? $_POST['campusName'] : "not entered";


$campus = new Campus(NULL, $campusName);



$result = $campus->campusExists($campusName);
echo $result;

if ($result == "does not exist"){
    $campus->insertNewCampus();
}






