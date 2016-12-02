<?php

require_once 'Database.php';
session_start();

$database = new Database();
$dbh = $database->getdbh();

$buildingId = isset($_POST['buildingId']) ? $_POST['buildingId'] : "not entered";

$classrooms = $database->getClassroomsInBuilding($buildingId);
$classrooms_json = [];



foreach($classrooms as $index=>$classroom){
    $sections = $database->getClassroomSections($classroom, $_SESSION['mainSemesterId']);
    $sections_json = [];
    foreach($sections as $section){
        array_push($sections_json,array(
            'pref'=>$section->getSectionProperty('course_prefix', 'Course', 'course_id', 'courseID'),
            'num'=>$section->getSectionProperty('course_number', 'Course', 'course_id', 'courseID'),
            'c_name'=>$section->getSectionProperty('course_title', 'Course', 'course_id', 'courseID'),
            'days'=>$section->getDayString_toUpper(),
            'startTime'=>$section->getStartTime(),
            'endTime'=>$section->getEndTime(),
            'campus'=>$section->getSectionProperty_Join_4('campus_name', 'Classroom', 'Building', 'Campus',
                'classroom_id', 'building_id', 'campus_id', 'classroomID'),
            'building'=>$section->getSectionProperty_Join_3('building_code', 'Classroom', 'Building',
                'classroom_id', 'building_id', 'classroomID'),
            'room'=>$section->getSectionProperty('classroom_number', 'Classroom', 'classroom_id', 'classroomID'),
            'profFirst'=>$section->getSectionProperty('prof_first', 'Professor', 'prof_id', 'profID'),
            'profLast'=>$section->getSectionProperty('prof_last', 'Professor', 'prof_id', 'profID')
        ));
    }
    $classrooms_json[$index] = array(
        'roomNumber'=> $classroom->getClassroomNum(),
        'sections'=>$sections_json
    );
}


echo json_encode($classrooms_json);


