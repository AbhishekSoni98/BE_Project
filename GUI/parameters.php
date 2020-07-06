<?php

// session_start();

$dataset=$masterid=$mode = $StartRow = $EndRow = $Cluster = $Confidence = $Support = $Algorithm = $Approach = $Available = $Type = $Factor = "";
$result = 0;  //initialize variable

$host = 'localhost';
$root = 'root';
$pwd = '';
$db = 'BE';
$port=3306;

$con = mysqli_connect($host,$root,$pwd,$db,$port); // connect to the database



// Check connection
if ($con->connect_error) {
    die("Connection Failed: " . $con->connect_error);
}

if (isset($_POST['submit'])) 
{
	// receive all input values from the form
	$masterid = mysqli_real_escape_string($con, $_POST['masterid']);
	$dataset = mysqli_real_escape_string($con, $_POST['dataset']);
    $mode = mysqli_real_escape_string($con, $_POST['mode']);
    $SlaveCount = mysqli_real_escape_string($con, $_POST['slavecount']);
    $StartRow = 0;
    $EndRow = mysqli_real_escape_string($con, $_POST['endRow']);
    $Cluster = mysqli_real_escape_string($con, $_POST['cluster']);
    $Confidence = mysqli_real_escape_string($con, $_POST['confidence']);
    $Support = mysqli_real_escape_string($con, $_POST['support']);
    $Algorithm = mysqli_real_escape_string($con, $_POST['algorithm']);
    $Approach = mysqli_real_escape_string($con, $_POST['approach']);
    // $Available = mysqli_real_escape_string($con, $_POST['availablemasterid']);
    // $Type = mysqli_real_escape_string($con, $_POST['type']);
    // $Factor = mysqli_real_escape_string($con, $_POST['value']);

	if ($mode=="All-Records")
	{
    $query = "INSERT INTO `masterrequestdetails`(`MasterReqID`, `Dataset`, `Mode`, `SlaveCount`, `StartRow`, `EndRow`, `Cluster`, `Confidence`, `Support`, `Algorithm`, `Approach`) 
              VALUES('$masterid','$dataset','$mode', '$SlaveCount','$StartRow', '$EndRow', '$Cluster', '$Confidence', '$Support', '$Algorithm', '$Approach' )"; 
    
    if (mysqli_query($con,$query)) {

    	echo "New record created successfully";
		echo '<script> window.open("MyDashboard/index.php",name = "_parent") </script>';
    }
    else {
    	echo "Error: " . $query . "<br>" . mysqli_error($con);
    }
    }
	else
	{
		echo "New increment created successfully";
		echo '<script> window.open("MyDashboard/index.php",name = "_parent") </script>';
	}
    mysqli_close($con); 
   

	

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
			
			<center style="color: black;"><br><br><h2>Get The Master Working</h2></center>
			
			<form class="form-container" method="POST" >
				
				<div class="form-group">
					<div class="row">
						<div class="col-sm-2 col-lg-1" id="label-masterid">
							<label for="masterid" style="color: black;">MasterID</label>
						</div>

						<div class="col-sm-4 col-lg-5" id="input-masterid">
							<input type="text" class="form-control" id="masterid" name="masterid"  style="color: black;">
						</div>

						<div class="col-sm-2 col-lg-1" id="label-dataset">
							<label for="dataset" style="color: black;">Database List</label>
						</div>

						<div class="col-sm-4 col-lg-5" id="input-dataset">
							<select id="dataset" class="form-control" name="dataset">
								<option value="">Select the database</option>
								<?php

								$q2 = "SELECT * FROM `dblist`";
								$result2 = mysqli_query($con, $q2);
								// $count = 1;
								while($rows = mysqli_fetch_array($result2)){	
								?>	
	    						<option value="<?php echo $rows['tablename'];?>"><?php echo $rows['tablename'];?></option>
								<?php
	   									//  $count++;
								   }
								?>
							</select>
						</div>

					</div>

				</div>


				<div class="form-group">
					<div class="row">
						<div class="col-sm-2 col-lg-1" id="label-algorithm">
							<label for="algorithm" style="color: black;">Algorithm</label>
						</div>

						<div class="col-sm-4 col-lg-5" id="input-algorithm">
							<select id="algorithm" class="form-control" name="algorithm" >
								<option value="Apriori" >Apriori</option>
								<option value="FP_Growth">FP-growth</option>
								<option value="Eclat">Eclat</option>
								<option value="New">New</option>
							</select>
						</div>

						<div class="col-sm-2 col-lg-1" id="label-approach">
							<label for="approach" style="color: black;">Approach</>
						</div>

						<div class="col-sm-4 col-lg-5" id="input-approach">
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
							<label for="mode" style="color: black;">Mode of operation</label>
						</div>
						<div class="col-sm-4 col-lg-5">
							<select id="mode" class="form-control" name="mode" style="color: black;">
								<option value="All-Records" selected="">Select the mode</option>
								<option value="Partial-Records" >Incremental</option>
								<option value="All-Records" >All Records</option>
							</select>
						</div>


						<div class="col-sm-2 col-lg-1" id="label-slavecount">
							<label for="slavecount" style="color: black;">Slave Count</label>
						</div>
						<div class="col-sm-4 col-lg-5" id="input-slavecount">
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
						</div>
						
					</div>	
				</div>




				<div class="form-group">
					<div class="row">

						<div class="col-sm-2 col-lg-1" id="label-cluster">
							<label for="cluster" style="color: black;">No of Cluster</label>
						</div>
						<div class="col-sm-4 col-lg-5" id="input-cluster">
							<input value=1 type="number" name="cluster" class="form-control" id="cluster" step="1" min="0" style="color: black;" >
						</div>
						

						<div class="col-sm-2 col-lg-1" id="label-endRow">
							<label for="endRow" style="color: black;">Rows</label>
						</div>
						<div class="col-sm-4 col-lg-5" id="input-endRow">
							<input type="number" name="endRow" class="form-control" id="endRow" step="1" min="0" >
						</div>
					</div>	
				</div>


				<div class="form-group">
					<div class="row">
						<div class="col-sm-2 col-lg-1" id="label-support">
							<label for="support" style="color: black;" >Support</label>
						</div>
						<div class="col-sm-4 col-lg-5" id="input-support">
							<input type="number" name="support" class="form-control" id="support" placeholder="eg:-0.1, 0.04, 0.001"  min="0" step="0.001" max="1">
						</div>

						<div class="col-sm-2 col-lg-1" id="label-confidence">
							<label for="confidence" style="color: black;" >Confidence</label>
						</div>
						<div class="col-sm-4 col-lg-5" id="input-confidence">
							<input type="number" name="confidence" class="form-control" id="confidence" placeholder="eg:-0.1,0.04" min="0" step="0.01" max="1">
						</div>
					</div>	
				</div>

				<div class="form-group">
					<div class="row">
						<div class="col-sm-2 col-lg-1" id="label-availablemasterid">
							<label for="availablemasterid" style="color: black;">Available MasterID</label>
						</div>
						<div class="col-sm-4 col-lg-5" id="input-availablemasterid">
							<select id="availablemasterid" class="form-control" name="availablemasterid">
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


						<div class="col-sm-2 col-lg-1" id="label-type">
							<label for="type" style="color: black;">Select type </label>
						</div>
						<div class="col-sm-4 col-lg-5" id="input-type">
							<select id="type" class="form-control" name="type" style="color: black;">
								<option value="1">Type-1</option>
								<option value="2">Type-2</option>
							</select>
						</div>
						
						

					</div>	
				</div>


				<div class="form-group">
					<div class="row">
						<div class="col-sm-2 col-lg-1" id="label-myRange">
							<label for="myRange" style="color: black;">Upgrade / Downgrade Factor(%)</label>
						</div>
						<div class="col-sm-10 col-lg-11" id="input-myRange">
							<div class="slidecontainer">
	  							<input type="range" class="slider" id="myRange" min="1" max="50" value="25">
	  						<p style="text-align: right">Value: <span id="value" name="value"></span></p>
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

				
				<center><button onclick="ML();" type="submit" id="submit" name="submit" class="btn btn-primary">Submit</button></center>
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
			$('#label-availablemasterid').show();
			$('#label-availablemasterid').attr('required', '');
			$('#label-availablemasterid').attr('data-error', 'This field is required.');

			$('#input-availablemasterid').show();
			$('#input-availablemasterid').attr('required', '');
			$('#input-availablemasterid').attr('data-error', 'This field is required.');

			$('#label-type').show();
			$('#label-type').attr('required', '');
			$('#label-type').attr('data-error', 'This field is required.');

			$('#input-type').show();
			$('#input-type').attr('required', '');
			$('#input-type').attr('data-error', 'This field is required.');

			$('#label-myRange').show();
			$('#label-myRange').attr('required', '');
			$('#label-myRange').attr('data-error', 'This field is required.');

			$('#input-myRange').show();
			$('#input-myRange').attr('required', '');
			$('#input-myRange').attr('data-error', 'This field is required.');
			

			$('#label-masterid').hide();
			$('#label-masterid').removeAttr('required');
    		$('#label-masterid').removeAttr('data-error');

    		$('#input-masterid').hide();
			$('#input-masterid').removeAttr('required');
    		$('#input-masterid').removeAttr('data-error');

    		$('#label-algorithm').hide();
			$('#label-algorithm').removeAttr('required');
    		$('#label-algorithm').removeAttr('data-error');

    		$('#input-algorithm').hide();
			$('#input-algorithm').removeAttr('required');
    		$('#input-algorithm').removeAttr('data-error');

    		$('#label-approach').hide();
			$('#label-approach').removeAttr('required');
    		$('#label-approach').removeAttr('data-error');

    		$('#input-approach').hide();
			$('#input-approach').removeAttr('required');
    		$('#input-approach').removeAttr('data-error');

    		$('#label-cluster').hide();
			$('#label-cluster').removeAttr('required');
    		$('#label-cluster').removeAttr('data-error');

    		$('#input-cluster').hide();
			$('#input-cluster').removeAttr('required');
    		$('#input-cluster').removeAttr('data-error');

    		$('#label-endRow').hide();
			$('#label-endRow').removeAttr('required');
    		$('#label-endRow').removeAttr('data-error');

    		$('#input-endRow').hide();
			$('#input-endRow').removeAttr('required');
    		$('#input-endRow').removeAttr('data-error');

    		$('#label-support').hide();
			$('#label-support').removeAttr('required');
    		$('#label-support').removeAttr('data-error');

    		$('#input-support').hide();
			$('#input-support').removeAttr('required');
    		$('#input-support').removeAttr('data-error');

    		$('#label-confidence').hide();
			$('#label-confidence').removeAttr('required');
    		$('#label-confidence').removeAttr('data-error');

    		$('#input-confidence').hide();
			$('#input-confidence').removeAttr('required');
    		$('#input-confidence').removeAttr('data-error');
		}
		else
		{
			$('#label-availablemasterid').hide();
			$('#label-availablemasterid').removeAttr('required');
    		$('#label-availablemasterid').removeAttr('data-error');

    		$('#input-availablemasterid').hide();
			$('#input-availablemasterid').removeAttr('required');
    		$('#input-availablemasterid').removeAttr('data-error');

    		$('#label-type').hide();
    		$('#label-type').removeAttr('required');
    		$('#label-type').removeAttr('data-error');

    		$('#input-type').hide();
    		$('#input-type').removeAttr('required');
    		$('#input-type').removeAttr('data-error');

    		$('#label-myRange').hide();
    		$('#label-myRange').removeAttr('required');
    		$('#label-myRange').removeAttr('data-error');

    		$('#input-myRange').hide();
    		$('#input-myRange').removeAttr('required');
    		$('#input-myRange').removeAttr('data-error');
 
			$('#label-masterid').show();
			$('#label-masterid').attr('required', '');
			$('#label-masterid').attr('data-error', 'This field is required.');

			$('#input-masterid').show();
			$('#input-masterid').attr('required', '');
			$('#input-masterid').attr('data-error', 'This field is required.');

			$('#label-algorithm').show();
			$('#label-algorithm').attr('required', '');
			$('#label-algorithm').attr('data-error', 'This field is required.');

			$('#input-algorithm').show();
			$('#input-algorithm').attr('required', '');
			$('#input-algorithm').attr('data-error', 'This field is required.');

			$('#label-approach').show();
			$('#label-approach').attr('required', '');
			$('#label-approach').attr('data-error', 'This field is required.');

			$('#input-approach').show();
			$('#input-approach').attr('required', '');
			$('#input-approach').attr('data-error', 'This field is required.');

			$('#label-cluster').show();
			$('#label-cluster').attr('required', '');
			$('#label-cluster').attr('data-error', 'This field is required.');

			$('#input-cluster').show();
			$('#input-cluster').attr('required', '');
			$('#input-cluster').attr('data-error', 'This field is required.');

			$('#label-endRow').show();
			$('#label-endRow').attr('required', '');
			$('#label-endRow').attr('data-error', 'This field is required.');

			$('#input-endRow').show();
			$('#input-endRow').attr('required', '');
			$('#input-endRow').attr('data-error', 'This field is required.');

			$('#label-support').show();
			$('#label-support').attr('required', '');
			$('#label-support').attr('data-error', 'This field is required.');

			$('#input-support').show();
			$('#input-support').attr('required', '');
			$('#input-support').attr('data-error', 'This field is required.');

			$('#label-confidence').show();
			$('#label-confidence').attr('required', '');
			$('#label-confidence').attr('data-error', 'This field is required.');

			$('#input-confidence').show();
			$('#input-confidence').attr('required', '');
			$('#input-confidence').attr('data-error', 'This field is required.');
		}
	});
	$("#label-availablemasterid").trigger("change");
	$("#input-availablemasterid").trigger("change");

	$("#label-type").trigger("change");
	$("#input-type").trigger("change");

	$("#label-myRange").trigger("change");
	$("#input-myRange").trigger("change");
	
	$("#label-masterid").trigger("change");
	$("#input-masterid").trigger("change");

	$("#label-algorithm").trigger("change");
	$("#input-algorithm").trigger("change");

	$("#label-approach").trigger("change");
	$("#input-approach").trigger("change");

	$("#label-cluster").trigger("change");
	$("#input-cluster").trigger("change");

	$("#label-endRow").trigger("change");
	$("#input-endRow").trigger("change");

	$("#label-support").trigger("change");
	$("#input-support").trigger("change");

	$("#label-confidence").trigger("change");
	$("#input-confidence").trigger("change");


	$("#approach").change(function(){
		if (($(this).val() == "Horizontal")||($(this).val() == "Hybrid"))
		{
			$('#label-cluster').show();
			$('#label-cluster').attr('required', '');
			$('#label-cluster').attr('data-error', 'This field is required.');

			$('#input-cluster').show();
			$('#input-cluster').attr('required', '');
			$('#input-cluster').attr('data-error', 'This field is required.');
		}
		else
		{
			$('#label-cluster').hide();
			$('#label-cluster').removeAttr('required');
    		$('#label-cluster').removeAttr('data-error');

    		$('#input-cluster').hide();
			$('#input-cluster').removeAttr('required');
    		$('#input-cluster').removeAttr('data-error');

		}

	});

	$("#label-cluster").trigger("change");
	$("#input-cluster").trigger("change");

	$("#type").change(function(){
		if (($(this).val() == "2"))
		{
			$('#label-myRange').show();
			$('#label-myRange').attr('required', '');
			$('#label-myRange').attr('data-error', 'This field is required.');

			$('#input-myRange').show();
			$('#input-myRange').attr('required', '');
			$('#input-myRange').attr('data-error', 'This field is required.');

		}
		else
		{
			$('#label-myRange').hide();
    		$('#label-myRange').removeAttr('required');
    		$('#label-myRange').removeAttr('data-error');

    		$('#input-myRange').hide();
    		$('#input-myRange').removeAttr('required');
    		$('#input-myRange').removeAttr('data-error');
			
		}

	});

	$("#label-myRange").trigger("change");
	$("#input-myRange").trigger("change");

</script>


<script type = "text/javascript">
	
	function ML(){
		const data = {};
	const mode = document.getElementById('mode').value;
	if (mode=="All-Records")
	{
		data['Master']=parseInt(document.getElementById('masterid').value);
		data['dataset']=document.getElementById('dataset').value;
		data['mode']=document.getElementById('mode').value;
		data['slavecount']=document.getElementById('slavecount').value;
		data['c_size']=parseInt(document.getElementById('cluster').value);
		data['startRow']=0;
		data['endRow']=parseInt(document.getElementById('endRow').value);
		data['min_confidence']=parseFloat(document.getElementById('confidence').value);
		data['min_support']=parseFloat(document.getElementById('support').value);
		data['Algo']=document.getElementById('algorithm').value;
		data['Approach']=document.getElementById('approach').value;
	}
	else 
	{
		data['Master']=parseInt(document.getElementById('availablemasterid').value);
		data['dataset']=document.getElementById('dataset').value;
		data['Incremental']=true;
		data['slavecount']=document.getElementById('slavecount').value;
		data['factor']=parseInt(document.getElementById('myRange').value);
		data['Type']=parseInt(document.getElementById('type').value);
	}
	
	fetch('http://127.0.0.1:5000/main',{

    method: 'POST',
    headers: {
        'Content-Type': 'application/json'
    },
    body: JSON.stringify(data)
});
}
</script>
</body>
	
</html>
