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
                <select size=5 class='form-control' id='pick_editDepartment' > </select>
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
