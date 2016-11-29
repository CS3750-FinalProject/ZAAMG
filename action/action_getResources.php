<?php

require_once '../Database.php';
require_once '../Department.php';


$resource = isset($_POST['resource']) ? $_POST['resource'] : "not entered";


$database = new Database();
$dbh = $database->getdbh();

$resource_json = [];

switch($resource){
    case "departments":
        $selectStmt = $dbh->prepare("
          SELECT * FROM ZAAMG.Department
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
    case "campus_buildings":
        $selectBuilding = $database->getdbh()->prepare(
            "SELECT ZAAMG.Campus.campus_id, campus_name, building_name, building_id
                                  FROM ZAAMG.Campus JOIN ZAAMG.Building
                                  ON ZAAMG.Campus.campus_id = ZAAMG.Building.campus_id
                                  ORDER BY campus_name ASC");
        $selectBuilding->execute();
        $resources = $selectBuilding->fetchAll();
        foreach($resources as $resource){
            $resource_json[] = array(
                'building_id'=>$resource['building_id'],
                'campus'=>$resource['campus_name'],
                'building_name'=>$resource['building_name']
            );
        }
    break;
    case "courses":
        $selectCourse = $database->getdbh()->prepare(
            "SELECT course_id, course_prefix, course_number, course_title
                  FROM ZAAMG.Course
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
            'SELECT prof_id, prof_first, prof_last FROM ZAAMG.Professor
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
                                  FROM ZAAMG.Campus c JOIN ZAAMG.Building b
                                  ON c.campus_id = b.campus_id
                                  JOIN ZAAMG.Classroom r
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
        'SELECT sem_id, sem_season, sem_year, sem_start_date
                                  FROM ZAAMG.Semester
                                  ORDER BY sem_start_date DESC');
        $selectSem->execute();
        $sems = $selectSem->fetchAll();

        foreach($sems as $sem){
            $resource_json[] = array(
                'id'=>$sem['sem_id'],
                'year'=>$sem['sem_year'],
                'season'=>$sem['sem_season']
            );
        }
    break;



}

echo json_encode($resource_json);