<?php
echo '<div class="modal fade" id="newSemesterModal" tabindex="-1" role="dialog" aria-labelledby="semester-label">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="semester-label">Create New Semester</h4>
            </div>
            <div class="modal-body" style="margin-bottom: 225px; ">
                <div class="form-group" >
                    <div class="col-xs-3">
                        <label for="semesterYear">Year</label>
                        <input style="margin-bottom:10px" type="number" class="form-control" id="semesterYear" value=2017 >
                    </div>
                    <div class="col-xs-8">
                        <label for="semesterSeason">Season</label>
                        <select style="margin-bottom:10px" class="form-control" id="semesterSeason" >
                            <option value="0">Please Select...</option>
                            <option value="Spring">Spring</option>
                            <option value="Summer">Summer</option>
                            <option value="Fall">Fall</option>
                        </select>
                    </div>
                </div>
                <div class="form-group" >
                    <div class="col-xs-5">
                        <label for="semesterStartDate">Start Date</label>
                        <input type="date" class="form-control" id="semesterStartDate" >
                    </div>
                    <div class="col-xs-3"></div>
                    <div class="col-xs-3">
                        <label for="semesterNumberWeeks">Number Weeks</label>
                        <input type="number" class="form-control" id="semesterNumberWeeks" value=14 min=14 max=16>
                    </div>
                </div>
                <div class="col-xs-12">
                    <hr style="border-width: 2px">
                </div>
                <div class="form-group" >
                    <div class="col-xs-5">
                        <label for="firstBlockStart">First Block Start Date</label>
                        <input type="date" class="form-control" id="firstBlockStart" >
                    </div>
                    <div class="col-xs-1"></div>
                    <div class="col-xs-5">
                        <label for="secondBlockStart">Second Block Start Date</label>
                        <input type="date" class="form-control" id="secondBlockStart" >
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <span class="error-message"></span>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="btn_insertSemester">Save</button>
            </div>
        </div>
    </div>
</div>';
