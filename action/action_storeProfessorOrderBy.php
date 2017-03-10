<?php
session_start();

$_SESSION['profIndex_orderBy'] = $_POST['profIndex_orderBy'];

echo $_SESSION['profIndex_orderBy'];