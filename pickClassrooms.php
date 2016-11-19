<?php
/**
 * Created by IntelliJ IDEA.
 * User: Gisela
 * Date: 11/19/2016
 * Time: 8:45 AM
 */

require_once 'Database.php';

$database = new Database();
$dbh = $database->getdbh();

$buildingId = isset($_POST['buildingId']) ? $_POST['buildingId'] : "not entered";


$selectClassroom = $database->getdbh()->prepare(
    'SELECT classroom_number, classroom_id
                            FROM ZAAMG.Classroom WHERE building_id = '.$dbh->quote($buildingId).'
                            ORDER BY classroom_number ASC');
$selectClassroom->execute();
$result = $selectClassroom->fetchAll();

echo json_encode($result);








