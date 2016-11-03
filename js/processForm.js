/**
 * Created by Gisela on 10/31/2016.
 */

$(function() {

    $("#btn_insertCourse").click(function() {
        // validate and process form here
        var courseCode = $("input#courseCode").val();
        var courseTitle = $("input#courseTitle").val();
        var courseCapacity = $("input#courseCapacity").val();
        var courseCredits = $("input#courseCredits").val();
        var deptId = $("#courseDepartment").val()

        if (courseCode.length == 0){
            $("span.error-message").text("A course code is required.")
            return false;
        }

        courseExists = false;  //global variable

        var dataString = 'courseCode='+ courseCode + '&courseTitle=' + courseTitle
            + '&courseCap=' + courseCapacity + '&courseCred=' + courseCredits
            + '&deptId=' + deptId;
        //alert (dataString);return false;
        $.ajax({
            type: "POST",
            url: "action_insertCourse.php",
            data: dataString,
            success: function(msg) {
                if (msg.indexOf("does exist") != -1){
                    window.courseExists = true;
                    $("span.error-message").text("This Course already exists.")
                }
                if (!window.courseExists)
                    $('#newCourseModal').modal('hide');

            }
        });
    }); //end of function for btn_insertCourse



    $("#btn_insertProfessor").click(function() {
        // validate and process form here
        var profFirst = $("input#profFirst").val();
        var profLast = $("input#profLast").val();
        var profEmail = $("input#profEmail").val();
        var deptId = $("#profDepartment").val()

        if (profEmail.length == 0){
            $("span.error-message").text("An email address is required.")
            return false;
        }

        profExists = false;

        var dataString = 'profFirst='+ profFirst + '&profLast=' + profLast
            + '&profEmail=' + profEmail + '&deptId=' + deptId;
        //alert (dataString);return false;
        $.ajax({
            type: "POST",
            url: "action_insertProfessor.php",
            data: dataString,
            success: function(msg) {
                if (msg.indexOf("does exist") != -1){
                    window.profExists = true;
                    $("span.error-message").text("A Professor with this email address already exists.")
                }
                if (!window.profExists)
                    $('#newProfessorModal').modal('hide');

            }
        });
    }); //end of function for btn_insertProfessor


    $("#btn_insertClassroom").click(function() {
        // validate and process form here
        var classCap = $("input#roomCapacity").val();
        var classNum = $("input#classroomNumber").val();
        var buildId = $("select#classroomBuilding").val();

        if (classNum.length == 0){
            $("span.error-message").text("A room number is required.")
            return false;
        }
        if (buildId == 0){
            $("span.error-message").text("Please select a building.")
            return false;
        }

        classroomExists = false;

        var dataString = 'classNum='+ classNum + '&classCapacity=' + classCap
            + '&buildId=' + buildId;
        //alert (dataString);return false;
        $.ajax({
            type: "POST",
            url: "action_insertClassroom.php",
            data: dataString,
            success: function(msg) {
                if (msg.indexOf("does exist") != -1){
                    window.classroomExists = true;
                    $("span.error-message").text("This Classroom already exists.")
                }
                if (!window.classroomExists)
                    $('#newClassroomModal').modal('hide');

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
            $("span.error-message").text("Please select a season.")
            return false;
        }

        semesterExists = false;

        var dataString = 'semYear='+ semYear + '&semSeason=' + semSeason
            + '&semStart=' + semStartDate + '&semNumWeeks=' + semNumWeeks
            + '&firstBlockStart=' + firstBlockStart + '&secondBlockStart=' + secondBlockStart;
        //alert (dataString); return false;
        $.ajax({
            type: "POST",
            url: "action_insertSemester.php",
            data: dataString,
            success: function(msg) {
                if (msg.indexOf("does exist") != -1){
                    window.semesterExists = true;
                    $("span.error-message").text("This Semester already exists.")
                }
                if (!window.semesterExists)
                    $('#newSemesterModal').modal('hide');

            }
        });
    }); //end of function for btn_insertSemester




    $("#btn_insertBuilding").click(function() {
        // validate and process form here
        var buildCode = $("input#buildingCode").val();
        var buildCampus = $("select#buildingCampus").val();
        var buildName = $("input#buildingName").val();

        if (buildCode.length == 0){
         $("span.error-message").text("A building code is required.")
         return false;
         }
        if (buildName.length == 0){
            $("span.error-message").text("A building name is required.")
            return false;
        }

        buildingExists = false;

        var dataString = 'buildCode='+ buildCode + '&buildName=' + buildName
            + '&campusId=' + buildCampus;
        //alert (dataString); return false;
        $.ajax({
            type: "POST",
            url: "action_insertBuilding.php",
            data: dataString,
            success: function(msg) {
                if (msg.indexOf("does exist") != -1){
                    window.buildingExists = true;
                    $("span.error-message").text("This Building already exists.")
                }
                if (!window.buildingExists)
                    $('#newBuildingModal').modal('hide');

            }
        });
    }); //end of function for btn_insertBuilding


    $("#btn_insertCampus").click(function() {
        // validate and process form here
        var campusName = $("input#campusName").val();

        if (campusName.length == 0){
            $("span.error-message").text("A campus name is required.")
            return false;
        }

        campusExists = false;

        var dataString = 'campusName='+ campusName;
        //alert (dataString); return false;
        $.ajax({
            type: "POST",
            url: "action_insertCampus.php",
            data: dataString,
            success: function(msg) {
                if (msg.indexOf("does exist") != -1){
                    window.campusExists = true;
                    $("span.error-message").text("This Campus already exists.")
                }
                if (!window.campusExists)
                    $('#newCampusModal').modal('hide');

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
            url: "action_insertDepartment.php",
            data: dataString,
            success: function(msg) {
                if (msg.indexOf("does exist") != -1){
                    window.departmentExists = true;
                    $("span.error-message").text("This Department already exists.")
                }
                if (!window.departmentExists) {
                    $('#newDepartmentModal').modal('hide');
                    location.reload();//reloads window so that new Database is refreshed
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
        var daysInt = 0;
        $.each(sectionDays, function(index, value){
            daysInt += parseInt(value);
        });
        //alert(daysInt);
        var sectionStartTime = $("#sectionStartTime").val();
        var sectionEndTime = $("#sectionEndTime").val();
        var sectionBlock = $("#sectionBlock").val();
        var sectionSemester = $("#sectionSemester").val();
        var sectionCapacity = $("#sectionCapacity").val();


        sectionExists = false;

        var dataString = 'sectionCourse=' + sectionCourse + '&sectionProfessor=' + sectionProfessor
            + '&sectionClassroom=' + sectionClassroom + '&sectionDays=' + daysInt
            + '&sectionStartTime=' + sectionStartTime + '&sectionEndTime=' + sectionEndTime
            + '&sectionBlock=' + sectionBlock + '&sectionCapacity=' + sectionCapacity
            + '&sectionSemester=' + sectionSemester;
        //alert (dataString); return false;
        $.ajax({
            type: "POST",
            url: "action_insertSection.php",
            data: dataString,
            success: function(msg) {
                alert(msg);
                if (msg.indexOf("does exist") != -1){
                    window.sectionExists = true;
                    $("span.error-message").text("This Section already exists.")
                }
                if (!window.sectionExists) {
                    $('#newSectionModal').modal('hide');
                    location.reload();//reloads window so that new Database is refreshed
                }

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