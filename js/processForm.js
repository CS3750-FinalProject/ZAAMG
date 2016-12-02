//http://stackoverflow.com/questions/11519660/twitter-bootstrap-modal-backdrop-doesnt-disappear
//I just spent way too long on this problem :)

function refresh_activePage(){
    var activePage;
    $('[id^=' + 'navbar_' +']').each(function(){
        if ($(this).hasClass('active')){
            activePage = $(this).attr('id').split('navbar_').pop();
            switch(activePage){
                case "sec": loadPhpPage('section_page.php'); break;
                case "prof": loadPhpPage('prof_page.php'); break;
                case "room": loadPhpPage('classroom_page.php'); break;
            }
        }
    });
}


$(function() {

    $("#btn_insertCourse").click(function() {
        // validate and process form here
        var courseCode = $("input#courseCode").val();
        //var coursePrefix = $("input#coursePrefix").val();
        //var courseNumber = $("input#courseNumber").val();
        var courseTitle = $("input#courseTitle").val();
        var courseCredits = $("input#courseCredits").val();
        var deptId = $("#courseDepartment").val();

        var coursePattern = /[A-Z]?[A-Z]?[A-Z][A-Z] [0-9]?[0-9][0-9][0-9]/;

        if (!coursePattern.test(courseCode)) {
            window.alert("Invalid Course format! Enter the course in this fashion:\nPREF ####");
            //this.modal('show');   console logs an error saying this.modal() is not a function.
            return false;     //I think this is the desired outcome
        }
        courseCode = coursePattern.exec(courseCode);
        var space = courseCode.indexOf(" ");
        try {
            var coursePrefix = courseCode.slice(0, space);
            var courseNumber = courseCode.slice(space);
        }catch (err) {
            window.alert("Undefined Error");
            this.modal('show');
        }

        /*if (courseCode.length == 0){
            $("span.error-message").text("A course code is required.");
            return false;
        }*/

        courseExists = false;  //global variable

        var dataString = 'coursePrefix='+ coursePrefix + '&courseNumber=' + courseNumber + '&courseTitle=' + courseTitle
             + '&courseCred=' + courseCredits
            + '&deptId=' + deptId;
        //alert(dataString); return false;
        $.ajax({
            type: "POST",
            url: "action/action_insertCourse.php",
            data: dataString,
            success: function(msg) {
                //alert(msg); return false;
                if (msg.indexOf("does exist") != -1){
                    window.courseExists = true;
                    $("span.error-message").text("This Course already exists.")
                }
                if (!window.courseExists) {
                    $('#newCourseModal').modal('hide');
                    $('#newCourseModal').on('hidden.bs.modal', refresh_activePage());
                }

            }
        });
    }); //end of function for btn_insertCourse



    $("#btn_insertProfessor").click(function() {
        // validate and process form here
        var profFirst = $("input#profFirst").val();
        var profLast = $("input#profLast").val();
        var profEmail = $("input#profEmail").val();
        var profReqHours = $("input#profHours").val();
        var profRelHours = $("input#profRelease").val();
        var deptId = $("#profDepartment").val();

        if (profEmail.length == 0){
            $("span.error-message").text("An email address is required.");
            return false;
        }

        profExists = false;

        var dataString = 'profFirst='+ profFirst + '&profLast=' + profLast
            + '&profEmail=' + profEmail + '&profReqHours=' + profReqHours
            + '&profRelHours=' + profRelHours  + '&deptId=' + deptId;
        //console.log(dataString);return false;
        $.ajax({
            type: "POST",
            url: "action/action_insertProfessor.php",
            data: dataString,
            success: function(msg) {
                //console.log(msg); return false;
                if (msg.indexOf("does exist") != -1) {
                    window.profExists = true;
                    $("span.error-message").text("A Professor with this email address already exists.")
                }
                if (!window.profExists){
                    $('#newProfessorModal').modal('hide');
                    $('#newProfessorModal').on('hidden.bs.modal', refresh_activePage());
                }
            }
        });
    }); //end of function for btn_insertProfessor


    $("#btn_insertClassroom").click(function() {
        // validate and process form here
        var classCap = $("input#roomCapacity").val();
        var classNum = $("input#classroomNumber").val();
        var workstations = $("input#roomWorkstations").val();
        var buildId = $("select#classroomBuilding").val();

        if (classNum.length == 0){
            $("span.error-message").text("A room number is required.");
            return false;
        }
        if (buildId == 0){
            $("span.error-message").text("Please select a building.");
            return false;
        }

        classroomExists = false;

        var dataString = 'classNum='+ classNum + '&classCapacity=' + classCap
            + '&roomWorkstations=' + workstations + '&buildId=' + buildId;
        //alert (dataString);return false;
        $.ajax({
            type: "POST",
            url: "action/action_insertClassroom.php",
            data: dataString,
            success: function(msg) {
                //alert(msg); return false; use for debugging
                if (msg.indexOf("does exist") != -1){
                    window.classroomExists = true;
                    $("span.error-message").text("This Classroom already exists.");
                }
                if (!window.classroomExists) {
                    $('#newClassroomModal').modal('hide');
                    $('#newClassroomModal').on('hidden.bs.modal', refresh_activePage());
                }

            }
        });
    }); //end of function for btn_insertClassroom



    $("#btn_insertSemester").click(function() {
        // validate and process form here
        var semYear = $("input#semesterYear").val();
        var semSeason = $("select#semesterSeason").val();
        var semStartDate = $("input#semesterStartDate").val();
        var semNumWeeks = $("input#semesterNumberWeeks").val();
        var firstBlockStart = $("input#firstBlockStart").val();
        var secondBlockStart = $("input#secondBlockStart").val();
        if (semSeason == 0){
            $("span.error-message").text("Please select a season.");
            return false;
        }

        semesterExists = false;

        var dataString = 'semYear='+ semYear + '&semSeason=' + semSeason
            + '&semStart=' + semStartDate + '&semNumWeeks=' + semNumWeeks
            + '&firstBlockStart=' + firstBlockStart + '&secondBlockStart=' + secondBlockStart;
        //alert (dataString); return false;
        $.ajax({
            type: "POST",
            url: "action/action_insertSemester.php",
            data: dataString,
            success: function(msg) {
                //alert(msg); return false;
                if (msg.indexOf("does exist") != -1){
                    window.semesterExists = true;
                    $("span.error-message").text("This Semester already exists.");
                }
                if (!window.semesterExists) {
                    $('#newSemesterModal').modal('hide');
                    $('#newSemesterModal').on('hidden.bs.modal', refresh_activePage());
                }
            }
        });
    }); //end of function for btn_insertSemester




    $("#btn_insertBuilding").click(function() {
        // validate and process form here
        var buildCode = $("input#buildingCode").val();
        var buildCampus = $("select#buildingCampus").val();
        var buildName = $("input#buildingName").val();

        if (buildCode.length == 0){
         $("span.error-message").text("A building code is required.");
         return false;
         }
        if (buildName.length == 0){
            $("span.error-message").text("A building name is required.");
            return false;
        }

        buildingExists = false;

        var dataString = 'buildCode='+ buildCode + '&buildName=' + buildName
            + '&campusId=' + buildCampus;
        //alert (dataString); return false;
        $.ajax({
            type: "POST",
            url: "action/action_insertBuilding.php",
            data: dataString,
            success: function(msg) {
                if (msg.indexOf("does exist") != -1){
                    window.buildingExists = true;
                    $("span.error-message").text("This Building already exists.")
                }
                if (!window.buildingExists) {
                    $('#newBuildingModal').modal('hide');
                    $('#newBuildingModal').on('hidden.bs.modal', refresh_activePage());
                }
            }
        });
    }); //end of function for btn_insertBuilding


    $("#btn_insertCampus").click(function() {
        // validate and process form here
        var campusName = $("input#campusName").val();

        if (campusName.length == 0){
            $("span.error-message").text("A campus name is required.");
            return false;
        }

        campusExists = false;

        var dataString = 'campusName='+ campusName;
        //alert (dataString); return false;
        $.ajax({
            type: "POST",
            url: "action/action_insertCampus.php",
            data: dataString,
            success: function(msg) {
                //alert("insertCampus: " + msg); return false;
                if (msg.indexOf("does exist") != -1){
                    window.campusExists = true;
                    $("span.error-message").text("This Campus already exists.")
                }
                if (!window.campusExists) {
                    $('#newCampusModal').modal('hide');
                    $('#newCampusModal').on('hidden.bs.modal', refresh_activePage());
                }
            }
        });
    }); //end of function for btn_insertCampus


    $("#btn_insertDepartment").click(function() {
        // validate and process form here
        var deptCode = $("input#departmentCode").val();
        var deptName = $("input#departmentName").val();

        departmentExists = false;

        var dataString = 'deptCode=' + deptCode + '&deptName=' + deptName;
        //alert (dataString); return false;
        $.ajax({
            type: "POST",
            url: "action/action_insertDepartment.php",
            data: dataString,
            success: function(msg) {
                if (msg.indexOf("does exist") != -1){
                    window.departmentExists = true;
                    $("span.error-message").text("This Department already exists.")
                }
                if (!window.departmentExists) {
                    $('#newDepartmentModal').modal('hide');
                    $('#newDepartmentModal').on('hidden.bs.modal', refresh_activePage());
                }

            }
        });
    }); //end of function for btn_insertDepartment


    $("#btn_insertSection").click(function() {

        // validate and process form here
        var sectionCourse = $("#sectionCourse").val();
        var sectionProfessor = $("#sectionProfessor").val();
        var sectionClassroom = $("#sectionClassroom").val();
        var sectionDays = $("select#sectionDays").val();
        var sectionStartTime = $("#sectionStartTime").val();
        var sectionEndTime = $("#sectionEndTime").val();
        var sectionIsOnline = $("#sectionOnline:checkbox:checked").length > 0 ? 1 : 0;
        var sectionBlock = $("#sectionBlock").val();
        var sectionSemester = $("#sectionSemester").val();
        var sectionCapacity = $("#sectionCapacity").val();
        var daysString = "";
        if (sectionDays != "Online" ){  // || sectionClassroom != 0  don't need this ?
            $.each(sectionDays, function(index, value){
                daysString += value;
            });
            //only tests if not online
            /*if (sectionStartTime.indexOf('M') == -1){
                sectionStartTime += parseInt(sectionStartTime.slice(0,2)) < 12 ? ' AM' : ' PM';
            }
            if (sectionEndTime.indexOf('M') == -1){
                sectionEndTime += parseInt(sectionEndTime.slice(0,2)) < 12 ? ' AM' : ' PM';
            }*/
            console.log("sectionStartTime: " + sectionStartTime);
            var timePattern = /[0-1][0-9]:[0-5][0-9]/i;//[AP]M
            if (!timePattern.test(sectionStartTime)){
                window.alert("Invalid start time entered! Please enter a valid time in this format:\n00:00 AM");
                this.modal("show");
            }
            if(timePattern.exec(sectionStartTime).length < sectionStartTime.length){
                sectionStartTime = timePattern.exec(sectionStartTime);
            }
            if (!timePattern.test(sectionEndTime)){
                window.alert("Invalid end time entered! Please enter a valid time in this format:\n00:00 AM");
                this.modal("show");
            }
            if(timePattern.exec(sectionEndTime).length < sectionEndTime.length){
                sectionEndTime = timePattern.exec(sectionEndTime);
            }
            startMoment = moment(sectionStartTime, 'hh:mm A');
            endMoment = moment(sectionEndTime, 'hh:mm A');
            sectionStartTime = startMoment.format('hh:mm:ss');
            sectionEndTime = endMoment.format('hh:mm:ss');
        }else {
            daysString = "Online";
            sectionIsOnline = 1;
            sectionStartTime = '00:00:00';
            sectionEndTime = '00:00:00';
        }
        //alert(daysInt);
        //alert( $("#sectionStartTime").val());

        sectionExists = false;

        var dataString = 'sectionCourse=' + sectionCourse + '&sectionProfessor=' + sectionProfessor
            + '&sectionClassroom=' + sectionClassroom + '&sectionDays=' + daysString
            + '&sectionStartTime=' + sectionStartTime + '&sectionEndTime=' + sectionEndTime
            + '&sectionIsOnline=' + sectionIsOnline
            + '&sectionBlock=' + sectionBlock + '&sectionCapacity=' + sectionCapacity
            + '&sectionSemester=' + sectionSemester;
        //console.log (dataString); return false;
        $.ajax({
            type: "POST",
            url: "action/action_insertSection.php",
            data: dataString,
            success: function(msg) {
                //console.log(msg); return false;
                if (msg.indexOf("does exist") != -1){
                    window.sectionExists = true;
                    $("span.error-message").text("This Section already exists.")
                }
                if (!window.sectionExists) {

                    $("#newSectionModal").modal('hide');
                    $("#newSectionModal").on('hidden.bs.modal', function(){
                        console.log("modal hidden");
                        refresh_activePage()
                    });
                }
            },
            error: function(msg){
                console.log(msg);
                return false;
            }
        });
    }); //end of function for btn_insertSection

    var clearErrorMessage = function() {
        $("span.error-message").text("");
    };

    $("input.form-control").click(clearErrorMessage);
    $("select.form-control").click(clearErrorMessage);

    /*$("input#courseCode").click(clearErrorMessage);
    $("#courseDepartment").click(clearErrorMessage);*/

    $(".newResourceLink").click(function() {
        $("span.error-message").text("");  //start out with no error message
        $("input.form-control").val("");
        $("select.form-control").prop('selectedIndex', 0);
        $("input#semesterYear").val(new Date().getFullYear()+1);
        $("input#semesterNumberWeeks").val(15);

        return true;
    });
});