var ModalEditing = function() {
    var toggleHideClass_modal = function() {
        //.unbind() tip from https://stackoverflow.com/questions/14969960/jquery-click-events-firing-multiple-times
        $('.btn-modalEdit').unbind().click(function() {
            text='editModalDiv_';

            $('[id^=' + text + ']').toggleClass('hide');

        });
    }

    toggleHideClass_modal();
}