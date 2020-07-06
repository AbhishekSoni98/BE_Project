<?php
$host = 'localhost';
$root = 'root';
$pwd = '';
$db = 'BE';
$port=3306;
$con = mysqli_connect($host,$root,$pwd,$db,$port);

$masterid = $_POST['masterid'];
$slaveid = $_POST['slaveid'];

// $masterid = 1011;
// $slaveid = 1;

$q1 = "SELECT `MasterReqID`, `Dataset`, `Mode`, `SlaveCount`, `EndRow`, `Cluster`, `Confidence`, `Support`, `Algorithm`, `Approach`, `MasterReqTime`, `MasterResTime`, `Status` FROM `masterrequestdetails` WHERE
 MasterReqID = $masterid";

$q2 = "SELECT COUNT(`SlaveReqID`) AS Count FROM `slaverequestdetails` WHERE MasterReqID = $masterid AND SlaveID = $slaveid ";

$q3 = "SELECT SUM(FreqItemSet) AS TotalFreqItemSet
        FROM 
        (SELECT sr.SlaveReqID AS ClusterID , count(sr.SlaveReqID) AS FreqItemSet FROM slaverequestdetails sd left join slaveresponsedetails sr on sr.MasterReqID=sd.MasterReqID and sr.MasterReqID=$masterid and sr.SlaveReqID=sd.SlaveReqID 
        where sd.MasterReqID=$masterid and sd.SlaveID=$slaveid group by sr.SlaveReqID ) AS derivedTable";


$query1 = mysqli_query($con, $q1);
$query2 = mysqli_query($con, $q2);
$query3 = mysqli_query($con, $q3);


$result = mysqli_fetch_array($query1);
$data['Dataset'] = $result['Dataset'];

$data['Algorithm'] = $result['Algorithm'];
$data['Approach'] = $result['Approach'];

$data['Support'] = $result['Support'];

$result2 = mysqli_fetch_array($query2);
$data['Cluster'] = $result2['Count'];

$result3 = mysqli_fetch_array($query3);
$data['TotalFreqItemSet'] = $result3['TotalFreqItemSet'];


echo json_encode($data);

    										        
?>