<?php
require_once 'Database.php';
$database = new Database();
session_start();

$mainSemesterId = 2;
$mainSemesterLabel= 'Spring 2017';
$orderBy = "course";

if (isset($_SESSION['mainSemesterId'])){
    $mainSemesterId = $_SESSION['mainSemesterId'];
}
if (isset($_SESSION['mainSemesterLabel'])){
    $mainSemesterLabel = $_SESSION['mainSemesterLabel'];
}

if (isset($_SESSION['sectionIndex_orderBy'])){
    $orderBy = $_SESSION['sectionIndex_orderBy'];
}

$body = "

<script src='js/calendar.js' charset='utf-8'></script>
<script>
function getExcelDoc(){
$.ajax({
    url : 'action/action_getExcelDoc.php',
    success : function(data) {
        window.open('action/action_getExcelDoc.php','_blank' );
    },
    error : function(error)
    {
        alert('Error: ' + JSON.stringify(error));
    }
});
}

var ajax_orderBy = function(orderBy){
    $.ajax({
        type: 'POST',
        url: 'action/action_storeSectionOrderBy.php',
        data: 'sectionIndex_orderBy=' + orderBy,
        success: function(data){
            console.log(data);
            loadPhpPage('section_page.php');
        },
        error: function(data){
            console.log(data);
        }
    });
}

    //check for classroom conflicts here:
    $.ajax({
        url: 'action/action_checkConflicts_classroom.php',
        dataType: 'json',
        success: function(conflicts) {
            if (Object.keys(conflicts).length > 0){
                var secIds = [];
                var roomIds = [];

                for (var key in conflicts){
                    if (roomIds.indexOf(conflicts[key].roomId) == -1){
                        roomIds.push(conflicts[key].roomId);
                    }
                    if (secIds.indexOf(conflicts[key].secId_1) == -1){
                        secIds.push(conflicts[key].secId_1);
                    }
                    if (secIds.indexOf(conflicts[key].secId_2) == -1){
                        secIds.push(conflicts[key].secId_2);
                    }
                }

                secIds.forEach(function(id) {
                    $('tr#' + 'record_sectiont' + id).find('span.glyphicon-alert').removeClass('hide')
                        .attr('title', 'Classroom Conflict');
                    console.log(\"secId: \" + id);
                });
            }
        },
        error: function(msg){
            console.log(\"checkConflicts ajax error: \" +  JSON.stringify(msg));
        }
    });


    //check for professor conflicts here:
    $.ajax({
        url: 'action/action_checkConflicts_professor.php',
        dataType: 'json',
        success: function(conflicts) {
            if (Object.keys(conflicts).length > 0){
                var secIds = [];
                var profIds = [];

                for (var key in conflicts){
                    if (profIds.indexOf(conflicts[key].profId) == -1){
                        profIds.push(conflicts[key].profId);
                    }
                    if (secIds.indexOf(conflicts[key].secId_1) == -1){
                        secIds.push(conflicts[key].secId_1);
                    }
                    if (secIds.indexOf(conflicts[key].secId_2) == -1){
                        secIds.push(conflicts[key].secId_2);
                    }
                }

                secIds.forEach(function(id) {
                    $('#' + 'record_sectiont' + id).find('span.glyphicon-alert').removeClass('hide')
                        .attr('title', 'Professor Conflict').css('color','#ef0946');
                });
            }
        },
        error: function(msg){
            console.log(\"checkConflicts ajax error: \" +  JSON.stringify(msg));
        }
    });

    //check for capacity exceedance here:
    $.ajax({
        url: 'action/action_checkCapacity.php',
        dataType: 'json',
        success: function(capOvers) {
        console.log(JSON.stringify(capOvers));
            capOvers.forEach(function(capOver){
                $('#' + 'sect_capGlyph' + capOver.secId).removeClass('hide')
                        .attr('title', 'Room Cap: ' + capOver.roomCap + ', Section Cap: ' + capOver.secCap);
            });
        },
        error: function(msg){
            console.log('checkCapacity ajax error: ' +  JSON.stringify(msg));
        }
    });


</script>


<div class='col-xs-12'>
        <div class='page-header'>
          <h1>Sections <small>for <span class='main_semester_span'>{$mainSemesterLabel}</span></small>
          <span class='glyphicon glyphicon-cloud-download pointer'
                title=' Download .csv '></span>
          <span class='glyphicon glyphicon-save-file pointer'
                title=' Download .xlsx ' onclick='getExcelDoc()'></span>
          </h1>

          <span class='glypicon glyphicon-plus quick-add pointer'

                data-toggle='modal' data-target='#newSectionModal'
                 id='newSectionPlus'></span>
        </div>

     </div>
</div>

    <div class='container'>
      <div class='col-xs-12' id='sectionIndex' style='margin-bottom: 20%;'>
        <table class='list-data' id='table_sectionIndex'>
          <tr>
            <th colspan='3'><a href='#' class='pointer a_indexHeader' onclick='ajax_orderBy(\"course\")'>Course</a></th>
            <th><a href='#' class='pointer a_indexHeader' onclick='ajax_orderBy(\"prof\")'>Professor</a></th>
            <th>Scheduled Time</th>
            <th><a href='#' class='pointer a_indexHeader' onclick='ajax_orderBy(\"room\")'>Location</a></th>
            <th>Actions</th>
          </tr>";



//"c.course_prefix, c.course_number"
$allSections = $database->getAllSections($mainSemesterId, $orderBy);
foreach ($allSections as $section){
    $body .= addSection($section, $database);
}

$body .= "</table>";
$body .= "</div>";



echo $body;

//<tr> (section record)     id = record_sectiont<#>  //if it doesn't end in _sect# then it won't toggle 'hide'
                                                     //also 'sectiont' is correct, the t is there on purpose to match last letter of 'sect'
//<span> (pencil)            id = pencil_sect<#>
//<span> (little arrow):    id = seeCal_sect<#>
//<tr>  (editing div)       id = edit_sect<#>
//<img> (save disc)         id = save_sect<#>


function addSection(Section $section, $database){
      $secId = $section->getSectionID();

$row = "

    <tr id='record_sectiont{$secId}'>
        <td>{$section->getSectionProperty('course_prefix', 'Course', 'course_id', 'courseID')}</td>"
        ."<td>{$section->getSectionProperty('course_number', 'Course', 'course_id', 'courseID')}</td>"
        ."<td> <i>{$section->getSectionProperty('course_title', 'Course', 'course_id', 'courseID')}</i></td>
        <td>{$section->getSectionProperty('prof_first', 'Professor', 'prof_id', 'profID')}"."
                {$section->getSectionProperty('prof_last', 'Professor', 'prof_id', 'profID')}<br>
                <small><em>{$section->getSectionProperty('prof_email', 'Professor', 'prof_id', 'profID')}
                </em></small></td>";

        if (strtoupper($section->getDayString()) === "O"){
            $row .= "<td><strong>Online</strong><br/>";
        }else{
            $row .= "<td><strong>{$section->getDayString_toUpper()}:</strong>"."
            {$section->getStartTime()} - {$section->getEndTime()}<br/>";
        }

$row .= "<small><em>{$section->getBlock()}</em></small></td>

        <td>
        <div class='capIcon_divNeighbor'>
            <strong>
            {$section->getSectionProperty_Join_3('building_code', 'Classroom', 'Building',
            'classroom_id', 'building_id', 'classroomID')}"."
            {$section->getSectionProperty('classroom_number', 'Classroom', 'classroom_id', 'classroomID')}
            </strong>
            <br/>
            <small>
            {$section->getSectionProperty_Join_4('campus_name', 'Classroom', 'Building', 'Campus',
            'classroom_id', 'building_id', 'campus_id', 'classroomID')}
            </small>
        </div>
        <div class='capIcon_div'>
            <span class='glyphicon glyphicon-user hide' id='sect_capGlyph{$secId}' </span>
        <div>

        </td>
        <td>
            <span class='action-edit pencil pointer glyphicon glyphicon-pencil' id='pencil_sect{$secId}'></span>

            <span style='color: orangered; margin-left: 10%' class='glyphicon glyphicon-alert hide'></span>
        </td>
   </tr>


    <tr class='hide' id='edit_sect{$secId}'>
        <td style='padding-bottom: 4%; padding-left: 1%' colspan='3'>

            <label for='inlineEdit_sectCourse{$secId}' >Course</label>
            <select class='form-control' id='inlineEdit_sectCourse{$secId}' style='margin-bottom: 10px'>";

                $selectCourse = $database->getdbh()->prepare(
                    'SELECT course_id, course_prefix, course_number, course_title FROM W01143557.Course
                      ORDER BY course_prefix, course_number');
                $selectCourse->execute();
                $result = $selectCourse->fetchAll(PDO::FETCH_ASSOC);

                foreach($result as $course){
                    if ($course['course_id'] == $section->getCourseID()){
                        $row .= '<option selected value='.$course['course_id'].'>';
                    }else{
                        $row .= '<option value='.$course['course_id'].'>';
                    }
                    $row .= $course['course_prefix']
                        .' '.$course['course_number']
                        .' '.$course['course_title']
                        .'</option>';
                }
$row .= "</select>

        <label for='inlineEdit_sectProf{$secId}'>Professor</label>
        <select class='form-control' id='inlineEdit_sectProf{$secId}' style='margin-bottom: 10px'></select>

        <label for='inlineEdit_sectRoom{$secId}'>Classroom</label>
        <select class='form-control' style='margin-bottom: 10px' id='inlineEdit_sectRoom{$secId}'></select>
         </td>

        <td  style='padding-left: 1%'>

        <label for='inlineEdit_sectDays{$secId}'>Days</label>
        <select multiple class='form-control' style='margin-bottom: 10px'
             id='inlineEdit_sectDays{$secId}'>";

    $days = explode("day", $section->getDays());
    //$days = $section->getDays();

    /*$row.= "<option "; $row.= ($days == 'Online' || $days == 'online')
            ? "selected value='online'>Online</option>" : "value='online'>Online</option>";
    $row.= "<option "; $row.= $days == 'MondayWednesday'
            ? "selected value='MondayWednesday'>MW</option>" : "value='MondayWednesday'>MW</option>";*/

    $row.= "<option "; $row.= in_array("Online", $days)
            ? "selected value='Online'>Online</option>" : "value='Online'>Online</option>";
    $row.= "<option "; $row.= in_array("Mon", $days)
            ? "selected value='Monday'>Monday</option>" : "value='Monday'>Monday</option>";
    $row.= "<option "; $row.= in_array("Tues", $days)
            ? "selected value='Tuesday'>Tuesday</option>" : "value='Tuesday'>Tuesday</option>";
    $row.= "<option "; $row.= in_array("Wednes", $days)
            ? "selected value='Wednesday'>Wednesday</option>" : "value='Wednesday'>Wednesday</option>";
    $row.= "<option "; $row.= in_array("Thurs", $days)
            ? "selected value='Thursday'>Thursday</option>" : "value='Thursday'>Thursday</option>";
    $row.= "<option "; $row.= in_array("Fri", $days)
            ? "selected value='Friday'>Friday</option>" : "value='Friday'>Friday</option>";
    $row.= "<option "; $row.= in_array("Satur", $days)
            ? "selected value='Saturday'>Saturday</option>" : "value='Saturday'>Saturday</option>";

$row.="</select>

     <label  for='inlineEdit_sectStartTime{$secId}'>Start Time</label>";
$row.=     $section->getIsOnline() ? "<input disabled " : "<input ";
$row.=     "type='time' placeholder='09:30 AM' id='inlineEdit_sectStartTime{$secId}'
                    style='margin-bottom: 10px' class='form-control'>

      <label for='inlineEdit_sectEndTime{$secId}'>End Time</label>";
$row.=     $section->getIsOnline() ? "<input disabled " : "<input ";
$row.=     "type='time' placeholder='11:20 AM' id='inlineEdit_sectEndTime{$secId}'  class='form-control'>
    </td>


    <td  style='padding-bottom: 1%; padding-left: 1%'>
        <label for='inlineEdit_sectSem{$secId}'>Semester</label>
            <select class='form-control' style='margin-bottom: 10px' id='inlineEdit_sectSem{$secId}'>
            </select>

            <label for='inlineEdit_sectBlock{$secId}'>Block</label>
            <select class='form-control'
            style='margin-bottom: 10px' id='inlineEdit_sectBlock{$secId}'>
                <option value='0'>Full</option>
                <option value='1'>First</option>
                <option value='2'>Second</option>
            </select>


            <label for='inlineEdit_sectCap{$secId}'>Capacity</label>
            <input style='width: 55%; margin-bottom: 20px' type='number' class='form-control'
                    id='inlineEdit_sectCap{$secId}' min='1' value='{$section->getCapacity()}' >

            <div style='width: 55%; margin-left: 2%'>
            <label class='checkbox-inline' for='inlineEdit_sectOnline{$secId}' style='font-weight: bold;'>";
$row.=     $section->getIsOnline() ? "<input checked " : "<input ";
$row.=     "type='checkbox'  id='inlineEdit_sectOnline{$secId}'
                            value='1' style='transform: scale(1.5); '>
                            &nbsp;&nbsp;&nbsp;Online</label>
                        </div>
            </td>
            <td></td>
            <td>
            <div style='padding-bottom: 20%;' class='action-save hide' id='save_sect{$secId}'>
                <button type=button class='btn btn-xs btn-success'>Update&nbsp;&nbsp;
                <span class='glyphicon glyphicon-floppy-save'></button>
                </span>
            </div>
            <div style='padding-bottom:90%;' id='sect_delete{$secId}'>
                <button type=button class='btn btn-xs btn-danger'>Delete&nbsp;&nbsp;&nbsp;
                <span class='glyphicon glyphicon-remove'></button>
                </span>
            </div>
            <div id='cancel_sect{$secId}' class='action-edit hide'>
                <button type=button class='btn btn-xs btn-warning'>Cancel&nbsp;&nbsp;
                <span class='glyphicon glyphicon-remove'></button>
                </span>
                </div>
            </td>
            <!-- <img src='img/save.png' width='30px'  class='action-save hide' id='save_sect{$secId}'/>-->
            <!-- <img src='img/close.png' class='action-delete' id='sect_delete{$secId}'/> -->
    </tr>

    <tr class='hide' id='hiddenRow_sect{$secId}'></tr>

";
    return $row;
}
