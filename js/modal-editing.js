//.unbind() tip from https://stackoverflow.com/questions/14969960/jquery-click-events-firing-multiple-times

var ModalEditing = function() {
    var toggleHideClass_modal = function() {

        $('.btn-modalEdit').unbind().click(function() {
            $('[id^=' + 'editModalDiv_' + ']').toggleClass('hide');

            //if this Edit button has editSemesterModal as an ancestor:
            if($(this).parents('div#editSemesterModal').length){
                loadSemesterFields();
            }

            //if this Edit button has editCampusModal as an ancestor:
            if($(this).parents('div#editCampusModal').length){
                loadCampusFields();
            }

            //if this Edit button has editBuildingModal as an ancestor:
            if($(this).parents('div#editBuildingModal').length){
                loadBuildingFields();
            }

        });


    }
    //toggleHideClass_modal();  //try not doing this

    //reset the modal when it gets shown/hidden

    $("[id^='edit'][id$='Modal']").unbind().on("shown.bs.modal", function () {
        //$("[id^='editModalDiv_']").addClass('hide'); //try not doing this
        $("[id^='pick_edit']").find('option:eq(0)').prop('selected', true);
        var whichThing = $(this).attr('id').split('edit').pop().split('Modal').shift();
        loadFields(whichThing);

        $(this).on("hidden.bs.modal", function () {
            //$("[id^='editModalDiv_']").addClass('hide');  //try not doing this
            $("[id^='pick_edit']").find('option:eq(0)').prop('selected', true);
        });
    });


    $("[id^='pick_edit']").unbind().change(function(){
        var whichThing = $(this).attr('id').split('pick_edit').pop();
        loadFields(whichThing);
    })

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
        }
    };

    var loadSemesterFields = function(){
        var pickedSemId = $('#pick_editSemester :selected').val();

        $.ajax({
            type: 'POST',
            url: 'loadSemester_toEdit.php',  //the script to call to get data
            data: 'semId=' + pickedSemId,
            dataType: 'json',                //data format
            success: function(data)          //on receive of reply
            {
                //remove 'selected' from previous choice so there won't be multiple selected
                $('#editModal_semesterSeason option').each(function(){
                    $(this).removeAttr('selected');
                });
                $('#editModal_semesterYear').val(data.year);
                $('#editModal_semesterSeason option[value=' + data.season+']').attr('selected', 'selected');
                $('#editModal_semesterStartDate').val(data.start.slice(0,10));
                $('#editModal_semesterNumberWeeks').val(data.weeks);
                $('#editModal_firstBlockStart').val(data.firstBlock.slice(0,10));
                $('#editModal_secondBlockStart').val(data.secondBlock.slice(0,10));
            },
            error: function(data){
                console.log(data.responseText);
            }
        });
    }

    var loadCampusFields = function(){
        var pickedCampusId = $('#pick_editCampus :selected').val();

        $.ajax({
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
    }

    var loadBuildingFields = function(){
        var pickedBuildingId = $('#pick_editBuilding :selected').val();

        $.ajax({
            type: 'POST',
            url: 'loadBuilding_toEdit.php',
            data: 'buildingId=' + pickedBuildingId,
            dataType: 'json',
            success: function(data)
            {
                $('#editModal_buildingCode').val(data.code);
                $('#editModal_buildingName').val(data.name);
                $('#editModal_buildingCampus option[value=' + data.campus+']').attr('selected', 'selected');
            },
            error: function(data){
                console.log(data.responseText);
            }
        });
    }

}













//http://stackoverflow.com/questions/36393409/ajax-call-to-populate-select-list-when-another-select-list-changes
//http://www.codingcage.com/2015/11/ajax-login-script-with-jquery-php-mysql.html
//https://openenergymonitor.org/emon/node/107

/*$('#' + 'editModal_form' + whichThing).find('input').each(function(){
 $(this).val('');
 });*/