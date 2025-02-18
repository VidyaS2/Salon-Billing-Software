<?php
// Start session
session_start();

// Include database connection
include("../connect.php");

function generateToken($customer_id) {
  $secretKey = 'YourSecretKey'; // Change this to a strong secret key
  $timestamp = time(); // Include a timestamp to ensure uniqueness

  // Create a data string to encrypt
  $data = $customer_id . '|' . $timestamp;

  // Encrypt the data using the secret key
  $hash = hash_hmac('sha256', $data, $secretKey, true);
  $token = base64_encode($hash . '|' . $data);

  return $token;
}

// Initialize an empty array to store fetched data
$customer_details = array();

// Fetch customer details along with their services from the database
$sql = "
    SELECT cd.id, cd.name, cd.mobile, cd.email, cd.amount, cd.stylist, GROUP_CONCAT(s.service_name SEPARATOR ', ') as services
    FROM customer_details cd
    LEFT JOIN services s ON cd.id = s.customer_id
    GROUP BY cd.id, cd.name, cd.mobile, cd.email, cd.amount, cd.stylist
";
$result = mysqli_query($conn, $sql);

if ($result) {
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $customer_details[] = $row;
        }
    } else {
        $error_message = "No customer records found.";
    }
} else {
    $error_message = "Error fetching customer details: " . mysqli_error($conn);
}

// Close the database connection
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
  <title>Salon - Customer Details</title>
  <!-- loader-->
  <link href="../assets/css/pace.min.css" rel="stylesheet"/>
  <script src="../assets/js/pace.min.js"></script>
  <!--favicon-->
  <link rel="icon" href="../assets/images/favicon.ico" type="image/x-icon">
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
  <div id="pageloader-overlay" class="visible incoming"><div class="loader-wrapper-outer"><div class="loader-wrapper-inner"><div class="loader"></div></div></div></div>
  <!-- end loader -->
  <?php include 'header.php'; ?>
  <!-- Start wrapper-->
  <div id="wrapper">
    <div class="content-wrapper">
      <div class="">
        <div class="col-lg-12">
          <div class="">
            <div class="">
              <h5 class="card-title">Daily Customer Details</h5>
              <div class="">
                <?php if (isset($error_message)): ?>
                  <div class="alert alert-danger" role="alert">
                    <?php echo $error_message; ?>
                  </div>
                <?php else: ?>
                  <table class="table table-hover table-responsive">
                    <thead>
                      <tr>
                        <th scope="col">Name</th>
                        <th scope="col">Mobile</th>
                        <th scope="col">Email</th>
                        <th scoper="col">Stylist Name</th>
                        <th scope="col">Service</th>
                        <th scope="col">Amount</th>
                        <th scope='col'>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php foreach ($customer_details as $customer): ?>
                        <tr>
                          <td style="text-transform: capitalize;"><?php echo htmlspecialchars($customer['name']); ?></td>
                          <td style="text-transform: capitalize;"><?php echo htmlspecialchars($customer['mobile']); ?></td>
                          <td><?php echo htmlspecialchars($customer['email']); ?></td>
                          <td style="text-transform: capitalize;"><?php echo htmlspecialchars($customer['stylist']); ?></td>

                          <td style="text-transform: capitalize;"><?php echo htmlspecialchars($customer['services']); ?></td>
                          <td><?php echo htmlspecialchars($customer['amount']); ?></td>
                          <td>
                          <a href="invoice.php?token=<?php echo urlencode(generateToken($customer['id'])); ?>" class="btn btn-primary">Invoice</a>

                          </td>
                        </tr>
                      <?php endforeach; ?>
                    </tbody>
                  </table>
                <?php endif; ?>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!--End content-wrapper-->
    <!--Start Back To Top Button-->
    <a href="javaScript:void();" class="back-to-top"><i class="fa fa-angle-double-up"></i> </a>
    <!--End Back To Top Button-->
    <?php include 'footer.php'; ?>
  </div>
  <!--End wrapper-->
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
  <!-- Bootstrap core JavaScript-->
  <script src="../assets/js/jquery.min.js"></script>
  <script src="../assets/js/popper.min.js"></script>
  <script src="../assets/js/bootstrap.min.js"></script>
  <!-- simplebar js -->
  <script src="../assets/plugins/simplebar/js/simplebar.js"></script>
  <!-- sidebar-menu js -->
  <script src="../assets/js/sidebar-menu.js"></script>
  <!-- Custom scripts -->
  <script src="../assets/js/app-script.js"></script>
</body>
</html>
 