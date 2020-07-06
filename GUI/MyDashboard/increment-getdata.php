<?php
$host = 'localhost';
$root = 'root';
$pwd = '';
$db = 'BE';
$port=3306;
$con = mysqli_connect($host,$root,$pwd,$db,$port);

$masterid = $_POST['masterid'];
$incrementid = $_POST['incrementid'];
// $masterid = 1011;

$q1 = "SELECT `MasterReqID`, `Dataset`, `Mode`, `SlaveCount`, `EndRow`, `Cluster`, `Confidence`, `Support`, `Algorithm`, `Approach`, `MasterReqTime`, `MasterResTime`, `Status` FROM `masterrequestdetails` WHERE
 MasterReqID = $masterid";

$query1 = mysqli_query($con, $q1);

$result = mysqli_fetch_array($query1);
$data['Dataset'] = $result['Dataset'];
// $data['Cluster'] = $result['Cluster'];
$data['Algorithm'] = $result['Algorithm'];
$data['Approach'] = $result['Approach'];
$data['Confidence'] = $result['Confidence'];
$data['Support'] = $result['Support'];
// $data['EndRow'] = $result['EndRow'];



// $q2 = "SELECT COUNT(DISTINCT SlaveID) AS Count FROM `slaverequestdetails` WHERE MasterReqID = $masterid";
$q2 = "SELECT * FROM `increment_details` WHERE `MasterReqID`= $masterid AND `Increment_id`= $incrementid";

$q3 = "SELECT TIMESTAMPDIFF(SECOND,ReqTime,ResTime) AS UpTime FROM increment_details WHERE MasterReqID = $masterid and Increment_id = $incrementid";

// $endrow = $result['EndRow'];
$q4 = "SELECT sum(Transactions) AS Transactions FROM `increment_details` WHERE MasterReqID= $masterid and Increment_id<= $incrementid";

$q5 = "SELECT count(*) AS Rules from finalmasterrules where MasterReqID = $masterid and Inc_id= $incrementid";

$q6 = "SELECT COUNT(*) AS FIS FROM `finalmasterpatterns` WHERE MasterReqID = $masterid and Inc_id= $incrementid";

$q7 = "SELECT sum(`Rows`) AS TotalRows FROM `increment_details` WHERE MasterReqID=$masterid and Increment_id<=$incrementid";


$query2 = mysqli_query($con, $q2);
$query3 = mysqli_query($con, $q3);
$query4 = mysqli_query($con, $q4);
$query5 = mysqli_query($con, $q5);
$query6 = mysqli_query($con, $q6);
$query7 = mysqli_query($con, $q7);


$result2 = mysqli_fetch_array($query2);
$data['IncrementalDataset'] = $result2['Dataset'];
$data['Factor'] = $result2['Factor'];
$data['Type'] = $result2['Type'];

if($result2['ResTime'] == ""){

    $data['Status'] = "In Progress";
}else{
    $data['Status'] = "Completed"; 
}

$result3 = mysqli_fetch_array($query3);
$data['Uptime'] = $result3['UpTime'];

$result4 = mysqli_fetch_array($query4);
$data['Transactions'] = $result4['Transactions'];

$result5 = mysqli_fetch_array($query5);
$data['Rules'] = $result5['Rules'];

$result6 = mysqli_fetch_array($query6);
$data['FIS'] = $result6['FIS'];

$result7 = mysqli_fetch_array($query7);
$data['EndRow'] = $result7['TotalRows'];


echo json_encode($data);

    										        
?>