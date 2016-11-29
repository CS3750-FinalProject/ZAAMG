<?php

require_once '../Database.php';
require_once '../Professor.php';


$profId = isset($_POST['profId']) ? $_POST['profId'] : "not entered";


$database = new Database();
$dbh = $database->getdbh();

$message = "";

$deleteStmt = $dbh->prepare(
    "  DELETE FROM ZAAMG.Professor
        WHERE prof_id = $profId
    "
);

try{
    $deleteStmt->execute();
    $message = "success";
}catch(Exception $e){
    $message = "action_deleteProfessor: ".$e->getMessage();
}


$selectProf = $dbh->prepare(
    'SELECT prof_id, prof_first, prof_last FROM ZAAMG.Professor
                                  ORDER BY prof_last ASC');
$selectProf->execute();
$professors = $selectProf->fetchAll();
$professors_json = [];

foreach($professors as $professor){
    $professors_json[] = array(
        'id'=>$professor['prof_id'],
        'first'=>$professor['prof_first'],
        'last'=>$professor['prof_last'],
    );
}

echo json_encode($professors_json);  // to use in refreshing dropdown lists inside modals