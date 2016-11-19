<?php
require_once 'Classroom.php';

$database = new Database();

$body = "

<div class='col-xs-12'>
    <div class='page-header'>
          <h1>Classrooms <small>for Spring 2017</small></h1>
    </div>
</div>

</div>
    <div class='container'>
      <div class='col-xs-12'>
        <table class='list-data'>
        <tr>
            <th>Campus</th>
            <th>Building</th>
            <th>Room Number</th>
            <th>Capacity</th>
			<th>Number of Computers</th>
			<th>Actions</th>
         </tr>";



$allClassrooms = $database->getAllClassrooms(null);
foreach ($allClassrooms as $classroom){
    $body .= addClassroom($classroom);
}




$body .= "</table>";




$body .= "<div class='form-group' style=    'margin-bottom: 40px;
                                            border-top: 1px solid black;
                                            padding-top: 5px;'>

            <div class='col-xs-3'>
            <select type='text' class='form-control' id='pickCampus' >
                <option value='0'>Campus...</option>";
            $selectCampus = $database->getdbh()->prepare(
                            'SELECT campus_name, campus_id
                            FROM ZAAMG.Campus
                            ORDER BY campus_name ASC');
            $selectCampus->execute();
            $result = $selectCampus->fetchAll();

            foreach($result as $row){
                $body .= "<option value=".$row['campus_id'].">"
                    .$row['campus_name']."</option>";
            }

 $body .= " </select>
            </div>


            <div class='col-xs-3'>
            <select type='text' class='form-control' id='pickBuilding' >

                   <option value='0'>Building...</option>";

            $selectBuilding = $database->getdbh()->prepare(
                            'SELECT building_name, building_id
                            FROM ZAAMG.Building
                            ORDER BY building_name ASC');
            $selectBuilding->execute();
            $result = $selectBuilding->fetchAll();

            foreach($result as $row){
                $body .= "<option value=".$row['building_id'].">"
                    .$row['building_name']."</option>";
            }

$body .= " </select>
            </div>


            <div class='col-xs-3'>
            <select type='text' class='form-control' id='pickClassroom' >

                   <option value='0'>Classroom...</option>";

            $selectClassroom = $database->getdbh()->prepare(
                            'SELECT classroom_id, classroom_number
                            FROM ZAAMG.Classroom
                            ORDER BY classroom_number ASC');
            $selectClassroom->execute();
            $result = $selectClassroom->fetchAll();

            foreach($result as $row){
                $body .= "<option value=".$row['classroom_id'].">"
                    .$row['classroom_number']."</option>";
            }

$body .= "</select>
        </div>  <!-- end of 'col-xs-3'  -->






        </div> <!-- end of 'form-group'  -->";


$body .= "<div  id='classroomOverviewSchedule'
                style='
                background-color: #fff;
                padding-top: 15px;
                border-top: 1px solid #492365'></div>";  // this div holds the schedule showing all classrooms

$body .= "</div>";  //end of 'col-xs-12'



/*
 *  javascript variable theClassroomSet is the array that
 *  will hold arrays of JSON event objects for each classroom.
 */
$body .= "<script> var theClassroomSet = [];</script>";



/* load_ClassroomSet is defined in this file (classroom_page.php)
 * It loads the array defined just above here (theClassroomSet).
 * theClassroomSet array will next be sent to javascript function
 * displayClassroomOverviewSchedule(theClassroomSet), which constructs
 * and displays the fullCalendar overview schedule of all rooms.
 */
$body .= load_ClassroomSet($database->getClassroomsInBuilding(6), $database);


/*
 *  javascript function displayClassroomSchedule is defined in
 *  classroomCalendar.js
 */
$body .= "<script>displayClassroomSchedule(theClassroomSet)</script>";



echo $body;
echo "<script> console.log(theClassroomSet.length) </script>";


/*
 * TODO:  probably better to use json_encode to do some of this!
 * I did it by scratch here out of ignorance.  :-(
 */
function load_ClassroomSet($classrooms, $db){
    $body = '<script> '; //getting ready to echo javascript code...
    foreach($classrooms as $classroom){
        /*
         *  function add_toClassroomSet(profFirst (string), profLast (string), profId (int),
         *                          timedCourseObjects (array of JSON objects,
         *                          onlineCourseObjects (array of JSON objects)
         *  defined in professorSet.js
         */
        $body.='
            add_toClassroomSet('  /*  first argument:  */
            .'"'.$classroom->getClassroomNum().'"'.','.'
                [ ';  //  <--  opening square bracket for the "timed courses" JSON obj. array
        $sections = $db->getClassroomSections($classroom);

        // looping through each of one classroom's sections...
        foreach($sections as $oneSection){
            $course = $db->getCourse($oneSection);
            $body .= '
            { pref:  '.'"'.$course->getCoursePrefix().'"'.',
              num:   '.'"'.$course->getCourseNumber().'"'.',
              days:  '.'"'.$oneSection->getDayString().'"'.',
              startTime:  '.'"'.$oneSection->getStartTime().'"'.',
              endTime:  '.'"'.$oneSection->getEndTime().'"'.',
            },';  // (just wrote one JSON object for each section)
        }
        //$body .= " ]";
        $body.= ' ]);'; // end of sending arguments to add_toProfSet()
    }
    $body.='</script>';
    return $body;
}







function addClassroom(Classroom $classroom){
    $row = "<tr class='{$classroom->getClassroomID()}'>
			<td>{$classroom->getClassroomProperty_Join_3('campus_name', 'Building', 'Campus',
                'building_id', 'campus_id', 'buildId')}</td>
			<td>{$classroom->getClassroomProperty('building_name', 'Building', 'building_id', 'buildId')}</td>
			<td><small><em>{$classroom->getClassroomNum()}</em></small></td>
			<td> {$classroom->getClassroomCap()}</td>
			<td>{$classroom->getNumWorkstations()}</td>
			<td><img src='img/pencil.png' class='action-edit'/><img src='img/close.png' class='action-delete'></td>
		  </tr>";


    return $row;
}



function getClassroomsByCampus($campusId, $db){
    return $db->getClassroomsOnCampus($campusId);
}