<?php
session_start();

$_SESSION['mainSemesterId'] = $_POST['mainSemesterId'];
$_SESSION['mainSemesterLabel'] = $_POST['mainSemesterLabel'];

echo $_SESSION['mainSemesterId'];