<?php
// Start session
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    // User is not logged in, redirect to login page
    header("Location: index.php?error=You must be logged in to view this page");
    exit();
}

// Include your database connection file
include '../connect.php';

// Error handling function
function check_query($result, $conn) {
    if (!$result) {
        die("Query failed: " . $conn->error);
    }
    return $result;
}

// Get the logged-in stylist's username from the session
$loggedInStylist = $_SESSION['username'];

// Prepare the SQL query to count the total number of customers for the logged-in stylist
$sql = "SELECT COUNT(*) AS total_customers 
        FROM customer_details 
        WHERE stylist = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $loggedInStylist);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$total_customers = $row['total_customers'];

// Prepare and execute the statement to fetch the total number of services
$sql = "SELECT COUNT(*) AS total_services 
        FROM services s 
        JOIN customer_details cd ON s.customer_id = cd.id
        WHERE cd.stylist = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $loggedInStylist);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$total_services = $row['total_services'];

// Prepare and execute the statement to fetch the total revenue
$sql = "SELECT SUM(amount) AS total_revenue 
        FROM customer_details 
        WHERE stylist = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $loggedInStylist);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$total_revenue = $row['total_revenue'];

// Prepare and execute the statement to fetch the monthly revenue
$current_month = date('Y-m');
$sql = "SELECT SUM(amount) AS monthly_revenue 
        FROM customer_details 
        WHERE stylist = ? AND DATE_FORMAT(timestamp, '%Y-%m') = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $loggedInStylist, $current_month);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$monthly_revenue = $row['monthly_revenue'];

// Query to calculate old and new visitors
$sql_visitors = "SELECT MONTH(timestamp) as month, 
                        SUM(CASE WHEN visits > 1 THEN 1 ELSE 0 END) as old_visitors,
                        SUM(CASE WHEN visits = 1 THEN 1 ELSE 0 END) as new_visitors
                FROM (
                    SELECT name, email, mobile, stylist, COUNT(*) as visits, timestamp
                    FROM customer_details 
                    GROUP BY name, email, mobile, stylist, MONTH(timestamp)
                ) as subquery
                GROUP BY month";
$result_visitors = check_query($conn->query($sql_visitors), $conn);

$months = [];
$oldVisitors = [];
$newVisitors = [];

while ($row = $result_visitors->fetch_assoc()) {
    $months[] = $row['month'];
    $oldVisitors[] = $row['old_visitors'];
    $newVisitors[] = $row['new_visitors'];
}

// Get the logged-in stylist's username from the session
$loggedInStylist = $_SESSION['username'];

// Initialize variables for service revenues
$totalRevenueHairCut = 0;
$totalRevenuePedicure = 0;
$totalRevenueManicure = 0;
$totalRevenueFacial = 0;
$totalRevenueWaxing = 0;
$totalRevenueThreading = 0;
$totalRevenueKeratin = 0;
$totalRevenueHairSpa = 0;

// Prepare and execute the statement to fetch the total revenue for each service
$serviceNames = ['hair cut', 'pedicure', 'manicure', 'facial', 'waxing', 'threading', 'keratin', 'hair spa'];

foreach ($serviceNames as $service) {
    $sql = "SELECT SUM(service_amount) AS total_revenue 
            FROM services s 
            JOIN customer_details cd ON s.customer_id = cd.id
            WHERE s.service_name = ? AND cd.stylist = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $service, $loggedInStylist);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    
    // Assign the fetched revenue to the corresponding variable
    switch ($service) {
        case 'hair cut':
            $totalRevenueHairCut = $row['total_revenue'] ?? 0;
            break;
        case 'pedicure':
            $totalRevenuePedicure = $row['total_revenue'] ?? 0;
            break;
        case 'manicure':
            $totalRevenueManicure = $row['total_revenue'] ?? 0;
            break;
        case 'facial':
            $totalRevenueFacial = $row['total_revenue'] ?? 0;
            break;
        case 'waxing':
            $totalRevenueWaxing = $row['total_revenue'] ?? 0;
            break;
        case 'threading':
            $totalRevenueThreading = $row['total_revenue'] ?? 0;
            break;
        case 'keratin':
            $totalRevenueKeratin = $row['total_revenue'] ?? 0;
            break;
        case 'hair spa':
            $totalRevenueHairSpa = $row['total_revenue'] ?? 0;
            break;
        default:
            // Handle unknown service names if needed
            break;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8"/>
  <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
  <meta name="description" content=""/>
  <meta name="author" content=""/>
  <title>Salon - Dashboard</title>
  <!-- loader-->
  <link href="../assets/css/pace.min.css" rel="stylesheet"/>
  <script src="../assets/js/pace.min.js"></script>
  <!--favicon-->
  <link rel="icon" href="../assets/images/favicon.ico" type="image/x-icon">
  <!-- Vector CSS -->
  <link href="../assets/plugins/vectormap/jquery-jvectormap-2.0.2.css" rel="stylesheet"/>
  <!-- simplebar CSS-->
  <link href="../assets/plugins/simplebar/css/simplebar.css" rel="stylesheet"/>
  <!-- Bootstrap core CSS-->
  <link href="../assets/css/bootstrap.min.css" rel="stylesheet"/>
  <!-- animate CSS-->
  <link href="../assets/css/animate.css" rel="stylesheet" type="text/css"/>
  <!-- Icons CSS-->
  <link href="../assets/css/icons.css" rel="stylesheet" type="text/css"/>
  <!-- Sidebar CSS-->
  <link href="../assets/css/sidebar-menu.css" rel="stylesheet"/>
  <!-- Custom Style-->
  <link href="../assets/css/app-style.css" rel="stylesheet"/>
  
</head>

<body class="bg-theme bg-theme1">

<!-- start loader -->
   <div id="pageloader-overlay" class="visible incoming"><div class="loader-wrapper-outer"><div class="loader-wrapper-inner" ><div class="loader"></div></div></div></div>
   <!-- end loader -->

<!-- Start wrapper-->
 <div id="wrapper">
 
 <?php
include 'header.php';
?>

<div class="clearfix"></div>
	
  <div class="content-wrapper">
    <div class="container-fluid">

  <!--Start Dashboard Content-->

	<div class="card mt-3">
    <div class="card-content">
        <div class="row row-group m-0">
        <div class="col-12 col-lg-6 col-xl-3 border-light">
            <div class="card-body">
              <h5 class="text-white mb-0"><?php echo htmlspecialchars($total_customers); ?> <span class="float-right"><i class="zmdi zmdi-account"></i></span></h5>
              <div class="progress my-3" style="height:3px;">
                <div class="progress-bar" style="width:55%"></div>
              </div>
              <p class="mb-0 text-white small-font">Total Customers <span class="float-right"></span></p>
            </div>
          </div>

          <!-- Total Services Card -->
          <div class="col-12 col-lg-6 col-xl-3 border-light">
            <div class="card-body">
              <h5 class="text-white mb-0"><?php echo htmlspecialchars($total_services); ?> <span class="float-right"><i class="zmdi zmdi-accounts"></i></span></h5>
              <div class="progress my-3" style="height:3px;">
                <div class="progress-bar" style="width:55%"></div>
              </div>
              <p class="mb-0 text-white small-font">Total Services <span class="float-right"></span></p>
            </div>
          </div>

          <!-- Monthly Revenue Card -->
          <div class="col-12 col-lg-6 col-xl-3 border-light">
            <div class="card-body">
              <h5 class="text-white mb-0"><?php echo htmlspecialchars(number_format($monthly_revenue, 2)); ?> <span class="float-right"><i class="zmdi zmdi-money"></i></span></h5>
              <div class="progress my-3" style="height:3px;">
                <div class="progress-bar" style="width:55%"></div>
              </div>
              <p class="mb-0 text-white small-font">Monthly Revenue <span class="float-right"></span></p>
            </div>
          </div>

          <!-- Total Revenue Card -->
          <div class="col-12 col-lg-6 col-xl-3 border-light">
            <div class="card-body">
              <h5 class="text-white mb-0"><?php echo htmlspecialchars(number_format($total_revenue, 2)); ?> <span class="float-right"><i class="zmdi zmdi-money-box"></i></span></h5>
              <div class="progress my-3" style="height:3px;">
                <div class="progress-bar" style="width:55%"></div>
              </div>
              <p class="mb-0 text-white small-font">Total Revenue <span class="float-right"></span></p>
            </div>
          </div>
  </div>
    </div>
  </div>
	<div class="row">
     <div class="col-12 col-lg-8 col-xl-8 ">
	    <div class="card">
		 <div class="card-header">Salon Traffic
		   <div class="card-action">
			 <div class="dropdown">
			 <a href="javascript:void();" class="dropdown-toggle dropdown-toggle-nocaret" data-toggle="dropdown">
			  <!-- <i class="icon-options"></i> -->
			 </a>
				<!-- <div class="dropdown-menu dropdown-menu-right">
				<a class="dropdown-item" href="javascript:void();">Action</a>
				<a class="dropdown-item" href="javascript:void();">Another action</a>
				<a class="dropdown-item" href="javascript:void();">Something else here</a>
				<div class="dropdown-divider"></div>
				<a class="dropdown-item" href="javascript:void();">Separated link</a>
			   </div> -->
			  </div>
		   </div>
		 </div>
		 <div class="card-body">
		    
			<div class="chart-container-1">
      <canvas id="myChart" style="width:100%;max-width:600px"></canvas>
			</div>
		 </div>
		 
		 <div class="row m-0 row-group text-center border-top border-light-3">
		   <div class="col-12 col-lg-4">
		     <div class="p-3">
		       <h5 class="mb-0"><?php echo htmlspecialchars($total_customers); ?></h5>
			   <small class="mb-0">Total Customer <span> </span></small>
		     </div>
		   </div>
		   <div class="col-12 col-lg-4">
		     <div class="p-3">
		       <h5 class="mb-0"><?php echo htmlspecialchars($total_revenue); ?></h5>
			   <small class="mb-0">Total Revenue <span> </span></small>
		     </div>
		   </div>
		   <div class="col-12 col-lg-4">
		     <div class="p-3">
		       <h5 class="mb-0"><?php echo htmlspecialchars($total_services); ?></h5>
			   <small class="mb-0">Total Services <span> </span></small>
		     </div>
		   </div>
		 </div>
		</div>
	 </div>

     <div class="col-12 col-lg-4 col-xl-4">
        <div class="card">
           <div class="card-header">Monthly sales
             <div class="card-action">
             <div class="dropdown">
             <a href="javascript:void();" class="dropdown-toggle dropdown-toggle-nocaret" data-toggle="dropdown">
              <!-- <i class="icon-options"></i> -->
             </a>
              <div class="dropdown-menu dropdown-menu-right">
              <a class="dropdown-item" href="javascript:void();">Action</a>
              <a class="dropdown-item" href="javascript:void();">Another action</a>
              <a class="dropdown-item" href="javascript:void();">Something else here</a>
              <div class="dropdown-divider"></div>
              <a class="dropdown-item" href="javascript:void();">Separated link</a>
               </div>
              </div>
             </div>
           </div>
           <!-- <div class="card-body">
		     <div class="chart-container-2">
               <canvas id="chart2"></canvas>
			  </div>
           </div> -->
           <div class="table-responsive">
             <table class="table align-items-center">
               <tbody>
               <tr>
                  <td><i class="fa fa-circle text-white mr-2"></i> Hair Cut</td>
                  <td><?= $totalRevenueHairCut ?></td>
              </tr>
                 <tr>
                   <td><i class="fa fa-circle text-white mr-2"></i>Pedicure</td>
                   <td><?= $totalRevenuePedicure ?></td>
                 </tr>
                 <tr>
                   <td><i class="fa fa-circle text-white mr-2"></i>Manicure</td>
                   <td><?= $totalRevenueManicure ?></td>
                 </tr>
                 <tr>
                   <td><i class="fa fa-circle text-white mr-2"></i>Facial</td>
                   
                   <td><?= $totalRevenueFacial ?></td>
                 </tr>
                 <tr>
                   <td><i class="fa fa-circle text-white mr-2"></i>Waxing</td>
                   
                   <td><?= $totalRevenueWaxing ?></td>
                 </tr>
                 <tr>
                   <td><i class="fa fa-circle text-white mr-2"></i>Threading</td>
                  
                   <td><?= $totalRevenueThreading ?></td>
                 </tr>
                 <tr>
                   <td><i class="fa fa-circle text-white mr-2"></i>Keratin</td>
              
                   <td><?= $totalRevenueKeratin ?></td>
                 </tr>
                 <tr>
                   <td><i class="fa fa-circle text-white mr-2"></i>Hair spa</td>
                
                   <td><?= $totalRevenueHairSpa ?></td>
                 </tr>
               </tbody>
             </table>
           </div>
         </div>
     </div>
	</div><!--End Row-->
	
	<div class="row">
	 <div class="col-12 col-lg-12">
	   <div class="card">
	     <div class="card-header">Recent Appointments
		  <div class="card-action">
             <div class="dropdown">
             <a href="javascript:void();" class="dropdown-toggle dropdown-toggle-nocaret" data-toggle="dropdown">
              <!-- <i class="icon-options"></i> -->
             </a>
              <!-- <div class="dropdown-menu dropdown-menu-right">
              <a class="dropdown-item" href="javascript:void();">Action</a>
              <a class="dropdown-item" href="javascript:void();">Another action</a>
              <a class="dropdown-item" href="javascript:void();">Something else here</a>
              <div class="dropdown-divider"></div>
              <a class="dropdown-item" href="javascript:void();">Separated link</a>
               </div> -->
              </div>
             </div>
		 </div>
     <div class="col-lg-12">
          <div class="">
      
              <div class="">
              <table class="table table-hover">
  <thead>
    <tr>
      <th scope="col">Date</th>
      <th scope="col">Name</th>
      <th scope="col">Mobile</th>
      <th scope="col">Email</th>
      <th scope="col">Service</th>
    </tr>
  </thead>
  <tbody>
    <?php
    include("../connect.php");

    // Get the current date
    $current_date = date('Y-m-d');

    // Fetch data from database for the current date
    $sql = "SELECT appointment_date, name, mobile, email, service FROM appointments WHERE DATE(appointment_date) = '$current_date'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Output data of each row
        while($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . date('d/m/Y', strtotime($row["appointment_date"])) . "</td>";
            echo "<td>" . $row["name"] . "</td>";
            echo "<td>" . $row["mobile"] . "</td>";
            echo "<td>" . $row["email"] . "</td>";
            echo "<td>" . $row["service"] . "</td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='5'>No appointments found</td></tr>";
    }

    $conn->close();
    ?>
  </tbody>
</table>

              </div>
            </div>
          </div>
        </div>
	 </div>
	</div><!--End Row-->

      <!--End Dashboard Content-->
	  
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
	<?php include 'footer.php';?>
	<!--End footer-->
	
  <!--start color switcher-->
   <!-- <div class="right-sidebar">
    <div class="switcher-icon">
      <i class="zmdi zmdi-settings zmdi-hc-spin"></i>
    </div>
    <div class="right-sidebar-content">

      <p class="mb-0">Gaussion Texture</p>
      <hr>
      
      <ul class="switcher">
        <li id="theme1"></li>
        <li id="theme2"></li>
        <li id="theme3"></li>
        <li id="theme4"></li>
        <li id="theme5"></li>
        <li id="theme6"></li>
      </ul>

      <p class="mb-0">Gradient Background</p>
      <hr>
      
      <ul class="switcher">
        <li id="theme7"></li>
        <li id="theme8"></li>
        <li id="theme9"></li>
        <li id="theme10"></li>
        <li id="theme11"></li>
        <li id="theme12"></li>
		<li id="theme13"></li>
        <li id="theme14"></li>
        <li id="theme15"></li>
      </ul>
      
     </div>
   </div> -->
  <!--end color switcher-->
   
  </div><!--End wrapper-->
 
  <!-- Bootstrap core JavaScript-->
  <script src="../assets/js/jquery.min.js"></script>
  <script src="../assets/js/popper.min.js"></script>
  <script src="../assets/js/bootstrap.min.js"></script>
	
 <!-- simplebar js -->
  <script src="../assets/plugins/simplebar/js/simplebar.js"></script>
  <!-- sidebar-menu js -->
  <script src="../assets/js/sidebar-menu.js"></script>
  <!-- loader scripts -->
  <script src="../assets/js/jquery.loading-indicator.js"></script>
  <!-- Custom scripts -->
  <script src="../assets/js/app-script.js"></script>
  <!-- Chart js -->
  
  <script src="../assets/plugins/Chart.js/Chart.min.js"></script>
 
  <!-- Index js -->
  <script src="../assets/js/index.js"></script>

  <?php
// Fetch current and previous month's total amount from your backend and store them in variables
$currentMonthAmount = $monthly_revenue; // Replace with actual value
$previousMonthAmount = $total_revenue; // Replace with actual value
?>

<script>
// Define labels and data for the chart
const labels = ["Previous Month", "Current Month"];
const monthlyAmounts = [<?php echo $previousMonthAmount; ?>, <?php echo $currentMonthAmount; ?>];
const totalAmounts = [<?php echo $total_revenue; ?>, <?php echo $total_revenue; ?>]; // Assuming total revenue remains constant for previous and current months

new Chart("myChart", {
  type: "line",
  data: {
    labels: labels,
    datasets: [{ 
      label: 'Monthly Revenue',
      data: monthlyAmounts,
      borderColor: "#ADD8E6", // Blue color
      fill: false
    }, { 
      label: 'Total Revenue',
      data: totalAmounts,
      borderColor: "#9ACD32", // Green color
      fill: false
    }]
  },
  options: {
    legend: { display: true },
    scales: {
      yAxes: [{
        ticks: {
          beginAtZero: true
        }
      }]
    }
  }
});
</script>

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
