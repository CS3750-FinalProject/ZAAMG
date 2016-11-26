<?php
require_once 'Professor.php';

$database = new Database();

$body = "
<script src='js/calendar.js' charset='utf-8'></script>
";

$body .= "
<div class='col-xs-12' >
        <div class='page-header'>
          <h1>Professors <small>for Spring 2017</small></h1>
        </div>
</div>


    <div class='container' >

      <div class='col-xs-12' id='profIndex'
            style='
            max-height: 360px;
            overflow-y: auto;
            '>

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
$body .= "<script>displayTest(theProfSet)
//init_ProfOverviewSchedule(); // probably don't need this
</script>";

echo $body;


/*
 * TODO:  probably better to use json_encode to do some of this!
 * I did it by scratch here out of ignorance.  :-(
 */
function load_ProfSet($allTheProfs, $db){
    $body = '<script> '; //getting ready to echo javascript code...
    foreach($allTheProfs as $professor){
        $onlineCourses = [];  // this array will hold online course JSON objects
        /*
         *  function add_toProfSet(profFirst (string), profLast (string), profId (int),
         *                          timedCourseObjects (array of JSON objects,
         *                          onlineCourseObjects (array of JSON objects)
         *  defined in professorSet.js
         */
        $body.='
            add_toProfSet('  /*  first three arguments:  */
            .'"'.$professor->getProfFirst().'"'.','
            .'"'.$professor->getProfLast().'"'.','
            .'"'.$professor->getProfId().'"'.','.'
                [ ';  //  <--  opening square bracket for the "timed courses" JSON obj. array
        $sections = $db->getProfSections($professor, null);

        // looping through each of one professor's sections...
        foreach($sections as $oneSection){
            $course = $db->getCourse($oneSection);
            if (!$oneSection->getIsOnline()){  // if the section is not online, add it to "timed courses"
                $body .= '
                    { pref:  '.'"'.$course->getCoursePrefix().'"'.',
                      num:   '.'"'.$course->getCourseNumber().'"'.',
                      days:  '.'"'.$oneSection->getDayString().'"'.',
                      startTime:  '.'"'.$oneSection->getStartTime().'"'.',
                      endTime:  '.'"'.$oneSection->getEndTime().'"'.',
                    },';  // (just wrote one JSON object for each section)
            } else{
                // if a section is online, put it in $onlineCourses array
                array_push($onlineCourses, $course);
            }
        }
        $body .= " ],[ ";  //  <-- done with timedCourses array, beginning onlineCourses array...
        // loop through array of onlineCourses and construct JSON objects
        foreach($onlineCourses as $onlineCourse){
            $body .= '
            { pref:  '.'"'.$onlineCourse->getCoursePrefix().'"'.',
              num:   '.'"'.$onlineCourse->getCourseNumber().'"'.'
            },';
        }
        $body.= ' ]);'; // end of sending arguments to add_toProfSet()
    }
    $body.='</script>';
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

    $profSections = $db->getProfSections($professor, null);
    foreach($profSections as $section){
        $prefix = $section->getSectionProperty('course_prefix', 'Course', 'course_id', 'courseID');
        $number = $section->getSectionProperty('course_number', 'Course', 'course_id', 'courseID');
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
    $row = "<tr id='record_professorf{$id}' >

            <td>{$professor->getProfLast()}</td>
			<td>{$professor->getProfFirst()}</td>
			<td><small><em>{$professor->getProfEmail()}</em></small></td>
			<td> {$professor->getProfessorProperty('dept_name', 'Department', 'dept_id', 'deptId')}</td>
			<td>{$professor->getProfRequiredHours()}</td>
			<td>{$professor->getProfRelease()}</td>
			<td>to be calc...</td>
			<td>
			    <img src='img/pencil.png' class='action-edit' id='pencil_prof{$id}'/>
			    <img src='img/close.png' class='action-delete'>

			<!--this span *is* the little up/down arrow that shows/hides individual prof calendar-->
			<!--so the span itself has a onClick() set on it -->
			    <span id='seeCal_prof{$id}'
			    onclick='on_profRowClick({$id}, [";

            /*function 'on_profRowClick()' is defined in calendar.js
            on_profRowClick(profRowId (int), sectionObjects (array of objects from top of this function)*/


    foreach($eventObjects as $eventObj) {
            $row .= $eventObj . ",";  //these are JSON objects
    }

    // finish giving attributes to the <span> and close it...
    $row .= "])' class='glyphicon glyphicon-menu-down' aria-hidden='true'></span>
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
                placeholder='{$professor->getProfFirst()}' style='margin-bottom: 10px' >

                <label for='inlineEdit_profLast{$id}' >Last Name</label>

                <input type='text' class='form-control' id='inlineEdit_profLast{$id}'
                placeholder='{$professor->getProfLast()}' style='margin-bottom: 10px' >

            </td>

            <td colspan='2'>
                <label for='inlineEdit_profEmail{$id}' >Email</label>

                <input type='email' class='form-control' id='inlineEdit_profEmail{$id}'
                placeholder='{$professor->getProfEmail()}' style='margin-bottom: 10px'>

            <label for='inlineEdit_profDept{$id}'>Department</label>
                        <select class='form-control' id='inlineEdit_profDept{$id}' style='margin-bottom: 10px'>";

                            $selectDepts = $db->getdbh()->prepare(
                                'SELECT dept_id, dept_name FROM ZAAMG.Department
                                  ORDER BY dept_name ASC');
                            $selectDepts->execute();
                            $result = $selectDepts->fetchAll();

                            foreach($result as $dept){
                                if ($dept['dept_id'] == $professor->getDeptId()){
                                    $row.=
                                        "<option selected value=".$dept['dept_id'].'>'.$dept['dept_name'].'</option>';
                                }else{
                                    $row.=
                                        "<option value=".$dept['dept_id'].'>'.$dept['dept_name'].'</option>';
                                }
                            }
$row.="
                        </select>
            </td>

            <td colspan='2'>
                <label for='inlineEdit_profReqHours{$id}'>Required Hours</label>
                <input type='number' class='form-control' id='profinlineEdit_profReqHours{$id}'
                    style='margin-bottom: 10px'
                    placeholder={$professor->getProfRequiredHours()}>

                <label for='inlineEdit_profRelHours{$id}'>Release Hours</label>
                <input type='number' class='form-control' id='inlineEdit_profRelHours{$id}'
                    placeholder={$professor->getProfRelease()} style='margin-bottom: 10px'>
            </td>
            <td></td>
            <td><img src='img/save.png' width='30px' class='action-save hide' id='save_prof{$id}'/></td>
          </tr>

            ";

    return $row;  //finally the long $row string can be echoed
}

