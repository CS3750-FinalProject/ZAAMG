<?php
require_once 'Section.php';
require_once 'Course.php';
require_once 'Semester.php';
require_once 'Classroom.php';
require_once 'Campus.php';

class Database
{
    private $host = "localhost";
    private $dbname  = "W01143557";
    private $username = "W01143557";
    private $password = "Ashtoncs!";
    private $dbh; //let's not expose the database

    public function __construct() {

        try {
            $this->dbh = new PDO("mysql:host=$this->host;dbname:$this->dbname", $this->username, $this->password);
            $this->dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            #echo "<br>Success creating Database Object<br>";
        } catch (PDOException $e) {
            echo "Error creating Database Object";
            die(); //if the database fails, don't go on.
        }
    }





    /*  Returns:            an array of Section objects, one per Section record in database
                            with sem_id matching the $semesterId
     *  Args:
     *      $semesterId:   the semester for which Sections should be retrieved
     */
    public function getAllSections($semesterId, $orderBy){
        $allSections = [];
        $dbh = $this->getdbh();
        switch($orderBy){
            case "course":
                $stmtSelect = $dbh->prepare("
                SELECT s.*, c.course_prefix, c.course_number
                FROM W01143557.Section s JOIN W01143557.Course c
                ON s.course_id = c.course_id and s.sem_id = $semesterId
                ORDER BY course_prefix, course_number");
            break;
            case "prof":
                $stmtSelect = $dbh->prepare("
                SELECT s.*, p.prof_first, p.prof_last
                FROM W01143557.Section s JOIN W01143557.Professor p
                ON s.prof_id = p.prof_id and s.sem_id = $semesterId
                ORDER BY prof_last, prof_first");
                break;
            case "room":
                $stmtSelect = $dbh->prepare("
                SELECT s.*, r.classroom_number, b.building_code, c.campus_name
                FROM W01143557.Section s JOIN W01143557.Classroom r
                ON s.classroom_id = r.classroom_id and s.sem_id = $semesterId
                JOIN W01143557.Building b ON r.building_id = b.building_id
                JOIN W01143557.Campus c ON b.campus_id = c.campus_id
                ORDER BY campus_name, building_code, classroom_number");
                break;
        }

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
        //$prepString = 'SELECT * FROM W01143557.Professor'.
          //  $orderBy != null ? ' ORDER BY '.$orderBy : '';
        $stmtSelect = $dbh->prepare('SELECT * FROM W01143557.Professor ORDER BY '.$orderBy);
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
        $stmtSelect = $dbh->prepare('SELECT * FROM W01143557.Classroom c
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


    public function getSumScheduledCredits($profId){
        $dbh = $this->getdbh();
        $stmtSum = $dbh->prepare("SELECT SUM(c.course_credits)
                                FROM W01143557.Section s JOIN W01143557.Course c
                                ON s.course_id = c.course_id
                                WHERE s.prof_id = :profId
                                AND sem_id = :semId");
        $stmtSum->bindValue(":profId", $profId);
        $stmtSum->bindValue(":semId", $_SESSION['mainSemesterId']);

        $stmtSum->execute();
        $sumResult = $stmtSum->fetchAll()[0];
        $sum = $sumResult[0];
        return $sum;
    }



    public function getAllClassrooms(){
        $allClassrooms = [];
        $dbh = $this->getdbh();
        $stmtSelect = $dbh->prepare("SELECT classroom_id, classroom_number, classroom_capacity, classroom_workstations,
                                      room.building_id, building_name, campus_name
                                      FROM W01143557.Classroom room
                                      JOIN W01143557.Building bld ON room.building_id = bld.building_id
                                      JOIN W01143557.Campus camp ON bld.campus_id = camp.campus_id
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

    public function getProfSections($prof, $semesterId){
        $profSections = [];
        $dbh = $this->getdbh();
        $stmtSelect = $dbh->prepare("SELECT * FROM W01143557.Section
                                      WHERE prof_id = {$prof->getProfId()}
                                      AND sem_id = {$semesterId}");
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



    public function getClassroomSections($classroom, $semesterId){
        $classroomSections = [];
        $dbh = $this->getdbh();
        $stmtSelect = $dbh->prepare("SELECT * FROM W01143557.Section
                                      WHERE classroom_id = {$classroom->getClassroomID()}
                                      AND sem_id = {$semesterId}");
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


    public function getOnlineSections($semesterId){
        $onlineSections = [];
        $dbh = $this->getdbh();
        $stmtSelect = $dbh->prepare("
            SELECT  section_id, s.course_id, prof_id, classroom_id, sem_id, section_days,
                    section_start_time, section_end_time, section_is_online, section_block, section_capacity,
                    course_prefix, course_number
            FROM    W01143557.Section s JOIN W01143557.Course c
            ON      s.course_id = c.course_id
            AND     section_is_online = 1
            AND     sem_id = {$semesterId}
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
        $stmtSelect = $dbh->prepare("SELECT * FROM W01143557.Course
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
        $stmtSelect = $dbh->prepare("SELECT * FROM `W01143557`.`Classroom` JOIN `W01143557`.`Building` ON `building_id`
            JOIN `W01143557`.`Campus` ON `campus_id`");
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
        $stmtSelect = $dbh->prepare("SELECT * FROM `W01143557`.`Semester`
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
        $stmtSelect = $dbh->prepare("SELECT * FROM `W01143557`.`Campus`
                                      WHERE `campus_id` = {$dbh->quote($id)}");
        try{
            $stmtSelect->execute();
            $result = $stmtSelect->fetch();

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
        $stmtSelect = $dbh->prepare("SELECT * FROM `W01143557`.`Building`
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
        $stmtSelect = $dbh->prepare("SELECT * FROM `W01143557`.`Department`
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
