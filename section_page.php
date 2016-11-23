<?php
require_once 'Database.php';
$database = new Database();

$body = "

<!--<script>
$(document).ready(function() {
    InlineEditing();
});
</script>-->

<script src='js/calendar.js' charset='utf-8'></script>

<div class='col-xs-12'>
        <div class='page-header'>
          <h1>Sections <small>for Spring 2017</small></h1>
        </div>
     </div>
</div>

    <div class='container'>
      <div class='col-xs-12' id='sectionIndex'>
        <table class='list-data'>
          <tr>
            <th colspan='3'>Course</th>
            <th>Professor</th>
            <th>Scheduled Time</th>
            <th>Location</th>
            <th>Actions</th>
          </tr>";




$allSections = $database->getAllSections(null);
foreach ($allSections as $section){
    $body .= addSection($section);
}

$body .= "</table>";
$body .= "</div>";



echo $body;

//<tr> (section record)     id = record_sec<#>
//<img> (pencil)            id = pencil_sec<#>
//<span> (little arrow):    id = seeCal_sec<#>
//<tr>  (editing div)       id = edit_sec<#>
//<img> (save disc)         id = save_sec<#>


function addSection(Section $section){
    $row = "<tr id='record_sec{$section->getSectionID()}'>
            <td>{$section->getSectionProperty('course_prefix', 'Course', 'course_id', 'courseID')}</td>"
        ."<td>{$section->getSectionProperty('course_number', 'Course', 'course_id', 'courseID')}</td>"
        ."<td> <i>{$section->getSectionProperty('course_title', 'Course', 'course_id', 'courseID')}</i></td>
            <td>{$section->getSectionProperty('prof_first', 'Professor', 'prof_id', 'profID')}"."
                {$section->getSectionProperty('prof_last', 'Professor', 'prof_id', 'profID')}<br>
                <small><em>{$section->getSectionProperty('prof_email', 'Professor', 'prof_id', 'profID')}</em></small>
            </td>";
                if ($section->getDayString() == ''){
                    $row .= "<td><strong>Online</strong><br/>";
                }else{
                    $row .= "<td><strong>{$section->getDayString()}:</strong>"."
                    {$section->getStartTime()} - {$section->getEndTime()}<br/>";
                }
        $row .= "
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
                <td>
                <img src='img/pencil.png' class='action-edit' id='pencil_sec{$section->getSectionID()}' />
                <img src='img/close.png' class='action-delete'></td>
           </tr>


            <tr class='hide' id='edit_sec{$section->getSectionID()}'>
            <td colspan='3'>
              <p><strong> Editing: </strong>CS 1030 <em>Foundations of Computer Science</em></p>
              <label for='sectionProfessor'>Professor</label>
              <select class='form-control' id='sectionProfessor' >
                <option value=''''>Please Select...</option>
                <option value='1'>Spencer Hilton</option>
                <option value='2'>Garth Tuck</option>
                <option value='3'>Joshua Jensen</option>
                <option value='4'>Brian Rague</option>
              </select>
            </td>
            <td>
              <label class='checkbox-inline'><input type='checkbox' value='m'>M</label>
              <label class='checkbox-inline'><input type='checkbox' value='t'>T</label>
              <label class='checkbox-inline'><input type='checkbox' value='w'>W</label>
              <label class='checkbox-inline'><input type='checkbox' value='r'>R</label>
              <label class='checkbox-inline'><input type='checkbox' value='f'>F</label>
              <label class='checkbox-inline'><input type='checkbox' value='s'>S</label>
              <br>
              <br>
              <label for='startTime'>Start Time<input type='time' id='startTime'  class='form-control'></label>
              <label for='endTime'>End Time<input type='time' id='endTime'  class='form-control'></label>
              <br />
              <label for='block'>Block:</label>
              <br>
              <label class='radio-inline'><input type='radio' name='full'>Full</label>
              <label class='radio-inline'><input type='radio' name='first'>First</label>
              <label class='radio-inline'><input type='radio' name='second'>Second</label>
            </td>
            <td colspan='2'>
              <div class='col-xs-12'>
                <label class='radio-inline'><input type='radio' name='ogden'>Ogden</label>
                <label class='radio-inline'><input type='radio' name='davis'>Davis</label>
                <label class='radio-inline'><input type='radio' name='slcc'>SLCC</label>
                <label class='radio-inline'><input type='radio' name='online'>Online</label>
                <br />
                <label for='sectionBuilding'>Building</label>
                <select class='form-control' id='sectionBuilding' >
                  <option value=''''>Please Select...</option>
                  <option value='1'>Blah</option>
                  <option value='2'>Bleh</option>
                  <option value='3'>Yo!</option>
                  <option value='4'>No</option>
                </select>
                <label for='sectionClassroom'>Classroom</label>
                <select class='form-control' id='sectionClassroom' >
                  <option value=''''>Please Select...</option>
                  <option value='1'>Blah</option>
                  <option value='2'>Bleh</option>
                  <option value='3'>Yo!</option>
                  <option value='4'>No</option>
                </select>
              </div>
            </td>
            <td><img src='img/save.png' width='30px' class='action-save hide' id='save_sec{$section->getSectionID()}'/></td>
</tr>
<tr class='hide' id='hiddenRow_sec{$section->getSectionID()}'></tr>



           ";
    return $row;
}