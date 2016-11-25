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

        });


    }
    toggleHideClass_modal();

    //reset the modal when it gets shown/hidden

    $("[id^='edit'][id$='Modal']").unbind().on("shown.bs.modal", function () {
        $("[id^='editModalDiv_']").addClass('hide');
        $("[id^='pick_edit']").find('option:eq(0)').prop('selected', true);
        $(this).on("hidden.bs.modal", function () {
            console.log('hidden');
            $("[id^='editModalDiv_']").addClass('hide');
            $("[id^='pick_edit']").find('option:eq(0)').prop('selected', true);

        });
    });

    var loadSemesterFields = function(){
        var pickedSemId = $('#pick_editSemester :selected').val();

        $.ajax({
            type: 'POST',
            url: 'loadSemester_toEdit.php',  //the script to call to get data
            data: 'semId=' + pickedSemId,
            dataType: 'json',                //data format
            success: function(data)          //on receive of reply
            {
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

}
