
<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Dashboard</title>

  <!-- Custom fonts for this template-->
  <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

  <!-- DATA TABLES -->
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.6.1/css/buttons.dataTables.min.css">
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css">


  <!-- Custom styles for this template-->
  <link href="css/sb-admin-2.min.css" rel="stylesheet">

 

  

</head>

<body id="page-top">

  <!-- Page Wrapper -->
<div id="wrapper">

    <!-- Sidebar -->
    <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

      <!-- Sidebar - Brand -->
        <a class="sidebar-brand d-flex align-items-center justify-content-center" href="../index.html">
          <div class="sidebar-brand-icon rotate-n-15"></div>
          <div class="sidebar-brand-text mx-3">Data Analytics</div>
        </a>

      <!-- Divider -->
        <hr class="sidebar-divider my-0">

      <!-- Nav Item - Dashboard -->
        <li class="nav-item active">
          <a class="nav-link" href="index.php">
              <i class="fas fa-fw fa-tachometer-alt"></i>
              <span>Dashboard</span>
            </a>
        </li>

      <!-- Divider -->
        <hr class="sidebar-divider">

      <!-- Heading -->
        <div class="sidebar-heading">
          Interface
        </div>

      <!-- Nav Item - Pages Collapse Menu -->
        <!-- <li class="nav-item">
          <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
              <i class="fas fa-fw fa-cog"></i>
              <span>Available Slaves</span>
          </a>
          <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
              <div id = "getslavedata" class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Slaves:</h6>
                <a class="collapse-item" href="#">Slave 2</a>
              </div>
          </div>
        </li> -->

      <!-- Nav Item - Utilities Collapse Menu -->
        <li class="nav-item">
          <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUtilities" aria-expanded="true" aria-controls="collapseUtilities">
              <i class="fas fa-fw fa-wrench"></i>
              <span>Clusters completed</span>
          </a>
          <div id="collapseUtilities" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
              <div id="getclustercompleted" class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Clusters:</h6>
                <a class="collapse-item" href="#"></a>
              </div>
          </div>
        </li>

        <li class="nav-item">
          <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUtilitie" aria-expanded="true" aria-controls="collapseUtilitie">
              <i class="fas fa-fw fa-wrench"></i>
              <span>Clusters in progress</span>
          </a>
          <div id="collapseUtilitie" class="collapse" aria-labelledby="headingUtilitie" data-parent="#accordionSidebar">
              <div id="getclusterprogress" class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Clusters:</h6>
                <a class="collapse-item" href="#"></a>
              </div>
          </div>
        </li>

        <!-- <li class="nav-item">
          <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseThree" aria-expanded="true" aria-controls="collapseThree">
              <i class="fas fa-fw fa-cog"></i>
              <span>Clusters not assigned</span>
          </a>
          <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordionSidebar">
              <div id = "getclusternotassigned" class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Clusters:</h6>
                <a class="collapse-item" href="#">Slave 2</a>
              </div>
          </div>
        </li> -->

      <!-- Divider -->
        <hr class="sidebar-divider">

      <!-- Heading -->
        <div class="sidebar-heading">
        Maintainance
        </div>

      
   

      <!-- Nav Item - Charts -->
        <!-- <li class="nav-item">
          <a class="nav-link" href="charts.html">
              <i class="fas fa-fw fa-chart-area"></i>
              <span>Graphical View</span></a>
        </li> -->

      <!-- Nav Item - Tables -->
        <li class="nav-item">
          <a class="nav-link" href="../slave-info-table.html">
              <i class="fas fa-fw fa-table"></i>
              <span>Slaves Information</span></a>
        </li>

      <!-- Divider -->
        <hr class="sidebar-divider d-none d-md-block">

      <!-- Sidebar Toggler (Sidebar) -->
        <div class="text-center d-none d-md-inline">
          <button class="rounded-circle border-0" id="sidebarToggle"></button>
        </div>

    </ul>
    <!-- End of Sidebar -->

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

      <!-- Main Content -->
        <div id="content">

        <!-- Topbar -->
          <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

          <!-- Sidebar Toggle (Topbar) -->
              <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                <i class="fa fa-bars"></i>
              </button>


          <!-- Topbar Navbar -->
              <ul class="navbar-nav ml-auto">
                <div class="topbar-divider d-none d-sm-block">
              
                </div>
              </ul>

          </nav>
        <!-- End of Topbar -->

        <!-- Begin Page Content -->
          <div class="container-fluid">

          <!-- Page Heading -->
              <div class="d-sm-flex align-items-center justify-content-between mb-4">
                  <?php $masterid = $_GET['masterid']; ?>
                  <?php $slaveid = $_GET['slaveid']; ?>
                <h1 class="h3 mb-0 text-gray-800">Master ID- <?php echo $masterid ?> &nbsp; : &nbsp; Slave ID- <?php echo $slaveid ?> </h1>
                
                <!-- <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-download fa-sm text-white-50"></i> Generate Report</a> -->
              </div>
              <div class="d-sm-flex align-items-center justify-content-between mb-4">
              <!-- <h1 class="h2 mb-0 text-gray-800">Slave ID- <?php echo $slaveid ?> </h1> -->
              </div>

          <!-- Content Row -->
              <div class="row">
                <div class="col-xl-4 col-md-6 mb-4">
                  <div class="card border-left-info shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                          <div class="col mr-2">
                              <div class="text-xs font-weight-bold text-info text-uppercase mb-1">DataSet Name</div>
                              <div id="getdataset" class="h5 mb-0 font-weight-bold text-gray-800"></div>
                          </div>
                          <div class="col-auto">
                            <i class="fas fa-database fa-2x text-gray-300"></i>
                            
                        
                          </div>
                        </div>
                    </div>
                  </div>
              </div>
              <!-- <div class="col-xl-3 col-md-6 mb-4">
                  <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                          <div class="col mr-2">
                              <div class="text-xs font-weight-bold text-success text-uppercase mb-1">No. Of Transactions</div>
                              <div id="gettransaction" class="h5 mb-0 font-weight-bold text-gray-800">100,000</div>
                          </div>
                          <div class="col-auto">
                            <i class="fas fa-receipt fa-2x text-gray-300"></i>
                          
                        
                          </div>
                        </div>
                    </div>
                  </div>
              </div> -->
              <div class="col-xl-4 col-md-6 mb-4">
                  <div class="card border-left-danger shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                          <div class="col mr-2">
                              <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Algorithm</div>
                              <div id="getalgorithm" class="h5 mb-0 font-weight-bold text-gray-800"></div>
                          </div>
                          <div class="col-auto">
                            <i class="fas fa-book fa-2x text-gray-300"></i>
                            
                        
                          </div>
                        </div>
                    </div>
                  </div>
              </div>

              <div class="col-xl-4 col-md-6 mb-4">
                  <div class="card border-left-warning shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                          <div class="col mr-2">
                              <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Approach</div>
                              <div id="getapproach" class="h5 mb-0 font-weight-bold text-gray-800"></div>
                          </div>
                          <div class="col-auto">
                            <i class="fas fa-file-contract fa-2x text-gray-300"></i>
                            
                        
                          </div>
                        </div>
                    </div>
                  </div>
              </div>

              <div class="col-xl-4 col-md-6 mb-4">
                  <div class="card border-left-info shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                          <div class="col mr-2">
                              <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Support</div>
                              <div id="getsupportconfidence" class="h5 mb-0 font-weight-bold text-gray-800"></div>
                          </div>
                          <div class="col-auto">
                            <i class="fas fa-cogs fa-2x text-gray-300"></i>
                          </div>
                        </div>
                    </div>
                  </div>
              </div>

              <!-- <div class="col-xl-3 col-md-6 mb-4">
                  <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                          <div class="col mr-2">
                              <div class="text-xs font-weight-bold text-success  text-uppercase mb-1">Up Time</div>
                              <div id="getuptime" class="h5 mb-0 font-weight-bold text-gray-800">20 min</div>
                          </div>
                          <div class="col-auto">
                            <i class="fas fa-stopwatch fa-2x text-gray-300"></i>
                        
                          </div>
                        </div>
                    </div>
                  </div>
              </div> -->

              <div class="col-xl-4 col-md-6 mb-4">
                  <div class="card border-left-danger shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                          <div class="col mr-2">
                              <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">No. Of Clusters</div>
                              <div id="getcluster"class="h5 mb-0 font-weight-bold text-gray-800"></div>
                          </div>
                          <div class="col-auto">
                            <i class="fab fa-cloudversify fa-2x text-gray-300"></i>
                            
                          </div>
                        </div>
                    </div>
                  </div>
              </div>

              <div class="col-xl-4 col-md-6 mb-4">
                  <div class="card border-left-warning shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                          <div class="col mr-2">
                              <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Frequent Items</div>
                              <div id="getfrequentitem" class="h5 mb-0 font-weight-bold text-gray-800"></div>
                          </div>
                          <div class="col-auto">
                            <i class="fas fa-people-carry fa-2x text-gray-300"></i>
                          </div>
                        </div>
                    </div>
                  </div>
              </div>
              </div>

              <div class="row">
              	<div class="col-xl-12 col-lg-12" style="height: 150%;">
                <div class="card shadow mb-4">
            <div class="card-header py-3">
              <h2 class="m-0 font-weight-bold text-primary">Cluster Details</h2>
            </div>
            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th>Cluster ID</th>
                      <th>Start Time</th>
                      <th>End Time</th>
                      <th>Frequent itemset Generated</th>
                    </tr>
                  </thead>
                  <tfoot>
                    <tr>
                      <th>Cluster ID</th>
                      <th>Start Time</th>
                      <th>End Time</th>
                      <th>Frequent itemset Generated</th>
                    </tr>
                  </tfoot>
                  <tbody>
                    <?php
					$host = 'localhost';
$root = 'root';
$pwd = '';
$db = 'BE';
$port=3306;
                      $conn = mysqli_connect($host,$root,$pwd,$db,$port) or die("Error in connection");
                      $query = mysqli_query($conn, "SELECT sr.SlaveReqID AS ClusterID, sd.SlaveReqTimeStamp AS StartTime, sd.SlaveResTimeStamp AS EndTime , count(sr.SlaveReqID) AS FreqItemSet FROM slaverequestdetails sd LEFT JOIN slaveresponsedetails sr ON sr.MasterReqID=sd.MasterReqID AND sr.MasterReqID=$masterid AND sr.SlaveReqID=sd.SlaveReqID WHERE sd.MasterReqID=$masterid AND sd.SlaveID=$slaveid GROUP BY sr.SlaveReqID");

                      while ($result = mysqli_fetch_array($query)) {
                        echo "<tr>
                              <td>".$result['ClusterID']."</td>
                              <td>".$result['StartTime']."</td>
                              <td>".$result['EndTime']."</td>
                              <td>".$result['FreqItemSet']."</td>
                              </tr>";
                      }
                    ?>
                  </tbody>
                </table>
              </div>
            </div>
        </div>
          </div>
              </div>


            <!-- Area Chart -->
              

          <!-- Content Row -->

              <div class="row"></div>   
        <!-- /.container-fluid -->

        </div> 
      <!-- End of Main Content -->

      <!-- Footer -->
        <footer class="sticky-footer bg-white">
          <div class="container my-auto">
              <div class="copyright text-center my-auto">
                <span>Copyright &copy; Your Website 2019</span>
              </div>
          </div>
        </footer>
      <!-- End of Footer -->

    </div>
    <!-- End of Content Wrapper -->

</div>
  <!-- End of Page Wrapper -->

  <!-- Scroll to Top Button-->
  <a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
  </a>

  <!-- Bootstrap core JavaScript-->
<script src="vendor/jquery/jquery.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Core plugin JavaScript-->
<script src="vendor/jquery-easing/jquery.easing.min.js"></script>

  <!-- Custom scripts for all pages-->
<script src="js/sb-admin-2.min.js"></script>

  <!-- Page level plugins -->


  <!-- Page level custom scripts -->

<script src="vendor/datatables/jquery.dataTables.min.js"></script>
  <script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>
  <script type="text/javascript" src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.6.1/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.flash.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js
"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.html5.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.print.min.js"></script>

  <!-- Page level custom scripts -->
  <!-- <script src="js/demo/datatables-demo.js"></script> -->
<script>

$(document).ready(function() {

    getdataset();
    $('#dataTable').DataTable({
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ]
      });
        
  });


  function getdataset(){
    var masterid = "<?php echo $masterid; ?>";
    var slaveid = "<?php echo $slaveid; ?>";
    var arrayReturn = [];

    $.ajax({
        type: 'post',
        url: 'slave-getdata.php',
        dataType: "json",
        data: {masterid:masterid,
              slaveid:slaveid},

        success:function(data, status) {
          $("#getdataset").html(data.Dataset);
          $("#getalgorithm").html(data.Algorithm);
          $("#getcluster").html(data.Cluster);
          $("#getapproach").html(data.Approach);
          $("#getsupportconfidence").html(data.Support);
          $('#getfrequentitem').html(data.TotalFreqItemSet);

        }
      }); 


      $.ajax({
        type: 'post',
        url: 'slave-getclustercompleted.php',
        data: {masterid:masterid,
              slaveid:slaveid},

        success:function(data, status){
          $("#getclustercompleted").html(data);
        }

      });

      $.ajax({
        type: 'post',
        url: 'slave-getclusterprogress.php',
        data: {masterid:masterid,
              slaveid:slaveid},

        success:function(data, status){
          $("#getclusterprogress").html(data);
        }

      });


  }


</script>

</body>

</html>
