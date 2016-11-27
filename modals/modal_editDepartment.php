<?php
$body = "
<div class='modal fade' id='editDepartmentModal' tabindex='-1' role='dialog' aria-labelledby='department-label'>
    <div class='modal-dialog' role='document'>


        <div class='modal-content'>
            <div class='modal-header'>
                <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                <span aria-hidden='true'>&times;</span></button>
                <h4 class='modal-title' id='department-label'>Edit Department</h4>
            </div>


        <div class='modal-body' style='margin-bottom: 240px;'>

            <div class='col-xs-8'>
                <label for='pick_editDepartment'>Department</label>
                <select size=5 class='form-control' id='pick_editDepartment' >";


                    $selectDepts = $database->getdbh()->prepare(
                        'SELECT dept_id, dept_code, dept_name FROM ZAAMG.Department
                          ORDER BY dept_code ASC');
                    $selectDepts->execute();
                    $result = $selectDepts->fetchAll();

                    foreach($result as $dept){
                        $body.= "<option value=".$dept['dept_id'].">".
         str_replace('~','&nbsp;',str_pad($dept['dept_code'],(12-strlen($dept['dept_code'])),'~'))
                             .$dept['dept_name']."</option>";
                    }
$body .= "
            </select>
            </div>

            <div class='col-xs-4' style='padding-top: 5%'>

                <button type='button' class='btn btn-default' id='btn_deleteDepartment'>Delete</button>
            </div>

            <div class='col-xs-12'> <hr style='border-width: 2px'></div>


            <div class=' col-xs-12' id='editModalDiv_Department'>

                <div class='form-group' >
                        <div class='col-xs-4'>
                            <label for='editModal_departmentCode'>Department Code</label>
                            <input type='text' class='form-control' id='editModal_departmentCode'  >
                        </div>
                        <div class='col-xs-8'>
                            <label for='editModal_departmentName'>Department Name</label>
                            <input type='text' class='form-control' id='editModal_departmentName'  >
                        </div>
                </div>
            </div>

            </div> <!--  end of <div class='modal-content'>  -->

            <div class='modal-footer'>
                <span class='error-message'></span>
                <button type='button' class='btn btn-default' data-dismiss='modal'>Close</button>
                <button type='button' class='btn btn-primary' id='btn_updateDepartment'>Update</button>
            </div>

        </div>
    </div>
</div>";

echo $body;
