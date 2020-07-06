<?php
$host = 'localhost';
$root = 'root';
$pwd = '';
$db = 'BE';
$port=3306;
$con = mysqli_connect($host,$root,$pwd,$db,$port);

$masterid = $_POST['masterid'];
$slaveid = $_POST['slaveid'];

$q = "SELECT `SlaveReqId` FROM `slaverequestdetails` WHERE MasterReqID = $masterid AND SlaveID= $slaveid
AND Status = 'Complete' ";
$query = mysqli_query($con, $q);

?>
<h6 class="collapse-header">Clusters ID:</h6>

<?php

while($result = mysqli_fetch_array($query)){
	?>
		<a class="collapse-item" href="#"><?php echo $result['SlaveReqId']; ?></a>
		
<?php
}

?>