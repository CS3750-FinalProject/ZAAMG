

<?php

include 'Professor.php';

$profFirst = isset($_POST['profFirst']) ? $_POST['profFirst'] : "not entered";
$profLast = isset($_POST['profLast']) ? $_POST['profLast'] : "not entered";
$profEmail = isset($_POST['profEmail']) ? $_POST['profEmail'] : "not entered";
$profReqHours = isset($_POST['profReqHours']) ? $_POST['profReqHours'] : "not entered";
$profOverHours = isset($_POST['profOverHours']) ? $_POST['profOverHours'] : "not entered";
$profRelHours = isset($_POST['profRelHours']) ? $_POST['profRelHours'] : "not entered";
$deptId = isset($_POST['deptId']) ? $_POST['deptId'] : "not entered";


$professor = new Professor(NULL, $profFirst, $profLast, $profEmail,
    $profReqHours, $profOverHours, $profRelHours, $deptId);


echo<<<YO
Professor First: $profFirst <br>
Professor Last: $profLast <br>
Professor Email: $profEmail <br>
Department Id: $deptId <br>
YO;




$result = $professor->professorExists($profEmail);
echo $result;

if ($result == "does not exist"){
    $professor->insertNewProfessor();
}





