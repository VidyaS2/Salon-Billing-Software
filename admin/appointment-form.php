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
  <title>Salon - Appointment Form</title>
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
              <div class="card-title">Appointment Form</div>
              <hr>
              <form  action="appointment-backend.php" method="post">
                <div class="form-group">
                  <label for="input-5">Date</label>
                  <input type="date" class="form-control form-control-rounded" id="input-5" name="date" placeholder="yyyy-mm-dd" required>
                </div>
                <div class="form-group">      
                  <label for="input-6">Name</label>
                  <input type="text" class="form-control form-control-rounded" id="input-6" name="name" placeholder="Enter Name" required>
                </div>
                <div class="form-group">
                  <label for="input-7">Email</label>
                  <input type="email" class="form-control form-control-rounded" id="input-7" name="email" placeholder="Enter Email Address" required>
                </div>
                <div class="form-group">
                  <label for="input-8">Mobile</label>
                  <input type="text" class="form-control form-control-rounded" id="input-8" name="mobile" placeholder="Enter Mobile Number" 
                  pattern="[0-9]{10}" title="Mobile number must be exactly 10 digits" required>                </div>
                <div class="form-group">
                  <label for="services-container">Services</label>
                  <div id="services-container">
                    <div class="row mb-2">
                      <div class="col-md-12">
                        <input type="text" class="form-control form-control-rounded" name="service" placeholder="Enter Service" required>
                      </div>
                    </div>
                  </div>
                  <!-- <button type="button" class="btn btn-secondary btn-round" id="add-service-btn">Add More Services</button> -->
                </div>
                <div class="form-group py-2">
                  <div class="icheck-material-white">
                    <input type="checkbox" id="user-checkbox2" checked=""/>
                  </div>
                </div>
                <div class="form-group">
                  <button type="submit" name="aptform" class="btn btn-light btn-round px-5"><i class="icon-lock"></i> Approve</button>
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
