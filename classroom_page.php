<?php
require_once 'Classroom.php';

$database = new Database();
$dbh = $database->getdbh();

$body = "

<div class='col-xs-12'>
    <div class='page-header'>
          <h1>Classrooms <small>for Spring 2017</small></h1>
    </div>
</div>

</div>
    <div class='container'>
      <div class='col-xs-12' id='roomIndex'>
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
    $body .= addClassroom($classroom, $database);
    //function addClassroom is defined in this file (classroom_page.php)
    //it produces each row of individual classrooms.
}




$body .= "</table>";


$body .= "<div class='form-group' style=    'margin-bottom: 40px;
                                            border-top: 1px solid #492365;
                                            padding-top: 5px;'>
            <span style='float:left; padding-top: 5px; font-weight:bold; color:#492365'>
            Select a Campus and a Building:</span>
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
            <select type='text' class='form-control' id='pickBuilding' name='pickBuilding'
             disabled='true'>
                  <option value='0'>Building...</option> </select>
                  ";




$body .= "
        </div>  <!-- end of 'col-xs-3'  -->
        </div> <!-- end of 'form-group'  -->";

$body .= "
<script>
    $('#pickCampus').change(function(){
        var campusId = $(this).val();
        if ($('#pickCampus').val() > 0)
            $('#pickBuilding').removeAttr('disabled');
        else{
            $('#pickBuilding').attr('disabled', 'true');
            //$('#pickClassroom').attr('disabled', 'true');
        }


 //http://stackoverflow.com/questions/36393409/ajax-call-to-populate-select-list-when-another-select-list-changes
//http://www.codingcage.com/2015/11/ajax-login-script-with-jquery-php-mysql.html
//https://openenergymonitor.org/emon/node/107

$.ajax({
      type: 'POST',
      url: 'pickBuildings.php',        //the script to call to get data
      data: 'campusId=' + campusId,    //you can insert url arguments here to pass to pickBuildings.php
                                       //for example \"id=5&parent=6\"
      dataType: 'json',                //data format
      success: function(data)          //on receive of reply
      {
        var dropdown_Building = $('#pickBuilding');
        dropdown_Building.empty();
        dropdown_Building.append($('<option />').val(0).text('Building...'));

        $.each(data, function() {
            dropdown_Building.append($('<option />').val(this.building_id).text(this.building_name));
        });


      }
    });
}); // end of pickCampus.change()




$('#pickBuilding').change(function(){

var buildingId = $(this).val();
$('#pickBuilding:focus').blur();

$.ajax({
      type: 'POST',
      url: 'showClassroomOverviewSchedule.php',        //the script to call to get data
      data: 'buildingId=' + buildingId,    //you can insert url arguments here to pass to pickClassrooms.php
                                       //for example \"id=5&parent=6\"
      dataType: 'text',                //data format
      success: function(data)          //on receive of reply
      {
            console.log(data.substr(data.lastIndexOf('var theClassroomSet')));
            eval(data.substr(data.lastIndexOf('var theClassroomSet')));
      },

    });

}); // end of pickBuilding.onChange()

</script>";

$body .= "<div  id='classroomOverviewSchedule'
                style='
                background-color: #fff;
                padding-top: 15px;
                border-top: 1px solid #492365'>
                </div>";  // this div holds the schedule showing all classrooms

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
$body .= "<script>displayClassroomSchedule(theClassroomSet); console.log(theClassroomSet.length);</script>";



echo $body;


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
            add_toClassroomSet([], '  /*  first argument:  */
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






function addClassroom(Classroom $classroom, Database $db){
    //var_dump($classroom);
    $eventObjects = array();

    // $daysToDates maps section weekdays to the dates that position the courses on the
    // individual classroom's fullCalendar schedule
    $daysToDates = array("Mon"=>"2016-11-07", "Tues" => "2016-11-08", "Wednes" => "2016-11-09",
        "Thurs" => "2016-11-10", "Fri" => "2016-11-11", "Satur" => "2016-11-12" , "online"=> "2016-11-13");

    $classroomSections = $db->getClassroomSections($classroom, null);
    foreach($classroomSections as $section){
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
        //$classroom = $section->getSectionProperty('classroom_number', 'Classroom', 'classroom_id', 'classroomID');
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
                //"classroom" => $classroom,
                "professor" => $prof,
                "online" => $isOnline
            )));
        }

    }

    //Here's where we create the table of Classrooms on the "Classroom Page".
    $row = "<tr id='.{$classroom->getClassroomID()}.'>
			<td>{$classroom->getClassroomProperty_Join_3('campus_name', 'Building', 'Campus',
                'building_id', 'campus_id', 'buildId')}</td>
			<td>{$classroom->getClassroomProperty('building_name', 'Building', 'building_id', 'buildId')}</td>
			<td><small><em>{$classroom->getClassroomNum()}</em></small></td>
			<td> {$classroom->getClassroomCap()}</td>
			<td>{$classroom->getNumWorkstations()}</td>
			<td><img src='img/pencil.png' class='action-edit'/><img src='img/close.png' class='action-delete'>

            <!--this span *is* the little up/down arrow that shows/hides individual prof calendar-->
			<!--so the span itself has a onClick() set on it -->
			    <span id='seeRoomCal_{$classroom->getClassroomID()}'
			    onclick='on_roomRowClick({$classroom->getClassroomID()}, [";

            /*function 'on_roomRowClick()' is defined in classroomCalendar.js
            on_roomRowClick(roomRowId (int), sectionObjects (array of objects from top of this function)*/

    foreach($eventObjects as $eventObj) {
        $row .= $eventObj . ",";  //these are JSON objects
    }

    // finish giving attributes to the <span> and close it...
    $row .= "])' class=' glyphicon glyphicon-menu-down' aria-hidden='true'></span>
			</td>
		  </tr>";

    /*
    *  the next two rows are set to display:none so that they exist but are hiding until
    *      the calendar displays.
    *  the second (empty) row is a placeholder so that the stripe color alternates correctly.
    */
    $row .= "<tr style='display:none' id='roomRow_{$classroom->getClassroomID()}'>
                <td colspan='8' style='padding:0'>
                <!-- roomCalendar_<id>:  the div that the individual calendar lives in. -->
                <div class='indRoomCal' id='roomCalendar_{$classroom->getClassroomID()}'></div>
                </td>
            </tr>
            <tr style='display:none'></tr>
            ";


    return $row;  //finally the long $row string can be echoed
}



function getClassroomsByCampus($campusId, $db){
    return $db->getClassroomsOnCampus($campusId);
}