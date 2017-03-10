

<?php

require_once '../Campus.php';

$campusName = isset($_POST['campusName']) ? $_POST['campusName'] : "not entered";


$campus = new Campus(NULL, $campusName);

foreach ($_POST as $item){
    strip_tags($item);
}

$result = $campus->campusExists($campusName);
echo $result;

if ($result == "does not exist"){
    try{
        $campus->insertNewCampus();
    }catch (Exception $e){
        echo "insertNewCampus: " + $e->getMessage();
    }
}






