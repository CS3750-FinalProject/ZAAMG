function validate() {

        if (document.form_insertProf.profEmail.value == "yo@mama") {
            var input = document.form_insertProf.profEmail;
            input.value = "invalid email address";
            input.focus();
            return false;
        }
}


