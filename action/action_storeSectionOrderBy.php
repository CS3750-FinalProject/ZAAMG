<?php
session_start();

$_SESSION['sectionIndex_orderBy'] = $_POST['sectionIndex_orderBy'];

echo $_SESSION['sectionIndex_orderBy'];