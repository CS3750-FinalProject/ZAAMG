<?php
$body = "
<div class='modal fade' id='editBuildingModal' tabindex='-1' role='dialog' aria-labelledby='building-label'>
    <div class='modal-dialog' role='document'>
        <div class='modal-content'>
            <div class='modal-header'>
                <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                <span aria-hidden='true'>&times;</span></button>
                <h4 class='modal-title' id='campus-label'>Edit Building</h4>
            </div>
        <div class='modal-body' style='margin-bottom: 300px;'>
            <div class='col-xs-8'>
                <label for='pick_editBuilding'>Building</label>
                    <select size='5' class='form-control'  id='pick_editBuilding'>";
$selectBuilding = $database->getdbh()->prepare(
    'SELECT W01143557.Campus.campus_id, campus_name, building_name, building_id
                                                      FROM W01143557.Campus JOIN W01143557.Building
                                                      ON W01143557.Campus.campus_id = W01143557.Building.campus_id
                                                      ORDER BY campus_name ASC');
$selectBuilding->execute();
$result = $selectBuilding->fetchAll();
foreach($result as $building){
    //declare separate variable for cleaner code and better readability
    $buildingID = $building['building_id'];
    $buildingCampus = $building['campus_name'];
    $buildingName = $building['building_name'];
    $body.= "<option value=\"$buildingID\">$buildingCampus: $buildingName</option>";
}
$body.= "
                    </select>
            </div>
            <div class='col-xs-4' style='padding-top: 5%'>
            <!-- <button type='button' class='btn btn-primary btn-modalEdit' id='btn_editBuilding'
                        style='margin-right: 12px'>Edit</button>-->
                <button type='button' class='btn btn-default' id='btn_deleteBuilding'>Delete</button>
            </div>
            <div class='col-xs-12'> <hr style='border-width: 2px'></div>
            <!--style='max-height: 190px; overflow-y:auto'-->
            <div class=' col-xs-12' id='editModalDiv_Building'>
            <form id='editModal_formBuilding'>
                <div class='col-xs-3'>
                    <label for='editModal_buildingCode'>Building Code</label>
                    <input autofocus type='text' class='form-control' id='editModal_buildingCode'  >
                </div>
                <div class='col-xs-6'>
                        <label for='editModal_buildingCampus'>Campus</label>
                        <select class='form-control' id='editModal_buildingCampus' >";
$selectCampi = $database->getdbh()->prepare(
    'SELECT campus_id, campus_name FROM W01143557.Campus
                                  ORDER BY campus_name ASC');
$selectCampi->execute();
$result = $selectCampi->fetchAll();
foreach($result as $campus){
    $campusID = $campus['campus_id'];
    $campusName = $campus['campus_name'];
    $body.= "<option value=\"$campusID\">$campusName</option>";
}
$body.="
                        </select>
                </div>
                <div class='col-xs-9'>
                    <label for='editModal_buildingName'>Building Name</label>
                    <input type='text' class='form-control' id='editModal_buildingName'  >
                </div>
            </form>
            </div>
            </div> <!--  end of <div class='modal-content'>  -->
            <div class='modal-footer'>
                <span class='error-message'></span>
                <button type='button' class='btn btn-default' data-dismiss='modal'>Close</button>
                <button type='button' class='btn btn-primary' id='btn_updateBuilding'>Update</button>
            </div>
        </div>
    </div>
</div>";
echo $body;