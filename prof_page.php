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
      <div class='col-xs-12' >
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
    $body .= addProfessor($professor);
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

function addProfessor(Professor $professor){
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
			    <span id='seeCal_{$professor->getProfId()}'
			          class=' glyphicon glyphicon-menu-down' aria-hidden='true'></span>
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
