<?php

// $con = mysqli_connect('localhost','root','','project', 3307) or die("Connection Failed: " . $con->connect_error);
header('Content-Type: application/json');

// $conn = mysqli_connect("localhost","root","","project",3307) or die("Error in connection");

define('DB_HOST', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', 'Root123#');
define('DB_NAME', 'be_project');
define('DB_PORT', 3307);


$mysqli = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME, DB_PORT);
if(!$mysqli){
	die("Connection failed: ");
}

$query = sprintf("SELECT Status, COUNT(Status) AS status_count FROM slaverequestdetails WHERE MasterReqID = 10001 GROUP BY        
	  Status  ORDER BY `Status`  DESC");


$result = $mysqli->query($query);

$data = array();
foreach ($result as $row) {
	$data[] = $row;
}


$result->close();


$mysqli->close();

print json_encode($data);

?>