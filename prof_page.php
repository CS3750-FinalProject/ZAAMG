<?php
require_once 'Professor.php';

$database = new Database();

$body = "

<script src='js/calendar.js' charset='utf-8'></script>

";


#<script src='js/section-updates.js' charset='utf-8'></script>";

$body .= "
<div class='col-xs-12' >
        <div class='page-header'>
          <h1>Professors <small>for Spring 2017</small></h1>
        </div>
</div>


    <div class='container' >
      <div class='col-xs-12' id='profIndex'>
        <table class='list-data'>
          <tr>
            <th>First Name</th>
            <th>Last Name</th>
            <th>E-Mail</th>
            <th>Department</th>
            <th>Req Hours</th>
			<th>Rel Hours</th>
			<th>Over Hours</th>
			<th>Actions</th>
          </tr>";




$allProfessors = $database->getAllProfessors('prof_last');
foreach ($allProfessors as $professor){
    $body .= addProfessor($professor, $database);
}


$body .= "</table>";


$body .= "<div id='profOverviewSchedule'></div>";

$body .= "</div>";
$body .= "</div>";

echo "<script> var theProfSet = []; console.log(theProfSet);</script>";

echo load_ProfSet($allProfessors, $database);
echo "<script> console.log('theProfSet:' + theProfSet); </script>";

$body .= "<script>displayProfOverviewSchedule(theProfSet)</script>";

echo $body;


//TODO:  use json_encode to do this!  I did it by scratch here out of ignorance.  :-(
function load_ProfSet($allTheProfs, $db){
    $body = '<script> ';
    foreach($allTheProfs as $professor){
        $onlineCourses = [];
        $body.='
            add_toProfSet('
            .'"'.$professor->getProfFirst().'"'.','
            .'"'.$professor->getProfLast().'"'.','.'
                [ ';
        $sections = $db->getProfSections($professor, null);
        foreach($sections as $oneSection){
            $course = $db->getCourse($oneSection);
            if (!$oneSection->getIsOnline()){
                $body .= '
                    { pref:  '.'"'.$course->getCoursePrefix().'"'.',
                      num:   '.'"'.$course->getCourseNumber().'"'.',
                      days:  '.'"'.$oneSection->getDayString().'"'.',
                      startTime:  '.'"'.$oneSection->getStartTime().'"'.',
                      endTime:  '.'"'.$oneSection->getEndTime().'"'.',
                    },';
            } else{
                array_push($onlineCourses, $course);
            }
        }
        $body .= " ],[ ";
        foreach($onlineCourses as $onlineCourse){
            $body .= '
            { pref:  '.'"'.$onlineCourse->getCoursePrefix().'"'.',
              num:   '.'"'.$onlineCourse->getCourseNumber().'"'.'
            },';
        }
        $body.= ' ]);';
    }
    $body.='</script>';
    return $body;
}

function addProfessor(Professor $professor, Database $db){
    $eventObjects = array();
    $daysToDates = array("Mon"=>"2016-11-07", "Tues" => "2016-11-08", "Wednes" => "2016-11-09",
        "Thurs" => "2016-11-10", "Fri" => "2016-11-11", "Satur" => "2016-11-12" , "online"=> "2016-11-13");
    echo "<script>console.log(\"day\" + {$daysToDates['Mon']});</script>";
    $profSections = $db->getProfSections($professor, null);
    foreach($profSections as $section){
        $prefix = $section->getSectionProperty('course_prefix', 'Course', 'course_id', 'courseID');
        $number = $section->getSectionProperty('course_number', 'Course', 'course_id', 'courseID');
        $title = $prefix . " " . $number;
        $dayString = $section->getDayString();
        if ($dayString != "online"){
            $days = explode('day', $section->getDays());
            array_pop($days); // last element is useless
        }
        else
            $days = array("online" => "online");

        $eventStart = $section->getStartTime();
        $eventEnd = $section->getEndTime();
        $location = $section->getSectionProperty_Join_4('campus_name', 'Classroom', 'Building', 'Campus',
            'classroom_id', 'building_id', 'campus_id', 'classroomID');
        $classroom = $section->getSectionProperty('classroom_number', 'Classroom', 'classroom_id', 'classroomID');
        $profFirst = $section->getSectionProperty('prof_first', 'Professor', 'prof_id', 'profID');
        $profLast = $section->getSectionProperty('prof_last', 'Professor', 'prof_id', 'profID');
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

/*
    $eventObjects = array(
        json_encode(array("name" => "Gisela", "gender" => "female")),
        json_encode(array("name" => "Leon", "gender" => "male"))
    );*/


    $row = "<tr class='{$professor->getProfId()}' >
			<td>{$professor->getProfFirst()}</td>
			<td>{$professor->getProfLast()}</td>
			<td><small><em>{$professor->getProfEmail()}</em></small></td>
			<td> {$professor->getProfessorProperty('dept_name', 'Department', 'dept_id', 'deptId')}</td>
			<td>{$professor->getProfRequiredHours()}</td>
			<td>{$professor->getProfRelease()}</td>
			<td>to be calc...</td>
			<td>
			    <img src='img/pencil.png' class='action-edit' />
			    <img src='img/close.png' class='action-delete'>
			    <span id='seeProfCal_{$professor->getProfId()}'
			    onclick='on_profRowClick({$professor->getProfId()}, [";
    foreach($eventObjects as $eventObj){$row .= $eventObj . ",";}

    $row .= "])' class=' glyphicon glyphicon-menu-down' aria-hidden='true'></span>
			</td>
		  </tr>";

    /*
     *  the next two rows are set to display:none so that they exist but are hiding until
     *      the calendar displays.
     *  the second (empty) row is a placeholder so that the stripe color alternates correctly.
     */
    $row .= "<tr style='display:none' id='profRow_{$professor->getProfId()}'>
                <td colspan='8' style='padding:0'>
                <div class='indProfCal' id='profCalendar_{$professor->getProfId()}'></div>
                </td>
            </tr>
            <tr style='display:none'></tr>
            ";

    return $row;
}

