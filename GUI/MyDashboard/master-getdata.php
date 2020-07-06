<?php
$host = 'localhost';
$root = 'root';
$pwd = '';
$db = 'BE';
$port=3306;
$con = mysqli_connect($host,$root,$pwd,$db,$port);

$masterid = $_POST['masterid'];
// $masterid = 1011;

$q1 = "SELECT `MasterReqID`, `Dataset`, `Mode`, `SlaveCount`, `EndRow`, `Cluster`, `Confidence`, `Support`, `Algorithm`, `Approach`, `MasterReqTime`, `MasterResTime`, `Status` FROM `masterrequestdetails` WHERE
 MasterReqID = $masterid";

$query1 = mysqli_query($con, $q1);

$result = mysqli_fetch_array($query1);
$data['Dataset'] = $result['Dataset'];
$data['Cluster'] = $result['Cluster'];
$data['Algorithm'] = $result['Algorithm'];
$data['Approach'] = $result['Approach'];
$data['Confidence'] = $result['Confidence'];
$data['Support'] = $result['Support'];

if($result['MasterResTime'] == ""){

    $data['Status'] = "In Progress";
}else{
    $data['Status'] = "Completed"; 
}

$q2 = "SELECT COUNT(DISTINCT SlaveID) AS Count FROM `slaverequestdetails` WHERE MasterReqID = $masterid";

$q3 = "SELECT TIMESTAMPDIFF(SECOND,MasterReqTime,MasterResTime) AS UpTime FROM masterrequestdetails WHERE MasterReqID = $masterid";

// $endrow = $result['EndRow'];
// $q4 = "SELECT COUNT(DISTINCT TransID) AS Transactions FROM `dmarttransdetails`WHERE Seq <= $endrow";
$q4 ="SELECT sum(Transactions) AS Transactions FROM `increment_details` WHERE MasterReqID=$masterid and Increment_id=0";

$q5 = "SELECT count(*) AS Rules from finalmasterrules where MasterReqID = $masterid and inc_id =0 ";

$q6 = "SELECT COUNT(*) AS FIS FROM `finalmasterpatterns` WHERE MasterReqID = $masterid and inc_id=0";

$q7 = "SELECT sum(`Rows`) AS EndRow FROM `increment_details` WHERE MasterReqID=$masterid and Increment_id=0";

$query2 = mysqli_query($con, $q2);
$query3 = mysqli_query($con, $q3);
$query4 = mysqli_query($con, $q4);
$query5 = mysqli_query($con, $q5);
$query6 = mysqli_query($con, $q6);
$query7 = mysqli_query($con, $q7);




$result2 = mysqli_fetch_array($query2);
$data['Slavecount'] = $result2['Count'];

$result3 = mysqli_fetch_array($query3);
$data['Uptime'] = $result3['UpTime'];

$result4 = mysqli_fetch_array($query4);
$data['Transactions'] = $result4['Transactions'];

$result5 = mysqli_fetch_array($query5);
$data['Rules'] = $result5['Rules'];

$result6 = mysqli_fetch_array($query6);
$data['FIS'] = $result6['FIS'];

$result7 = mysqli_fetch_array($query7);
$data['EndRow'] = $result7['EndRow'];


echo json_encode($data);

    										        
?>