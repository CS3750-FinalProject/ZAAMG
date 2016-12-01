//.unbind() tip from https://stackoverflow.com/questions/14969960/jquery-click-events-firing-multiple-times

function ModalEditing(){

    $("[id^='new'][id$='Modal']").unbind().on("shown.bs.modal", function () {
        var whichThing = $(this).attr('id').split('new').pop().split('Modal').shift();
        loadNewModalFields(whichThing);
    });



    //preload edit modal fields when it gets shown/hidden

    $("[id^='edit'][id$='Modal']").unbind().on("shown.bs.modal", function () {
        var whichThing = $(this).attr('id').split('edit').pop().split('Modal').shift();
        loadFields(whichThing);
    });


    var loadFields = function(whichThing){
        switch (whichThing){
            case 'Semester':
                loadSemesterFields();
                break;
            case 'Campus':
                loadCampusFields();
                break;
            case 'Building':
                loadBuildingFields();
                break;
            case 'Department':
                loadDepartmentFields();
                break;
        }
    };

    var loadNewProfessorFields = function(){
        $.ajax({
            type: "POST",
            url: "action/action_getResources.php",
            data: "resource=departments",
            dataType: 'json',
            success: function (depts) {
                $('#profDepartment').empty();
                $('#profDepartment').append($('<option />').val("").text("Please Select..."));
                depts.forEach(function (obj) {
                    $('#profDepartment').append($('<option />').val(obj.id).text(obj.code + " " + obj.name));
                });
            },
            error: function (msg) {
                console.log("error: " + JSON.stringify(msg));
            }
        });
    };

    var loadNewClassroomFields = function(){
        $.ajax({
            type: "POST",
            url: "action/action_getResources.php",
            data: "resource=campus_buildings",
            dataType:  'json',
            success: function(buildings) {
                $('#classroomBuilding').empty();
                $('#classroomBuilding').append($('<option />').val("").text("Please Select..."));
                buildings.forEach(function(obj){
                    $('#classroomBuilding').append($('<option />').val(obj.building_id).text(obj.campus + ": " + obj.building_name));
                });
                $('#classroomBuilding option[value=0]').detach() //remove "online" building
            },
            error: function(msg) {
                console.log("error: " + JSON.stringify(msg));
            }
        });
    };

    var loadNewCourseFields = function(){
        $.ajax({
            type: "POST",
            url: "action/action_getResources.php",
            data: "resource=departments",
            dataType: 'json',
            success: function (depts) {
                $('#courseDepartment').empty();
                $('#courseDepartment').append($('<option />').val("").text("Please Select..."));
                depts.forEach(function (obj) {
                    $('#courseDepartment').append($('<option />').val(obj.id).text(obj.code + " " + obj.name));
                });
            },
            error: function (msg) {
                console.log("error: " + JSON.stringify(msg));
            }
        });
    };

    var loadNewBuildingFields = function(){
        $.ajax({
            type: "POST",
            url: "action/action_getResources.php",
            data: "resource=campus",
            dataType: 'json',
            success: function (campi) {
                $('#buildingCampus').empty();
                $('#buildingCampus').append($('<option />').val("").text("Please Select..."));

                campi.forEach(function (obj) {
                    $('#buildingCampus').append($('<option />').val(obj.id).text(obj.name));
                });
            },
            error: function (msg) {
                console.log("error: " + JSON.stringify(msg));
            }

        });

    };



    var loadNewSectionFields = function(){
        $.ajax({
            type: "POST",
            url: "action/action_getResources.php",
            data: "resource=courses",
            dataType:  'json',
            success: function(courses) {
                $('#sectionCourse').empty();
                $('#sectionCourse').append($('<option />').val("").text("Please Select..."));
                courses.forEach(function(obj){
                    $('#sectionCourse').append($('<option />').val(obj.id).text(obj.pref + " " + obj.num + " " + obj.title));
                });
            },
            error: function(msg) {
                console.log("error: " + JSON.stringify(msg));
            }
        });
        $.ajax({
            type: "POST",
            url: "action/action_getResources.php",
            data: "resource=professors",
            dataType:  'json',
            success: function(profs) {
                $('#sectionProfessor').empty();
                $('#sectionProfessor').append($('<option />').val("").text("Please Select..."));

                profs.forEach(function(obj){
                    $('#sectionProfessor').append($('<option />').val(obj.id).text(obj.last + ", " + obj.first));
                });
            },
            error: function(msg) {
                console.log("error: " + JSON.stringify(msg));
            }
        });
        $.ajax({
            type: "POST",
            url: "action/action_getResources.php",
            data: "resource=rooms",
            dataType:  'json',
            success: function(rooms) {
                $('#sectionClassroom').empty();
                $('#sectionClassroom').append($('<option />').val("").text("Please Select..."));
                rooms.forEach(function(obj){
                    $('#sectionClassroom').append($('<option />').val(obj.id).text(obj.campus + ", " + obj.building + ": " + obj.number));
                });
                $('#sectionClassroom option[value=0]').detach()//remove the Online Classroom from the list
            },
            error: function(msg) {
                console.log("error: " + JSON.stringify(msg));
            }
        });
        $.ajax({
            type: "POST",
            url: "action/action_getResources.php",
            data: "resource=semesters",
            dataType:  'json',
            success: function(sems) {
                $('#sectionSemester').empty();
                $('#sectionSemester').append($('<option />').val("").text("Please Select..."));
                sems.forEach(function(obj){
                    $('#sectionSemester').append($('<option />').val(obj.id).text(obj.year + " " + obj.season));
                });
            },
            error: function(msg) {
                console.log("error: " + JSON.stringify(msg));
            }
        });
    };


    var loadNewModalFields = function(whichThing){
        switch (whichThing){
            case 'Section':
                loadNewSectionFields();
                break;
            case 'Professor':
                loadNewProfessorFields();
                break;
            case 'Building':
                loadNewBuildingFields();
                break;
            case 'Department':
                loadNewDepartmentFields();
                break;
            case 'Classroom':
                loadNewClassroomFields();
                break;
            case 'Course':
                loadNewCourseFields();
                break;
        }
    }

    var loadSemesterFields = function(){
        console.log('got here');
        $('#editModal_semesterSeason').empty();
        $('#editModal_semesterSeason').append($('<option />').val("Spring").text("Spring"));
        $('#editModal_semesterSeason').append($('<option />').val("Summer").text("Summer"));
        $('#editModal_semesterSeason').append($('<option />').val("Fall").text("Fall"));
        $.ajax({
            type: "POST",
            url: "action/action_getResources.php",
            data: "resource=semesters",
            dataType: 'json',
            success: function (sems) {
                $('#pick_editSemester').empty();
                sems.forEach(function (obj, i) {
                    $('#pick_editSemester').append($('<option />').val(obj.id).text(obj.year + " " + obj.season));
                    if (i == 0){
                        $('#pick_editSemester').val(obj.id).attr('selected', 'selected');
                        $('#editModal_semesterSeason').val(obj.season).attr('selected', 'selected');
                    }
                    $('#editModal_semesterYear').val(obj.year);
                    var start = moment(obj.start, 'YYYY-MM-DD hh:mm:ss');
                    var firstStart = moment(obj.firstStart, 'YYYY-MM-DD hh:mm:ss');
                    var secondStart = moment(obj.secondStart, 'YYYY-MM-DD hh:mm:ss');
                    $('#editModal_semesterStartDate').val(start.format('YYYY-MM-DD')); //WORKS IN CHROME!  yay!
                    $('#editModal_firstBlockStart').val(firstStart.format('YYYY-MM-DD'));
                    $('#editModal_secondBlockStart').val(secondStart.format('YYYY-MM-DD'));
                    $('#editModal_semesterNumberWeeks').val(obj.weeks);
                });
            },
            error: function (msg) {
                console.log("error: " + JSON.stringify(msg));
            }
        });



        $('#pick_editSemester').change(function(){
            var pickedSemesterId = $(this).val();
            $.ajax({  //when the user selects a semester, the name is loaded into the name input field
                type: 'POST',
                url: 'loadSemester_toEdit.php',
                data: 'semId=' + pickedSemesterId,
                dataType: 'json',
                success: function(data)
                {
                    $('#pick_editSemester').val(data.id).attr('selected', 'selected');
                    $('#editModal_semesterYear').val(data.year);
                    $('#editModal_semesterSeason').val(data.season).attr('selected', 'selected');
                    var start = moment(data.start, 'YYYY-MM-DD hh:mm:ss');
                    var firstStart = moment(data.firstBlock, 'YYYY-MM-DD hh:mm:ss');
                    var secondStart = moment(data.secondBlock, 'YYYY-MM-DD hh:mm:ss');
                    $('#editModal_semesterStartDate').val(start.format('YYYY-MM-DD')); //WORKS IN CHROME!  yay!
                    $('#editModal_firstBlockStart').val(firstStart.format('YYYY-MM-DD'));
                    $('#editModal_secondBlockStart').val(secondStart.format('YYYY-MM-DD'));
                    $('#editModal_semesterNumberWeeks').val(data.weeks);
                },
                error: function(data){
                    console.log(data.responseText);
                }
            });
        });

    }


    var loadCampusFields = function(){
        $.ajax({
            type: "POST",
            url: "action/action_getResources.php",
            data: "resource=campus",
            dataType: 'json',
            success: function (campi) {
                $('#pick_editCampus').empty();
                campi.forEach(function (obj, i) {
                    console.log(obj.id);
                    $('#pick_editCampus').append($('<option />').val(obj.id).text(obj.name));
                    if (i == 0){
                        $('#pick_editCampus').val(obj.id).attr('selected', 'selected');
                        $('#editModal_campusName').val(obj.name);
                    }
                    $('#pick_editCampus option[value=0]').detach() //remove "Online" Campus
                });
            },
            error: function (msg) {
                console.log("error: " + JSON.stringify(msg));
            }
        });
        $('#pick_editCampus').change(function(){
            var pickedCampusId = $(this).val();
            $.ajax({  //when the user selects a campus, the name is loaded into the name input field
                type: 'POST',
                url: 'loadCampus_toEdit.php',
                data: 'campusId=' + pickedCampusId,
                dataType: 'json',
                success: function(data)
                {
                    $('#editModal_campusName').val(data.name);
                },
                error: function(data){
                    console.log(data.responseText);
                }
            });
        });
    }




    var loadBuildingFields = function(){
        $.ajax({
            type: "POST",
            url: "action/action_getResources.php",
            data: "resource=campus",
            dataType: 'json',
            success: function (campi) {
                $('#editModal_buildingCampus').empty();
                campi.forEach(function (obj, i) {
                    $('#editModal_buildingCampus').append($('<option />').val(obj.id).text(obj.name));
                    console.log(obj.id);
                });
            },
            error: function (msg) {
                console.log("error: " + JSON.stringify(msg));
            }
        });
        $.ajax({
            type: "POST",
            url: "action/action_getResources.php",
            data: "resource=campus_buildings",
            dataType: 'json',
            success: function (data) {
                $('#pick_editBuilding').empty();
                data.forEach(function (obj, i) {
                    $('#pick_editBuilding').append($('<option />').val(obj.building_id).text(obj.campus + ": " + obj.building_name));

                    if (i == 0){
                        $('#pick_editBuilding').val(obj.building_id).attr('selected', 'selected');
                        $('#editModal_buildingCampus').val(obj.campusId).attr('selected', 'selected');
                        $('#editModal_buildingCode').val(obj.building_code);
                        $('#editModal_buildingName').val(obj.building_name);
                    }
                });
                $('#pick_editBuilding option[value=0]').detach() //remove "Online" Building
            },
            error: function (msg) {
                console.log("error: " + JSON.stringify(msg));
            }
        });
        $('#pick_editBuilding').change(function(){
            var pickedBuildingId = $(this).val();
            $.ajax({  //when the user selects a campus, the name is loaded into the name input field
                type: 'POST',
                url: 'loadBuilding_toEdit.php',
                data: 'buildingId=' + pickedBuildingId,
                dataType: 'json',
                success: function(data)
                {
                    $('#editModal_buildingCampus').val(data.campus).attr('selected', 'selected');
                    $('#editModal_buildingCode').val(data.code);
                    $('#editModal_buildingName').val(data.name);
                },
                error: function(data){
                    console.log(data.responseText);
                }
            });
        });
    }

    var loadDepartmentFields = function(){
        $.ajax({
            type: "POST",
            url: "action/action_getResources.php",
            data: "resource=departments",
            dataType: 'json',
            success: function (depts) {
                $('#pick_editDepartment').empty();
                depts.forEach(function (obj, i) {
                    $('#pick_editDepartment').append($('<option />').val(obj.id).text(obj.code + "  " + obj.name));
                    if (i == 0){
                        $('#pick_editDepartment').val(obj.id).attr('selected', 'selected');
                        $('#editModal_departmentCode').val(obj.code);
                        $('#editModal_departmentName').val(obj.name);
                    }
                });
            },
            error: function (msg) {
                console.log("error: " + JSON.stringify(msg));
            }
        });
        $('#pick_editDepartment').change(function(){
            var pickedDeptId = $(this).val();
            $.ajax({  //when the user selects a campus, the name is loaded into the name input field
                type: 'POST',
                url: 'loadDepartment_toEdit.php',
                data: 'deptId=' + pickedDeptId,
                dataType: 'json',
                success: function(data)
                {
                    $('#editModal_departmentCode').val(data.code);
                    $('#editModal_departmentName').val(data.name);
                },
                error: function(data){
                    console.log(data.responseText);
                }
            });
        });
    }



$('#btn_updateCampus').click(function(){
        var activePage;
        var campusId            = $('#pick_editCampus :selected').val();
        var campusName          = $('#editModal_campusName').val();
        $('[id^=' + 'navbar_' +']').each(function(){
            if ($(this).hasClass('active')){
                activePage = $(this).attr('id').split('navbar_').pop();
            }
        });
        $.ajax({
            type: "POST",
            url: "action/action_updateCampus.php",
            data:
            "campusId="           + campusId +
            "&campusName="        + campusName +
            "&action=update",
            success: function(msg) {
                console.log(JSON.stringify(msg));
                $('#editCampusModal').modal('hide');
                switch(activePage){
                    case "sec": loadPhpPage('section_page.php'); break;
                    case "prof": loadPhpPage('prof_page.php'); break;
                    case "room": loadPhpPage('classroom_page.php'); break;
                }
            },
            error: function(msg) {
                console.log("error: " + JSON.stringify(msg));
            }
        });

    });

$('#btn_updateDepartment').click(function(){
    var activePage;
    var deptId            = $('#pick_editDepartment :selected').val();
    var deptName          = $('#editModal_departmentName').val();
    var deptCode          = $('#editModal_departmentCode').val();
    $('[id^=' + 'navbar_' +']').each(function(){
        if ($(this).hasClass('active')){
            activePage = $(this).attr('id').split('navbar_').pop();
        }
    });
    $.ajax({
        type: "POST",
        url: "action/action_updateDepartment.php",
        data:
        "deptId="           + deptId +
        "&deptName="        + deptName +
        "&deptCode="        + deptCode +
        "&action=update",
        success: function(msg) {
            console.log("dept: " + JSON.stringify(msg));
            $('#editDepartmentModal').modal('hide');
            switch(activePage){
                case "sec": loadPhpPage('section_page.php'); break;
                case "prof": loadPhpPage('prof_page.php'); break;
                case "room": loadPhpPage('classroom_page.php'); break;
            }
        },
        error: function(msg) {
            console.log("error: " + JSON.stringify(msg));
        }
    });

});

  $('#btn_updateBuilding').click(function(){
        var activePage;
        var buildingId              = $('#pick_editBuilding :selected').val();
        var campusId                = $('#editModal_buildingCampus :selected').val();
        var buildingCode            = $('#editModal_buildingCode').val();
        var buildingName            = $('#editModal_buildingName').val();

        $('[id^=' + 'navbar_' +']').each(function(){
            if ($(this).hasClass('active')){
                activePage = $(this).attr('id').split('navbar_').pop();
            }
        });
        $.ajax({
            type: "POST",
            url: "action/action_updateBuilding.php",
            data:
            "buildingId="           + buildingId +
            "&campusId="            + campusId +
            "&buildingCode="        + buildingCode +
            "&buildingName="        + buildingName +
            "&action=update",
            success: function(msg) {
                $('#editBuildingModal').modal('hide');
                switch(activePage){
                    case "sec": loadPhpPage('section_page.php'); break;
                    case "prof": loadPhpPage('prof_page.php'); break;
                    case "room": loadPhpPage('classroom_page.php'); break;
                }
            },
            error: function(msg) {
                console.log("error: " + JSON.stringify(msg));
            }
        });

    });

$('#btn_updateSemester').click(function(){
        var activePage;
        var semId                   = $('#pick_editSemester :selected').val();
        var semYear                 = $('#editModal_semesterYear').val();
        var semSeason               = $('#editModal_semesterSeason :selected').val();
        var semWeeks                = $('#editModal_semesterNumberWeeks').val();
        var semStart                = $('#editModal_semesterStartDate').val();
        var semFirstStart           = $('#editModal_firstBlockStart').val();
        var semSecondStart          = $('#editModal_secondBlockStart').val();

        $('[id^=' + 'navbar_' +']').each(function(){
            if ($(this).hasClass('active')){
                activePage = $(this).attr('id').split('navbar_').pop();
            }
        });
        $.ajax({
            type: "POST",
            url: "action/action_updateSemester.php",
            data:
            "semId="           + semId +
            "&semYear="        + semYear +
            "&semSeason="      + semSeason +
            "&semWeeks="       + semWeeks +
            "&semStart="       + semStart +
            "&semFirstStart="  + semFirstStart +
            "&semSecondStart=" + semSecondStart +
            "&action=update",
            success: function(msg) {
                console.log(JSON.stringify(msg));
                $('#editSemesterModal').modal('hide');
                switch(activePage){
                    case "sec": loadPhpPage('section_page.php'); break;
                    case "prof": loadPhpPage('prof_page.php'); break;
                    case "room": loadPhpPage('classroom_page.php'); break;
                }
            },
            error: function(msg) {
                console.log("error: " + JSON.stringify(msg));
            }
        });

    });



$('#btn_deleteCampus').click(function(){
    var activePage;
    var campusId            = $('#pick_editCampus :selected').val();
    $('[id^=' + 'navbar_' +']').each(function(){
        if ($(this).hasClass('active')){
            activePage = $(this).attr('id').split('navbar_').pop();
        }
    });
    $.ajax({
        type: "POST",
        url: "action/action_updateCampus.php",
        data:
        "campusId="           + campusId +
        "&action=delete",
        success: function(msg) {
            $('#editCampusModal').modal('hide');
            switch(activePage){
                case "sec": loadPhpPage('section_page.php'); break;
                case "prof": loadPhpPage('prof_page.php'); break;
                case "room": loadPhpPage('classroom_page.php'); break;
            }
        },
        error: function(msg) {
            console.log("error: " + JSON.stringify(msg));
        }
    });

});

$('#btn_deleteBuilding').click(function(){
    var activePage;
    var buildingId              = $('#pick_editBuilding :selected').val();

    $('[id^=' + 'navbar_' +']').each(function(){
        if ($(this).hasClass('active')){
            activePage = $(this).attr('id').split('navbar_').pop();
        }
    });
    $.ajax({
        type: "POST",
        url: "action/action_updateBuilding.php",
        data:
        "buildingId="           + buildingId +
        "&action=delete",
        success: function(msg) {
            $('#editBuildingModal').modal('hide');
            switch(activePage){
                case "sec": loadPhpPage('section_page.php'); break;
                case "prof": loadPhpPage('prof_page.php'); break;
                case "room": loadPhpPage('classroom_page.php'); break;
            }
        },
        error: function(msg) {
            console.log("error: " + JSON.stringify(msg));
        }
    });

});

$('#btn_deleteSemester').click(function(){
    var activePage;
    var semId                   = $('#pick_editSemester :selected').val();


    $('[id^=' + 'navbar_' +']').each(function(){
        if ($(this).hasClass('active')){
            activePage = $(this).attr('id').split('navbar_').pop();
        }
    });
    $.ajax({
        type: "POST",
        url: "action/action_updateSemester.php",
        data:
        "semId="           + semId +
        "&action=delete",
        success: function(msg) {
            console.log(JSON.stringify(msg));
            $('#editSemesterModal').modal('hide');
            switch(activePage){
                case "sec": loadPhpPage('section_page.php'); break;
                case "prof": loadPhpPage('prof_page.php'); break;
                case "room": loadPhpPage('classroom_page.php'); break;
            }
        },
        error: function(msg) {
            console.log("error: " + JSON.stringify(msg));
        }
    });

});

$('#btn_deleteDepartment').click(function(){
    var activePage;
    var deptId            = $('#pick_editDepartment :selected').val();
    $('[id^=' + 'navbar_' +']').each(function(){
        if ($(this).hasClass('active')){
            activePage = $(this).attr('id').split('navbar_').pop();
        }
    });
    $.ajax({
        type: "POST",
        url: "action/action_updateDepartment.php",
        data:
        "deptId="           + deptId +
        "&action=delete",
        success: function(msg) {
            console.log("dept: " + JSON.stringify(msg));
            $('#editDepartmentModal').modal('hide');
            switch(activePage){
                case "sec": loadPhpPage('section_page.php'); break;
                case "prof": loadPhpPage('prof_page.php'); break;
                case "room": loadPhpPage('classroom_page.php'); break;
            }
        },
        error: function(msg) {
            console.log("error: " + JSON.stringify(msg));
        }
    });

});




}




//http://stackoverflow.com/questions/36393409/ajax-call-to-populate-select-list-when-another-select-list-changes
//http://www.codingcage.com/2015/11/ajax-login-script-with-jquery-php-mysql.html
//https://openenergymonitor.org/emon/node/107

/*$('#' + 'editModal_form' + whichThing).find('input').each(function(){
 $(this).val('');
 });*/