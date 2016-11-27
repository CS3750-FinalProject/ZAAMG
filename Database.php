<?php
require_once 'Section.php';
require_once 'Course.php';
require_once 'Semester.php';
require_once 'Classroom.php';

class Database
{
    private $host = "localhost";
    private $dbname  = "zaamg";
    private $username = "zaamg";
    private $dbh; //let's not expose the database

    public function __construct() {

        try {
            $this->dbh = new PDO("mysql:host=$this->host;dbname:$this->dbname", $this->username);
            $this->dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            #echo "<br>Success creating Database Object<br>";
        } catch (PDOException $e) {
            echo "Error creating Database Object";
            die(); //if the database fails, don't go on.
        }
    }


    /*  Returns:        an array of Section objects, one per Section record in database
     *  Args:
     *      $orderBy:   might need this for sorting the Sections different ways
     */
    public function getAllSections($orderBy){
        $allSections = [];
        $dbh = $this->getdbh();
        $stmtSelect = $dbh->prepare("SELECT * FROM ZAAMG.Section");
        try{
            $stmtSelect->execute();
            $result = $stmtSelect->fetchAll();
            foreach($result as $index=>$sectionRecord){
                $allSections[] = new Section(  //don't need to put an index number between those brackets, awesome
                    $sectionRecord['section_id'],
                    $sectionRecord['course_id'],
                    $sectionRecord['prof_id'],
                    $sectionRecord['classroom_id'],
                    $sectionRecord['section_block'],
                    $sectionRecord['section_days'],
                    $sectionRecord['section_start_time'],
                    $sectionRecord['section_end_time'],
                    $sectionRecord['section_is_online'],
                    $sectionRecord['sem_id'],
                    $sectionRecord['section_capacity']);
            }
            return $allSections;
        }catch(Exception $e){
            echo "getAllSections: ".$e->getMessage();
        }
    }



    public function getAllProfessors($orderBy){
        $allProfessors = [];
        $dbh = $this->getdbh();
        //$prepString = 'SELECT * FROM ZAAMG.Professor'.
          //  $orderBy != null ? ' ORDER BY '.$orderBy : '';
        $stmtSelect = $dbh->prepare('SELECT * FROM ZAAMG.Professor ORDER BY '.$orderBy);
        try{
            $stmtSelect->execute();
            $result = $stmtSelect->fetchAll();
            foreach($result as $index=>$profRecord){
                $allProfessors[] = new Professor(  //don't need to put an index number between those brackets, awesome
                    $profRecord['prof_id'], $profRecord['prof_first'], $profRecord['prof_last'],
                    $profRecord['prof_email'],
                    $profRecord['prof_req_hours'],$profRecord['prof_rel_hours'],
                    $profRecord['dept_id']);
            }
            return $allProfessors;
        }catch(Exception $e){
            echo "getAllProfessors: ".$e->getMessage();
        }
    }


    public function getClassroomsInBuilding($buildingId){
        $allClassrooms = [];
        $dbh = $this->getdbh();
        $stmtSelect = $dbh->prepare('SELECT * FROM ZAAMG.Classroom c
                                      WHERE c.building_id = '.$dbh->quote($buildingId));
        try{
            $stmtSelect->execute();
            $result = $stmtSelect->fetchAll();
            foreach($result as $index=>$classroomRecord){
                $allClassrooms[] = new Classroom(  //don't need to put an index number between those brackets, awesome
                    $classroomRecord['classroom_id'],
                    $classroomRecord['classroom_number'],
                    $classroomRecord['classroom_capacity'],
                    $classroomRecord['classroom_workstations'],
                    $classroomRecord['building_id']);
            }
            return $allClassrooms;
        }catch(Exception $e){
            echo "getClassroomsOnCampus: ".$e->getMessage();
        }
    }



    public function getAllClassrooms($orderBy){
        $allClassrooms = [];
        $dbh = $this->getdbh();
        $stmtSelect = $dbh->prepare("SELECT classroom_id, classroom_number, classroom_capacity, classroom_workstations,
                                      room.building_id, building_name, campus_name
                                      FROM ZAAMG.Classroom room
                                      JOIN ZAAMG.Building bld ON room.building_id = bld.building_id
                                      JOIN ZAAMG.Campus camp ON bld.campus_id = camp.campus_id
                                      ORDER BY camp.campus_name, bld.building_name, room.classroom_number");
        try{
            $stmtSelect->execute();
            $result = $stmtSelect->fetchAll();
            foreach($result as $index=>$classroomRecord){
                $allClassrooms[] = new Classroom(  //don't need to put an index number between those brackets, awesome
                    $classroomRecord['classroom_id'],
                    $classroomRecord['classroom_number'],
                    $classroomRecord['classroom_capacity'],
                    $classroomRecord['classroom_workstations'],
                    $classroomRecord['building_id']);
            }
            return $allClassrooms;
        }catch(Exception $e){
            echo "getAllClassrooms: ".$e->getMessage();
        }
    }

    public function getProfSections($prof, $orderBy){
        $profSections = [];
        $dbh = $this->getdbh();
        $stmtSelect = $dbh->prepare("SELECT * FROM ZAAMG.Section
                                      WHERE prof_id = {$prof->getProfId()}");
        try{
            $stmtSelect->execute();

            $result = $stmtSelect->fetchAll();
            foreach($result as $index=>$sectionRecord){
                $profSections[] = new Section(  //don't need to put an index number between those brackets, awesome
                    $sectionRecord['section_id'],
                    $sectionRecord['course_id'],
                    $sectionRecord['prof_id'],
                    $sectionRecord['classroom_id'],
                    $sectionRecord['sem_id'],
                    $sectionRecord['section_days'],
                    $sectionRecord['section_start_time'],
                    $sectionRecord['section_end_time'],
                    $sectionRecord['section_is_online'],
                    $sectionRecord['section_block'],
                    $sectionRecord['section_capacity']);
            }
            return $profSections;
        }catch(Exception $e){
            echo "getProfSections: ".$e->getMessage();
        }
    }



    public function getClassroomSections($classroom){
        $classroomSections = [];
        $dbh = $this->getdbh();
        $stmtSelect = $dbh->prepare("SELECT * FROM ZAAMG.Section
                                      WHERE classroom_id = {$classroom->getClassroomID()}");
        try{
            $stmtSelect->execute();

            $result = $stmtSelect->fetchAll();
            foreach($result as $index=>$sectionRecord){
                $classroomSections[] = new Section(  //don't need to put an index number between those brackets, awesome
                    $sectionRecord['section_id'],
                    $sectionRecord['course_id'],
                    $sectionRecord['prof_id'],
                    $sectionRecord['classroom_id'],
                    $sectionRecord['sem_id'],
                    $sectionRecord['section_days'],
                    $sectionRecord['section_start_time'],
                    $sectionRecord['section_end_time'],
                    $sectionRecord['section_is_online'],
                    $sectionRecord['section_block'],
                    $sectionRecord['section_capacity']);
            }
            return $classroomSections;
        }catch(Exception $e){
            echo "getClassroomSections: ".$e->getMessage();
        }
    }


    public function getOnlineSections(){
        $onlineSections = [];
        $dbh = $this->getdbh();
        $stmtSelect = $dbh->prepare("
            SELECT  section_id, s.course_id, prof_id, classroom_id, sem_id, section_days,
                    section_start_time, section_end_time, section_is_online, section_block, section_capacity,
                    course_prefix, course_number
            FROM    ZAAMG.Section s JOIN ZAAMG.Course c
            ON      s.course_id = c.course_id
            AND     section_is_online = 1
            ORDER BY  course_prefix, course_number");

        try{
            $stmtSelect->execute();

            $result = $stmtSelect->fetchAll();
            foreach($result as $index=>$sectionRecord){
                $onlineSections[] = new Section(  //don't need to put an index number between those brackets, awesome
                    $sectionRecord['section_id'],
                    $sectionRecord['course_id'],
                    $sectionRecord['prof_id'],
                    $sectionRecord['classroom_id'],
                    $sectionRecord['sem_id'],
                    $sectionRecord['section_days'],
                    $sectionRecord['section_start_time'],
                    $sectionRecord['section_end_time'],
                    $sectionRecord['section_is_online'],
                    $sectionRecord['section_block'],
                    $sectionRecord['section_capacity']);
            }
            return $onlineSections;
        }catch(Exception $e){
            echo "getOnlineSections: ".$e->getMessage();
        }
    }





    public function getCourse($section){
        $theCourse = null;
        $dbh = $this->getdbh();
        $stmtSelect = $dbh->prepare("SELECT * FROM ZAAMG.Course
                                      WHERE course_id = {$section->getCourseID()}");
        try{
            $stmtSelect->execute();

            $courseRecord = $stmtSelect->fetchAll()[0];

                $theCourse = new Course(  //don't need to put an index number between those brackets, awesome
                    $courseRecord['course_id'],
                    $courseRecord['course_prefix'],
                    $courseRecord['course_number'],
                    $courseRecord['course_title'],
                    $courseRecord['course_credits'],
                    $courseRecord['dept_id']);

            return $theCourse;
        }catch(Exception $e){
            echo "getProfSections: ".$e->getMessage();
        }
    }


    public function getClassroomsWithAll($orderBy = null){
        $allClassrooms = [];
        $dbh = $this->getdbh();
        $stmtSelect = $dbh->prepare("SELECT * FROM `ZAAMG`.`Classroom` JOIN `ZAAMG`.`Building` ON `building_id`
            JOIN `ZAAMG`.`Campus` ON `campus_id`");
        try{
            $stmtSelect->execute();
            $result = $stmtSelect->fetchAll();
            foreach($result as $index=>$classroomRecord){
                $allClassrooms[] = new Classroom(  //don't need to put an index number between those brackets, awesome
                    $classroomRecord['classroom_id'], $classroomRecord['classroom_number'],
                    $classroomRecord['building_name'], $classroomRecord['campus_name'],
                    $classroomRecord['classroom_capacity'],
                    $classroomRecord['classroom_workstations'],
                    $classroomRecord['building_id']);
            }
            return $allClassrooms;
        }catch(Exception $e){
            return "getClassroomsWithAll: ".$e->getMessage();
        }
    }

    public function getSemester($id){
        $theSemester = null;
        $dbh = $this->getdbh();
        $stmtSelect = $dbh->prepare("SELECT * FROM `ZAAMG`.`Semester`
                                      WHERE `sem_id` = {$dbh->quote($id)}");
        try{
            $stmtSelect->execute();
            $result = $stmtSelect->fetchAll()[0];
            $theSemester = new Semester(
                $result['sem_id'],
                $result['sem_year'],
                $result['sem_season'],
                $result['sem_num_weeks'],
                $result['sem_start_date'],
                $result['sem_first_block_start_date'],
                $result['sem_second_block_start_date']);

            return $theSemester;
        }catch(Exception $e){
            return "getSemester: ".$e->getMessage();
        }
    }


    public function getCampus($id){
        $theCampus = null;
        $dbh = $this->getdbh();
        $stmtSelect = $dbh->prepare("SELECT * FROM `ZAAMG`.`Campus`
                                      WHERE `campus_id` = {$dbh->quote($id)}");
        try{
            $stmtSelect->execute();
            $result = $stmtSelect->fetchAll()[0];
            $theCampus = new Campus(
                $result['campus_id'],
                $result['campus_name']);

            return $theCampus;
        }catch(Exception $e){
            return "getCampus: ".$e->getMessage();
        }
    }

    public function getBuilding($id){
        $theBuilding = null;
        $dbh = $this->getdbh();
        $stmtSelect = $dbh->prepare("SELECT * FROM `ZAAMG`.`Building`
                                      WHERE `building_id` = {$dbh->quote($id)}");
        try{
            $stmtSelect->execute();
            $result = $stmtSelect->fetchAll()[0];
            $theBuilding = new Building(
                $result['building_id'],
                $result['building_code'],
                $result['building_name'],
                $result['campus_id']);

            return $theBuilding;
        }catch(Exception $e){
            return "getBuilding: ".$e->getMessage();
        }
    }


    public function getDepartment($id){
        $theDepartment = null;
        $dbh = $this->getdbh();
        $stmtSelect = $dbh->prepare("SELECT * FROM `ZAAMG`.`Department`
                                      WHERE `dept_id` = {$dbh->quote($id)}");
        try{
            $stmtSelect->execute();
            $result = $stmtSelect->fetchAll()[0];
            $theDepartment = new Department(
                $result['dept_id'],
                $result['dept_name'],
                $result['dept_code']
            );

            return $theDepartment;
        }catch(Exception $e){
            return "getDepartment: ".$e->getMessage();
        }
    }


    public function getdbh(){
        return $this->dbh;
    }
}
