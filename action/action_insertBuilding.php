<!--

Just testing php/mysql wire-up.

-->

<?php

include '../Building.php';

$buildCode = isset($_POST['buildCode']) ? $_POST['buildCode'] : "not entered";
$buildName = isset($_POST['buildName']) ? $_POST['buildName'] : "not entered";
$campusId = isset($_POST['campusId']) ? $_POST['campusId'] : "not entered";

$building = new Building(NULL, $buildCode, $buildName, $campusId);



$result = $building->buildingExists($buildName, $buildCode, $campusId);
echo $result;

if ($result == "does not exist"){
    $building->insertNewBuilding();
}



