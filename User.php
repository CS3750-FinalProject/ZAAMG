<?php

#NOTE: I wrote this at work without a ide, so if there are typos or it is otherwise weird, please fix it. Thanks!

require_once "Database.php";

class User {
    private $username;
    private $password;
    public static $ADMIN_FLAG = 0;
    public static $USER_FLAG = 1;
    public static $VIEWER_FLAG = 2;
    /*I'm not sure about these names. do they make sense? Essentially, we have a "adminstrator,"
    some one who can change anything, a "user" who can change their own program, and a viewer that
    can see everything, but can't change it.*/
    private $userType;
    
    public static __construct($username, $password, $userType = $this->$VIEWER_FLAG) {
        $this->username = $username;
        $this->password = $password;
        if($userType < 0 || $usertype > 3) {
            echo "Error. Invalid User Type entered, user will be a viewer."
            $userType = $this->VIEWER_FLAG;
        }
        else $this->userType = $userType;
    }
}
