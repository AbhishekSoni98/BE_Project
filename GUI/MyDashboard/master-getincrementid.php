<?php
$host = 'localhost';
$root = 'root';
$pwd = '';
$db = 'BE';
$port=3306;
$con = mysqli_connect($host,$root,$pwd,$db,$port);

$masterid = $_POST['masterid'];

$q = "SELECT `Increment_id` FROM `increment_details` WHERE MasterReqID = $masterid AND Increment_id > 0";
$query = mysqli_query($con, $q);

?>
<h6 class="collapse-header">Increment ID:</h6>

<?php

while($result = mysqli_fetch_array($query)){
	?>
		<a class="collapse-item" href="increment.php?incrementid=<?php echo $result['Increment_id']; ?>&masterid=<?php echo $masterid; ?>">
		<?php echo $result['Increment_id']; ?></a>
		
<?php
}

?>