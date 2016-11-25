var ModalEditing = function() {
    var toggleHideClass_modal = function() {
        //.unbind() tip from https://stackoverflow.com/questions/14969960/jquery-click-events-firing-multiple-times
        $('.btn-modalEdit').unbind().click(function() {
            $('[id^=' + 'editModalDiv_' + ']').toggleClass('hide');

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

        });


    }
    toggleHideClass_modal();

    $("#editSemesterModal").unbind().on("hidden.bs.modal", function () {
        $('#pick_editSemester').find('option:eq(0)').prop('selected', true);
        $('[id^=' + 'editModalDiv_' + ']').addClass('hide');
    });
}

/*


 //http://stackoverflow.com/questions/36393409/ajax-call-to-populate-select-list-when-another-select-list-changes
 //http://www.codingcage.com/2015/11/ajax-login-script-with-jquery-php-mysql.html
 //https://openenergymonitor.org/emon/node/107

 $.ajax({
 type: 'POST',
 url: 'pickBuildings.php',        //the script to call to get data
 data: 'campusId=' + campusId,    //you can insert url arguments here to pass to pickBuildings.php
 //for example 'id=5&parent=6"
 dataType: 'json',                //data format
 success: function(data)          //on receive of reply
 {
 var dropdown_Building = $('#pickBuilding');
 dropdown_Building.empty();
 dropdown_Building.append($('<option />').val(0).text('Building...'));

 $.each(data, function() {
 dropdown_Building.append($('<option />').val(this.building_id).text(this.building_name));
 });


 }
 });


 */