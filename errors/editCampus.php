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


        <div class='modal-body' style='margin-bottom: 200px;'>

            <div class='col-xs-8'>
                <label for='pick_editBuilding'>Campus</label>
                    <select size='3' class='form-control'  id='pick_editBuilding'>";

                    $selectBuilding = $database->getdbh()->prepare(
                        'SELECT ZAAMG.Campus.campus_id, campus_name, building_name, building_id
                                                      FROM ZAAMG.Campus JOIN ZAAMG.Building
                                                      ON ZAAMG.Campus.campus_id = ZAAMG.Building.campus_id
                                                      ORDER BY campus_name ASC');
                    $selectBuilding->execute();
                    $result = $selectBuilding->fetchAll();

                    foreach($result as $building){
                        echo "<option value=".$building['building_id'].">"
                            .$building['campus_name'].": ".$building['building_name']."</option>";
                    }
$body.= "
                    </select>
            </div>

            <div class='col-xs-4' style='padding-top: 5%'>
                <button type='button' class='btn btn-primary btn-modalEdit' id='btn_editBuilding' style='margin-right: 12px'>Edit</button>
                <button type='button' class='btn btn-default' id='btn_deleteBuilding'>Delete</button>
            </div>

            <div class='col-xs-12'> <hr style='border-width: 2px'></div>


            <!--style='max-height: 190px; overflow-y:auto'-->
            <div class='hide col-xs-12' id='editModalDiv_Building'>


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