<?php

$database = new Database();

$body = '
<div class="modal fade" id="newClassroomModal" tabindex="-1" role="dialog" aria-labelledby="classroom-label">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="classroom-label">Create New Classroom</h4>
            </div>
            <div class="modal-body" style="margin-bottom: 150px;">
                <div class="form-group">

                    <div class="col-xs-7">
                        <label for="classroomBuilding">Campus/Building</label>
                        <select type="text" class="form-control" id="classroomBuilding">
                        </select>
                    </div>
                    <div class="col-xs-1"></div> <!-- spacer div -->
                    <div class="col-xs-3">
                        <label for="classroomNumber">Room Number</label>
                        <input type="text" class="form-control" id="classroomNumber">
                    </div>
                    <div class="col-xs-12"><hr style="border-width: 2px"></div>
                    <div class="col-xs-3">
                        <label for="classroomCapacity">Capacity</label>
                        <input type="number" class="form-control" id="roomCapacity" value=30 min=1>
                    </div>
                    <div class="col-xs-1"></div> <!-- spacer div -->
                    <div class="col-xs-3">
                        <label for="classroomWorkstations">Workstations</label>
                        <input type="number" class="form-control" id="roomWorkstations" value=0 min=0 placeholder="0">
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <span class="error-message"></span>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="btn_insertClassroom">Save</button>
            </div>
        </div>
    </div>
</div>';

echo $body;
