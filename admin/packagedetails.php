<?php

session_start();


// Include the database connection file
include '../connect.php';

// Fetch all packages
$packagesQuery = "SELECT * FROM packages";
$packagesResult = mysqli_query($conn, $packagesQuery);

if ($packagesResult && mysqli_num_rows($packagesResult) > 0) {
    $packages = mysqli_fetch_all($packagesResult, MYSQLI_ASSOC);
} else {
    $packages = [];
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
  <title>Salon - Package Details</title>
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
  <!-- SweetAlert2 -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
</head>

<body class="bg-theme bg-theme1">
  <!-- start loader -->
  <div id="pageloader-overlay" class="visible incoming">
    <div class="loader-wrapper-outer">
      <div class="loader-wrapper-inner">
        <div class="loader"></div>
      </div>
    </div>
  </div>
  <!-- end loader -->

  <!-- Start wrapper-->
  <div id="wrapper">

  <?php include 'header.php'; ?>

  <div class="clearfix"></div>

  <div class="content-wrapper">
    <div class="container-fluid" >
      <div class="row mt-3">
        <div class="col-lg-12">
          <button class="btn btn-warning float-right" data-toggle="modal" data-target="#addPackageModal">Add Packages+</button>
        </div>
      </div>
      <!-- First Row of Cards -->
      <div class="row mt-3">
        <?php foreach ($packages as $package): ?>
          <?php
          // Fetch services for the current package
          $packageId = $package['id'];
          $servicesQuery = "SELECT service_name FROM package_services WHERE package_id = $packageId";
          
          $servicesResult = mysqli_query($conn, $servicesQuery);
          $services = [];
          if ($servicesResult && mysqli_num_rows($servicesResult) > 0) {
              $services = mysqli_fetch_all($servicesResult, MYSQLI_ASSOC);
          }

          // Fetch package name and package amount for the current package
          $packageDetailsQuery = "SELECT package_name, amount FROM packages WHERE id = $packageId";
          $packageDetailsResult = mysqli_query($conn, $packageDetailsQuery);
          $packageDetails = [];
          if ($packageDetailsResult && mysqli_num_rows($packageDetailsResult) > 0) {
              $packageDetails = mysqli_fetch_assoc($packageDetailsResult);
          }
          ?>
          <div class="col-lg-4">
            <div class="card" style="border-radius:20px">
              <div class="card-body">
              <button class="btn btn-danger delete-package-btn float-right" data-package-id="<?php echo $package['id']; ?>">Delete</button>
                <h3 class="" style="text-align: center; text-transform:capitalize"><?php echo htmlspecialchars($package['packageName']); ?></h3>
                <ul>
                  <?php foreach ($services as $service): ?>
                    <li style="text-transform:capitalize"><?php echo htmlspecialchars($service['service_name']); ?></li>
                    
                  <?php endforeach; ?>
                  <hr>
                  <h3 style="text-align: right;"> <?php echo htmlspecialchars($package['packageAmount']); ?></h3>
                </ul>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    

      <!-- Modal for Adding Package -->
      <div class="modal fade" id="addPackageModal" tabindex="-1" role="dialog" aria-labelledby="addPackageModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="addPackageModalLabel">Add Package</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <form id="packageForm" action="package-backend.php" method="post">
                <div class="form-group">
                  <label for="packageName" style="color:black">Package Name</label>
                  <input type="text" class="form-control" placeholder="Enter Package Name" id="packageName" name="packageName" required style="background-color:silver; color: #fff; border: 1px solid #495057;">
                </div>
                <div class="form-group">
                  <label for="packageAmount" style="color:black">Package Amount</label>
                  <input type="number" class="form-control" placeholder="Enter Total Amount" id="packageAmount" name="packageAmount" required style="background-color:silver; color: #fff; border: 1px solid #495057;">
                </div>
                <div class="form-group">
                  <label for="services" style="color:black">Services</label>
                  <div id="servicesContainer">
                    <input type="text" class="form-control mb-2" name="services[]" placeholder="Enter Service" required style="background-color:silver; color: #fff; border: 1px solid #495057;">
                  </div>
                  <button type="button" class="btn btn-secondary" id="addMoreServicesBtn">Add More Services</button>
                </div>
                <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
              <button type="submit" name="package" class="btn btn-primary">Save Package</button>
            </div>
              </form>
            </div>
            
          </div>
        </div>
      </div>
    </div>
    <!-- End container-fluid-->
  </div>
  <!--End content-wrapper-->

  <!--Start Back To Top Button-->
  <a href="javaScript:void();" class="back-to-top"><i class="fa fa-angle-double-up"></i> </a>
  <!--End Back To Top Button-->

  <!--Start footer-->
  <?php include 'footer.php'; ?>
  <!--End footer-->

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
  document.getElementById('addMoreServicesBtn').addEventListener('click', function() {
    var servicesContainer = document.getElementById('servicesContainer');
    var newServiceField = document.createElement('input');
    newServiceField.type = 'text';
    newServiceField.name = 'services[]';
    newServiceField.className = 'form-control mb-2';
    newServiceField.placeholder = 'Enter Service';
    newServiceField.required = true;
    newServiceField.style.backgroundColor = 'silver';
    newServiceField.style.color = '#fff';
    newServiceField.style.border = '1px solid #495057';
    servicesContainer.appendChild(newServiceField);
  });

  document.getElementById('savePackageBtn').addEventListener('click', function() {
    var form = document.getElementById('packageForm');
    var formData = new FormData(form);

    // Send form data using AJAX
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'package-backend.php', true);
    xhr.onload = function() {
      if (xhr.status === 200) {
        // Request was successful
        alert('Package saved successfully!');
        // Optionally, you can clear the form or close the modal here
      } else {
        // Request failed
        alert('Failed to save package. Please try again.');
      }
    };
    xhr.onerror = function() {
      // Request error
      alert('Error occurred while saving package.');
    };
    xhr.send(formData);
  });

  
</script>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

<!-- Include SweetAlert and AJAX script -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Function to handle package deletion
    function deletePackage(packageId) {
        // Display SweetAlert confirmation
        Swal.fire({
            title: 'Are you sure?',
            text: 'You will not be able to recover this package and its services!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                // Send AJAX request to delete package
                fetch(`delete-package.php?package_id=${packageId}`, {
                    method: 'DELETE'
                })
                .then(response => {
                    if (response.ok) {
                        // Reload the page to reflect the deletion
                        window.location.reload();
                    } else {
                        throw new Error('Failed to delete package.');
                    }
                })
                .catch(error => {
                    console.error('Error deleting package:', error);
                    Swal.fire(
                        'Error!',
                        'Failed to delete the package.',
                        'error'
                    );
                });
            }
        });
    }

    // Event listener for delete buttons (run after page is fully loaded)
    $(document).ready(function() {
        $('.delete-package-btn').each(function() {
            $(this).on('click', function() {
                const packageId = $(this).data('package-id');
                deletePackage(packageId);
            });
        });
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
