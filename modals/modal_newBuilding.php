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
                        <input style="margin-bottom:10px" autofocus type="text" class="form-control" id="buildingCode"  >
                    </div>
                    <div class="col-xs-6">
                        <label for="buildingCampus">Campus</label>
                        <select style="margin-bottom:10px" type="text" class="form-control" id="buildingCampus" ></select>
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
