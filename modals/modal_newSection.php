<?php
require_once "Database.php";

$database = new Database();

$body = "
<div class='modal fade' id='newSectionModal' tabindex='-1' role='dialog' aria-labelledby='section-label'>
    <div class='modal-dialog' role='document'>
        <div class='modal-content'>
            <div class='modal-header'>
                <button type='button' class='close' data-dismiss='modal' aria-label='Close'><span aria-hidden='true'>&times;</span></button>
                <h4 class='modal-title' id='section-label'>Create New Section</h4>
            </div>
            <div class='modal-body' style='margin-bottom: 360px; '>
                <div class='form-group'>
                <label for='sectionCourse' class='col-sm-3 control-label'>Course</label>
                    <div class='col-xs-8'>

                        <select style='margin-bottom: 10px' class='form-control' id='sectionCourse'  ></select>
                    </div>
                    <label for='sectionProfessor' class='col-sm-3 control-label'>Professor</label>
                    <div class='col-xs-8'>

                        <select style='margin-bottom: 10px' class='form-control' id='sectionProfessor' ></select>
                    </div>
                    <label for='sectionClassroom' class='col-sm-3 control-label'>Classroom</label>
                    <div class='col-xs-8'>
                        <select class='form-control' id='sectionClassroom'></select>
                    </div>

                </div>
                <div class='col-xs-12'>
                    <hr style='border-width: 2px'>
                </div>
                <div class='form-group' >
                    <div class='col-xs-4'>
                        <label for='sectionDays'>Days</label>
                        <select multiple size='8' class='form-control' id='sectionDays'  >
                            <option value='Online'>Online</option>
                            <option value='Monday'>Monday</option>
                            <option value='Tuesday'>Tuesday</option>
                            <option value='Wednesday'>Wednesday</option>
                            <option value='Thursday'>Thursday</option>
                            <option value='Friday'>Friday</option>
                            <option value='Saturday'>Saturday</option>
                            <option value='Sunday'>Sunday</option> //are there ever Sunday classes?
                        </select>
                    </div>
                    <div class='col-xs-4'>
                        <label for='sectionStartTime'>Start Time</label>
                        <input style='margin-bottom: 10px' type='time' class='form-control' id='sectionStartTime'
                            placeholder='09:30 AM' value='00:00:00' >
                    </div>
                    <div class='col-xs-4'>
                        <label for='sectionEndTime'>End Time</label>
                        <input style='margin-bottom: 10px' type='time' class='form-control' id='sectionEndTime'
                            placeholder='11:20 AM' value='00:00:00'  >
                    </div>
                    <div class='col-xs-4'>
                        <label for='sectionSemester'>Semester</label>
                        <select  class='form-control' id='sectionSemester'> </select>
                    </div>

                    <div class='col-xs-4'>
                        <label for='sectionBlock'>Block</label>
                        <select class='form-control' style='margin-bottom:10px' id='sectionBlock'>
                            <option value='0'>Full</option>
                            <option value='1'>First</option>
                            <option value='2'>Second</option>
                        </select>
                    </div>

                    <div class='col-xs-4'>
                        <label for='sectionCapacity'>Capacity</label>
                        <input type='number' class='form-control' id='sectionCapacity' min='1' >
                    </div>
                    <div class='col-xs-1'></div>
                    <div class='col-xs-4'>
                        <label style='margin-top: 30px; font-weight:bold' class='checkbox-inline' for='sectionOnline'>
                        <input type='checkbox' id='sectionOnline' value='1'>Online</label>
                    </div>


                </div>
            </div>
            <div class='modal-footer'>
                <span class='error-message'></span>
                <button type='button' class='btn btn-default' data-dismiss='modal'>Close</button>
                <button type='button' class='btn btn-primary' id='btn_insertSection'>Save</button>
            </div>
        </div>
    </div>
</div>
";

echo $body;
