<?php
// Start the session and check if user is logged in and has the correct role
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8"/>
  <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
  <meta name="description" content=""/>
  <meta name="author" content=""/>
  <title>Salon - Business Form</title>
  <!-- loader-->
  <link href="assets/css/pace.min.css" rel="stylesheet"/>
  <script src="assets/js/pace.min.js"></script>
  <!--favicon-->
  <link rel="icon" href="assets/images/favicon.ico" type="image/x-icon">
  <!-- simplebar CSS-->
  <link href="assets/plugins/simplebar/css/simplebar.css" rel="stylesheet"/>
  <!-- Bootstrap core CSS-->
  <link href="assets/css/bootstrap.min.css" rel="stylesheet"/>
  <!-- animate CSS-->
  <link href="assets/css/animate.css" rel="stylesheet" type="text/css"/>
  <!-- Icons CSS-->
  <link href="assets/css/icons.css" rel="stylesheet" type="text/css"/>
  <!-- Sidebar CSS-->
  <link href="assets/css/sidebar-menu.css" rel="stylesheet"/>
  <!-- Custom Style-->
  <link href="assets/css/app-style.css" rel="stylesheet"/>
</head>

<body class="bg-theme bg-theme1">

<!-- start loader -->
   <div id="pageloader-overlay" class="visible incoming"><div class="loader-wrapper-outer"><div class="loader-wrapper-inner" ><div class="loader"></div></div></div></div>
   <!-- end loader -->

<!-- Start wrapper-->
 <div id="wrapper">


 
<div class="clearfix"></div>
	
  <div class="content-wrapper">
      
    <div class="container-fluid">
    
    <div class="row mt-3">
      
      <div class="col-lg-10" >
        <div class="card" >
           <div class="card-body">
           <div class="card-title">Business Form</div>
           <hr>
            <form action="businessform-backend.php" method="post" enctype="multipart/form-data">
           <div class="form-group">
            <label for="input-6">Company Name:</label>
            <input type="text" class="form-control form-control-rounded" name="name" id="input-6" placeholder="Enter Business Name">
            </div>
           <div class="form-group">
            <label for="input-7">Email:</label>
            <input type="email" class="form-control form-control-rounded" name="email" id="input-7" placeholder="Enter Email Address">
           </div>
           <div class="form-group">
            <label for="input-8">Mobile:</label>
            <input type="text" class="form-control form-control-rounded" name="mobile" id="input-8" placeholder="Enter Mobile Number" 
             pattern="[0-9]{10}" title="Mobile number must be exactly 10 digits" required>
           </div>
           <div class="form-group">
            <label for="input-9">Upload Logo:</label>
            <input type="file" class="form-control form-control-rounded" name="logo" id="input-9" accept="image/*">
           </div>
           <div class="form-group">
            <label for="input-10">Address:</label>
            <input type="text" class="form-control form-control-rounded" name="address" id="input-10" placeholder="Enter Address">
            </div>
           <div class="form-group">
            <button type="submit" name="employee" class="btn btn-light btn-round px-5"> Register</button>
          </div>
          </form>
         </div>
         </div>
      </div>
    </div>
  
    <!--End Row-->

	<!--start overlay-->
		  <div class="overlay toggle-menu"></div>
		<!--end overlay-->

    </div>
    <!-- End container-fluid-->
    
   </div><!--End content-wrapper-->
   <!--Start Back To Top Button-->
    <a href="javaScript:void();" class="back-to-top"><i class="fa fa-angle-double-up"></i> </a>
    <!--End Back To Top Button-->
	
	<!--Start footer-->

  <?php include '../dashtreme-master/admin/footer.php';?>
	<!--End footer-->
	
  </div><!--End wrapper-->
  
  <!-- Bootstrap core JavaScript-->
  <script src="assets/js/jquery.min.js"></script>
  <script src="assets/js/popper.min.js"></script>
  <script src="assets/js/bootstrap.min.js"></script>
	
 <!-- simplebar js -->
  <script src="assets/plugins/simplebar/js/simplebar.js"></script>
  <!-- sidebar-menu js -->
  <script src="assets/js/sidebar-menu.js"></script>
  
  <!-- Custom scripts -->
  <script src="assets/js/app-script.js"></script>
	
</body>
</html>
