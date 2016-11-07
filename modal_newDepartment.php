<div class="modal fade" id="newDepartmentModal" tabindex="-1" role="dialog" aria-labelledby="department-label">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="department-label">Create New Department</h4>
            </div>
            <div class="modal-body" style="margin-bottom: 65px; ">
                <div class="form-group">
                    <div class="col-xs-4">
                        <label for="departmentCode">Department Code</label>
                        <input type="text" class="form-control" id="departmentCode"  >
                    </div>
                    <div class="col-xs-8">
                        <label for="departmentName">Department Name</label>
                        <input type="text" class="form-control" id="departmentName"  >
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <span class="error-message"></span>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="btn_insertDepartment">Save</button>
            </div>
        </div>
    </div>
</div>