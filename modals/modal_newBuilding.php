<?php

$database = new Database();

echo '<div class="modal fade" id="newBuildingModal" tabindex="-1" role="dialog" aria-labelledby="building-label">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="building-label">Create New Building</h4>
            </div>
            <div class="modal-body" style="margin-bottom: 125px; ">
                <div class="form-group">
                    <div class="col-xs-3">
                        <label for="buildingCode">Building Code</label>
                        <input autofocus type="text" class="form-control" id="buildingCode"  >
                    </div>
                    <div class="col-xs-6">
                        <label for="buildingCampus">Campus</label>
                        <select type="text" class="form-control" id="buildingCampus" >

                            <option value="">Please Select...</option>';

                            $selectCampi = $database->getdbh()->prepare(
                                'SELECT campus_id, campus_name FROM ZAAMG.Campus
                                  ORDER BY campus_name ASC');
                            $selectCampi->execute();
                            $result = $selectCampi->fetchAll();

                            foreach($result as $row){
                                echo "<option value=\"".$row['campus_id']."\">".$row['campus_name']."</option>";
                            }
echo '

                        </select>


                    </div>

                </div>
                <div class="form-group" >
                    <div class="col-xs-9">
                        <label for="buildingName">Building Name</label>
                        <input type="text" class="form-control" id="buildingName"  >
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
</div>';
