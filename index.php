<?php

// Include database connection
include 'connect.php';

// Initialize variables for company details and logo path
$logoPath = '';

// Fetch company details from the business table
$sql = "SELECT logo FROM business LIMIT 1";
$result = mysqli_query($conn, $sql);

if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}

if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $logoPath = $row['logo']; // This should be the path stored in the database
} else {
    // Handle case where no company data found (optional)
    $logoPath = ''; // Set a default or handle as needed
}

// Close database connection
mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8"/>
  <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
  <meta name="description" content=""/>
  <meta name="author" content=""/>
  <title>Salon - Sign In</title>
  <!-- loader-->
  <link href="assets/css/pace.min.css" rel="stylesheet"/>
  <script src="assets/js/pace.min.js"></script>
  <!-- favicon-->
  <link rel="icon" href="assets/images/favicon.ico" type="image/x-icon">
  <!-- Bootstrap core CSS-->
  <link href="assets/css/bootstrap.min.css" rel="stylesheet"/>
  <!-- animate CSS-->
  <link href="assets/css/animate.css" rel="stylesheet" type="text/css"/>
  <!-- Icons CSS-->
  <link href="assets/css/icons.css" rel="stylesheet" type="text/css"/>
  <!-- Custom Style-->
  <link href="assets/css/app-style.css" rel="stylesheet"/>
  <style>
   .select-role {
    display: flex;
    justify-content: center;
    gap: 10px; /* Adjusts the gap between the boxes */
}

.btn-group-toggle .btn {
    padding: 10px 15px; /* Smaller padding for a smaller box */
    border-radius: 8px;
    border: 2px solid #ccc;
    background-color: white;
    cursor: pointer;
    display: inline-block;
    text-align: center;
    width: 100px; /* Smaller width */
}

.btn-group-toggle .btn.active {
    background-color: orange;
    color: white;
}

.btn-group-toggle .btn input[type="radio"] {
    display: none;
}

.btn-group-toggle .btn span {
    display: block;
    font-size: 14px; /* Smaller font size */
}
  </style>
</head>

<body class="bg-theme bg-theme1">

<!-- start loader -->
   <div id="pageloader-overlay" class="visible incoming"><div class="loader-wrapper-outer"><div class="loader-wrapper-inner"><div class="loader"></div></div></div></div>
   <!-- end loader -->

<!-- Start wrapper-->
<div id="wrapper" style="margin-top:10%">

<div class="loader-wrapper"><div class="lds-ring"><div></div><div></div><div></div><div></div></div></div>
  <div class="card card-authentication1 mx-auto my-5">
    <div class="card-body">
     <div class="card-content p-2">
     <div class="text-center">
    <?php if (!empty($logoPath) && file_exists("uploads/" . $logoPath)): ?>
        <img src="uploads/<?php echo htmlspecialchars($logoPath); ?>" style="height:70px; width:70px" alt="Company Logo">
    <?php else: ?>
        <img src="assets/images/logo-icon.png" alt="Default Logo">
    <?php endif; ?>
</div>
      <div class="card-title text-uppercase text-center py-3">Sign In</div>

      <?php
        // Display error message if redirected with 'error' parameter
        if (isset($_GET['error'])) {
          $error = htmlspecialchars($_GET['error'], ENT_QUOTES, 'UTF-8');
          echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                  ' . $error . '
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                  </button>
                </div>';
        }

        // Display success message if redirected with 'success' parameter
        if (isset($_GET['success'])) {
          $success = htmlspecialchars($_GET['success'], ENT_QUOTES, 'UTF-8');
          echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                  ' . $success . '
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                  </button>
                </div>';
        }
      ?>

      <form action="login.php" method="post">
        <div class="select-role">
          <div class="btn-group btn-group-toggle" data-toggle="buttons">
            <label class="btn active">
              <input required type="radio" id="admin" value="admin" name="role">
              <div class="icon"><img src="vendors/images/briefcase.svg" class="svg" alt=""></div>
              <span>I'm</span>
              Admin
            </label>
            <label class="btn">
              <input required type="radio" id="user" value="user" name="role">
              <div class="icon"><img src="vendors/images/person.svg" class="svg" alt=""></div>
              <span>I'm</span>
              Staff
            </label>
          </div>
        </div><br>

        <div class="form-group">
          <label for="exampleInputUsername" class="sr-only">Email</label>
          <div class="position-relative has-icon-right">
            <input type="email" id="exampleInputUsername" class="form-control input-shadow" name="email" placeholder="Enter your Email">
            <div class="form-control-position">
              <i class="icon-user"></i>
            </div>
          </div>
        </div>

        <div class="form-group">
          <label for="exampleInputPassword" class="sr-only">Password</label>
          <div class="position-relative has-icon-right">
            <input type="password" id="exampleInputPassword" class="form-control input-shadow" name="password" placeholder="Enter your Password"><br>
            <div class="form-control-position">
              <i class="icon-lock"></i>
            </div>
          </div>
        </div>

        <button type="submit" name="login" class="btn btn-light btn-block">Sign In</button>
      </form>

     </div>
    </div>
    <div class="card-footer text-center py-3">
      <p class="text-warning mb-0">Do not have an account? <a href="register.php"> Sign Up here</a></p>
    </div>
  </div>

<!-- Start Back To Top Button-->
<a href="javaScript:void();" class="back-to-top"><i class="fa fa-angle-double-up"></i> </a>
<!-- End Back To Top Button-->

<!-- Bootstrap core JavaScript-->
<script src="assets/js/jquery.min.js"></script>
<script src="assets/js/popper.min.js"></script>
<script src="assets/js/bootstrap.min.js"></script>

<!-- sidebar-menu js -->
<script src="assets/js/sidebar-menu.js"></script>

<!-- Custom scripts -->
<script src="assets/js/app-script.js"></script>


<script>
    
// Function to disable right-click context menu
document.addEventListener('contextmenu', function(event) {
	event.preventDefault();
});

// Function to disable specific key combinations
document.addEventListener('keydown', function(event) {
	// List of keycodes to block
	const blockedKeys = [123, // F12
						73,  // I key
						74,  // J key
						67,  // C key
						85,  // U key
						75]; // K key (Firefox)

	if (blockedKeys.includes(event.keyCode) && 
		(event.ctrlKey || event.shiftKey || event.keyCode === 123)) {
		event.preventDefault();
		event.stopPropagation();
	}
}, true);

// Disable inspect element in iframes
function disableInspectForIframes() {
	const iframes = document.getElementsByTagName('iframe');
	for (let i = 0; i < iframes.length; i++) {
		const iframeDocument = iframes[i].contentDocument || iframes[i].contentWindow.document;
		iframeDocument.addEventListener('contextmenu', function(event) {
			event.preventDefault();
		});
		iframeDocument.addEventListener('keydown', function(event) {
			if (blockedKeys.includes(event.keyCode) && 
				(event.ctrlKey || event.shiftKey || event.keyCode === 123)) {
				event.preventDefault();
				event.stopPropagation();
			}
		}, true);
	}
}

// Call the function to disable inspect for iframes after the window loads
window.onload = function() {
	disableInspectForIframes();
};

// Attempt to disable right-click and inspect for dynamically added elements
new MutationObserver(function(mutations) {
	mutations.forEach(function(mutation) {
		const addedNodes = mutation.addedNodes;
		for (let i = 0; i < addedNodes.length; i++) {
			const node = addedNodes[i];
			if (node.nodeType === 1) { // Element node
				node.addEventListener('contextmenu', function(event) {
					event.preventDefault();
				});
				node.addEventListener('keydown', function(event) {
					if (blockedKeys.includes(event.keyCode) && 
						(event.ctrlKey || event.shiftKey || event.keyCode === 123)) {
						event.preventDefault();
						event.stopPropagation();
					}
				}, true);
			}
		}
	});
}).observe(document.body, { childList: true, subtree: true });
  </script>
</body>
</html>
