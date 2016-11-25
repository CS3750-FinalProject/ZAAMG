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
            <div class='modal-body' style='margin-bottom: 350px; '>
                <div class='form-group'>
                    <div class='col-xs-7'>
                        <label for='sectionCourse'>Course</label>
                        <select class='form-control' id='sectionCourse'  >
                            <option value='0'>Please Select...</option>";

                            $selectCourse = $database->getdbh()->prepare(
                                'SELECT course_id, course_prefix, course_number, course_title FROM W01143557.Course
                                  ORDER BY course_prefix, course_number');
                            $selectCourse->execute();
                            $result = $selectCourse->fetchAll(PDO::FETCH_ASSOC);

                            foreach($result as $row){
                                $body .= '<option value=\''.$row['course_id']
                                    .'\'>'.$row['course_prefix']
                                    .' '.$row['course_number']
                                    .' '.$row['course_title']
                                    .'</option>';
                            }
                        $body .= "</select>
                    </div>
                    <div class='col-xs-7'>
                        <label for='sectionProfessor'>Professor</label>
                        <select  class='form-control' id='sectionProfessor' >

                            <option value='0'>Please Select...</option>";

                            $selectProf = $database->getdbh()->prepare(
                                'SELECT prof_id, prof_first, prof_last FROM W01143557.Professor
                                  ORDER BY prof_last ASC');
                            $selectProf->execute();
                            $result = $selectProf->fetchAll();

                            foreach($result as $row){
                                $body .= "<option value=\"".$row["prof_id"]
                                    ."\">"
                                    .$row["prof_last"].", ".$row["prof_first"]
                                    ."</option>";
                            }

                        $body .= "</select>
                    </div>
                    <div class='col-xs-7'>
                        <label for='sectionClassroom'>Classroom</label>
                        <select class='form-control' id='sectionClassroom'  >
                            <option value='-1'>Please Select...</option>
                            <option value='0'>Online</option>";
                            $selectRoom = $database->getdbh()->prepare(
                                "SELECT classroom_id, campus_name, building_name, classroom_number
                                  FROM W01143557.Campus c JOIN W01143557.Building b
                                  ON c.campus_id = b.campus_id
                                  JOIN W01143557.Classroom r
                                  ON b.building_id = r.building_id
                                  ORDER BY campus_name ASC");
                            $selectRoom->execute();
                            $result = $selectRoom->fetchAll(PDO::FETCH_ASSOC);

                            foreach($result as $row){
                                $body .= "<option value=\""
                                .$row["classroom_id"]."\">"
                                    .$row["campus_name"].", "
                                    .$row["building_name"].": "
                                    .$row["classroom_number"]
                                    ."</option>";
                            }
        $body .= "
                        </select>
                    </div>

                </div>
                <div class='col-xs-12'>
                    <hr style='border-width: 2px'>
                </div>
                <div class='form-group' >
                    <div class='col-xs-4'>
                        <label for='sectionDays'>Days</label>
                        <select multiple  class='form-control' id='sectionDays'  >
                            <option value='online'>Online</option>
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
                        <input type='time' class='form-control' id='sectionStartTime'
                            placeholder='09:30 AM' value='00:00:00' >
                    </div>
                    <div class='col-xs-4'>
                        <label for='sectionEndTime'>End Time</label>
                        <input type='time' class='form-control' id='sectionEndTime'
                            placeholder='11:20 AM' value='00:00:00'  >
                    </div>
                    <div class='col-xs-4'>
                        <label for='sectionSemester'>Semester</label>
                        <select class='form-control' id='sectionSemester'  >
                            <option value='0'>Please Select...</option>
";

                            $selectSem = $database->getdbh()->prepare(
                                'SELECT sem_id, sem_season, sem_year, sem_start_date
                                  FROM W01143557.Semester
                                  ORDER BY sem_start_date DESC');
                            $selectSem->execute();
                            $result = $selectSem->fetchAll(PDO::FETCH_ASSOC);

                            foreach($result as $row){
                                $body .= "<option value=\"".$row['sem_id']."\">"
                                .$row['sem_year']." "
                                .$row['sem_season']
                                ."</option>";
                            }
                            $body .= "
                        </select>
                    </div>

                    <div class='col-xs-2'>
                        <label for='sectionBlock'>Block</label>
                        <select class='form-control' id='sectionBlock'>
                            <option value='0'>Full</option>
                            <option value='1'>First</option>
                            <option value='2'>Second</option>
                        </select>
                    </div>

                    <div class='col-xs-2'>
                        <label for='sectionCapacity'>Capacity</label>
                        <input type='number' class='form-control' id='sectionCapacity' min='1' >
                    </div>
                    <div class='col-xs-3'>
                        <label class='checkbox-inline' for='sectionOnline'>
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
