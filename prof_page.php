<?php
require_once 'Professor.php';

$database = new Database();
session_start();

$body = "
<script src='js/calendar.js' charset='utf-8'></script>
";

$body .= "
<div class='col-xs-12' >
        <div class='page-header'>

          <h1 style='display:inline'>Professors <small>for {$_SESSION['mainSemesterLabel']}</small></h1>

          <img src='img/ajax-loader.gif'  id='prof_ajax-loader'
          style='display:inline-block; padding-left: 3%; padding-bottom: 8px'/>
        </div>
</div>


    <div class='container' >

      <div class='col-xs-12' id='profIndex'
            style='
            max-height: 440px;
            overflow-y: auto;  '>

        <table class='list-data'>
          <tr>
            <th>Last Name</th>
            <th>First Name</th>
            <th>E-Mail</th>
            <th>Department</th>
            <th>Req Hours</th>
			<th>Rel Hours</th>
			<th>Over Hours</th>
			<th>Actions</th>
          </tr>";


$allProfessors = $database->getAllProfessors('prof_last');//argument is the field to ORDER BY
foreach ($allProfessors as $professor){
    $body .= addProfessor($professor, $database);
    //function addProfessor is defined in this file (prof_page.php)
    //it produces each row of individual professors.
}


$body .= "</table>";

$body .= "</div>";  //   end of <div class='col-xs-12' id='profIndex'>

$body .= "<div class='col-xs-12'><hr style='border-width: 2px border-color: #492365'></div>";
$body .= "<div
                class='col-xs-12'
                id='profOverviewSchedule'
                style='
                background-color: #fff;
                padding-top: 15px;
                margin-bottom: 50px;
                border-bottom: 1px solid #492365;
                '></div>";  // this div holds the schedule showing all professors

$body .= "</div>";  //   end of  <div class='container' >



/*
 *  javascript variable theProfSet is the array that
 *  will hold arrays of JSON event objects for each professor.
 */
$body .= "<script> var theProfSet = [];</script>";


/* load_ProfSet is defined in this file (prof_page.php)
 * It loads the array defined just above here (theProfSet).
 * theProfSet array will next be sent to javascript function
 * displayProfOverviewSchedule_Test(theProfSet), which constructs
 * and displays the fullCalendar overview schedule of all profs.
 */
$body .= load_ProfSet($allProfessors, $database);

/*
 *  javascript function displayTest is defined in
 *  Calendar.js   (was called test when I was retooling the whole thing)
 */
$body .= "<script>  displayTest(theProfSet)  </script>";

echo $body;

function load_ProfSet($allTheProfs, $db){
    $body = "<script> ";

    foreach($allTheProfs as $professor){
        $onlineCourses = [];
        $timedCourses = [];
        $sections = $db->getProfSections($professor, $_SESSION['mainSemesterId']);

        foreach($sections as $section){
            if (!$section->getIsOnline()){                  //not online
                array_push($timedCourses, array(
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
                    'room'=>$section->getSectionProperty('classroom_number', 'Classroom', 'classroom_id', 'classroomID')
                ));
            }else{
                array_push($onlineCourses, array(
                    'pref'=>$section->getSectionProperty('course_prefix', 'Course', 'course_id', 'courseID'),
                    'num'=>$section->getSectionProperty('course_number', 'Course', 'course_id', 'courseID'),
                    'c_name'=>$section->getSectionProperty('course_title', 'Course', 'course_id', 'courseID')
                ));
            }


        }
         /*  function add_toProfSet(profFirst (string), profLast (string), profId (int),
         *                          timedCourseObjects (array of JSON objects,
         *                          onlineCourseObjects (array of JSON objects)
         *   defined in professorSet.js
         */
        $timedCourses_json = count($timedCourses) != 0 ? json_encode($timedCourses) : json_encode(array());
        $onlineCourses_json = count($onlineCourses) != 0 ? json_encode($onlineCourses) : json_encode(array());
        $body.= "
                add_toProfSet('{$professor->getProfFirst()}','{$professor->getProfLast()}',{$professor->getProfId()},
                {$timedCourses_json}, {$onlineCourses_json});";

    }
    $body.="</script>";
    return $body;
}



/*  this function is ginormous.
 *
 */
function addProfessor(Professor $professor, Database $db){
    $eventObjects = array();
    $id = $professor->getProfId();

    // $daysToDates maps section weekdays to the dates that position the courses on the
    // individual professor's fullCalendar schedule
    $daysToDates = array("Mon"=>"2016-11-07", "Tues" => "2016-11-08", "Wednes" => "2016-11-09",
        "Thurs" => "2016-11-10", "Fri" => "2016-11-11", "Satur" => "2016-11-12" , "online"=> "2016-11-13");

    $profSections = $db->getProfSections($professor, $_SESSION['mainSemesterId']);
    foreach($profSections as $section){
        $prefix = $section->getSectionProperty('course_prefix', 'Course', 'course_id', 'courseID');
        $number = $section->getSectionProperty('course_number', 'Course', 'course_id', 'courseID');
        $name = $section->getSectionProperty('course_title', 'Course', 'course_id', 'courseID');
        $title = $prefix . " " . $number;
        $dayString = $section->getDayString();
        if ($dayString != "online"){
            $days = explode('day', $section->getDays()); //converts a string like TuesdayThursday into ['Tues','Thurs']
            array_pop($days); // last element is useless and breaks things, pop it off.
        }
        else
            $days = array("online" => "online");

        $eventStart = $section->getStartTime();
        $eventEnd = $section->getEndTime();
        $location = $section->getSectionProperty_Join_4('campus_name', 'Classroom', 'Building', 'Campus',
            'classroom_id', 'building_id', 'campus_id', 'classroomID');
        $classroom = $section->getSectionProperty('classroom_number', 'Classroom', 'classroom_id', 'classroomID');
        $profLast = $section->getSectionProperty('prof_last', 'Professor', 'prof_id', 'profID');
        $profFirst = $section->getSectionProperty('prof_first', 'Professor', 'prof_id', 'profID');
        $prof = $profFirst . " " . $profLast;
        $isOnline = $section->getIsOnline();

        foreach($days as $day){
            array_push($eventObjects, json_encode(array(
                "title" => $title,
                "name"=> $name,
                "start" => $daysToDates[$day]."T".$eventStart,
                "end" => $daysToDates[$day]."T".$eventEnd,
                "location" => $location,
                "classroom" => $classroom,
                "professor" => $prof,
                "online" => $isOnline
            )));
        }
    }

    //<tr> (prof record)        id = record_professorf<#>   //if it doesn't end in _prof# then it won't toggle 'hide'
                                //also 'professorf' is correct, the f is there on purpose to match last letter of 'prof'
    //<img> (pencil)            id = pencil_prof<#>
    //<span> (little arrow):    id = seeCal_prof<#>
    //<tr> (contains cal div):  id = calRow_prof<#>
    //<div> (contains cal)      id = cal_prof<#>
    //<tr>  (editing div)       id = edit_prof<#>
    //<img> (disc)              id = save_prof<#>

    //Here's where we create the table of Professors on the "Professor Page".
    $row = "<tr id='record_professorf{$id}' >   <!-- NOT A TYPO (f)  -->

            <td>{$professor->getProfLast()}</td>
			<td>{$professor->getProfFirst()}</td>
			<td><small><em>{$professor->getProfEmail()}</em></small></td>
			<td> {$professor->getProfessorProperty('dept_name', 'Department', 'dept_id', 'deptId')}</td>
			<td>{$professor->getProfRequiredHours()}</td>
			<td>{$professor->getProfRelease()}</td>
			<td>to be calc...</td>
			<td>


			<!--this span *is* the little up/down arrow that shows/hides individual prof calendar-->
			<!--so the span itself has a onClick() set on it -->
			    <span id='seeCal_prof{$id}' style='padding-right: 2%; margin-right: 7%'
			    onclick='on_profRowClick({$id}, [";

            /*function 'on_profRowClick()' is defined in calendar.js
            on_profRowClick(profRowId (int), sectionObjects (array of objects from top of this function)*/


    foreach($eventObjects as $eventObj) {
            $row .= $eventObj . ",";  //these are JSON objects
    }

    // finish giving attributes to the <span> and close it...
    $row .= "])' class='glyphicon glyphicon-calendar' aria-hidden='true' style='margin-left: 15%'></span>
<img src='img/pencil_green.png' class='action-edit pencil'  id='pencil_prof{$id}' style='margin-right: 8%'/>
<span style='color: orangered; ' class='glyphicon glyphicon-alert hide'></span>
			</td>
		  </tr>";

    /*
     *  the next two rows are set to display:none so that they exist but are hiding until
     *      the calendar displays.
     *  the second (empty) row is a placeholder so that the stripe color alternates correctly.
     */
    $row .= "<tr class='hide' id='calRow_prof{$id}'>
                <td colspan='8' style='padding:0'>
                <!-- profCal_<id>:  the div that the individual calendar lives in. -->
                <div class='indProfCal' id='cal_prof{$id}'></div>
                </td>
            </tr>
            <!--<tr style='display:none'></tr>-->

           <tr class='hide' id='edit_prof{$id}'>
            <td colspan='2'>
                <label for='inlineEdit_profFirst{$id}' >First Name</label>

                <input type='text' class='form-control' id='inlineEdit_profFirst{$id}'
                 style='margin-bottom: 10px' >

                <label for='inlineEdit_profLast{$id}' >Last Name</label>

                <input type='text' class='form-control' id='inlineEdit_profLast{$id}'
                 style='margin-bottom: 10px' >

            </td>

            <td colspan='2'>
                <label for='inlineEdit_profEmail{$id}' >Email</label>

                <input type='email' class='form-control' id='inlineEdit_profEmail{$id}'
                 style='margin-bottom: 10px'>

            <label for='inlineEdit_profDept{$id}'>Department</label>
                        <select class='form-control' id='inlineEdit_profDept{$id}' style='margin-bottom: 10px'>
                        </select>
            </td>

            <td colspan='2'>
                <label for='inlineEdit_profReqHours{$id}'>Required Hours</label>
                <input type='number' class='form-control' id='inlineEdit_profReqHours{$id}'
                    style='margin-bottom: 10px'>

                <label for='inlineEdit_profRelHours{$id}'>Release Hours</label>
                <input type='number' class='form-control' id='inlineEdit_profRelHours{$id}'
                    style='margin-bottom: 10px'>
            </td>
            <td></td>
            <td>
                <div style='padding-bottom: 20%;' class='action-save hide' id='save_prof{$id}'>
                <button type=button class='btn btn-xs btn-success'>Update&nbsp;&nbsp;
                <span class='glyphicon glyphicon-floppy-save'></button>
                </span>
                </div>
                <div id='prof_delete{$id}' style='padding-bottom: 50%;'>
                <button type=button class='btn btn-xs btn-danger'>Delete&nbsp;&nbsp;&nbsp;
                <span class='glyphicon glyphicon-trash'></button>
                </span>
                </div>
                <div id='cancel_prof{$id}' class='action-edit hide'>
                <button type=button class='btn btn-xs btn-warning'>Cancel&nbsp;&nbsp;
                <span class='glyphicon glyphicon-remove'></button>
                </span>
                </div>
            </td>

          <!--  <img src='img/save.png' width='30px' class='action-save hide' id='save_prof{$id}'/> -->
          </tr>

            ";

    return $row;  //finally the long $row string can be echoed
}

