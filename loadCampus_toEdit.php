<?php

require_once 'Database.php';
require_once 'Campus.php';

$database = new Database();
$dbh = $database->getdbh();


$campusId = isset($_POST['campusId']) ? $_POST['campusId'] : "not entered";

$campus = $database->getCampus($campusId);

try{
    $campus_json = json_encode(
        array(
            'id'=>$campus->getCampusID(),
            'name'=>$campus->getCampusName()
        ));
    echo $campus_json;
}catch (Error $e){
    echo "problem: ".$e->getMessage();

}


