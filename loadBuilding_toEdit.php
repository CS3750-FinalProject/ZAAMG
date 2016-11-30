<?php

require_once 'Database.php';
require_once 'Building.php';

$database = new Database();
$dbh = $database->getdbh();


$buildingId = isset($_POST['buildingId']) ? $_POST['buildingId'] : "not entered";


$building = $database->getBuilding($buildingId);


$building_json = json_encode(
    array(
        'id'=>$building->getBuildingID(),
        'code'=>$building->getBuildingCode(),
        'name'=>$building->getBuildingName(),
        'campus'=>$building->getCampusID()
    ));

echo $building_json;