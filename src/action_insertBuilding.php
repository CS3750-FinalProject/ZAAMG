<!--

Just testing php/mysql wire-up.

-->

<?php

include 'Building_mod1.php';


if (isset($_POST['buildCode'])) $buildCode = $_POST['buildCode'];
else $buildCode = "(not entered)";

if (isset($_POST['buildName'])) $buildName = $_POST['buildName'];
else $buildName = "(not entered)";

if (isset($_POST['campusID'])) $campusID = $_POST['campusID'];
else $campusID = "(not entered)";

$building = new Building(NULL, $buildCode, $buildName, $campusID);


echo<<<YO
Building Code: $buildCode <br>
Building Name: $buildName <br>
Campus Id: $campusID <br>
YO;

$building->insertNewBuilding();




# echo $building->getBuildingName();  (just testing syntax)



