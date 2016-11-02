<?php
require_once 'Database.php';
require_once 'modal_newCourse.php';
require_once 'modal_newProfessor.php';
require_once 'modal_newClassroom.php';
require_once 'modal_newSemester.php';
require_once 'modal_newBuilding.php';
require_once 'modal_newCampus.php';
require_once 'modal_newDepartment.php';
echo "
<!DOCTYPE html>
<html>
  <head>
    <link href='css/bootstrap.min.css' rel='stylesheet'>
    <link href='css/application.css' rel='stylesheet'>
    <script src='js/jquery-3.1.1.min.js' charset='utf-8'></script>
    <script src='js/bootstrap.min.js' charset='utf-8'></script>
    <script src='js/processForm.js' charset='utf-8'></script>
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
            <li class='active'><a href='#'>Semester <span class='sr-only'>(current)</span></a></li>
            <li><a href='#'>Professor</a></li>
            <li><a href='#'>Classroom</a></li>
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
    </nav>
    <div class='container'>
      <div class='col-xs-12'>
        <div class='page-header'>
          <h1>Sections <small>for Spring 2017</small></h1>
        </div>
      </div>
    </div>
    <div class='container'>
      <div class='col-xs-12'>
        <table class='list-data'>
          <tr>
            <th>Course Name</th>
            <th>Professor</th>
            <th>Scheduled Time</th>
            <th>Location</th>
            <th>Actions</th>
          </tr>
          <tr>
            <td>CS 1030 <em>Foundations of Computer Science</em></td>
            <td>Spencer Hilton<br><small><em>spencerhilton@weber.edu</em></small></td>
            <td><strong>MW: </strong>10:00-11:50<br /><small><em>Full Block</em></small></td>
            <td><strong>TE 107</strong><br /><small>Ogden Campus</small>
            <td><img src='img/pencil.png' class='action-edit'/><img src='img/close.png' class='action-delete'></td>
          </tr>
          <tr>
            <td>CS 1400 <em>Fundamentals of Programming</em></td>
            <td>Spencer Hilton<br><small><em>spencerhilton@weber.edu</em></small></td>
            <td><strong>TR: </strong>10:00-11:50<br /><small><em>First Block</em></small></td>
            <td><strong>TE 109E</strong><br /><small>Ogden Campus</small>
            <td><img src='img/pencil.png' class='action-edit'/><img src='img/close.png' class='action-delete'></td>
          </tr>
          <tr>
            <td>CS 1410 <em>Object-Oriented Programming</em></td>
            <td>Brian Rague<br><small><em>brianrague@weber.edu</em></small></td>
            <td><strong>Online</strong><br /><small><em>Second Block</em></small></td>
            <td><strong>Online</strong><br />&nbsp;</td>
            <td><img src='img/pencil.png' class='action-edit'/><img src='img/close.png' class='action-delete'></td>
          </tr>
          <tr>
            <td>CS 2130 <em>Computational Structures</em></td>
            <td>Brian Rague<br><small><em>brianrague@weber.edu</em></small></td>
            <td><strong>MW: </strong>12:00-1:50<br /><small><em>Full Block</em></small></td>
            <td><strong>D-207</strong><br /><small>Davis Campus</small></td>
            <td><img src='img/pencil.png' class='action-edit'/><img src='img/close.png' class='action-delete'></td>
          </tr>
          <tr>
            <td>CS 2350 <em>Web Development</em></td>
            <td>Garth Tuck<br><small><em>garthtuck@weber.edu</em></small></td>
            <td><strong>S </strong>10:00-1:50<br /><small><em>First Block</em></small></td>
            <td><strong>TE 104</strong><br /><small>Ogden Campus</small></td>
            <td><img src='img/pencil.png' class='action-edit'/><img src='img/close.png' class='action-delete'></td>
          </tr>
          <tr>
            <td>CS 2420 <em>Introduction to Data Structures and Algorithms</em></td>
            <td>Brad Peterson<br><small><em>bradpeterson@weber.edu</em></small></td>
            <td><strong>TR: </strong>5:00-6:50<br /><small><em>Second Block</em></small></td>
            <td><strong>209</strong><br /><small>Davis Campus</small></td>
            <td><img src='img/pencil.png' class='action-edit'/><img src='img/close.png' class='action-delete'></td>
          </tr>
          <tr>
            <td>CS 2450 <em>Software Engineering I</em></td>
            <td>Joshua Jensen<br><small><em>joshuajensen@weber.edu</em></small></td>
            <td><strong>Online</strong><br /><small><em>Full Block</em></small></td>
            <td><strong>Online</strong><br> &nbsp;</td>
            <td><img src='img/pencil.png' class='action-edit'/><img src='img/close.png' class='action-delete'></td>
          </tr>
          <tr>
            <td>CS 2550 <em>Database Design and Application Development</em></td>
            <td>Roberth Hilton<br><small><em>roberthhilton@weber.edu</em></small></td>
            <td><strong>MW: </strong>8:00-9:50<br /><small><em>Second Block</em></small></td>
            <td><strong>TE 104</strong><br /><small>Ogden Campus</small></td>
            <td><img src='img/pencil.png' class='action-edit'/><img src='img/close.png' class='action-delete'></td>
          </tr>
          <tr>
            <td>CS 2705 <em>Network Fundamentals and Design</em></td>
            <td>Drew Weidman<br><small><em>drewweidman@weber.edu</em></small></td>
            <td><strong>Online</strong><br /><small><em>First Block</em></small></td>
            <td><strong>Online</strong><br /><small>&nbsp;</small></td>
            <td><img src='img/pencil.png' class='action-edit'/><img src='img/close.png' class='action-delete'></td>
          </tr>
          <tr>
            <td>CS 2810 <em>Computer Architecture/Organization</em></td>
            <td>Joshua Jensen<br><small><em>joshuajensen@weber.edu</em></small></td>
            <td><strong>TR: </strong>7:00-8:50<br /><small><em>Full Block</em></small></td>
            <td><strong>TE 102</strong><br /><small>Ogden Campus</small>
            <td><img src='img/pencil.png' class='action-edit'/><img src='img/close.png' class='action-delete'></td>
          </tr>
      </div>
    </div>
  </body>
</html>
";
