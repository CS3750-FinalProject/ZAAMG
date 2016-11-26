<?php

//require_once 'Database.php';
require_once 'classroom_page.php';

$database = new Database();
$dbh = $database->getdbh();

$buildingId = isset($_POST['buildingId']) ? $_POST['buildingId'] : "not entered";

$classrooms = $database->getClassroomsInBuilding($buildingId);

//$script = "<script>
$script = "

var theClassroomSet = []; ";

foreach($classrooms as $classroom){
        /*
         *  function add_toClassroomSet(profFirst (string), profLast (string), profId (int),
         *                          timedCourseObjects (array of JSON objects,
         *                          onlineCourseObjects (array of JSON objects)
         *  defined in classroomSet.js
         */
        $script.='
            add_toClassroomSet(theClassroomSet, '  /*  first argument:  */
            .$dbh->quote($classroom->getClassroomNum()).','.'
                [ ';  //  <--  opening square bracket for the \"timed courses\" JSON obj. array
        $sections = $database->getClassroomSections($classroom);

        // looping through each of one classroom's sections...
        foreach($sections as $oneSection){
            $course = $database->getCourse($oneSection);
            $script .= '
            { pref:  '.$dbh->quote($course->getCoursePrefix()).',
              num:   '.$dbh->quote($course->getCourseNumber()).',
              days:  '.$dbh->quote($oneSection->getDayString()).',
              startTime:  '.$dbh->quote($oneSection->getStartTime()).',
              endTime:  '.$dbh->quote($oneSection->getEndTime()).',
            },';  // (just wrote one JSON object for each section)
        }

        $script.= ' ]); '; // end of sending arguments to add_toClassroomSet()
    }



/*
 *  javascript function displayClassroomSchedule is defined in
 *  classroomCalendar.js
 */
$script .= 'displayClassroomSchedule(theClassroomSet);
            console.log("theClassroomSet.length: " + theClassroomSet.length);';

echo $script;

