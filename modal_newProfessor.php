<div class="modal fade" id="newProfessorModal" tabindex="-1" role="dialog" aria-labelledby="professor-label">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="professor-label">Create New Professor</h4>
            </div>
            <div class="modal-body" style="margin-bottom: 170px;">
                <div class="form-group">
                    <div class="col-xs-6">
                        <label for="profFirst">First Name</label>
                        <input type="text" class="form-control" id="profFirst" placeholder="First Name" >
                    </div>
                    <div class="col-xs-6">
                        <label for="profLast">Last Name</label>
                        <input type="text" class="form-control" id="profLast" placeholder="Last Name" >
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-xs-6">
                        <label for="profEmail">Email</label>
                        <input type="text" class="form-control" id="profEmail" placeholder="example@email.com" >
                    </div>
                    <div class="col-xs-6">
                        <label for="profDepartment">Department</label>
                        <select class="form-control" id="profDepartment" >
                            <option value="''">Please Select...</option>

                            <?php
                            $database = new Database();
                            $selectDepts = $database->getdbh()->prepare(
                                'SELECT dept_id, dept_name FROM ZAAMG.Department
                                  ORDER BY dept_name ASC');
                            $selectDepts->execute();
                            $result = $selectDepts->fetchAll();

                            foreach($result as $row){
                                echo "<option value=\"".$row['dept_id']."\">".$row['dept_name']."</option>";
                            }
                            ?>

                            <!--<option value="cs">Computer Science</option>
                            <option value="nmt">Network, Multimedia and Technology</option>
                            <option value="web">Web Development</option>-->
                        </select>
                    </div>
                    <div class="col-xs-3">
                        <label for="profRequiredHours">Required Hours</label>
                        <input type="number" class="form-control" id="profRequiredHours" placeholder=4 >
                    </div>
                    <div class="col-xs-3">
                        <label for="profOverloadHours">Overload Hours</label>
                        <input type="number" class="form-control" id="profOverloadHours" placeholder=12 >
                    </div>
                    <div class="col-xs-3">
                        <label for="profReleaseHours">Release Hours</label>
                        <input type="number" class="form-control" id="profReleaseHours" placeholder=4 >
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <span class="error-message"></span>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="btn_insertProfessor">Save</button>
            </div>
        </div>
    </div>
</div>

