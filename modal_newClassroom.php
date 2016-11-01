<div class="modal fade" id="newClassroomModal" tabindex="-1" role="dialog" aria-labelledby="classroom-label">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="classroom-label">Create New Classroom</h4>
            </div>
            <div class="modal-body" style="margin-bottom: 50px;">
                <div class="form-group">
                    <div class="col-xs-2">
                        <label for="classroomCapacity">Capacity</label>
                        <input type="number" class="form-control" id="courseCapacity" value=30 >
                    </div>
                    <div class="col-xs-4">
                        <label for="classroomNumber">Classroom Number</label>
                        <input type="number" class="form-control" id="classroomNumber" value=100 >
                    </div>
                    <div class="col-xs-6">
                        <label for="classroomBuilding">Building</label>
                        <select class="form-control" id="classroomBuilding" >
                            <option value="''">Please Select...</option>
                            <option value="cs">Technology Education</option>
                            <option value="nmt">Blah</option>
                            <option value="web">Bleh</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>
