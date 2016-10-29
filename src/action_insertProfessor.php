

<?php

include 'Professor.php';


if (isset($_POST['profFirst'])) $profFirst = $_POST['profFirst'];
else $profFirst = "(not entered)";

if (isset($_POST['profLast'])) $profLast = $_POST['profLast'];
else $profLast = "(not entered)";

if (isset($_POST['profEmail'])) $profEmail = $_POST['profEmail'];
else $profEmail = "(not entered)";

if (isset($_POST['deptId'])) $deptId = $_POST['deptId'];
else $deptId = "(not entered)";



$professor = new Professor(NULL, $profFirst, $profLast, $profEmail, $deptId);


echo<<<YO
Professor First: $profFirst <br>
Professor Last: $profLast <br>
Professor Email: $profEmail <br>
Department Id: $deptId <br>
YO;

$professor->insertNewProfessor();





