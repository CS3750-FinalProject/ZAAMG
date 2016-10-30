<!--
This form tests getting Building info and calling
action_insertBuilding.php with the Submit button
-->


<!--
The user needs the Campus ID to fill out the Building form.
The Campus Id is assigned by the database,
so the following table will show the existing Campus records
to the user, including the correct Campus IDs.
-->
<table>
    <tr>
        <!--<th>Campus Id</th>--><th>Campus Name</th>
    </tr>
    <tr>    <!--open <tr> tag which will continue with a php echo-->


        <?php
        include 'Campus.php';      # so php can make Campus objects with Database results
        require_once 'Database.php';
        $database = new Database();

        $selectAll = $database->dbh->prepare('SELECT * FROM ZAAMG.Campus ORDER BY ZAAMG.Campus.campus_name ASC');
        $selectAll->execute();

        /* This line takes the query result and makes an array of Campus objects,
         * one object per row.
         * http://php.net/manual/en/pdostatement.fetchall.php
         */http://stackoverflow.com/questions/29805097/php-constructing-a-class-with-pdo-warning-missing-argument
        $result = $selectAll->fetchAll(PDO::FETCH_CLASS, "Campus",
            array('id','name'));


        #continuing the Campus display table...
        foreach ($result as $row){
            echo #"<td>".$row->campus_id."</td>".
                "<td>".$row->campus_name."</td>"
                ."</tr>";  #close table row
        }
        #close table tag
        echo "</table>";
        echo "</br>";

        # var_dump($result);    # use this to see the attributes of the object


        echo "</br>";
        ?>


        <!--  Here is the Insert Building form:  -->



<form action="action_insertBuilding.php" method="post">
  <p>Building Code: <input type="text" name="buildCode" /></p>
  <p>Building Name: <input type="text" name="buildName" /></p>
  <!--<p>Campus ID: <input type="text" name="campusID" /></p>-->

    <p>Campus ID: <select name="campusID" required>

            <?php
            foreach ($result as $row) {
                echo "<option value=\"" . $row->campus_id . "\">" .$row->campus_name."</option>";
            }
            ?>

        </select></p>

  <p><input type="submit" /></p>
</form>