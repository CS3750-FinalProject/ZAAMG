<!--

Just testing php/mysql wire-up.

-->

<?php

if (isset($_POST['buildCode'])) $buildCode = $_POST['buildCode'];
else $buildCode = "(not entered)";

if (isset($_POST['buildName'])) $buildName = $_POST['buildName'];
else $buildName = "(not entered)";

if (isset($_POST['campusID'])) $campusID = $_POST['campusID'];
else $campusID = "(not entered)";

?>

Building Code: <?php echo $buildCode?><br>
Building Name: <?php echo $buildName?><br>
Campus Id:<?php echo $campusID?><br>


