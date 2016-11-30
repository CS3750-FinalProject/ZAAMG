<?php

require_once '../Database.php';
require_once '../Professor.php';


$profId = isset($_POST['profId']) ? $_POST['profId'] : "not entered";
$first = isset($_POST['first']) ? $_POST['first'] : "not entered";
$last = isset($_POST['last']) ? $_POST['last'] : "not entered";
$email = isset($_POST['email']) ? $_POST['email'] : "not entered";
$dept = isset($_POST['dept']) ? $_POST['dept'] : "not entered";
$reqHrs = isset($_POST['reqHrs']) ? $_POST['reqHrs'] : "not entered";
$relHrs = isset($_POST['relHrs']) ? $_POST['relHrs'] : "not entered";
$action = isset($_POST['action']) ? $_POST['action'] : "not entered";


$database = new Database();
$dbh = $database->getdbh();

$message = "";

if ($action == 'update'){
    $updateStmt = $dbh->prepare(
        "  UPDATE W01143557.Professor
        SET prof_first        = '$first',
            prof_last         = '$last',
            prof_email        = '$email',
            dept_id           = $dept,
            prof_req_hours    = $reqHrs,
            prof_rel_hours    = $relHrs
        WHERE prof_id = $profId");
    try{
        $updateStmt->execute();
        $message = "success";
    }catch(Exception $e){
        $message = "action_updateProfessor: ".$e->getMessage();
        echo $message;
    }
}else if ($action == 'delete'){
    $deleteStmt = $dbh->prepare(
        "  DELETE FROM W01143557.Professor
        WHERE prof_id = $profId");

    try{
        $deleteStmt->execute();
        $message = "success";
    }catch(Exception $e){
        $message = "action_deleteProfessor: ".$e->getMessage();
    }
}


$selectProf = $dbh->prepare(
    'SELECT * FROM W01143557.Professor
      ORDER BY prof_last ASC');
$selectProf->execute();
$professors = $selectProf->fetchAll();
$professors_json = [];

foreach($professors as $professor){
    $professors_json[] = array(
        'id'=>$professor['prof_id'],
        'first'=>$professor['prof_first'],
        'last'=>$professor['prof_last'],
        'email'=>$professor['prof_email'],
        'dept'=>$professor['dept_id'],
        'req'=>$professor['prof_req_hours'],
        'rel'=>$professor['prof_rel_hours']
    );
};



echo json_encode($professors_json);  // to use in refreshing dropdown lists inside modals