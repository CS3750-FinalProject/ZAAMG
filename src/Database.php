<?php


class Database
{
    private $host = "localhost";
    private $dbname  = "zaamg";
    private $username = "zaamg";
    public $dbh;

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



}