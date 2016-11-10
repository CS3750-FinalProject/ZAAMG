<?php
require_once 'Classroom.php';

$database = new Database();

$body = "

<div class='col-xs-12'>
        <div class='page-header'>
          <h1>Classrooms <small>for Spring 2017</small></h1>
        </div>
     </div>
</div>

    <div class='container'>
      <div class='col-xs-12'>
        <table class='list-data'>
        <tr>
            <th>Campus</th>
            <th>Building</th>
            <th>Room Number</th>
            <th>Capacity</th>
			<th>Number of Computers</th>
			<th>Actions</th>
          </tr>";




$allClassrooms = $database->getAllClassrooms(null);
foreach ($allClassrooms as $classroom){
    $body .= addClassroom($classroom);
}
$body .= "</div>";


echo $body;










function addClassroom(Classroom $classroom){
    $row = "<tr class='{$classroom->getClassroomID()}'>
			<td>{$classroom->getClassroomProperty_Join_3('campus_name', 'Building', 'Campus',
                'building_id', 'campus_id', 'buildId')}</td>
			<td>{$classroom->getClassroomProperty('building_name', 'Building', 'building_id', 'buildId')}</td>
			<td><small><em>{$classroom->getClassroomNum()}</em></small></td>
			<td> {$classroom->getClassroomCap()}</td>
			<td>{$classroom->getNumWorkstations()}</td>
			<td><img src='img/pencil.png' class='action-edit'/><img src='img/close.png' class='action-delete'></td>
		  </tr>";


    return $row;
}


