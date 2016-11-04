<?php
require_once 'Section.php';

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
            return;
        }
    }

    public function getAllSections($orderBy){
        $allSections = [];
        $dbh = $this->getdbh();
        $stmtSelect = $dbh->prepare("SELECT * FROM ZAAMG.Section");
        try{
            $stmtSelect->execute();
            $result = $stmtSelect->fetchAll();
            foreach($result as $index=>$section){
                $allSections[$index] = new Section(
                    $section['section_id'], $section['course_id'], $section['prof_id'], $section['classroom_id'],
                    $section['sem_id'],$section['section_days'], $section['section_start_time'], $section['section_end_time'],
                    $section['section_block'], $section['section_capacity']);
            }
            return $allSections;
        }catch(Exception $e){
            echo "Here's what went wrong this time: ".$e->getMessage();
        }
    }


    
    public function getdbh(){
        return $this->dbh;
    }
}
