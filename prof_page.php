<?php
require_once 'Professor.php';

$database = new Database();

$body = "

<script src='js/calendar.js' charset='utf-8'></script>\";



";
#<script src='js/section-updates.js' charset='utf-8'></script>";

$body .= "
<div class='col-xs-12' >
        <div class='page-header'>
          <h1>Professors <small>for Spring 2017</small></h1>
        </div>
</div>


    <div class='container'  >
      <div class='col-xs-12' >
        <table class='list-data'>
          <tr>
            <th>First Name</th>
            <th>Last Name</th>
            <th>E-Mail</th>
            <th>Department</th>
            <th>Req Hours</th>
			<th>Rel Hours</th>
			<th>Over Hours</th>
			<th>Actions</th>
          </tr>";




$allProfessors = $database->getAllProfessors(null);
foreach ($allProfessors as $professor){
    $body .= addProfessor($professor);
}



$body .= "</table>";


$body .= "



<table class='table header-fixed' id='table_allProfsSchedule'>
<tr>
    <th>Credits</th>
    <th>OVRL</th>
    <th>Name</th>
    <th>Hours Req</th>
    <th>Day</th>
    <th>7:30 AM</th>
    <th>9:30 AM</th>
    <th>11:30 AM</th>
    <th>1:30 PM</th>
    <th>Online</th>
    <th>5:30 PM</th>
    <th>7:30 PM</th>
</tr>
<tr>
    <td>16</td>
    <td>4</td>
    <td class='td_colorBlock'>Brinkerhoff, Delroy</td>
    <td>12</td>
    <td class='td_days'>MW</td>
    <td></td>
    <td></td>
    <td class='td_colorBlock'>CS 1410</td>
    <td></td>
    <td class='td_colorBlock'>CS 1410</td>
    <td></td>
    <td></td>

</tr>
<tr>
    <td></td>
    <td></td>
    <td</td>
    <td></td>
    <td></td>
    <td class='td_days'>TTH</td>
    <td class='td_colorBlock'>CS 1410</td>
    <td></td>
    <td></td>
    <td class='td_colorBlock'>CS 3230</td>
    <td></td>
    <td></td>
    <td></td>
</tr>

<tr class='tr_divider'>
<td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
</tr>

<tr>
    <td>12</td>
    <td>4</td>
    <td class='td_colorBlock'>Ball, Bob</td>
    <td>12</td>
    <td class='td_days'>MW</td>
    <td></td>
    <td class='td_colorBlock'>CS 1400</td>
    <td class='td_colorBlock'>CS 2350</td>
    <td></td>
    <td class='td_colorBlock'>CS 1400</td>
    <td></td>
    <td></td>
</tr>

<tr>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td class='td_days'>TTH</td>
    <td class='td_colorBlock'>CS 3100</td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
</tr>



</table>";



$body .= "</div>";
$body .= "</div>";


echo $body;



function addProfessor(Professor $professor){
    $row = "<tr class='{$professor->getProfId()}' >
			<td>{$professor->getProfFirst()}</td>
			<td>{$professor->getProfLast()}</td>
			<td><small><em>{$professor->getProfEmail()}</em></small></td>
			<td> {$professor->getProfessorProperty('dept_name', 'Department', 'dept_id', 'deptId')}</td>
			<td>{$professor->getProfRequiredHours()}</td>
			<td>{$professor->getProfRelease()}</td>
			<td>to be calc...</td>
			<td>
			    <img src='img/pencil.png' class='action-edit' />
			    <img src='img/close.png' class='action-delete'>
			    <span id='seeCal_{$professor->getProfId()}'
			          class=' glyphicon glyphicon-menu-down' aria-hidden='true'></span>
			</td>
		  </tr>";

    /*
     *  the next two rows are set to display:none so that they exist but are hiding until
     *      the calendar displays.
     *  the second (empty) row is a placeholder so that the stripe color alternates correctly.
     */
    $row .= "<tr style='display:none' id='{$professor->getProfId()}'>
                <td colspan='8'>
                <div id='calendar'></div>
                </td>
            </tr>
            <tr style='display:none'></tr>
            ";

    return $row;
}

