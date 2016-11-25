<?php
$body = "
<div class='modal fade' id='editCampusModal' tabindex='-1' role='dialog' aria-labelledby='campus-label'>
    <div class='modal-dialog' role='document'>
        <div class='modal-content'>
            <div class='modal-header'>
                <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                <span aria-hidden='true'>&times;</span></button>
                <h4 class='modal-title' id='campus-label'>Edit Campus</h4>
            </div>
        <div class='modal-body' style='margin-bottom: 240px;'>
            <div class='col-xs-8'>
                <label for='pick_editCampus'>Campus</label>
                     <select size='5' class='form-control'  id='pick_editCampus'>";
$selectCampi = $database->getdbh()->prepare(
    'SELECT campus_id, campus_name FROM W01143557.Campus
                                                  ORDER BY campus_name ASC');
$selectCampi->execute();
$result = $selectCampi->fetchAll();
foreach($result as $campus){
    $body.= "<option value=".$campus['campus_id'].">".$campus['campus_name']."</option>";
}
$body .= "
                </select>
            </div>
            <div class='col-xs-4' style='padding-top: 5%'>
            <!--  <button type='button' class='btn btn-primary btn-modalEdit' id='btn_editCampus'
                            style='margin-right: 12px'>Edit</button>-->
                <button type='button' class='btn btn-default' id='btn_deleteCampus'>Delete</button>
            </div>
            <div class='col-xs-12'> <hr style='border-width: 2px'></div>
            <!--style='max-height: 190px; overflow-y:auto'-->
            <div class=' col-xs-12' id='editModalDiv_Campus'>
                <div class='form-group' >
                        <div class='col-xs-12'>
                            <label for='editModal_campusName'>Edit Campus Name</label>
                            <input type='text' class='form-control' id='editModal_campusName'  >
                        </div>
                </div>
            </div>
            </div> <!--  end of <div class='modal-content'>  -->
            <div class='modal-footer'>
                <span class='error-message'></span>
                <button type='button' class='btn btn-default' data-dismiss='modal'>Close</button>
                <button type='button' class='btn btn-primary' id='btn_updateCampus'>Update</button>
            </div>
        </div>
    </div>
</div>";
echo $body;