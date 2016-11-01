/**
 * Created by Gisela on 10/31/2016.
 */



$(function() {
    $(".btn.btn-primary").click(function() {
        // validate and process form here
        var courseExists = false;

        var courseCode = $("input#courseCode").val();
        var courseTitle = $("input#courseTitle").val();
        var courseCapacity = $("input#courseCapacity").val();
        var courseCredits = $("input#courseCredits").val();
        var deptId = $("#courseDepartment").val()
        var insertId = -1;  //

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
                    $("span#error-message").text("This Course already exists.");
                    courseExists = true;
                }

            }
        });
        if (!courseExists){//course didn't already exist, got inserted
            return true;
        }else {
            return false;
        }

    });

    $("input#courseCode").click(function(){
        $("span#error-message").text(""); //clear error message when user enters Course Code field
    });
    $("#newCourseButton").click(function() {
        $("span#error-message").text("");  //start out with no error message
        $("input#courseCode").val("");
        $("input#courseTitle").val("");
    });
});