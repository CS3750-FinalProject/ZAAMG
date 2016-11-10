<?php
require_once 'Database.php';
$database = new Database();

$body = "

<div class='col-xs-12'>
        <div class='page-header'>
          <h1>Sections <small>for Spring 2017</small></h1>
        </div>
     </div>
</div>

    <div class='container'>
      <div class='col-xs-12'>
        <table class='list-data'>
          <tr>
            <th colspan='3'>Course</th>
            <th>Professor</th>
            <th>Scheduled Time</th>
            <th>Location</th>
            <th>Actions</th>
          </tr>";






function addSection(Section $section){
    $row = "<tr class='{$section->getSectionID()}'>
            <td>{$section->getSectionProperty('course_prefix', 'Course', 'course_id', 'courseID')}</td>"
        ."<td>{$section->getSectionProperty('course_number', 'Course', 'course_id', 'courseID')}</td>"
        ."<td> <i>{$section->getSectionProperty('course_title', 'Course', 'course_id', 'courseID')}</i></td>
            <td>{$section->getSectionProperty('prof_first', 'Professor', 'prof_id', 'profID')}"."
                {$section->getSectionProperty('prof_last', 'Professor', 'prof_id', 'profID')}<br>
                <small><em>{$section->getSectionProperty('prof_email', 'Professor', 'prof_id', 'profID')}</em></small>
            </td>
            <td><strong>{$section->getDayString()}:</strong>"."
            {$section->getStartTime()} - {$section->getEndTime()}<br/>
            <small><em>{$section->getBlock()}</em></small></td>
            <td><strong>
                {$section->getSectionProperty_Join_3('building_code', 'Classroom', 'Building',
                'classroom_id', 'building_id', 'classroomID')}"."
                {$section->getSectionProperty('classroom_number', 'Classroom', 'classroom_id', 'classroomID')}
                </strong><br/>
                <small>
                {$section->getSectionProperty_Join_4('campus_name', 'Classroom', 'Building', 'Campus',
                'classroom_id', 'building_id', 'campus_id', 'classroomID')}
                </small></td>
                <td><img src='img/pencil.png' class='action-edit'/><img src='img/close.png' class='action-delete'></td>
           </tr>";
    return $row;
}




$allSections = $database->getAllSections(null);
foreach ($allSections as $section){
    $body .= addSection($section);
}
$body .= "</div>";


echo $body;
