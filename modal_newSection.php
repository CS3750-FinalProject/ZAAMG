<?php $database = new Database();?>

<div class="modal fade" id="newSectionModal" tabindex="-1" role="dialog" aria-labelledby="section-label">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="section-label">Create New Section</h4>
            </div>
            <div class="modal-body" style="margin-bottom: 325px; ">
                <div class="form-group">
                    <div class="col-xs-7">
                        <label for="sectionCourse">Course</label>
                        <select class="form-control" id="sectionCourse"  >
                            <option value="0">Please Select...</option>

                            <?php

                            $selectCourse = $database->getdbh()->prepare(
                                'SELECT course_id, course_code, course_title FROM ZAAMG.Course
                                  ORDER BY course_code ASC');
                            $selectCourse->execute();
                            $result = $selectCourse->fetchAll(PDO::FETCH_ASSOC);

                            foreach($result as $row){
                                echo "<option value=\"".$row['course_id']
                                    ."\">".$row['course_code']
                                    ." ".$row['course_title']
                                    ."</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-xs-7">
                        <label for="sectionProfessor">Professor</label>
                        <select  class="form-control" id="sectionProfessor" >

                            <option value="0">Please Select...</option>

                            <?php
                            $selectProf = $database->getdbh()->prepare(
                                'SELECT prof_id, prof_first, prof_last FROM ZAAMG.Professor
                                  ORDER BY prof_last ASC');
                            $selectProf->execute();
                            $result = $selectProf->fetchAll();

                            foreach($result as $row){
                                echo "<option value=\"".$row['prof_id']."\">"
                                    .$row['prof_last'].", ".$row['prof_first']
                                    ."</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-xs-7">
                        <label for="sectionClassroom">Classroom</label>
                        <select class="form-control" id="sectionClassroom"  >
                            <option value="0">Please Select...</option>

                            <?php
                            $selectRoom = $database->getdbh()->prepare(
                                'SELECT classroom_id, campus_name, building_name, classroom_number
                                  FROM ZAAMG.Campus c JOIN ZAAMG.Building b
                                  ON c.campus_id = b.campus_id
                                  JOIN ZAAMG.Classroom r
                                  ON b.building_id = r.building_id
                                  ORDER BY campus_name ASC');
                            $selectRoom->execute();
                            $result = $selectRoom->fetchAll(PDO::FETCH_ASSOC);

                            foreach($result as $row){
                                echo "<option value=\"".$row['classroom_id']."\">"
                                    .$row['campus_name'].", "
                                    .$row['building_name'].": "
                                    .$row['classroom_number']
                                    ."</option>";
                            }
                            ?>
                        </select>
                    </div>

                </div>
                <div class="col-xs-12">
                    <hr style="border-width: 2px">
                </div>
                <div class="form-group" >
                    <div class="col-xs-4">
                        <label for="sectionDays">Days</label>
                        <select multiple  class="form-control" id="sectionDays"  >
                        <option value="Monday">Monday</option>
                            <option value="Tuesday">Tuesday</option>
                            <option value="Wednesday">Wednesday</option>
                            <option value="Thursday">Thursday</option>
                            <option value="Friday">Friday</option>
                            <option value="Saturday">Saturday</option>
                            <option value="Sunday">Sunday</option>

                        </select>
                    </div>
                    <div class="col-xs-4">
                        <label for="sectionStartTime">Start Time</label>
                        <input type="time" class="form-control" id="sectionStartTime"  >
                    </div>
                    <div class="col-xs-4">
                        <label for="sectionEndTime">End Time</label>
                        <input type="time" class="form-control" id="sectionStartTime"  >
                    </div>



                </div>
            </div>
            <div class="modal-footer">
                <span class="error-message"></span>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="btn_insertBuilding">Save</button>
            </div>
        </div>
    </div>
</div>