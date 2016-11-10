<?php

include 'Database.php';

class Classroom {
    private $database;

    private $classroomID;
    private $classroomNum;
    private $classroomCapacity;
    private $numWorkstations;
    private $buildId;


    # removed types from formal arguments, don't think they're necessary
    public function __construct($classID, $classNum, $classCap, $numWorkstations=0, $buildId) {
        $this->classroomID = $classID;
        $this->classroomNum = $classNum;
        $this->classroomCapacity = $classCap;
        $this->numWorkstations = $numWorkstations;
        $this->buildId = $buildId;

        $this->database = new Database();
    }

    public function getClassroomID(){
        return $this->classroomID;
    }

    public function getClassroomNum(){
        return $this->classroomNum;
    }

    public function getClassroomCap(){
        return $this->classroomCapacity;
    }


    public function getNumWorkstations()
    {
        return $this->numWorkstations;
    }


    public function getBuildingId(){
        return $this->buildId;
    }

    public function insertNewClassroom(){
        $dbh = $this->database->getdbh();
        $stmtInsert = $dbh->prepare("INSERT INTO ZAAMG.Classroom VALUES (:id, :num, :cap, :stations, :buildId)");
        # send NULL for classroom_id because the database auto-increments it
        $stmtInsert->bindValue("id", NULL);
        $stmtInsert->bindValue(":num", $this->classroomNum);
        $stmtInsert->bindValue(":cap", $this->classroomCapacity);
        $stmtInsert->bindValue(":stations", $this->numWorkstations);
        $stmtInsert->bindValue(":buildId", $this->buildId);

            try {
                $stmtInsert->execute();

            echo "Success executing Insert";
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    public function classroomExists($classNum, $buildId){
        $dbh = $this->database->getdbh();
        $stmtSelect = $dbh->prepare(
            "SELECT classroom_id FROM ZAAMG.Classroom
              WHERE classroom_number = {$dbh->quote($classNum)} AND building_id = {$dbh->quote($buildId)}");
        try {
            $stmtSelect->execute();
            $result = $stmtSelect->fetch(PDO::FETCH_ASSOC);
            if ($result != NULL) {
                return "does exist";
            }else{
                return "does not exist";
            }
        } catch (Exception $e) {
            echo "classroomExists(): ".$e->getMessage();
        }
    }


    /*  Returns:    A Classroom property that has to be looked up in another table.
        *  Ex:         A Classroom table record contains a department name, but the department name
        *              must be looked up in the Department table.
        *  Args:
        *          $sql_property:      the database column name of the desired property,
        *                              eg, classroom_name
        *          $table:             the database table to get the property from, eg. Department
        *          $id:                the foreign key id number, eg. dept_id stored in Professor record
        *          $object_property:   the php Professor object attribute used as foreign key id,
        *                              eg "deptId" if using $professor->deptId
        */
    public function getClassroomProperty($sql_property, $table, $id, $object_property){
        $dbh = $this->database->getdbh();
        $stmtSelect = $dbh->prepare(
            "SELECT {$sql_property} FROM ZAAMG.{$table}
              WHERE {$id} = ".$dbh->quote($this->{$object_property}));
        try{
            $stmtSelect->execute();
            $result = $stmtSelect->fetch();
            return $result[0];
        }catch (Exception $e){
            echo "getClassroomProperty: ".$e->getMessage();
        }
    }






    /*  Returns:    A Classroom property that has to be looked up in the Classroom table
 *              joined to two other tables.
 *  Ex:         A Classroom table record contains a Campus with a name, but the campus_name
 *              must be looked up in the Campus table.  Classroom joins to Building
 *              joins to Campus.
 *  Args:
 *          $sql_property:      the database column name of the desired property,
 *                              eg, campus_name
 *          $table1:            the first database table to join to
 *          $table2:            the second database table to join to
 *                              (contains the desired property)
 *          $id1:               the foreign key id for the first join, eg. building_id
 *          $id2:               the foreign key id for the second join, eg. campus_id
 *          $object_property:   the php Classroom object attribute used as first foreign key id,
 *                              eg "buildId" if using $classroom->buildId
 */
    public function getClassroomProperty_Join_3($sql_property, $table1, $table2, $id1, $id2, $object_property){
        $dbh = $this->database->getdbh();
        $stmtSelect = $dbh->prepare(
            "SELECT {$sql_property} FROM ZAAMG.Classroom S
              JOIN ZAAMG.{$table1} A
              ON S.{$id1} = A.{$id1}
              JOIN ZAAMG.{$table2} B
              ON A.{$id2} = B.{$id2}
              WHERE S.{$id1} = ".$dbh->quote($this->{$object_property}));
        try{
            $stmtSelect->execute();
            $result = $stmtSelect->fetch();
            return $result[0];
        }catch (Exception $e){
            echo "getClassroomProperty: ".$e->getMessage();
        }
    }


    public function setClassroomID($classroomID)
    {
        $this->classroomID = $classroomID;
    }


    public function setClassroomNum($classroomNum)
    {
        $this->classroomNum = $classroomNum;
    }


    public function setClassroomCapacity($classroomCapacity)
    {
        $this->classroomCapacity = $classroomCapacity;
    }


    public function setNumWorkstations($numWorkstations)
    {
        $this->numWorkstations = $numWorkstations;
    }


    public function setBuildId($buildId)
    {
        $this->buildId = $buildId;
    }



}
