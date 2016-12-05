<?php

require_once '../Database.php';
require_once '../Department.php';


$resource = isset($_POST['resource']) ? $_POST['resource'] : "not entered";

foreach ($_POST as $item){
    strip_tags($item);
}

$database = new Database();
$dbh = $database->getdbh();

$resource_json = [];

switch($resource){
    case "departments":
        $selectStmt = $dbh->prepare("
          SELECT * FROM W01143557.Department
            ORDER BY dept_code");
        $selectStmt->execute();
        $resources = $selectStmt->fetchAll();
        foreach($resources as $resource){
            $resource_json[] = array(
                'id'=>$resource['dept_id'],
                'name'=>$resource['dept_name'],
                'code'=>$resource['dept_code']
            );
        }
    break;
    case "campus":
        $selectStmt = $dbh->prepare("
          SELECT * FROM W01143557.Campus
            ORDER BY campus_name");
        $selectStmt->execute();
        $resources = $selectStmt->fetchAll();
        foreach($resources as $resource){
            $resource_json[] = array(
                'id'=>$resource['campus_id'],
                'name'=>$resource['campus_name']
            );
        }
    break;
    case "campus_buildings":
        $selectBuilding = $database->getdbh()->prepare(
            "SELECT W01143557.Campus.campus_id, campus_name, building_name, building_id, building_code
                                  FROM W01143557.Campus JOIN W01143557.Building
                                  ON W01143557.Campus.campus_id = W01143557.Building.campus_id
                                  ORDER BY campus_name ASC");
        $selectBuilding->execute();
        $resources = $selectBuilding->fetchAll();
        foreach($resources as $resource){
            $resource_json[] = array(
                'building_id'=>$resource['building_id'],
                'campus'=>$resource['campus_name'],
                'campusId'=>$resource['campus_id'],
                'building_name'=>$resource['building_name'],
                'building_code'=>$resource['building_code']
            );
        }
    break;
    case "courses":
        $selectCourse = $database->getdbh()->prepare(
            "SELECT course_id, course_prefix, course_number, course_title
                  FROM W01143557.Course
                  ORDER BY course_prefix, course_number");
            $selectCourse->execute();
            $courses = $selectCourse->fetchAll();

            foreach($courses as $course){
                $resource_json[] = array(
                    'id'=>$course['course_id'],
                    'pref'=>$course['course_prefix'],
                    'num'=>$course['course_number'],
                    'title'=>$course['course_title']
                );
            }
    break;
    case "professors":
        $selectProf = $database->getdbh()->prepare(
            'SELECT prof_id, prof_first, prof_last FROM W01143557.Professor
                                  ORDER BY prof_last ASC');
        $selectProf->execute();
        $profs = $selectProf->fetchAll();

        foreach($profs as $prof){
            $resource_json[] = array(
                'id'=>$prof['prof_id'],
                'first'=>$prof['prof_first'],
                'last'=>$prof['prof_last']
            );
        }
    break;
    case "rooms":
        $selectRoom = $database->getdbh()->prepare(
            "SELECT classroom_id, campus_name, building_name, classroom_number
                                  FROM W01143557.Campus c JOIN W01143557.Building b
                                  ON c.campus_id = b.campus_id
                                  JOIN W01143557.Classroom r
                                  ON b.building_id = r.building_id
                                  ORDER BY campus_name ASC");
        $selectRoom->execute();
        $classrooms = $selectRoom->fetchAll();

        foreach($classrooms as $classroom){
            $resource_json[] = array(
                'id'=>$classroom['classroom_id'],
                'campus'=>$classroom['campus_name'],
                'building'=>$classroom['building_name'],
                'number'=>$classroom['classroom_number']
            );
        }
    break;
    case "semesters":

        $selectSem = $database->getdbh()->prepare(
        'SELECT sem_id, sem_season, sem_year, sem_start_date, sem_num_weeks,
          sem_first_block_start_date, sem_second_block_start_date
                                  FROM W01143557.Semester
                                  ORDER BY sem_start_date DESC');
        $selectSem->execute();
        $sems = $selectSem->fetchAll();

        foreach($sems as $sem){
            $resource_json[] = array(
                'id'=>$sem['sem_id'],
                'year'=>$sem['sem_year'],
                'season'=>$sem['sem_season'],
                'start'=>$sem['sem_start_date'],
                'weeks'=>$sem['sem_num_weeks'],
                'firstStart'=>$sem['sem_first_block_start_date'],
                'secondStart'=>$sem['sem_second_block_start_date']
            );
        }
    break;



}

echo json_encode($resource_json);