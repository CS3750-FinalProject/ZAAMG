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
                    $sectionRecord['section_id'], $sectionRecord['course_id'], $sectionRecord['prof_id'], $sectionRecord['classroom_id'],
                    $sectionRecord['sem_id'],$sectionRecord['section_days'], $sectionRecord['section_start_time'], $sectionRecord['section_end_time'],
                    $sectionRecord['section_block'], $sectionRecord['section_capacity']);
            }
            return $allSections;
        }catch(Exception $e){
            echo "getAllSections: ".$e->getMessage();
        }
    }


    
    public function getdbh(){
        return $this->dbh;
    }
}
