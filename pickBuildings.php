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

$campusId = isset($_POST['campusId']) ? $_POST['campusId'] : "not entered";


$selectBuilding = $database->getdbh()->prepare(
    'SELECT building_name, building_id
                            FROM ZAAMG.Building WHERE campus_id = '.$dbh->quote($campusId).'
                            ORDER BY building_name ASC');
$selectBuilding->execute();
$result = $selectBuilding->fetchAll();

echo json_encode($result);











