 <?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

//Database Connection
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "pasut";
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

?>



<head>
<title>iPasoet</title>
<script src="./plotly.js"></script>
<style type="text/css">
  .header {
    height: 49px;
    width: 100%;
    border-bottom: 1px solid #EDEFF1;
  }
  .header img{
        width: 39px;
    margin-left: 40px;
    margin-top: 1px;
  }

  #sensors {
position: fixed;
top: 13px;
right: 388px;
  }

 #dates {
position: fixed;
top: 13px;
right: 260px;
  }


</style>
</head>


<body>

  <div class="header"><a href="."><img src="./logo_big.png"></a>



<?php

//PHP Snippet To Show Sensor Selection Dropdown
echo "<select id='sensors' onchange='location = this.value;''>";
$sql = "SELECT DISTINCT StationID FROM data_vsat5   ";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            if ($row["StationID"] == $_GET['station'] ){
              echo "<option value='./?station=".$row["StationID"]."'selected>".$row["StationID"]."</option>";
            }
            else{
            echo "<option value='./?station=".$row["StationID"]."'>".$row["StationID"]."</option>";
            }
        }
    }
echo "</select>";

//PHP Snippet To Show Date Selection Dropdown
echo "<select id='dates' onchange='location = this.value;''>";
$sql = "SELECT DISTINCT Date(TimeStamp) as a FROM data_vsat5 WHERE StationID='". $_GET['station']."' ORDER BY TimeStamp DESC";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
          if ($row["a"] == $_GET['date'] ){
            echo "<option selected value='./?station=".$_GET['station']."&date=".$row['a']."'>".$row["a"]."</option>";
          }
          else {
            echo "<option value='./?station=".$_GET['station']."&date=".$row['a']."'>".$row["a"]."</option>";
          }
        }
    }
echo "</select>";







?>





  </div>
  <div id="graph"></div>


 <?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

//Database Connection
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "pasut";
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


//Choose Sensor
if(!isset($_GET['station'])){  
$sql = "SELECT DISTINCT StationID FROM data_vsat5   ";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            echo "<a href='./?station=".$row["StationID"]."'>".$row["StationID"]."</a><br>";
        }
    }

}

else if(isset($_GET['station'])) {

  //Choose Date
  echo $_GET['station']."<br>";
  $sql = "SELECT DISTINCT Date(TimeStamp) as a FROM data_vsat5 WHERE StationID='". $_GET['station']."' ORDER BY TimeStamp DESC";
  $result = $conn->query($sql);
  if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
      echo "<a href='./?station=".$_GET['station']."&date=".$row['a']."''>".$row["a"]."</a><br>";
    }
  }






	if(isset($_GET['date'])) {

   //Show Sensor Data (Finally)
	 echo "Time :". $_GET['date']."<br>";	

   //Use this for one day data
   $sql = "SELECT TimeStamp, Sensor1,Sensor2,Sensor3 FROM data_vsat5 WHERE StationID='". $_GET['station']."' 
	 AND DATE(TimeStamp) = '".$_GET['date']."' ORDER BY TimeStamp DESC";

   //Use this for all time
   if($_GET['date'] == "all"){
   $sql = "SELECT TimeStamp, Sensor1,Sensor2,Sensor3 FROM data_vsat5 WHERE StationID='". $_GET['station']."'ORDER BY TimeStamp DESC";
   }


	 $result = $conn->query($sql);
	 $timecounter = array();
   $sensor1 = array();
   $sensor2 = array();
   $sensor3 = array();
   if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            echo $row["TimeStamp"].";".$row["Sensor1"].";".$row["Sensor2"].";".$row["Sensor3"]."<br>";
            $timecounter[] = $row["TimeStamp"];
            $sensor1[] = $row["Sensor1"];
            $sensor2[] = $row["Sensor2"];
            $sensor3[] = $row["Sensor3"];
        }
        echo "<script>";
        echo "var sensor1 = { x: [";
        foreach ($timecounter as $x){
            echo "'".$x."',";
        }
        echo "],y: [";
        foreach ($sensor1 as $x){
            echo "'".$x."',";
        }
        echo "],mode: 'markers'};";

        echo "var sensor2 = { x: [";
        foreach ($timecounter as $x){
            echo "'".$x."',";
        }
        echo "],y: [";
        foreach ($sensor2 as $x){
            echo "'".$x."',";
        }
        echo "],mode: 'markers'};";

        echo "var sensor3 = { x: [";
        foreach ($timecounter as $x){
            echo "'".$x."',";
        }
        echo "],y: [";
        foreach ($sensor3 as $x){
            echo "'".$x."',";
        }
        echo "],mode: 'markers'};";
        echo "var data = [sensor1,sensor2,sensor3];Plotly.newPlot('graph', data);</script>";
		}
	 }
}
$conn->close();
 


?> 