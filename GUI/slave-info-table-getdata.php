

<?php 
$host = 'localhost';
$root = 'root';
$pwd = '';
$db = 'BE';
$port=3306;
 $con = mysqli_connect($host,$root,$pwd,$db,$port); 

	
	extract($_POST);

    // Insert records 

    if (isset($_POST['slaveid']) && isset($_POST['slaveip']) && isset($_POST['status']) && isset($_POST['comment'])) {

        $q = "INSERT INTO `slavedetails`(`SlaveID`,`SlaveIP`, `Status`, `Comments`) VALUES ('$slaveid','$slaveip','$status','$comment')";
        $res = mysqli_query($con,$q);       
    }

    // View records

	if (isset($_POST['readRecords'])) {
		
		$data = '<table class="table table-bordered table-striped">
    				<thead>
                      <tr>
                        <th>S.No.</th>
    				    <th>Slave ID</th>
    				    <th>Slave IP Address</th>
    				    <th>Status</th>
    				    <th>Comments</th>
    				    <th>Actions</th>
    				  </tr>
    				</thead>';

    	$q = "SELECT * FROM `slavedetails` ORDER BY `slavedetails`.`SlaveID` ASC ";
    	$res = mysqli_query($con,$q);
    	 if (mysqli_num_rows($res) > 0 ) {
    	 	$s_no = 1;
    	 	while ($row = mysqli_fetch_array($res)) {
    	 		$data.='<tbody>
                          <tr>
                            <td>'.$s_no.'</td>
                            <td>'.$row['SlaveID'].'</td>
    					    <td>'.$row['SlaveIP'].'</td>
    					    <td>'.$row['Status'].'</td>
    					    <td>'.$row['Comments'].'</td>
    					    <td><a href="javascript:void(0);"  onclick="editRecord('.$row['SlaveID'].')" class="btn btn-info"><i class="glyphicon glyphicon-edit"></i> Edit</a> &nbsp; 
    					        <a href="javascript:void(0);" onclick="deleteRecord('.$row['SlaveID'].')"  class="btn btn-danger"><i class="glyphicon glyphicon-remove"></i> Delete</a></td>
    					  </tr>     
    					</tbody>';
    					$s_no++;
    	 	}
    	 }
    	 $data.='</table>';

    	 echo $data;
	}

// Delete records

    if (isset($_POST['deleteId'])) {
        $deleteId = $_POST['deleteId'];

        $q = "DELETE FROM `slavedetails` WHERE SlaveID = $deleteId";
        $res = mysqli_query($con, $q);
    }


// Get data id for update

    if (isset($_POST['id']) && isset($_POST['id']) != "") {
        $u_id = $_POST['id'];

        $q = "SELECT * FROM `slavedetails` WHERE SlaveID = $u_id";
        if (!$result = mysqli_query($con,$q)) {
            exit(mysqli_error($con));
        }

        $response = array();

        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $response = $row;
            }
        }else{
            $response['status'] = 200;
            $response['message'] = "Data not found";
        }

        echo json_encode($response);

    }else{
        $response['status'] = 200;
        $response['message'] = "Invalid request";
    }



// update / edit record data

    if (isset($_POST['updateId'])) {
        $u_id = $_POST['updateId'];
        $u_slaveid = $_POST['u_slaveid'];
        $u_slaveip = $_POST['u_slaveip'];
        $u_status = $_POST['u_status'];
        $u_comment = $_POST['u_comment'];

        $query = "UPDATE `slavedetails` SET `SlaveID`='$u_slaveid',`SlaveIP`='$u_slaveip',`Status`='$u_status',`Comments`='$u_comment' WHERE SlaveID = '$u_id' ";

        $result = mysqli_query($con,$query);

    }

 ?>
