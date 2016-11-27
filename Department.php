<?php
require_once 'Database.php';

class Department {
    private $database;

    private $deptID;
    private $deptName;
    private $deptCode;


    public function getDepartmentID(){
        return $this->deptID;
    }

    public function getDepartmentName(){
        return $this->deptName;
    }

    public function getDepartmentCode(){
        return $this->deptCode;
    }

    public function __construct($deptID, $deptName, $deptCode) {
        $this->database = new Database();

        $this->deptID = $deptID;
        $this->deptName = $deptName;
        $this->deptCode = $deptCode;
    }

    public function insertNewDepartment(){
        $dbh = $this->database->getdbh();
        $stmtInsert = $dbh->prepare("INSERT INTO ZAAMG.Department VALUES (:id, :deptName, :code)");
        # send NULL for Department_id because the database auto-increments it
        $stmtInsert->bindValue(":id", NULL);
        $stmtInsert->bindValue(":deptName", $this->deptName);
        $stmtInsert->bindValue(":code", $this->deptCode);
        $stmtInsert->execute();
    }

    public function departmentExists($deptCode, $deptName){
        $dbh = $this->database->getdbh();
        $stmtSelect = $dbh->prepare(
            "SELECT dept_id FROM ZAAMG.Department
              WHERE dept_code = ".$dbh->quote($deptCode)."
              OR dept_name = ".$dbh->quote($deptName));
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
}


