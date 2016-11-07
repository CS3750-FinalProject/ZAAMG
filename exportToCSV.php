<?php
$host = "localhost";
$dbname  = "zaamg";
$username = "zaamg";
$password = "zaamg";
$sql = "SELECT * FROM Section";


//Create connection
$conn = new mysqli($host, $username, $password, $dbname);

//Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$result = $conn->query($sql);
$output = fopen("php://output", "w");

//fputcsv result to output
if ($result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {
    fputcsv($output, $row);
  }
} else {
  //No results from sql statement
  echo "0 results";
}

$conn->close();

/* Testing different methods
if(isset($_POST["export"]))
{
     $connect = mysqli_connect("localhost", "root", "", "testing");
     header('Content-Type: text/csv; charset=utf-8');
     header('Content-Disposition: attachment; filename=data.csv');
     $output = fopen("php://output", "w");
     fputcsv($output, array('ID', 'Name', 'Address', 'Gender', 'Designation', 'Age'));
     $query = "SELECT * from tbl_employee ORDER BY id DESC";
     $result = mysqli_query($connect, $query);
     while($row = mysqli_fetch_assoc($result))
     {
          fputcsv($output, $row);
     }
     fclose($output);
}
*/
?>
