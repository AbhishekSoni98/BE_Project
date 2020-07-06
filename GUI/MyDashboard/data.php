<?php
$host = 'localhost';
$root = 'root';
$pwd = '';
$db = 'BE';
$port=3306;
$con = mysqli_connect($host,$root,$pwd,$db,$port);

$q = "select * from slavedetails";

$query = mysqli_query($con, $q);

while($result = mysqli_fetch_array($query)){
	?>
		<a class="collapse-item" href="cards.html"><?php echo $result['SlaveID']; ?></a>
		
<?php
}

?>