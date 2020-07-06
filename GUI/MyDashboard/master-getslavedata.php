<?php
$host = 'localhost';
$root = 'root';
$pwd = '';
$db = 'BE';
$port=3306;
$con = mysqli_connect($host,$root,$pwd,$db,$port);

$masterid = $_POST['masterid'];

$q = "SELECT DISTINCT SlaveID FROM `slaverequestdetails` WHERE MasterReqID = $masterid";
$query = mysqli_query($con, $q);

?>
<h6 class="collapse-header">Slaves ID:</h6>

<?php

while($result = mysqli_fetch_array($query)){
	?>
		<a class="collapse-item" href="slave.php?slaveid=<?php echo $result['SlaveID']; ?>&masterid=<?php echo $masterid; ?>">
		<?php echo $result['SlaveID']; ?></a>
		
<?php
}

?>