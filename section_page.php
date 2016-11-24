<?php
require_once 'Database.php';
$database = new Database();

$body = "



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
    $body .= addSection($section, $database);
}

$body .= "</table>";
$body .= "</div>";



echo $body;

//<tr> (section record)     id = record_sectiont<#>  //if it doesn't end in _sect# then it won't toggle 'hide'
                                                     //also 'sectiont' is correct, the t is there on purpose to match last letter of 'sect'
//<img> (pencil)            id = pencil_sect<#>
//<span> (little arrow):    id = seeCal_sect<#>
//<tr>  (editing div)       id = edit_sect<#>
//<img> (save disc)         id = save_sect<#>


function addSection(Section $section, $database){

      $row = "<tr id='record_sectiont{$section->getSectionID()}'>
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
                <img src='img/pencil.png' class='action-edit' id='pencil_sect{$section->getSectionID()}' />
                <img src='img/close.png' class='action-delete'/></td>
           </tr>


            <tr class='hide' id='edit_sect{$section->getSectionID()}'>
            <td style='padding-bottom: 4%; padding-left: 1%' colspan='3'>

            <label for='inlineEdit_sectCourse{$section->getSectionID()}' >Course</label>
                        <select class='form-control' id='inlineEdit_sectCourse{$section->getSectionID()}' style='margin-bottom: 10px'>";

                            $selectCourse = $database->getdbh()->prepare(
                                'SELECT course_id, course_prefix, course_number, course_title FROM ZAAMG.Course
                                  ORDER BY course_prefix, course_number');
                            $selectCourse->execute();
                            $result = $selectCourse->fetchAll(PDO::FETCH_ASSOC);

                            foreach($result as $course){
                                if ($course['course_id'] == $section->getCourseID()){
                                    $row .= '<option selected value='.$course['course_id']
                                        .'>';
                                }else{
                                    $row .= '<option value='.$course['course_id']
                                        .'>';
                                }
                                $row .= $course['course_prefix']
                                    .' '.$course['course_number']
                                    .' '.$course['course_title']
                                    .'</option>';
                            }
                        $row .= "</select>

            <label for='inlineEdit_sectProf{$section->getSectionID()}'>Professor</label>
                        <select  class='form-control' id='inlineEdit_sectProf{$section->getSectionID()}' style='margin-bottom: 10px'>";

                            $selectProf = $database->getdbh()->prepare(
                                'SELECT prof_id, prof_first, prof_last FROM ZAAMG.Professor
                                  ORDER BY prof_last ASC');
                            $selectProf->execute();
                            $result = $selectProf->fetchAll();

                            foreach($result as $prof){
                                if ($prof['prof_id'] == $section->getProfID()){
                                    $row .='<option selected value='.$prof['prof_id']
                                        .'>';
                                }else{
                                    $row .='<option value='.$prof['prof_id']
                                        .'>';
                                }
                                $row .=$prof['prof_last'].', '.$prof['prof_first']
                                    .'</option>';
                            }

                        $row .= "</select>

                <label for='inlineEdit_sectRoom{$section->getSectionID()}'>Classroom</label>
                        <select class='form-control' style='margin-bottom: 10px' id='inlineEdit_sectRoom{$section->getSectionID()}'>
                            <option value='0'>Online</option>";

                            $selectRoom = $database->getdbh()->prepare(
                                "SELECT classroom_id, campus_name, building_name, classroom_number
                                  FROM ZAAMG.Campus c JOIN ZAAMG.Building b
                                  ON c.campus_id = b.campus_id
                                  JOIN ZAAMG.Classroom r
                                  ON b.building_id = r.building_id
                                  ORDER BY campus_name ASC");
                            $selectRoom->execute();
                            $result = $selectRoom->fetchAll(PDO::FETCH_ASSOC);

                            foreach($result as $room){
                                if ($room['classroom_id'] == $section->getClassroomID()){
                                    $row .= '<option selected value='.$room['classroom_id'].'>';
                                }else{
                                    $row .= '<option value='.$room['classroom_id'].'>';
                                }
                                $row .= $room['campus_name'].', '
                                    .$room['building_name'].': '
                                    .$room['classroom_number']
                                    .'</option>';
                            }
        $row .= "
                        </select>
            </td>
            <td  style='padding-left: 1%'>

                <label for='inlineEdit_sectDays{$section->getSectionID()}'>Days</label>
                        <select multiple  class='form-control' style='margin-bottom: 10px'
                             id='inlineEdit_sectDays{$section->getSectionID()}'>
                            <option value='online'>Online</option>
                            <option value='Monday'>Monday</option>
                            <option value='Tuesday'>Tuesday</option>
                            <option value='Wednesday'>Wednesday</option>
                            <option value='Thursday'>Thursday</option>
                            <option value='Friday'>Friday</option>
                            <option value='Saturday'>Saturday</option>
                        </select>

               <label  for='startTime'>Start Time</label>
               <input type='time' id='startTime'
                            style='margin-bottom: 10px' class='form-control'>
              <label for='endTime'>End Time</label><input type='time' id='endTime'  class='form-control'>
        </td>
        <td  style='padding-bottom: 1%; padding-left: 1%'>
                <label for='inlineEdit_sectSem{$section->getSectionID()}'>Semester</label>
                        <select class='form-control' style='margin-bottom: 10px' id='inlineEdit_sectSem{$section->getSectionID()}'>";

                            $selectSem = $database->getdbh()->prepare(
                                'SELECT sem_id, sem_season, sem_year, sem_start_date
                                  FROM ZAAMG.Semester
                                  ORDER BY sem_start_date DESC');
                            $selectSem->execute();
                            $result = $selectSem->fetchAll(PDO::FETCH_ASSOC);

                            foreach($result as $sem){
                                if ($sem['sem_id'] == $section->getSemester()){
                                    $row .= '<option selected value='.$sem['sem_id'].'>';
                                }else{
                                    $row .= '<option value='.$sem['sem_id'].'>';
                                }
                                $row .=$sem['sem_year'].' '
                                .$sem['sem_season']
                                .'</option>';
                            }
                            $row .= "
                        </select>

                        <label for='inlineEdit_sectBlock{$section->getSectionID()}'>Block</label>
                        <select class='form-control' style='margin-bottom: 10px' id='inlineEdit_sectBlock{$section->getSectionID()}'>
                            <option value='0'>Full</option>
                            <option value='1'>First</option>
                            <option value='2'>Second</option>
                        </select>


                        <label for='inlineEdit_sectCap{$section->getSectionID()}'>Capacity</label>
                        <input style='width: 55%; margin-bottom: 20px' type='number' class='form-control'
                                id='inlineEdit_sectCap{$section->getSectionID()}' min='1' value='{$section->getCapacity()}' >

                        <div style='width: 55%; margin-left: 2%'>
                        <label class='checkbox-inline' for='inlineEdit_sectOnl{$section->getSectionID()}' style='font-weight: bold; '>

                        <input type='checkbox' id='inlineEdit_sectOnl{$section->getSectionID()}'
                            value='1' style='transform: scale(1.5); '>
                            &nbsp;&nbsp;&nbsp;Online</label>
                        </div>


            </td>
            <td></td>
            <td><img src='img/save.png' width='30px' class='action-save hide' id='save_sect{$section->getSectionID()}'/></td>
</tr>
<tr class='hide' id='hiddenRow_sect{$section->getSectionID()}'></tr>



           ";
    return $row;
}