<?php

// session_start();



$dataset=$masterid=$mode = $StartRow = $EndRow = $Cluster = $Confidence = $Support = $Algorithm = $Approach ="";
$result = 0;  //initialize variable

$host = 'localhost';
$root = 'root';
$pwd = 'Root123#';
$db = 'be_project';

$con = mysqli_connect($host,$root,$pwd,$db,3307); // connect to the database



// Check connection
if ($con->connect_error) {
    die("Connection Failed: " . $con->connect_error);
}

if (isset($_POST['submit'])) 
{
	// receive all input values from the form
	$masterid = mysqli_real_escape_string($con, $_POST['masterid']);
	$dataset = mysqli_real_escape_string($con, $_POST['file']);
    $mode = mysqli_real_escape_string($con, $_POST['mode']);
    $SlaveCount = mysqli_real_escape_string($con, $_POST['slavecount']);
    $EndRow = mysqli_real_escape_string($con, $_POST['end']);
    $Cluster = mysqli_real_escape_string($con, $_POST['cluster']);
    $Confidence = mysqli_real_escape_string($con, $_POST['confidence']);
    $Support = mysqli_real_escape_string($con, $_POST['support']);
    $Algorithm = mysqli_real_escape_string($con, $_POST['algorithm']);
    $Approach = mysqli_real_escape_string($con, $_POST['approach']);

    $query = "INSERT INTO `masterrequestdetails`(`MasterReqID`, `Dataset`, `Mode`, `SlaveCount`, `EndRow`, `Cluster`, `Confidence`, `Support`, `Algorithm`, `Approach`) 
              VALUES('$masterid','$dataset','$mode', '$SlaveCount', '$EndRow', '$Cluster', '$Confidence', '$Support', '$Algorithm', '$Approach' )"; 
    
    if (mysqli_query($con,$query)) {

    	echo "New record created successfully";
    }
    else {
    	echo "Error: " . $query . "<br>" . mysqli_error($con);
    }
    
    mysqli_close($con); 
   
echo "<script> ML(); </script>";

echo '<script> window.open("MyDashboard/index.php",name = "_parent") </script>';

}


?>


<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Parameters</title>
	<link rel="stylesheet" href="bootstrap-4.1.3-dist/css/bootstrap.min.css">
	<link rel="stylesheet" href="style.css">
	<link rel="stylesheet" href="css/fixed.css">

</head>

<body data-spy="scroll" data-target="#navbarResponsive">

<div id="home">
		
	<nav class="navbar navbar-expand-md navbar-dark bg-dark fixed-top">
		<button class="navbar-toggler" type="button" data-toggle="collapse"
		data-target="#navbarResponsive">
			<span class="navbar-toggler-icon"></span>
		</button>
		<div class="collapse navbar-collapse" id="navbarResponsive">
			<ul class="navbar-nav ml-auto">
				<li class="nav-item">
					<a class="nav-link" href="#home">Home</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="parameters.php">Parameters</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="slave-info-table.html">Slave Info</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="MyDashboard/index.php">Dashboard</a>
				</li>
			</ul>
		</div>
	</nav>
</div>

<div class="container-fluid bgimage">
	
	<div class="row">
		
		<div class="col-md-12 col-sm-12 col-xs-12">
			
			<center style="color: black;"><br><br><h2>Get The Master Working</h2></center>
			
			<form class="form-container" method="POST" >
				
				<div class="form-group">
				<div class="row">
					<div class="col-sm-2 col-lg-1">
						<label for="masterid"style="color: black;">MasterID</label>
					</div>
					<div class="col-sm-4 col-lg-5">
						<input type="text" class="form-control" id="masterid" name="masterid" required style="color: black;">
					</div>

					<div class="col-sm-2 col-lg-1">
						<label for="dataset" style="color: black;">Dataset</label>
					</div>
					<div class="col-sm-4 col-lg-5">
						<input type="file" class="form-control" id="dataset" name="file" required style="color: black;">
					</div>
				</div>	
				</div>

				<div class="form-group">
				<div class="row">
					<div class="col-sm-2 col-lg-1">
						<label for="algorithm" style="color: black;">Algorithm</label>
					</div>
					<div class="col-sm-4 col-lg-5">
						<select id="algorithm" class="form-control" name="algorithm" >
							<option value="Apriori" >Apriori</option>
							<option value="FP_Growth">FP-growth</option>
							<option value="Eclat">Eclat</option>
						</select>
					</div>

					<div class="col-sm-2 col-lg-1">
						<label for="approach" style="color: black;">Approach</>
					</div>
					<div class="col-sm-4 col-lg-5">
						<select id="approach" class="form-control" name="approach">
							<option value="Horizontal">Horizontal</option>
							<option value="Vertical">Vertical</option>
							<option value="Hybrid">Hybrid</option>
							<option value="Normal">Normal</option>
						</select>
					</div>
				</div>	
				</div>


				<div class="form-group">
				<div class="row">
					<div class="col-sm-2 col-lg-1">
					<label for="mode" style="color: black;">Mode</label>
					</div>
					<div class="col-sm-4 col-lg-5">
						<select id="mode" class="form-control" name="mode" style="color: black;">
							<option value="Partial-Records" selected="">Partial Records</option>
							<option value="All-Records">All Records</option>
						</select>
					</div>

					<div class="col-sm-2 col-lg-1">
						<label for="cluster" style="color: black;">No of Cluster</label>
					</div>
					<div class="col-sm-4 col-lg-5">
						<input type="number" name="cluster" class="form-control" step="1" min="0" style="color: black;" id="cluster">
					</div>
				</div>	
				</div>




				<div class="form-group">
				<div class="row">
					<div class="col-sm-2 col-lg-1">
					<label for="slavecount" style="color: black;">Slave Count</label>
					</div>
					<div class="col-sm-4 col-lg-5" >
					<select id="slavecount" class="form-control" name="slavecount">
							<option value="">Select the Number Of Slaves</option>

							<?php

							$q2 = "SELECT * FROM `slavedetails`";
							$result2 = mysqli_query($con, $q2);
							$count = 1;
							while($rows = mysqli_fetch_array($result2)){	
							?>	
    						<option value="<?php echo $count;?>"><?php echo $count;?></option>
							<?php
   									 $count++;
							   }
							?>

					</select>
					<!-- <input type="number" name="start" class="form-control" required step="1" min="0" id="startRow"> -->
					</div>

					<div class="col-sm-2 col-lg-1">
						<label for="endRow" style="color: black;">Rows</label>
					</div>
					<div class="col-sm-4 col-lg-5">
						<input type="number" name="end" class="form-control" required step="1" min="0" id="endRow">
					</div>
				</div>	
				</div>


				<div class="form-group">
				<div class="row">
					<div class="col-sm-2 col-lg-1">
						<label for="support" style="color: black;">Support</label>
					</div>
					<div class="col-sm-4 col-lg-5">
					<input type="number" name="support" placeholder="eg:-0.1, 0.04, 0.001" class="form-control" required min="0" step="0.001" id="support">
					</div>

					<div class="col-sm-2 col-lg-1">
						<label for="confidence" style="color: black;">Confidence</label>
					</div>
					<div class="col-sm-4 col-lg-5">
						<input type="number" name="confidence" placeholder="eg:-0.1,0.04" class="form-control" required min="0" step="0.01"
						id="confidence">
					</div>
				</div>	
				</div>



				

				
				<center><button onclick= "ML();" type="submit" id="submit" name="submit" class="btn btn-primary">Submit</button></center>
				<br><br>
			</form>	
			
		</div>
		<!-- <div class="col-md-4 col-sm-4 col-xs-12"></div> -->
	</div>
</div>
<div id="contact" class="off">
	<footer class="sticky-footer">
		<div class="row justify-content-center">
			<div class="col-md-5 text-center">
				<strong>Contact Info</strong>
				<p>(+91) 9876543210<br>abcd@gmail.com</p>
				<a href="#" ><i class="fab fa-facebook-square"></i></a>
				<a href="#" ><i class="fab fa-twitter-square"></i></a>
				<a href="#" ><i class="fab fa-instagram"></i></a>
			</div>
			<hr class="socket">
			&copy: All rights reserved.
		</div>
	</footer>
</div>


<!--- Script Source Files -->
<script src="js/jquery-3.3.1.min.js"></script>
<script src="bootstrap-4.1.3-dist/js/bootstrap.min.js"></script>
<script src="https://use.fontawesome.com/releases/v5.5.0/js/all.js"></script>
<!--- End of Script Source Files -->
<script type="text/javascript">
	$("#mode").change(function() {
		if ($(this).val() == "Partial-Records"){
			$('#startRow').show();
			$('#startRow').attr('required', '');
			$('#startRow').attr('data-error', 'This field is required.');
			$('#endRow').show();
			$('#endRow').attr('required', '');
			$('#endRow').attr('data-error', 'This field is required.');
		}
		else
		{
			$('#startRow').hide();
			$('#startRow').removeAttr('required');
    		$('#startRow').removeAttr('data-error');
    		$('#endRow').hide();
    		$('#endRow').removeAttr('required');
    		$('#endRow').removeAttr('data-error');
		}
	});
	$("#startRow").trigger("change");
	$("#endRow").trigger("change");
	$("#approach").change(function(){
		if (($(this).val() == "Horizontal")||($(this).val() == "Hybrid"))
		{
			$('#cluster').show();
			$('#cluster').attr('required', '');
			$('#cluster').attr('data-error', 'This field is required.');
		}
		else
		{
			$('#cluster').hide();
			$('#cluster').removeAttr('required');
    		$('#cluster').removeAttr('data-error');
		}

	});
	$("#cluster").trigger("change");
</script>
<script type = "text/javascript">
	function ML(){
fetch('http://127.0.0.1:5000/main',{

    method: 'POST',
    headers: {
        'Content-Type': 'application/json'
    },
    body: JSON.stringify({
        Master : parseInt(document.getElementById('masterid').value),
        dataset : document.getElementById('dataset').value,
        mode : document.getElementById('mode').value,
        c_size : parseInt(document.getElementById('cluster').value),
        // startRow : parseInt(document.getElementById('startRow').value),
        endRow : parseInt(document.getElementById('endRow').value),
        min_confidence : parseFloat(document.getElementById('confidence').value),
        min_support : parseFloat(document.getElementById('support').value),
        Algo : document.getElementById('algorithm').value,
        Approach : document.getElementById('approach').value,
 
    })
});
}
</script>
</body>
	
</html>
