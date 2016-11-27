<?php

require_once 'Database.php';

$database = new Database();
$dbh = $database->getdbh();

$buildingId = isset($_POST['buildingId']) ? $_POST['buildingId'] : "not entered";

$classrooms = $database->getClassroomsInBuilding($buildingId);
$classrooms_json = [];



foreach($classrooms as $index=>$classroom){
    $sections = $database->getClassroomSections($classroom);
    $sections_json = [];
    foreach($sections as $section){
        array_push($sections_json,array(
            'pref'=>$section->getSectionProperty('course_prefix', 'Course', 'course_id', 'courseID'),
            'num'=>$section->getSectionProperty('course_number', 'Course', 'course_id', 'courseID'),
            'days'=>$section->getDayString(),
            'startTime'=>$section->getStartTime(),
            'endTime'=>$section->getEndTime()
        ));
    }
    $classrooms_json[$index] = array(
        'roomNumber'=> $classroom->getClassroomNum(),
        'sections'=>$sections_json
    );
}


echo json_encode($classrooms_json);


