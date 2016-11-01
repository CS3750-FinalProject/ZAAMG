/**
 * Created by Gisela on 10/31/2016.
 */



$(function() {
    $(".btn.btn-primary").click(function() {
        // validate and process form here
        var courseCode = $("input#courseCode").val();
        var courseTitle = $("input#courseTitle").val();
        var courseCapacity = $("input#courseCapacity").val();
        var courseCredits = $("input#courseCredits").val();
        var deptId = $("#courseDepartment").val()
        var insertId = -1;  //
            courseExists = false;

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


    });

    var clearErrorMessage = function() {
        $("span#error-message").text("");
    };

    $("input#courseCode").click(clearErrorMessage);
    $("#courseDepartment").click(clearErrorMessage);

    $("#newCourseButton").click(function() {
        $("span#error-message").text("");  //start out with no error message
        $("input#courseCode").val("");
        $("input#courseTitle").val("");
    });
});