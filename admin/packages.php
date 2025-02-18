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
  <title>Salon - Packages form</title>
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
    <div class="container-fluid">
      <div class="row mt-3">
        <div class="col-lg-12">
          <div class="card" id="customer-form-container">
            <div class="card-body">
              <div class="card-title">Pacakges Form</div>
              <hr>
              
              <form action="customer-backend.php" method="post" id="customer-form" class="hidden mt-3">
                  <div class="form-group">
                      <label for="input-6">Package Name</label>
                      <input type="text" class="form-control form-control-rounded" id="input-6" name="packagename" placeholder="Enter Name" required>
                  </div>
                 
                  <div class="form-group">
                      <label for="services-container">Services</label>
                      <div id="services-container">
                          <div class="row mb-2">
                              <div class="col-md-5">
                              <input type="number" class="form-control form-control-rounded service-amount" name="services[]" placeholder="Enter Service" required>
                              </div>
                              <div class="col-md-3">
                                  <input type="number" class="form-control form-control-rounded service-amount" name="service_amounts[]" placeholder="Enter Service Amount" required>
                              </div>
                          </div>
                      </div>
                      <button type="button" class="btn btn-secondary btn-round" id="add-service-btn">Add More Services</button>
                  </div>
                  <div class="form-group">
                      <label for="input-10">Total Amount</label>
                      <input type="text" class="form-control form-control-rounded" id="input-10" name="amount" placeholder="Enter Total Amount" readonly required>
                  </div>
                  <div class="form-group py-2">
                      <div class="icheck-material-white">
                          <input type="checkbox" id="user-checkbox2" checked=""/>
                      </div>
                  </div>
                  <div class="form-group">
                      <button type="submit" name="customer" class="btn btn-light btn-round px-5"><i class="icon-lock"></i> Register</button>
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
  </div>
  <!--End content-wrapper-->

  <!--Start Back To Top Button-->
  <a href="javaScript:void();" class="back-to-top"><i class="fa fa-angle-double-up"></i> </a>
  <!--End Back To Top Button-->

  <!--Start footer-->
  <?php include 'footer.php'; ?>
  <!--End footer-->

  </div><!--End wrapper-->

  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script>
    document.getElementById('add-service-btn').addEventListener('click', function() {
        var container = document.getElementById('services-container');
        var row = document.createElement('div');
        row.className = 'row mb-2';
        row.innerHTML = `
            <div class="col-md-5">
            <select class="form-control form-control-rounded" name="services[]" required>
                                                <option value="" disabled selected>Enter Service</option>
                                                <option value="threading">Threading</option>
                                                <option value="hair cut">Hair Cut</option>
                                                <option value="hair spa">Hair Spa</option>
                                                <option value="manicure">Manicure</option>
                                                <option value="pedicure">Pedicure</option>
                                                <option value="waxing">Waxing</option>
                                                <option value="facial">Facial</option>
                                                <option value="keratin">Keratin</option>
                                            </select>       
                                     </div>
            <div class="col-md-3">
                <input type="number" class="form-control form-control-rounded service-amount" name="service_amounts[]" placeholder="Enter Service Amount" required>
            </div>
            <div class="col-md-2">
                 <input type="number" class="form-control form-control-rounded service-amount" name="service_qty[]" placeholder="Enter Quantity" required>
            </div>
            <div class="col-md-2">
                 <input type="number" class="form-control form-control-rounded service-amount" name="service_total[]" placeholder="Total Amount" required>
            </div>
        `;
        container.appendChild(row);
    });

    function updateTotalAmount() {
        var serviceAmounts = document.getElementsByName('service_amounts[]');
        var serviceQuantities = document.getElementsByName('service_qty[]');
        var serviceTotals = document.getElementsByName('service_total[]');
        var totalAmount = 0;

        // Update each service total and calculate total amount
        for (var i = 0; i < serviceAmounts.length; i++) {
            var amount = parseFloat(serviceAmounts[i].value);
            var quantity = parseFloat(serviceQuantities[i].value);
            if (!isNaN(amount) && !isNaN(quantity)) {
                var serviceTotal = amount * quantity;
                serviceTotals[i].value = serviceTotal.toFixed(2);
                totalAmount += serviceTotal;
            }
        }

        // Update the total amount field
        document.getElementById('input-10').value = totalAmount.toFixed(2);
    }

    // Add event listener to listen for changes in service inputs
    document.getElementById('services-container').addEventListener('input', updateTotalAmount);

    document.getElementById('check-customer').addEventListener('click', function() {
    var mobile = document.getElementById('customer-id').value;
    if (mobile.length >= 10) { 
        fetch('check-customer.php?mobile=' + mobile)
            .then(response => response.json())
            .then(data => {
                if (data.exists) {
                    document.getElementById('customer-form').classList.remove('hidden');
                    // Populate input fields with customer details
                    document.getElementById('input-6').value = data.customer.name;
                    document.getElementById('input-7').value = data.customer.email;
                    document.getElementById('input-8').value = data.customer.mobile;
                    document.getElementById('input-9').value = data.customer.stylist;
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'No Existing Customer',
                        text: 'No customer found with the provided mobile number.'
                    });
                }
            })
            .catch(error => console.error('Error:', error));
    } else {
        Swal.fire({
            icon: 'warning',
            title: 'Invalid Input',
            text: 'Please enter a valid mobile number.'
        });
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
