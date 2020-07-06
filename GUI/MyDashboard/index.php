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
        	<div class="sidebar-brand-text mx-3">Home</div>
      	</a>

      <!-- Divider -->
      	<hr class="sidebar-divider my-0">

      <!-- Nav Item - Dashboard -->
      	<li class="nav-item active">
        	<a class="nav-link" href="#">
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

	  	<li class="nav-item">
        	<a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUtilitie" aria-expanded="true" aria-controls="collapseUtilitie">
          		<i class="fas fa-fw fa-wrench"></i>
          		<span>Incremental ID</span>
        	</a>
      	</li>

      	<li class="nav-item">
        	<a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
          		<i class="fas fa-fw fa-cog"></i>
          		<span>Available Slaves</span>
        	</a>
      	</li>

      <!-- Nav Item - Utilities Collapse Menu -->

	  
      	<li class="nav-item">
        	<a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUtilities" aria-expanded="true" aria-controls="collapseUtilities">
          		<i class="fas fa-fw fa-wrench"></i>
          		<span>Clusters completed</span>
        	</a>
		  </li>

		  
      	<li class="nav-item">
        	<a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUtilitie" aria-expanded="true" aria-controls="collapseUtilitie">
          		<i class="fas fa-fw fa-wrench"></i>
          		<span>Clusters in progress</span>
        	</a>
		  </li>
		  
		  <li class="nav-item">
        	<a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUtilitie" aria-expanded="true" aria-controls="collapseUtilitie">
          		<i class="fas fa-fw fa-wrench"></i>
          		<span>Clusters not assigned</span>
        	</a>
      	</li>

		

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

          <!-- Topbar Search -->


          <!-- Topbar Navbar -->
          		<ul class="navbar-nav ml-auto">
					<div class="topbar-divider d-none d-sm-block"></div>
				</ul>

        	</nav>
        <!-- End of Topbar -->

        <!-- Begin Page Content -->
        	<div class="container-fluid">

          <!-- Page Heading -->
          		<div class="d-sm-flex align-items-center justify-content-between mb-4">
            		<h1 class="h3 mb-0 text-gray-800">Select Master ID to view Details</h1>
            		<!-- <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-download fa-sm text-white-50"></i> Generate Report</a> -->
          		</div>

          <!-- Content Row -->
          		<div class="row">

            <!-- Earnings (Monthly) Card Example -->
		            <div class="col-xl-6 col-md-6 mb-4">
		              	<div class="card border-bottom-primary shadow h-100 py-3">
		                	<div class="card-body " >
		                  		<div class="row no-gutters align-items-center">
		                    		<div class="col mr-2">
		                      			<div class="h2 mb-0 font-weight-bold text-gray-800">
		                      				<a onclick="getmasterdata()" class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsea" aria-expanded="true" aria-controls="collapse">
								          	<i class="fas fa-paste fa-2x text-gray-300"></i>
								          	<span>Existing Master ID </span>
								        	</a>
		                      				<div id="collapsea" class="collapse" aria-labelledby="headinga" data-parent="#accordionSidebar">
    									    <div id="getdata" class="bg-white py-2 collapse-inner rounded" style="overflow: scroll; height: 10em;">
    										            <!-- <h6 class="collapse-header">Masters ID:</h6> -->
    										            <!-- <a class="collapse-item" href="utilities-color.html">1001</a><br> -->
    									    </div>
		        							</div>
		                      			</div>
		                    		</div>
		                  		</div>
		                	</div>
		              	</div>
		            </div>

<!--             <div class="col-xl-6 col-md-6 mb-4">
              <div class="card border-bottom-success shadow h-100 py-2 ">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-m font-weight-bold text-primary text-uppercase mb-1">
                        <a href="charts.html">Earnings (Monthly)</a>
                      </div>
                    </div>
                      <div class="col-auto">
                        <i class="fas fa-sign-in-alt fa-2x text-gray-300"></i>
                      </div>
                  </div>
                </div>
              </div>
            </div> -->


	            <!-- Earnings (Monthly) Card Example -->
		            <div class="col-xl-6 col-md-6 mb-4">
		              	<div class="card border-bottom-success shadow h-100 py-2 " >
		                	<div class="card-body">
		                  		<div class="row no-gutters align-items-center">
		                    		<div class="col mr-2 ">
		                      			<div class="h2 mb-0 font-weight-bold text-gray-800">
		                      				<a class="nav-link collapsed" href="../index.html" data-toggle="collapse" data-target="#collapseb" aria-expanded="true" aria-controls="collapseb">
								          	<i class="fas fa-sign-in-alt fa-2x text-gray-300"></i> 
								          	<span class="text-success">New Master ID</span>
								        	</a>
											<div id="collapseb" class="collapse" aria-labelledby="headingb" data-parent="#accordionSidebar">
											</div>
		                      			</div>
		                    		</div>
		                  		</div>
		                	</div>
		              	</div>
		            </div>
	        
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
<!-- <script src="vendor/chart.js/Chart.min.js"></script> -->

  <!-- Page level custom scripts -->
<!-- <script src="js/demo/chart-area-demo.js"></script>
<script src="js/demo/chart-pie-demo.js"></script> -->


<script type="text/javascript">
  function getmasterdata(){

    $.ajax({
      url : 'index-getmasterdata.php',
      type : 'get',
      success : function(value){
        $('#getdata').html(value)
      }

    });

  }

</script>

</body>

</html>
