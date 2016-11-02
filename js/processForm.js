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
        //var insertId = -1;  //??
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
        return true;
    });
});