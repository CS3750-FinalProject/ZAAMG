<?php
$body = '
<!-- Modal -->
<div class="modal fade" id="newCourseModal" tabindex="-1" role="dialog" aria-labelledby="course-label">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="course-label">Create New Course</h4>
            </div>
            <div class="modal-body" id="id_form-group" style="margin-bottom: 150px;">

                <div class="form-group" >
                    <div class="col-xs-3">
                        <label for="courseCode">Code</label>
                        <input style="margin-bottom:20px" type="text" class="form-control" id="courseCode" placeholder="CS 1010" >
                    </div>
                    <div class="col-xs-1"></div>

                    <div class="col-xs-8">
                        <label for="courseTitle">Title</label>
                        <input style="margin-bottom:20px" type="text" class="form-control" id="courseTitle" placeholder="Course Title Here..." >
                    </div>

                </div>
                <div class="form-group">

                    <div class="col-xs-3">
                        <label for="courseCredits">Credits</label>
                        <input type="number" class="form-control" id="courseCredits" value=4 min=1>
                    </div>
                    <div class="col-xs-1"></div>
                    <div class="col-xs-8">
                        <label for="courseDepartment">Department</label>
                        <select class="form-control" id="courseDepartment" >
                        </select>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <span class="error-message"></span>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="btn_insertCourse">Save</button>
            </div>
        </div>
    </div>
</div>';

echo $body;
