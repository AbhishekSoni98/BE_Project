<?php

$con = mysqli_connect('localhost','root','Root123#','be_project', 3307);


$q2 = "SELECT COUNT(DISTINCT SlaveID) AS SlaveCount FROM `slaverequestdetails` WHERE MasterReqID = 1002";

$query2 = mysqli_query($con, $q2);

$result2 = mysqli_fetch_array($query2);

$count= $result2['SlaveCount'];


$start = 1;
while ($start<= $count){
    $q1 = "SELECT COUNT(SlaveReqID) AS ClusterCount FROM `slaverequestdetails` WHERE MasterReqID = 1002 AND SlaveID = $start";
    $query1 = mysqli_query($con, $q1);
    $result1 = mysqli_fetch_array($query1);
    $data[$start]['SlaveID'] = $start;
    $data[$start]['Cluster'] = $result1['ClusterCount'];
    $start = $start + 1;
}

echo json_encode($data);

?>
