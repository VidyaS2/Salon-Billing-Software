<?php
// Start session
session_start();

// Include database connection
include("../connect.php");

// Include token functions
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

function decodeToken($token) {
  $secretKey = 'YourSecretKey'; // Use the same secret key
  $decoded = base64_decode($token);
  list($hash, $data) = explode('|', $decoded, 2);

  // Verify the hash
  if (hash_hmac('sha256', $data, $secretKey, true) === $hash) {
      list($customer_id, $timestamp) = explode('|', $data);
      return [$customer_id, $timestamp];
  }

  return false;
}

// Function to fetch customer details by ID
function getCustomerDetails($conn, $customer_id) {
    $customer_details = array();

    // Fetch customer details from customer_details table
    $fetch_sql = "SELECT * FROM customer_details WHERE id = ?";
    $stmt = mysqli_prepare($conn, $fetch_sql);
    mysqli_stmt_bind_param($stmt, "i", $customer_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result && mysqli_num_rows($result) > 0) {
        $customer_details = mysqli_fetch_assoc($result);
    }

    mysqli_stmt_close($stmt);
    
    return $customer_details;
}

// Function to fetch services for a customer
function getCustomerServices($conn, $customer_id) {
  $services = array();

  // Fetch services from services table for the customer
  $services_sql = "SELECT service_name, service_amount, service_qty FROM services WHERE customer_id = ?";
  $stmt = mysqli_prepare($conn, $services_sql);
  mysqli_stmt_bind_param($stmt, "i", $customer_id);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);

  if ($result && mysqli_num_rows($result) > 0) {
      while ($row = mysqli_fetch_assoc($result)) {
          $services[] = $row;
      }
  }

  mysqli_stmt_close($stmt);
  
  return $services;
}


// Initialize variables
$customer = null;
$error_message = null;
$invoice_number = '';
$amount = 0.0;
$tax = 0.0;
$grand_total = 0.0;
$timestamp = '';
$services = array(); // Initialize the services array
$current_date = date('Y-m-d'); // Set the current date

// Fetch customer ID from the token parameter
if (isset($_GET['token'])) {
    $token = $_GET['token'];
    $decoded = decodeToken($token);

    if ($decoded !== false) {
        list($customer_id, $timestamp) = $decoded;

        // Fetch customer details
        $customer = getCustomerDetails($conn, $customer_id);

        if (!empty($customer)) {
            // Generate invoice number based on customer ID
            $invoice_number = 'SALON2024' . $customer_id;

            // Fetch amount and stylist from customer_details table based on the customer ID
            $amount = (float) $customer['amount'];
            $stylist = $customer['stylist'];

            // Calculate tax (18% of the amount)
            $tax = $amount * 0.18;

            // Calculate grand total (amount + tax)
            $grand_total = $amount + $tax;

            // Fetch services for the customer
            $services = getCustomerServices($conn, $customer_id);
        } else {
            $error_message = "Customer details not found.";
        }
    } else {
        $error_message = "Invalid token.";
    }
} else {
    $error_message = "Token not provided.";
}

// Initialize company details
$companyName = $address = $mobile = $email = '';
$logoPath = ''; // Variable to store logo path

// Fetch company details from the business table
$sql = "SELECT name, address, mobile, email, logo FROM business LIMIT 1";

$result = mysqli_query($conn, $sql);

// Debug: Check if the query executed successfully
if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}

if (mysqli_num_rows($result) > 0) {
    // Fetch data from the result set
    $row = mysqli_fetch_assoc($result);
    $companyName = $row['name'];
    $address = $row['address'];
    $mobile = $row['mobile'];
    $email = $row['email'];
    $logoPath = $row['logo']; // Fetch the logo path

    // Debug: Display fetched data
    // Debug statement for logo path
} else {
    // Handle case where no data is found (optional)
    $companyName = 'Company Name Not Found';
    $address = 'Address Not Found';
    $mobile = 'Mobile Not Found';
    $email = 'Email Not Found';

    // Debug: Indicate no data found
    echo "No company data found\n";
}


// Close the database connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html  lang="en">

<head>
  <!-- Meta Tags -->
  <meta charset="utf-8">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="author" content="Laralink">
  <!-- Site Title -->
  <title>Salon - Invoice</title>
  <link rel="stylesheet" href="../assets/css/style.css">
  <link rel="stylesheet" href="../assets/css/invoice.css">
  <style>
        .back-button {
            display: inline-block;
            cursor: pointer;
        }
    </style>
</head>

<body>
  <div class="tm_container">
    <div class="tm_invoice_wrap">
      <div class="tm_invoice tm_style1 tm_type3" id="tm_download_section">
        <div class="tm_shape_1">
          <svg width="850" height="151" viewBox="0 0 850 151" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M850 0.889398H0V150.889H184.505C216.239 150.889 246.673 141.531 269.113 124.872L359.112 58.0565C381.553 41.3977 411.987 32.0391 443.721 32.0391H850V0.889398Z" fill="#007AFF" fill-opacity="0.1"/>
          </svg>                       
        </div>      
        <div class="tm_shape_2">
          <svg width="850" height="151" viewBox="0 0 850 151" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M0 150.889H850V0.889408H665.496C633.762 0.889408 603.327 10.2481 580.887 26.9081L490.888 93.7224C468.447 110.381 438.014 119.74 406.279 119.74H0V150.889Z" fill="#007AFF" fill-opacity="0.1"/>
          </svg>            
        </div>      
        <div class="tm_invoice_in">
          <div class="tm_invoice_head tm_align_center tm_mb20">
            <div class="tm_invoice_left">
<div class="tm_logo">
    <?php if (!empty($logoPath)): ?>
        <img src="../uploads/<?php echo $logoPath; ?>" class="logo-icon" alt="Company Logo">
    <?php else: ?>
        <img src="../assets/images/logo-icon.png" class="logo-icon" alt="Default Logo">
    <?php endif; ?>
</div>
            </div>
            <div class="tm_invoice_right tm_text_right">
              <div class="tm_primary_color tm_f50 tm_text_uppercase"> <?php echo htmlspecialchars($companyName); ?> Invoice</div>
            </div>
          </div>
          <div class="tm_invoice_info tm_mb20">
            <div class="tm_invoice_seperator">
              <img src="../assets/img/arrow_bg.svg" alt="">              
            </div>
            <div class="tm_invoice_info_list">
              <p class="tm_invoice_number tm_m0">Invoice No: <b class="tm_primary_color"><?php echo $invoice_number; ?></b></p>
              <p class="tm_invoice_date tm_m0">Date: <b class="tm_primary_color"><?php echo $current_date; ?></b></p>
              <p class="tm_invoice_date tm_m0">Stylist Name: <b class="tm_primary_color"  style="text-transform:capitalize"><?php echo $stylist; ?></b></p>

              <div class="tm_invoice_info_list_bg tm_accent_bg_10"></div>
          </div>
          </div>
          <div class="tm_invoice_head tm_mb10">
            <div class="tm_invoice_left">
              <p class="tm_mb2"><b class="tm_primary_color">Invoice To:</b></p>
              <p >
                Name:<span style="text-transform:capitalize"> <?php echo htmlspecialchars($customer['name']); ?></span><br>
                Mobile: <?php echo htmlspecialchars($customer['mobile']); ?><br>
               Email: <?php echo htmlspecialchars($customer['email']); ?><br>
              </p>
            </div>
            <div class="tm_invoice_right tm_text_right">
              <p class="tm_mb2"><b class="tm_primary_color">Pay To:</b></p>
              <p>
                 
                  <?php echo htmlspecialchars($address); ?> <br>
                  
                  <?php echo htmlspecialchars($email); ?><br>
                  <?php echo htmlspecialchars($mobile); ?> 
              </p>
            </div>
          </div>
          <div class="tm_table tm_style1 tm_mb30">
            <div class="tm_table_responsive">
            <table class="tm_border_bottom">
                <thead>
                  <tr class="tm_border_top">
                    <th class="tm_width_3 tm_semi_bold tm_accent_color">Services</th>
                    <th class="tm_width_2 tm_semi_bold tm_accent_color tm_text_center">Qty</th>
                    <th class="tm_width_2 tm_semi_bold tm_accent_color tm_text_center">Price</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($services as $service): ?>
                    <tr>
                      <td class="tm_width_3"><?php echo htmlspecialchars($service['service_name'] ?? 'N/A'); ?></td>
                      
                      <td class="tm_width_1"><?php echo isset($service['service_qty']) ? htmlspecialchars($service['service_qty']) : 'N/A'; ?></td>
                      <td class="tm_width_2 tm_text_right">
                        <?php
                          if (isset($service['service_amount']) && isset($service['service_qty'])) {
                              $total = $service['service_amount'] * $service['service_qty'];
                              echo htmlspecialchars($total);
                          } else {
                              echo 'N/A';
                          }
                        ?>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
            <div class="tm_invoice_footer">
              <div class="tm_left_footer">
               
              </div>
              <div class="tm_right_footer">
                <table>
                  <tbody>
                  <tr>
                    <td class="tm_width_3 tm_primary_color tm_border_none tm_bold">Subtotal</td>
                    <td class="tm_width_3 tm_primary_color tm_text_right tm_border_none tm_bold"><?php echo number_format($amount, 2); ?></td>
                </tr>


                        <tr>
                          <td class="tm_width_3 tm_primary_color tm_border_none tm_pt0">Tax <span class="tm_ternary_color">(18%)</span></td>
                          <td class="tm_width_3 tm_primary_color tm_text_right tm_border_none tm_pt0"><?php echo number_format($tax, 2); ?></td>
                      </tr>
                      <tr class="tm_border_top tm_border_bottom">
                          <td class="tm_width_3 tm_border_top_0 tm_bold tm_f16 tm_primary_color">Grand Total</td>
                          <td class="tm_width_3 tm_border_top_0 tm_bold tm_f16 tm_primary_color tm_text_right"><?php echo number_format($grand_total, 2); ?></td>
                      </tr>

                  </tbody>
                </table>
              </div>
            </div>
          </div>
          <div class="tm_padd_15_20">
            <p class="tm_mb2"><b class="tm_primary_color">Terms & Conditions:</b></p>
            <ul class="tm_m0 tm_note_list">
              <li>Please arrive on time for your appointment to ensure efficient service.</li>
              <li> Payment is expected at the time of service. We accept cash, credit/debit cards, and electronic payments.</li>
            </ul>
          </div>
          <!-- .tm_note -->
        </div>
      </div>
      <div class="tm_invoice_btns tm_hide_print">
        <a href="javascript:window.print()" class="tm_invoice_btn tm_color1">
          <span class="tm_btn_icon">
            <svg xmlns="http://www.w3.org/2000/svg" class="ionicon" viewBox="0 0 512 512"><path d="M384 368h24a40.12 40.12 0 0040-40V168a40.12 40.12 0 00-40-40H104a40.12 40.12 0 00-40 40v160a40.12 40.12 0 0040 40h24" fill="none" stroke="currentColor" stroke-linejoin="round" stroke-width="32"/><rect x="128" y="240" width="256" height="208" rx="24.32" ry="24.32" fill="none" stroke="currentColor" stroke-linejoin="round" stroke-width="32"/><path d="M384 128v-24a40.12 40.12 0 00-40-40H168a40.12 40.12 0 00-40 40v24" fill="none" stroke="currentColor" stroke-linejoin="round" stroke-width="32"/><circle cx="392" cy="184" r="24" fill='currentColor'/></svg>
          </span>
          <span class="tm_btn_text">Print</span>
        </a>
        <a href="customer.php" class="back-button">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="50" height="50">
            <rect x="48" y="96" width="416" height="320" rx="40" ry="40" fill="none" stroke="currentColor" stroke-linejoin="round" stroke-width="32"/>
            <path fill="blue" d="M217.4 176l-96 96 96 96V272h134.6v-32H217.4V176z"/>
        </svg>
    </a>
      </div>
    </div>
  </div>
  <script src="../assets/js/jquery.min.js"></script>
  <script src="../assets/js/jspdf.min.js"></script>
  <script src="../assets/js/html2canvas.min.js"></script>
  <script src="../assets/js/index.js"></script>
  <script>
    /* * * * * * * * * * * * * * * * *
  /////////////////   Down Load Button Function   /////////////////
  * * * * * * * * * * * * * * * * */
 
(function ($) {
  'use strict';

  $('#tm_download_btn').on('click', function () {
    var downloadSection = $('#tm_download_section');
    var cWidth = downloadSection.width();
    var cHeight = downloadSection.height();
    var topLeftMargin = 0;
    var pdfWidth = cWidth + topLeftMargin * 2;
    var pdfHeight = pdfWidth * 1.5 + topLeftMargin * 2;
    var canvasImageWidth = cWidth;
    var canvasImageHeight = cHeight;
    var totalPDFPages = Math.ceil(cHeight / pdfHeight) - 1;

    html2canvas(downloadSection[0], { allowTaint: true }).then(function (
      canvas
    ) {
      canvas.getContext('2d');
      var imgData = canvas.toDataURL('image/png', 1.0);
      var pdf = new jsPDF('p', 'pt', [pdfWidth, pdfHeight]);
      pdf.addImage(
        imgData,
        'PNG',
        topLeftMargin,
        topLeftMargin,
        canvasImageWidth,
        canvasImageHeight
      );
      for (var i = 1; i <= totalPDFPages; i++) {
        pdf.addPage(pdfWidth, pdfHeight);
        pdf.addImage(
          imgData,
          'PNG',
          topLeftMargin,
          -(pdfHeight * i) + topLeftMargin * 0,
          canvasImageWidth,
          canvasImageHeight
        );
      }
      pdf.save('download.pdf');
    });
  });

})(jQuery);
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