<?php

require_once "Database.php";
require_once "Token.php";
require_once "vendor/autoload.php";

/*$username = $_POST['username'] ?? 'invalid';
$password = $_POST['password'];

//strip html code from user input to keep from hacking
strip_tags($username);
strip_tags($password);

$user = new User($username, $password, User::$ADMIN_FLAG);
if(!$user->validateUser($username,$password)){
    echo "<h3>Invalid user! Please Try again</h3>";
    die();
}
$token = new Token();
$token = $token->buildToken(Token::ROLE_ADMIN, $username);

header("Location: index_lollar.php");
die();*/

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
    
    public function __construct($username, $password, $userType = -1) {
        $this->username = $username;
        $this->password = $password;
        if($userType < 0 || $userType > 3) {
            echo "Error. Invalid User Type entered, user will be a viewer.";
            $userType = User::$VIEWER_FLAG;
        }
        $this->userType = $userType;
    }

    public function validateUser(string $username, string $password) : bool {
        if($username === 'admin' && $password === 'zaamg'){ //yes, there are
            // 3 =, to check that the username and password is exactly what
            // they should be.
            return true;
        }
        return false;
    }
}
