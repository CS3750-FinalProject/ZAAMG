<?php

require_once 'Database.php';

class Professor{
    private $database;

    private $profId;
    private $profFirst;
    private $profLast;
    private $profEmail;
    private $profRequiredHours;
    private $profRelease;
    private $deptId;


    public function __construct($profId, $profFirst, $profLast, $profEmail, $profRequiredHours,
                                 $profRelease, $deptId) {

        /*$index_atSymbol = strpos($profEmail, "@");
        $fixed_profEmail = substr_replace($profEmail, "\\@", $index_atSymbol, 1);
        echo $fixed_profEmail;*/

        $this->profId = $profId;
        $this->profFirst = $profFirst;
        $this->profLast = $profLast;
        $this->profEmail = $profEmail;
        $this->profRequiredHours = $profRequiredHours;
        $this->profRelease = $profRelease;
        $this->deptId = $deptId;

        $this->database = new Database();
    }

    public function getProfId(){
        return $this->profId;
    }

    public function getProfFirst(){
        return $this->profFirst;
    }

    public function getProfLast(){
        return $this->profLast;
    }

    public function getProfEmail(){
        return $this->profEmail;
    }


    public function getProfRequiredHours()
    {
        return $this->profRequiredHours;
    }



    public function getProfRelease()
    {
        return $this->profRelease;
    }


    public function getDeptId(){
        return $this->deptId;
    }

    public function insertNewProfessor(){
        $dbh = $this->database->getdbh();
        $stmtInsert = $dbh->prepare(
            "INSERT INTO W01143557.Professor VALUES (
              :id, :first, :last, :email, :reqhours,  :release, :deptId)");
        # send NULL for course_id because the database auto-increments it
        $stmtInsert->bindValue("id", NULL);
        $stmtInsert->bindValue(":first", $this->profFirst);
        $stmtInsert->bindValue(":last", $this->profLast);
        $stmtInsert->bindValue(":email", strtolower($this->profEmail));
        $stmtInsert->bindValue(":reqhours", $this->profRequiredHours);
        $stmtInsert->bindValue(":release", $this->profRelease);
        $stmtInsert->bindValue(":deptId", $this->deptId);
        try {
            $stmtInsert->execute();
            echo "Success executing Insert";
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }


    public function professorExists($profEmail){
        $profEmail_lower = strtolower($profEmail);
        $dbh = $this->database->getdbh();
        $stmtSelect = $dbh->prepare(
            "SELECT prof_id FROM W01143557.Professor
              WHERE prof_email = '$profEmail_lower'");
        try {
            $stmtSelect->execute();
            $result = $stmtSelect->fetch(PDO::FETCH_ASSOC);
            if ($result != NULL) {
                return "does exist";
            }else{
                return "does not exist";
            }
        } catch (Exception $e) {
            echo "Here's what went wrong: ".$e->getMessage();
        }
    }


    /*  Returns:    A Professor property that has to be looked up in another table.
         *  Ex:         A Professor table record contains a department name, but the department name
         *              must be looked up in the Department table.
         *  Args:
         *          $sql_property:      the database column name of the desired property,
         *                              eg, classroom_name
         *          $table:             the database table to get the property from, eg. Department
         *          $id:                the foreign key id number, eg. dept_id stored in Professor record
         *          $object_property:   the php Professor object attribute used as foreign key id,
         *                              eg "deptId" if using $professor->deptId
         */
    public function getProfessorProperty($sql_property, $table, $id, $object_property){
        $dbh = $this->database->getdbh();
        $stmtSelect = $dbh->prepare(
            "SELECT {$sql_property} FROM W01143557.{$table}
              WHERE {$id} = ".$dbh->quote($this->{$object_property}));
        try{
            $stmtSelect->execute();
            $result = $stmtSelect->fetch();
            return $result[0];
        }catch (Exception $e){
            echo "getProfessorProperty: ".$e->getMessage();
        }
    }

}