<?php

$host = 'localhost';
$root = 'root';
$pwd = '';
$db = 'BE';
$port=3306;

$con = mysqli_connect($host,$root,$pwd,$db,$port);

$q = "select * from masterrequestdetails";

$query = mysqli_query($con, $q);
?>

<h6 class="collapse-header">Masters ID:</h6>

<?php

while($result = mysqli_fetch_array($query)){
	?>
		<!-- $masterid = $result['MasterReqID']; -->
		<a class="collapse-item" href="master.php?id= <?php echo $result['MasterReqID']; ?>">
		<?php echo $result['MasterReqID']; ?></a><br>
    										            
		
<?php
}

?>
