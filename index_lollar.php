<?php

#http://stackoverflow.com/questions/27139963/bootstrap-multiple-pages-divs-and-navbar
require_once 'Database.php';
require_once 'modal_newSection.php';
require_once 'modal_newCourse.php';
require_once 'modal_newProfessor.php';
require_once 'modal_newClassroom.php';
require_once 'modal_newSemester.php';
require_once 'modal_newBuilding.php';
require_once 'modal_newCampus.php';
require_once 'modal_newDepartment.php';
$database = new Database();
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

    <script src='js/professorSet.js' charset='utf-8'></script>
    <script src='js/processForm.js' charset='utf-8'></script>
    <script src='js/calendar.js' charset='utf-8'></script>



    <script>

    $(document).ready(function() {
        $(\"#main_container\").load(\"section_page.php\");
    });

    function loadPhpPage(page){
        $(\"#main_container\").load(page);
    }

    </script>

    <title>Project ZAAMG</title>
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
          <a class='navbar-brand' href='#'>ZAAMG</a>
        </div>
        <div class='collapse navbar-collapse' id='bs-example-navbar-collapse-1'>
          <ul class='nav navbar-nav'>
            <li id='sec' class='active'><a href='#' onclick='loadPhpPage(\"section_page.php\")'>Section <span class='sr-only'>(current)</span></a></li>
            <li id='prof'><a href='#' onclick='loadPhpPage(\"prof_page.php\")'>Professor</a></li>
            <li id='room'><a href='#' onclick='loadPhpPage(\"classroom_page.php\")'>Classroom</a></li>
          </ul>
          <form class='navbar-form navbar-left'>
            <div class='form-group'>
              <input type='text' class='form-control' placeholder='Search'>
            </div>
            <button type='submit' class='btn btn-default'>Submit</button>
          </form>
          <ul class='nav navbar-nav navbar-right'>
            <li class='dropdown'>
              <a href='#' class='dropdown-toggle' data-toggle='dropdown' role='button' aria-haspopup='true' aria-expanded='true'>Create New <span class='caret'></span></a>
              <ul class='dropdown-menu'>
                <li><a href='#' data-toggle='modal' data-target='#newSectionModal'
                       class='newResourceLink' id='newSectionLink'>Section</a></li>
                        <li role='separator' class='divider'></li>
                <li><a href='#' data-toggle='modal' data-target='#newCourseModal'
                       class='newResourceLink' id='newCourseLink'>Course</a></li>
                <li><a href='#' data-toggle='modal' data-target='#newProfessorModal'
                       class='newResourceLink'id='newProfLink'>Professor</a></li>
                <li><a href='#' data-toggle='modal' data-target='#newClassroomModal'
                       class='newResourceLink' id='newClassroomLink'>Classroom</a></li>
                <li role='separator' class='divider'></li>
                <li><a href='#' data-toggle='modal' data-target='#newSemesterModal'
                       class='newResourceLink' id='newSemesterLink'>Semester</a></li>
                <li><a href='#' data-toggle='modal' data-target='#newBuildingModal'
                       class='newResourceLink' id='newBuildingLink'>Building</a></li>
                <li><a href='#' data-toggle='modal' data-target='#newCampusModal'
                       class='newResourceLink' id='newCampusLink'>Campus</a></li>
                <li role='separator' class='divider'></li>
                <li><a href='#' data-toggle='modal' data-target='#newDepartmentModal'
                       class='newResourceLink' id='newDepartmentLink'>Department</a></li>
              </ul>
            </li>
          </ul>
        </div>
      </div>
    </nav>";

$body .= "




<div class='container' id='main_container'>";
echo $body;





$body = "</div>";  //should close div#main_container




$body .= "
  </body>
</html>
";

echo $body;


