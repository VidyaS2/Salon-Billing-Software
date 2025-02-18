<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if user is logged in and has staff role
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../index.php'); // Redirect to login page if not logged in or not staff
    exit;
}
// Check if the user is logged in
if (!isset($_SESSION['username'])) {
  // User is not logged in, redirect to login page
  header("Location: index.php?error=You must be logged in to view this page");
  exit();
}

// Get the logged-in stylist's username from the session
$loggedInStylist = $_SESSION['username'];
// Continue with admin dashboard content

// Include database connection
include '../connect.php';

// Initialize variables for company details and logo path
$companyName = $logoPath = '';

// Fetch company details from the business table
$sql = "SELECT name, logo FROM business LIMIT 1";
$result = mysqli_query($conn, $sql);

if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}

if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $companyName = $row['name'];
    $logoPath = $row['logo']; // This should be the path stored in the database
} else {
    // Handle case where no company data found (optional)
    $companyName = 'Company Name Not Found';
    $logoPath = ''; // Set a default or handle as needed
}

// Close database connection
mysqli_close($conn);
?>
<!-- Start wrapper-->
 <div id="wrapper">
 
  <!--Start sidebar-wrapper-->
   <div id="sidebar-wrapper" data-simplebar="" data-simplebar-auto-hide="true">
   <!-- Example usage in your HTML -->
<div class="brand-logo">
    <a >
        <?php if (!empty($logoPath)): ?>
            <img src="../uploads/<?php echo htmlspecialchars($logoPath); ?>" class="logo-icon" alt="<?php echo htmlspecialchars($companyName); ?> Logo">
        <?php else: ?>
            <img src="../assets/images/logo-icon.png" class="logo-icon" alt="Default Logo">
        <?php endif; ?>
        <h5 class="logo-text"><?php echo htmlspecialchars($companyName); ?></h5>
    </a>
</div>

   <ul class="sidebar-menu do-nicescrol">
      <li class="sidebar-header">Salon</li>
      <li>
        <a href="dashboard.php">
          <i class="zmdi zmdi-view-dashboard"></i> <span>Dashboard</span>
        </a>
      </li>

      <li>
        <a href="customer.php">
          <i class="zmdi zmdi-account"></i> <span>Customer Details</span>
        </a>
      </li>
      <li>
        <a href="appointment.php" >
          <i class="zmdi zmdi-accounts-list"></i> <span>Appointments</span>
        </a>
      </li>
      
      <li>
        <a href="employees.php" >
          <i class="zmdi zmdi-accounts-alt"></i> <span>Employees</span>
        </a>
      </li>
      <li>
        <a href="packagedetails.php" >
          <i class="zmdi zmdi-assignment"></i> <span>Packages</span>
        </a>
      </li>
      
      <li>
        <a href="cforms.php">
          <i class="zmdi zmdi-format-list-bulleted"></i> <span>Customer Form</span>
        </a>
      </li>

      <li>
        <a href="eforms.php">
          <i class="zmdi zmdi-format-list-numbered"></i> <span>Employee Form</span>
        </a>
      </li>
      <li>
        <a href="appointment-form.php" >
          <i class="zmdi zmdi-format-list-bulleted"></i> <span>Appointment From</span>
        </a>
      </li>
      <li>
        <a href="calendar.php">
          <i class="zmdi zmdi-calendar-check"></i> <span>Calendar</span>
        </a>
      </li>

      <li>
        <a href="profile.php">
          <i class="zmdi zmdi-face"></i> <span>Profile</span>
        </a>
      </li>
      <hr>
      <li>
        <a href="../index.php" target="_blank">
          <i class="zmdi zmdi-lock"></i> <span>Login</span>
        </a>
      </li>

       <li>
        <a href="../register.php" target="_blank">
          <i class="zmdi zmdi-account-circle"></i> <span>Registration</span>
        </a>
      </li>
      <li>
        <a href="../index.php"  >
        <i class="icon-power mr-2"></i> <span>Logout</span>
        </a>
      </li>
  
      <!-- <li class="sidebar-header">LABELS</li>
      <li><a href="javaScript:void();"><i class="zmdi zmdi-coffee text-danger"></i> <span>Important</span></a></li>
      <li><a href="javaScript:void();"><i class="zmdi zmdi-chart-donut text-success"></i> <span>Warning</span></a></li>
      <li><a href="javaScript:void();"><i class="zmdi zmdi-share text-info"></i> <span>Information</span></a></li> -->

    </ul>
   
   </div>
   <!--End sidebar-wrapper-->

<!--Start topbar header-->
<header class="topbar-nav">
 <nav class="navbar navbar-expand fixed-top">
  <ul class="navbar-nav mr-auto align-items-center">
    <li class="nav-item">
      <a class="nav-link toggle-menu" href="javascript:void();">
       <i class="icon-menu menu-icon"></i>
     </a>
    </li>
    <!-- <li class="nav-item">
      <form class="search-bar">
        <input type="text" class="form-control" placeholder="Enter keywords">
         <a href="javascript:void();"><i class="icon-magnifier"></i></a>
      </form>
    </li> -->
  </ul>
     
  <ul class="navbar-nav align-items-center right-nav-link">
    <!-- <li class="nav-item dropdown-lg">
      <a class="nav-link dropdown-toggle dropdown-toggle-nocaret waves-effect" data-toggle="dropdown" href="javascript:void();">
      <i class="fa fa-envelope-open-o"></i></a>
    </li>
    <li class="nav-item dropdown-lg">
      <a class="nav-link dropdown-toggle dropdown-toggle-nocaret waves-effect" data-toggle="dropdown" href="javascript:void();">
      <i class="fa fa-bell-o"></i></a>
    </li> -->
    <!-- <li class="nav-item language">
      <a class="nav-link dropdown-toggle dropdown-toggle-nocaret waves-effect" data-toggle="dropdown" href="javascript:void();"><i class="fa fa-flag"></i></a>
      <ul class="dropdown-menu dropdown-menu-right">
          <li class="dropdown-item"> <i class="flag-icon flag-icon-gb mr-2"></i> English</li>
          <li class="dropdown-item"> <i class="flag-icon flag-icon-fr mr-2"></i> French</li>
          <li class="dropdown-item"> <i class="flag-icon flag-icon-cn mr-2"></i> Chinese</li>
          <li class="dropdown-item"> <i class="flag-icon flag-icon-de mr-2"></i> German</li>
        </ul>
    </li> -->
    <li class="nav-item">
                    <a class="nav-link dropdown-toggle dropdown-toggle-nocaret" data-toggle="dropdown" href="#">
                        <span class="user-profile "><img src="../assets/images/contact-icon.png" class="img-circle" alt="user avatar"></span>
                        
                        <h6 class="mt-2 user-title" style="text-transform: capitalize;"><?php echo htmlspecialchars($loggedInStylist); ?></h6>

                    </a>
                    <ul class="dropdown-menu dropdown-menu-right">
                    <li class="dropdown-item user-details">
                        <a href="javascript:void();">
                            <div class="media">
                                <div class="avatar"><img class="align-self-start mr-3" src="../assets/images/contact-icon.png" alt="user avatar"></div>
                                <div class="media-body">
                                    <h6 class="mt-2 user-title" style="text-transform: capitalize;"><?php echo htmlspecialchars($loggedInStylist); ?></h6>
                                </div>
                            </div>
                        </a>
                    </li>
        <!-- <li class="dropdown-divider"></li>
        <li class="dropdown-item"><i class="icon-envelope mr-2"></i> Inbox</li>
        <li class="dropdown-divider"></li>
        <li class="dropdown-item"><i class="icon-wallet mr-2"></i> Account</li>
        <li class="dropdown-divider"></li>
        <li class="dropdown-item"><i class="icon-settings mr-2"></i> Setting</li>
        <li class="dropdown-divider"></li>
        <li class="dropdown-item"><i class="icon-power mr-2"></i><a href="logout.php"> Logout</a></li> -->
      </ul>
    </li>
  </ul>
</nav>
</header>
<!--End topbar header-->



