<?php

#http://stackoverflow.com/questions/27139963/bootstrap-multiple-pages-divs-and-navbar
require_once 'Database.php';
require_once 'Token.php';
require_once 'User.php';
require_once 'vendor/autoload.php';

$username = $_POST['username'] ?? 'invalid';
$password = $_POST['password'] ?? 'invalid';

//strip html code from user input to keep from hacking
strip_tags($username);
strip_tags($password);

$user = new User($username, $password, User::$ADMIN_FLAG);
if(!$user->validateUser($username,$password)){
    echo "<h3>Invalid user! Please Try again</h3>";
    echo "<p><a href='index.html'>Click Here</a> to login</p>";
    die();
}
$token = new Token();
$token = $token->buildToken(Token::ROLE_ADMIN, $username);

/*if(Token::getRoleFromToken() != Token::ROLE_ADMIN){
    echo "<h3>Please login.</h3>";
    die();
}*/
$database = new Database();

session_start();

if (!isset($_SESSION['mainSemesterId'])) {
    $_SESSION['mainSemesterId'] = 2;
}
if (!isset($_SESSION['mainSemesterLabel'])) {
    $_SESSION['mainSemesterLabel'] = "Spring 2017";
}

$body = "
<!DOCTYPE html>
<html>
  <head>
    <link href='css/bootstrap.min.css' rel='stylesheet' />
    <link href='css/application.css' rel='stylesheet' />
   <!-- <link href='css/fullcalendar.css' rel='stylesheet' />-->
    <link href='css/fullcalendar_custom.css' rel='stylesheet' />
    <script src='js/jquery.min.js' charset='utf-8'></script>
    <script src='js/jquery-3.1.1.min.js' charset='utf-8'></script>
    <script src='js/bootstrap.min.js' charset='utf-8'></script>
    <script src='js/moment.min.js' charset='utf-8'></script>
    <script src='js/fullcalendar.min.js' charset='utf-8'></script>
    <script src='js/inline-editing.js' charset='utf-8'></script>
    <script src='js/modal-editing.js' charset='utf-8'></script>
    <script src='js/professorSet.js' charset='utf-8'></script>
    <script src='js/processForm.js' charset='utf-8'></script>
    <script src='js/calendar.js' charset='utf-8'></script>
    <script src='js/ClassroomCalendar.js' charset='utf-8'></script>
    <script src='js/classroomSet.js' charset='utf-8'></script>
    <script src='js/list.js' charset='utf-8'></script>
    <script>

    $(document).ready(function() {
        $('#main_container').load('section_page.php');
      });
    </script>

  <title>Project ZAAMG &#9829;</title>
  </head>

  <body>
    <div class='page-top-banner'>
      <img src='img/wsu-logo.png' class='banner-logo' />
    </div>
    <nav class='navbar navbar-default'>
      <div class='container-fluid'>
        <div class='navbar-header'>
          <button type='button' class='navbar-toggle collapsed' data-toggle='collapse' data-target='#bs-example-navbar-collapse-1' aria-expanded='false'>
<span class='sr-only'>Toggle navigation</span>
            <span class='icon-bar'></span>
            <span class='icon-bar'></span>
            <span class='icon-bar'></span>
          </button>
          <a class='navbar-brand' href='#'>ZAAMG &#9829;</a>
        </div>
        <div class='collapse navbar-collapse' id='bs-example-navbar-collapse-1'>
          <ul class='nav navbar-nav'>
            <li id='navbar_sec' class='pointer active'><a  onclick='changePage(this)'>Section <span class='sr-only'>(current)</span></a></li>
            <li id='navbar_prof' class='pointer'><a onclick='changePage(this)'>Professor</a></li>
            <li id='navbar_room' class='pointer'><a onclick='changePage(this)'>Classroom</a></li>
          </ul>

          <ul style='display:inline' class='dropdown nav navbar-form navbar-left'>
              <button id='main_semester_dropdown' class='btn  dropdown-toggle'
              type='button' data-toggle='dropdown'>Semester
              <span class='caret'></span></button>
              <ul class='dropdown-menu' id='main_semester_menu'>
           <!--     <li><a href='#'>HTML</a></li>
                <li><a href='#'>CSS</a></li>
                <li><a href='#'>JavaScript</a></li>-->
              </ul>
            </ul>

          <ul class='nav navbar-nav navbar-right'>
              <li class='dropdown'>
                <a href='#' class='dropdown-toggle' data-toggle='dropdown' role='button'
                    aria-haspopup='true' aria-expanded='true' id='createNew'>Create New <span class='caret'></span></a>
              <ul class='dropdown-menu'>
                <li><a href='#' data-toggle='modal' data-target='#newSectionModal'
                       class='newResourceLink' id='newSectionLink'>Section</a></li>
                        <li role='separator' class='divider'></li>
                <li><a href='#' data-toggle='modal' data-target='#newCourseModal'
                       class='newResourceLink' id='newCourseLink'>Course</a></li>
                <li><a href='#' data-toggle='modal' data-target='#newProfessorModal'
                       class='newResourceLink' id='newProfLink'>Professor</a></li>
                <li><a href='#' data-toggle='modal' data-target='#newClassroomModal'
                       class='newResourceLink' id='newClassroomLink'>Classroom</a></li>
                <li role='separator' class='divider'></li>
                <li><a href='#' data-toggle='modal' data-target='#newCampusModal'
                       class='newResourceLink' id='newCampusLink'>Campus</a></li>
                <li><a href='#' data-toggle='modal' data-target='#newBuildingModal'
                       class='newResourceLink' id='newBuildingLink'>Building</a></li>
                <li><a href='#' data-toggle='modal' data-target='#newSemesterModal'
                       class='newResourceLink' id='newSemesterLink'>Semester</a></li>
                <li role='separator' class='divider'></li>
                <li><a href='#' data-toggle='modal' data-target='#newDepartmentModal'
                       class='newResourceLink' id='newDepartmentLink'>Department</a></li>
              </ul>
            </li>


            <li class='dropdown'>
                <a href='#' class='dropdown-toggle' data-toggle='dropdown' role='button'
                    aria-haspopup='true' aria-expanded='true' id='edit'>Edit <span class='caret'></span></a>
              <ul class='dropdown-menu'>
                <li><a href='#' data-toggle='modal' data-target='#editCampusModal'
                       class='editResourceLink' id='editCampusLink'>Campus</a></li>
                <li><a href='#' data-toggle='modal' data-target='#editBuildingModal'
                       class='editResourceLink' id='editBuildingLink'>Building</a></li>
                <li><a href='#' data-toggle='modal' data-target='#editSemesterModal'
                       class='editResourceLink' id='editSemesterLink'>Semester</a></li>
                <li role='separator' class='divider'></li>
                <li><a href='#' data-toggle='modal' data-target='#editDepartmentModal'
                       class='editResourceLink' id='editDepartmentLink'>Department</a></li>
              </ul>
            </li>


          </ul>
        </div>
      </div>
    </nav>
    <div class='container' id='main_container'>
    </div>
  </body>
</html>
";

echo $body;

require_once 'modals/modal_newSection.php';
require_once 'modals/modal_newCourse.php';
require_once 'modals/modal_newProfessor.php';
require_once 'modals/modal_newClassroom.php';
require_once 'modals/modal_newSemester.php';
require_once 'modals/modal_editSemester.php';
require_once 'modals/modal_newBuilding.php';
require_once 'modals/modal_newCampus.php';
require_once 'modals/modal_editCampus.php';
require_once 'modals/modal_newDepartment.php';
require_once 'modals/modal_editBuilding.php';
require_once 'modals/modal_editDepartment.php';
