
<!--
The user needs the Department ID to fill out the Course form.
The Department Id is assigned by the database,
so the following table will show the existing Department records
to the user, including the correct Department IDs.
-->
<table>
 <tr>
  <th>Department Id</th><th>Department Code</th><th>Department Name</th>
 </tr>
 <tr>    <!--open <tr> tag which will continue with a php echo-->


  <?php
  include 'Department.php';      # so php can make Department objects with Database results
  require_once 'Database.php';
  $database = new Database();

  $selectAll = $database->dbh->prepare('SELECT * FROM ZAAMG.Department');
  $selectAll->execute();

  /* This line takes the query result and makes an array of Department objects,
   * one object per row.
   * http://php.net/manual/en/pdostatement.fetchall.php
   */http://stackoverflow.com/questions/29805097/php-constructing-a-class-with-pdo-warning-missing-argument
  $result = $selectAll->fetchAll(PDO::FETCH_CLASS, "Department",
      array('id','code','name'));


  #continuing the Department display table...
  foreach ($result as $row){
   echo "<td>".$row->dept_id."</td>"
       ."<td>".$row->dept_code."</td>"
       ."<td>".$row->dept_name."</td>"
       ."</tr>";  #close table row
  }
  #close table tag
  echo "</table>";
  echo "</br>";

  # var_dump($result);    # use this to see the attributes of the object


  echo "</br>";
  ?>


  <!--  Here is the Insert Course form:  -->



<form action="action_insertCourse.php" method="post">
 <p>Course Code: <input type="text" name="courseCode" /></p>
 <p>Course Title: <input type="text" name="courseTitle" /></p>
 <p>Course Capacity: <input type="text" name="courseCap" /></p>
 <p>Course Credits: <input type="text" name="courseCred" /></p>
 <p>Department Id: <input type="text" name="deptId" /></p>
 <p><input type="submit" /></p>
</form>