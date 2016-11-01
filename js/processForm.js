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
                    $("span#error-message").text("This Course already exists.")
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




    var clearErrorMessage = function() {
        $("span.error-message").text("");
    };

    $("input#courseCode").click(clearErrorMessage);
    $("#courseDepartment").click(clearErrorMessage);

    $(".newResourceLink").click(function() {
        $("span.error-message").text("");  //start out with no error message
        $("input.form-control").val("");
        $("select.form-control").prop('selectedIndex', 0);
        return true;
    });
});