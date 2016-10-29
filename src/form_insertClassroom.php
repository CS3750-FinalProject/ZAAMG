<!--
This form tests getting Classroom info and calling
action_insertClassroom.php with the Submit button
-->



<table>
 <tr>
  <th>Building Id</th><th>Building Code</th><th>Building Name</th><th>Campus Id</th>
 </tr>
 <tr>    <!--open <tr> tag which will continue with a php echo-->


  <?php
  include 'Building.php';
  require_once 'Database.php';
  $database = new Database();

  $selectAll = $database->dbh->prepare('SELECT * FROM ZAAMG.Building');
  $selectAll->execute();

  /* This line takes the query result and makes an array of Building objects,
   * one object per row.
   * http://php.net/manual/en/pdostatement.fetchall.php
   */http://stackoverflow.com/questions/29805097/php-constructing-a-class-with-pdo-warning-missing-argument
  $result = $selectAll->fetchAll(PDO::FETCH_CLASS, "Building",
      array('id','code', 'name', 'campID'));


  foreach ($result as $row){
   echo "<td>".$row->building_id."</td>"
       ."<td>".$row->building_code."</td>"
       ."<td>".$row->building_name."</td>"
       ."<td>".$row->campus_id."</td>"
       ."</tr>";  #close table row
  }
  #close table tag
  echo "</table>";
  echo "</br>";

  # var_dump($result);    # use this to see the attributes of the object


  echo "</br>";

  ?>





<form action="action_insertClassroom.php" method="post">
 <p>Classroom Number: <input type="text" name="classNum" /></p>
 <p>Classroom Capacity: <input type="text" name="classCapacity" /></p>
 <p>Building Id: <input type="text" name="buildId" /></p>
 <p><input type="submit" /></p>
</form>