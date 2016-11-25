<?php
$body = "
<div class='modal fade' id='editSemesterModal' tabindex='-1' role='dialog' aria-labelledby='semester-label'>
    <div class='modal-dialog' role='document'>
        <div class='modal-content'>
            <div class='modal-header'>
                <button type='button' class='close' data-dismiss='modal' aria-label='Close'><span aria-hidden='true'>&times;</span></button>
                <h4 class='modal-title' id='semester-label'>Edit Semester</h4>
            </div>
            <div class='modal-body' style='margin-bottom: 360px; '>

            <div class='col-xs-8'>
            <label for='pick_editSemester'>Semester</label>
                        <select size='3' class='form-control'  id='pick_editSemester'>";

            $selectSem = $database->getdbh()->prepare(
                                "SELECT sem_id, sem_season, sem_year, sem_start_date
                                  FROM ZAAMG.Semester
                                  ORDER BY sem_start_date DESC");
                            $selectSem->execute();
                            $result = $selectSem->fetchAll(PDO::FETCH_ASSOC);

                            foreach($result as $sem){
                                $body .= "<option value=".$sem['sem_id'].">";
                                $body .=$sem['sem_year']." "
                                .$sem['sem_season']
                                ."</option>";
                            }
  $body .= "
                        </select>
                        </div>
            <!--<div class='col-xs-5' ></div>-->
            <div class='col-xs-4' style='padding-top: 5%'>
            <button type='button' class='btn btn-primary btn-modalEdit' id='btn_editSemester' style='margin-right: 12px'>Edit</button>
            <button type='button' class='btn btn-default' id='btn_deleteSemester'>Delete</button>
            </div>

            <div class='col-xs-12'> <hr style='border-width: 2px'></div>


            <!--style='max-height: 190px; overflow-y:auto'-->
            <div class='hide col-xs-12' id='editModalDiv_Semester'  >
            <div class='form-group' >
                    <div class='col-xs-3'>
                        <label for='editModal_semesterYear'>Year</label>
                        <input type='number' class='form-control' id='editModal_semesterYear' value='' >
                    </div>
                    <div class='col-xs-8'>
                        <label for='editModal_semesterSeason'>Season</label>
                        <select class='form-control' id='editModal_semesterSeason' >
                            <option value='Spring'>Spring</option>
                            <option value='Summer'>Summer</option>
                            <option value='Fall'>Fall</option>
                        </select>
                    </div>
            </div>

             <div class='form-group' >
                    <div class='col-xs-5'>
                        <label for='editModal_semesterStartDate'>Start Date</label>
                        <input type='date' class='form-control' id='editModal_semesterStartDate' >
                    </div>
                    <div class='col-xs-2'></div>
                    <div class='col-xs-4'>
                        <label for='editModal_semesterNumberWeeks'>Number Weeks</label>
                        <input type='number' class='form-control' id='editModal_semesterNumberWeeks' value='' min=14 max=16>
                    </div>
                </div>
                <div class='col-xs-12'>
                    <hr style='border-width: 2px'>
                </div>
                <div class='form-group' >
                    <div class='col-xs-5'>
                        <label for='editModal_firstBlockStart'>First Block Start Date</label>
                        <input type='date' class='form-control' id='editModal_firstBlockStart' >
                    </div>
                    <div class='col-xs-1'></div>
                    <div class='col-xs-5'>
                        <label for='editModal_secondBlockStart'>Second Block Start Date</label>
                        <input type='date' class='form-control' id='editModal_secondBlockStart' >
                    </div>
                </div>
            </div>



            </div>
            <div class='modal-footer'>
                <span class='error-message'></span>
                <button type='button' class='btn btn-default' data-dismiss='modal'>Close</button>
                <button type='button' class='btn btn-primary' id='btn_editSemester'>Update</button>
            </div>
        </div>
    </div>
</div>";

echo $body;
