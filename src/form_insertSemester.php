


<form action="action_insertSemester.php" method="post">
 <p>Year: <input type="number" value = "<?php echo date("Y");?>" name="semYear" /></p>

    <p>Season:  <select name="semSeason">
    <option value="Fall">Fall</option>
        <option value="Spring">Spring</option>
        <option value="Summer">Summer</option>
    </select></p>

    <p>Number Weeks:  <select name="semWeeks">
            <option value="14">14</option>
            <option value="15">15</option>
            <option value="16">16</option>
        </select></p>

 <p>Start Date: <input type="date"  name="semStart" placeholder="yyyy-mm-dd"/></p>
    <p>First Block Start Date: <input type="date" name="firstBlockStart" placeholder="yyyy-mm-dd" /></p>
    <p>Second Block Start Date: <input type="date" name="secondBlockStart" placeholder="yyyy-mm-dd"/></p>


 <p><input type="submit" /></p>
</form>