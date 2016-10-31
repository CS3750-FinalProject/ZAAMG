<!--
This form tests getting Classroom info and calling
action_insertClassroom.php with the Submit button
-->

<!--
The user needs the Building ID to fill out the Classroom form.
The Building Id is assigned by the database,
so the following table will show the existing Building records
to the user, including the correct Building IDs.
-->
<table>
 <tr>
  <!--<th>Building Id--></th><th>Building Code</th><th>Building Name</th><th>Campus Name</th>
 </tr>
 <tr>    <!--open <tr> tag which will continue with a php echo-->


  <?php
  include 'Building.php';      # so php can make Building objects with Database results
  require_once 'Database.php';
  $database = new Database();


  #$selectAll = $database->dbh->prepare('SELECT * FROM ZAAMG.Building');
  #$selectAll->execute();



  $selectAll_1 = $database->dbh->prepare('SELECT building_id, building_code, building_name, campus_name
      FROM ZAAMG.Building JOIN ZAAMG.Campus ON ZAAMG.Building.campus_id = ZAAMG.Campus.campus_id
      ORDER BY building_name ASC');
  $selectAll_1->execute();

  /* This line takes the query result and makes an array of Building objects,
   * one object per row.
   * http://php.net/manual/en/pdostatement.fetchall.php
   */http://stackoverflow.com/questions/29805097/php-constructing-a-class-with-pdo-warning-missing-argument
  #$result = $selectAll->fetchAll(PDO::FETCH_CLASS, "Building",
   #   array('id','code', 'name', 'campID'));



  $result1 = $selectAll_1->fetchAll();
  #var_dump($result1);


  #continuing the Building display table...
  foreach($result1 as $row){
   echo "<td>".$row['building_code']."</td>"
       ."<td>".$row['building_name']."</td>"
       ."<td>".$row['campus_name']."</td>"
       ."</tr>";
  }



/*  #continuing the Building display table...
  foreach ($result as $row){
   echo "<td>".$row->building_id."</td>"
       ."<td>".$row->building_code."</td>"
       ."<td>".$row->building_name."</td>"
       ."<td>".$row->campus_id."</td>"
       ."</tr>";  #close table row
  }*/


  #close table tag
  echo "</table>";
  echo "</br>";

  #var_dump($result);    # use this to see the attributes of the object


  echo "</br>";
  ?>


<!--  Here is the Insert Classroom form:  -->

<form action="action_insertClassroom.php" method="post">
 <p>Classroom Number: <input type="text" name="classNum" /></p>
 <p>Classroom Capacity: <input type="text" name="classCapacity" /></p>
 <!--<p>Building Id: <input type="text" name="buildId" /></p>-->

 <p>Building Code: <select name="buildId" required>

   <?php
   foreach ($result1 as $row) {
       # SHOWS building code but SENDS building id to database
    echo "<option value=\"" . $row['building_id'] . "\">" .$row['building_code']."</option>";
   }
   ?>
  </select></p>

 <p>Campus: <select name="campus_name" >

   <?php
   foreach ($result1 as $row) {
    echo "<option value=\"" . $row['campus_name'] . "\">" .$row['campus_name']."</option>";
   }
   ?>
  </select></p>

 <p><input type="submit" /></p>
</form>