<!-- Modal -->
<div class="modal fade" id="newCourseModal" tabindex="-1" role="dialog" aria-labelledby="course-label">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="course-label">Create New Course</h4>
            </div>
            <div class="modal-body" id="id_form-group" style="margin-bottom: 175px;">

                <div class="form-group" >
                    <div class="col-xs-3">
                        <label for="coursePrefix">Prefix</label>
                        <input type="text" class="form-control" id="coursePrefix" placeholder="CS" >
                    </div>
                    <div class="col-xs-4">
                        <label for="courseNumber">Number</label>
                        <input type="text" class="form-control" id="courseNumber" placeholder="1010" >
                    </div>
                    <div class="col-xs-8">
                        <label for="courseTitle">Title</label>
                        <input type="text" class="form-control" id="courseTitle" placeholder="Course Title Here..." >
                    </div>

                </div>
                <div class="form-group">
                    <div class="col-xs-2">
                        <label for="courseCapacity">Capacity</label>
                        <input type="number" class="form-control" id="courseCapacity" value=30 min=1>
                    </div>
                    <div class="col-xs-2">
                        <label for="courseCredits">Credits</label>
                        <input type="number" class="form-control" id="courseCredits" value=4 min=1>
                    </div>
                    <div class="col-xs-8">
                        <label for="courseDepartment">Department</label>
                        <select class="form-control" id="courseDepartment" >
                            <option value="0" >Please Select...</option>

                            <?php
                            $database = new Database();
                            $selectDepts = $database->getdbh()->prepare(
                                'SELECT dept_id, dept_name, dept_code FROM ZAAMG.Department
                            ORDER BY dept_code ASC');
                            $selectDepts->execute();
                            $result = $selectDepts->fetchAll();

                            foreach($result as $row){
                                echo "<option value=\"".$row['dept_id']."\">"
                                    .$row['dept_name']." (".$row['dept_code'].")"."</option>";
                            }
                            ?>

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
</div>