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
  <title>Salon - Appointments</title>
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
              <h5 class="card-title">Appointments</h5>
              <div class="">
                <table class="table table-hover">
                  <thead>
                    <tr>
                      <th scope="col">Date</th>
                      <th scope="col">Name</th>
                      <th scope="col">Mobile</th>
                      <th scope="col">Email</th>
                      <th scope="col">Service</th>
                      <th scope="col">Status</th>
                      <th scope='col'>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    include("../connect.php");

                    // Fetch data from database
                    $sql = "SELECT appointment_date, name, mobile, email, service, status FROM appointments";
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
                            echo "<td>" . $row["status"] . "</td>";
                            // Add approve button with JavaScript function to redirect
                            echo "<td><button class='btn btn-warning' name='appointment' onclick='approveAppointment(\"" . $row["name"] . "\", \"" . $row["appointment_date"] . "\")'>Approve</button></td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='6'>No appointments found</td></tr>";
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
    </div>
    <!--End content-wrapper-->
    <!--Start Back To Top Button-->
    <a href="javaScript:void();" class="back-to-top"><i class="fa fa-angle-double-up"></i> </a>
    <!--End Back To Top Button-->
   
  </div>
  <!--End wrapper-->
 <?php include 'footer.php'; ?>
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
  <script>
    function approveAppointment(name, date) {
        window.location.href = 'calendar.php?name=' + encodeURIComponent(name) + '&date=' + encodeURIComponent(date);
    }
  </script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> <!-- Include SweetAlert library -->

  <script>
        function approveAppointment(name, appointment_date) {
            updateStatus(name, appointment_date, 'approved');
        }

        function cancelAppointment(name, appointment_date) {
            updateStatus(name, appointment_date, 'cancelled');
        }

        function updateStatus(name, appointment_date, status) {
            // Display confirmation dialog using SweetAlert
            Swal.fire({
                title: 'Are you sure?',
                text: 'You are about to ' + status + ' this appointment',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'approve',
                cancelButtonText: 'cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    // If user confirms, send AJAX request to update status
                    $.ajax({
                        url: 'updateStatus.php',
                        type: 'POST',
                        data: { 
                            name: name, 
                            appointment_date: appointment_date,
                            status: status // Set status to the selected value (approved or cancelled)
                        },
                        success: function(response) {
                            // Handle success response
                            // For example, display a success message
                            Swal.fire('Success!', 'Appointment ' + status + ' successfully', 'success');
                            // Reload the page to reflect the updated status
                            location.reload();
                        },
                        error: function(xhr, status, error) {
                            // Handle error response
                            console.error(xhr.responseText);
                            Swal.fire('Error!', 'Failed to ' + status + ' appointment', 'error');
                        }
                    });
                }
            });
        }
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
