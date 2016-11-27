 <?php
require_once 'Classroom.php';

$database = new Database();
$dbh = $database->getdbh();

$body = "

<script src='js/ClassroomCalendar.js' charset='utf-8'></script>


<div class='col-xs-12'>
    <div class='page-header'>
          <h1>Classrooms <small>for Spring 2017</small></h1>
    </div>
</div>

</div>
    <div class='container'>
      <div class='col-xs-12' id='roomIndex'
            style='
            border-bottom: 1px solid #492365;
            margin-bottom: 15px;
            max-height: 440px;
            overflow-y: auto;
            '>

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
 $body .= "</div>";  //end of 'col-xs-12'

$body .= "<div class='form-group' style=   'padding-left: 10px;
                                            padding-bottom: 5px;'>

            <span style='float:left; padding-top: 5px; font-weight:bold; color:#492365' >
            Select a Campus and a Building:</span>
            <div class='col-xs-3'>

            <select type='text' class='form-control' id='pickCampus' >
                <option value='-1'>Campus...</option>
                <option value='0'>Online</option>";
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
                  <option value='0'>Building...</option> </select>";


$body .= "
        </div>  <!-- end of 'col-xs-3'  -->
        </div> <!-- end of 'form-group'  -->";

$body .= "
<script>
    $('#pickCampus').change(function(){
        var campusId = $(this).val();

        if ($('#pickCampus').val() == 0){           // 0 is the 'online' campus
            $('#pickBuilding').empty();
            $('#pickBuilding').append($('<option />').val(0).text('Online'));
            $('#pickBuilding').attr('disabled', 'true');
            $('#pickCampus:focus').blur();
            load_onlineSections();
        }else{
                $('#pickBuilding').removeAttr('disabled');
                $.ajax({
                      type: 'POST',
                      url: 'pickBuildings.php',        //the script to call to get data
                      data: 'campusId=' + campusId,    //you can insert url arguments here to pass to pickBuildings.php
                                                       //for example 'id=5&parent=6\"
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
            }

}); // end of pickCampus.change()




$('#pickBuilding').change(function(){

var buildingId = $(this).val();
load_buildingSections(buildingId);

$('#pickBuilding:focus').blur();

}); // end of pickBuilding.onChange()

</script>";



$body .= "<div  class='col-xs-12'
                id='classroomOverviewSchedule'
                style='
                background-color: #fff;
                margin-top: 15px;
                padding-top: 15px;
                margin-bottom: 50px;
                border-bottom: 1px solid #492365;
                border-top: 1px solid #492365'>
                </div>";  // this div holds the schedule showing all classrooms




/*
 *  javascript function displayClassroomSchedule is defined in
 *  classroomCalendar.js.  Here the calendar is initialized as blank.
 */

$body .= "<script>displayClassroomSchedule([], false);</script>";

echo $body;






function addClassroom(Classroom $classroom, Database $db){
    $roomId = $classroom->getClassroomID();
    $eventObjects = array();

    // $daysToDates maps section weekdays to the dates that position the courses on the
    // individual classroom's fullCalendar schedule
    $daysToDates = array("Mon"=>"2016-11-07", "Tues" => "2016-11-08", "Wednes" => "2016-11-09",
        "Thurs" => "2016-11-10", "Fri" => "2016-11-11", "Satur" => "2016-11-12" , "online"=> "2016-11-13");

    $classroomSections = $db->getClassroomSections($classroom, null);
    foreach($classroomSections as $section){
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
        //$classroom = $section->getSectionProperty('classroom_number', 'Classroom', 'classroom_id', 'classroomID');
        $profFirst = $section->getSectionProperty('prof_first', 'Professor', 'prof_id', 'profID');
        $profLast = $section->getSectionProperty('prof_last', 'Professor', 'prof_id', 'profID');
        $prof = $profFirst . " " . $profLast;
        $isOnline = $section->getIsOnline();

        foreach($days as $day){
            array_push($eventObjects, json_encode(array(
                "title" => $title,
                "name"=> $name,
                "start" => $daysToDates[$day]."T".$eventStart,
                "end" => $daysToDates[$day]."T".$eventEnd,
                "location" => $location,
                //"classroom" => $classroom,
                "professor" => $prof,
                "online" => $isOnline
            )));
        }

    }

    //<tr> (room record)        id = record_classRoom<#>  //if it doesn't end in _room# then it won't toggle 'hide'
    //<img> (pencil)            id = pencil_room<#>
    //<span> (little arrow):    id = seeCal_room<#>
    //<tr> (contains cal div):  id = calRow_room<#>
    //<div> (contains cal)      id = cal_room<#>
    //<tr>  (editing div)       id = edit_room<#>
    //<img> (disc)              id = save_room<#>



    //Here's where we create the table of Classrooms on the "Classroom Page".
    $row = "<tr id='record_classRoom{$classroom->getClassroomID()}'>
			<td>{$classroom->getClassroomProperty_Join_3('campus_name', 'Building', 'Campus',
                'building_id', 'campus_id', 'buildId')}</td>
			<td>{$classroom->getClassroomProperty('building_name', 'Building', 'building_id', 'buildId')}</td>
			<td><small><em>{$classroom->getClassroomNum()}</em></small></td>
			<td> {$classroom->getClassroomCap()}</td>
			<td>{$classroom->getNumWorkstations()}</td>
			<td>
			<img src='img/pencil.png' class='action-edit' id='pencil_room{$classroom->getClassroomID()}'/>
			<img src='img/close.png' class='action-delete'>

            <!--this span *is* the little up/down arrow that shows/hides individual prof calendar-->
			<!--so the span itself has a onClick() set on it -->
			    <span id='seeCal_room{$classroom->getClassroomID()}'
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
    $row .= "<tr class='hide' id='calRow_room{$classroom->getClassroomID()}'>
                <td colspan='8' style='padding:0'>
                <!-- roomCalendar_<id>:  the div that the individual calendar lives in. -->
                <div class='indRoomCal' id='cal_room{$classroom->getClassroomID()}'></div>
                </td>
            </tr>
            <!--<tr style='display:none'></tr>-->


           <tr class='hide' id='edit_room{$classroom->getClassroomID()}'>
            <td>
            <label for='inlineEdit_roomBuilding{$roomId}'>Campus/Building</label>
                        <select style='margin-bottom: 6px' type='text' class='form-control' id='inlineEdit_roomBuilding{$roomId}' >";

                            $selectBuilding = $db->getdbh()->prepare(
                                'SELECT ZAAMG.Campus.campus_id, campus_name, building_name, building_id
                                  FROM ZAAMG.Campus JOIN ZAAMG.Building
                                  ON ZAAMG.Campus.campus_id = ZAAMG.Building.campus_id
                                  ORDER BY campus_name ASC');
                            $selectBuilding->execute();
                            $result = $selectBuilding->fetchAll();

                            foreach($result as $room){
                                if ($room['building_id'] == $classroom->getBuildingId()){
                                    $row.= "<option selected value=".$room['building_id'].">"
                                        .$room['campus_name'].": ".$room['building_name']."</option>";
                                }else{
                                    $row.= "<option value=".$room['building_id'].">"
                                        .$room['campus_name'].": ".$room['building_name']."</option>";
                                }
                            }
$row.="
                        </select>
            </td>
            <td></td>
            <td>
                <label for='inlineEdit_roomNumber{$roomId}'>Room Number</label>
                        <input type='text'  class='form-control' id='inlineEdit_roomNumber{$roomId}'
                         placeholder='{$classroom->getClassroomNum()}' style='width: 60%; margin-bottom: 6px'>
            </td>
            <td>
                <label for='inlineEdit_roomCap{$roomId}'>Capacity</label>
                        <input type='number' class='form-control' id='inlineEdit_roomCap{$roomId}'
                        placeholder='{$classroom->getClassroomCap()}' min=1 style='width: 60%; margin-bottom: 6px'>
            </td>
            <td>
                <label for='inlineEdit_roomComputers{$roomId}'>Computers</label>
                <input type='number'  class='form-control' id='inlineEdit_roomComputers{$roomId}'
                        placeholder='{$classroom->getNumWorkstations()}' min=1 style='width: 60%; margin-bottom: 6px'>
            </td>
            <td style='padding-bottom: 1%'><img src='img/save.png' width='30px' class='action-save hide'
                id='save_room{$classroom->getClassroomID()}'/>
            </td>
          </tr>
            ";


    return $row;  //finally the long $row string can be echoed
}


















 /* load_ClassroomSet is defined in this file (classroom_page.php)
  * It loads the array defined just above here (theClassroomSet).
  * theClassroomSet array will next be sent to javascript function
  * displayClassroomOverviewSchedule(theClassroomSet), which constructs
  * and displays the fullCalendar overview schedule of all rooms.
  */
 //$body .= load_ClassroomSet($database->getClassroomsInBuilding(6), $database);//not really used