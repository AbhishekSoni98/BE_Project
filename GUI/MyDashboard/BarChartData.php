<?php

header('Content-Type: application/json');

define('DB_HOST', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', 'Root123#');
define('DB_NAME', 'be_project');
define('DB_PORT', 3307);


$mysqli = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME, DB_PORT);
if(!$mysqli){
	die("Connection failed: ");
}

$masterid = $_GET['masterid'];
// echo $masterid;

$query = sprintf("SELECT SlaveID, COUNT(SlaveID) AS cluster_count FROM slaverequestdetails WHERE MasterReqID = 1011 AND SlaveID IS NOT NULL GROUP BY SlaveID ORDER BY SlaveID");

$result = $mysqli->query($query);

$data = array();
foreach ($result as $row) {
	$data[] = $row;
}

$result->close();

$mysqli->close();

print json_encode($data);
?>