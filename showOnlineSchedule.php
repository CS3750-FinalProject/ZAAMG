<?php
//https://jonsuh.com/blog/convert-loop-through-json-php-javascript-arrays-objects/

require_once 'Database.php';
$database = new Database();
session_start();

$onlineSections = $database->getOnlineSections($_SESSION['mainSemesterId']);
$onlineSections_json = [];

foreach($onlineSections as $section){

    $onlineSections_json[] = array(
        'prefix'=>$section->getSectionProperty('course_prefix', 'Course', 'course_id', 'courseID'),
        'number'=>$section->getSectionProperty('course_number', 'Course', 'course_id', 'courseID'),
        'title'=>$section->getSectionProperty('course_title', 'Course', 'course_id', 'courseID'),
        'profFirst'=>$section->getSectionProperty('prof_first', 'Professor', 'prof_id', 'profID'),
        'profLast'=>$section->getSectionProperty('prof_last', 'Professor', 'prof_id', 'profID')
    );
}

echo json_encode($onlineSections_json);