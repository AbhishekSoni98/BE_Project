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

	<style>
.slidecontainer {
  width: 100%;
}

.slider {
  -webkit-appearance: none;
  width: 100%;
  height: 15px;
  border-radius: 5px;
  background: #d3d3d3;
  outline: none;
  opacity: 0.7;
  -webkit-transition: .2s;
  transition: opacity .2s;
}

.slider:hover {
  opacity: 1;
}

.slider::-webkit-slider-thumb {
  -webkit-appearance: none;
  appearance: none;
  width: 25px;
  height: 25px;
  border-radius: 50%;
  background: #0275D8;
  cursor: pointer;
}


</style>

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
					<a class="nav-link" href="index.html">Home</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="index.html">Services</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="index.html">About Us</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="#contact">Contact</a>
				</li>
			</ul>
		</div>
	</nav>
</div>

<div class="container-fluid bgimage">
	
	<div class="row">
		
		<div class="col-md-12 col-sm-12 col-xs-12">
			
			<center style="color: black;"><br><br><h2>Incremental Mining Details</h2></center>
			
			<form class="form-container" method="POST" >
				
				<div class="form-group">
				<div class="row">
					<div class="col-sm-2 col-lg-1">
						<label for="masterid"style="color: black;">Available MasterID</label>
					</div>
					<div class="col-sm-4 col-lg-5">
					<select id="masterid" class="form-control" name="masterid">
							<option value="">Select the Master ID</option>

							<?php

							$q = "SELECT * FROM `masterrequestdetails`";
							$result = mysqli_query($con, $q);
							while($rows = mysqli_fetch_array($result)){	
							?>	
    						<option value="<?php echo $rows['MasterReqID'];?>"><?php echo $rows['MasterReqID'];?></option>
							<?php
							   }
							?>

					</select>
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
					<label for="mode" style="color: black;">Mode</label>
					</div>
					<div class="col-sm-4 col-lg-5">
						<select id="mode" class="form-control" name="mode" style="color: black;">
							<option value="Type-1" selected="">Type-1</option>
							<option value="Type-2">Type-2</option>
						</select>
					</div>
					
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
					
				</div>	
				</div>




				<div class="form-group">
				<div class="row">
					

					<div class="col-sm-2 col-lg-1">
						<label for="myRange" style="color: black;">Upgrade / Downgrade Factor(%)</label>
					</div>
					<div class="col-sm-10 col-lg-11">
						<div class="slidecontainer">
  						<input type="range" min="1" max="50" value="25" class="slider" id="myRange">
  						<p style="text-align: right">Value: <span id="value" ></span></p>
					</div>

						<script>
						var slider = document.getElementById("myRange");
						var output = document.getElementById("value");
						output.innerHTML = slider.value;

						slider.oninput = function() {
						output.innerHTML = this.value;
						}
						</script>
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
        SlaveCount : document.getElementById('slavecount').value,
        Factor : document.getElementById('value').value,
 
    })
});


		// var test = document.getElementById("dataset").files[0];
		// alert(test.pathinfo);
				
}
</script>


</body>
	
</html>
