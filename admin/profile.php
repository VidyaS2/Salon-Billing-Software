<?php
session_start();

include '../connect.php'; // Include your database connection file

// Check if the user is logged in, if not, redirect to the registration page
if (!isset($_SESSION['username'])) {
    header("Location: register.php");
    exit;
}

$username = $_SESSION['username'];

// Fetch user details from the employee_details table
$sql = "SELECT ed.id, ed.name, ed.email, ed.mobile, ed.address, ed.role 
        FROM employee_details ed
        JOIN register r ON ed.email = r.email
        WHERE r.username = ?";

if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("s", $username);
    if ($stmt->execute()) {
        $stmt->bind_result($id,$name, $email, $mobile, $address, $role);
        $stmt->fetch();
        $stmt->close();
    } else {
        // Handle database error
        echo "Error fetching user details: " . $conn->error;
        exit;
    }
} else {
    // Handle database error
    echo "Error preparing statement: " . $conn->error;
    exit;
}

// Check if an AJAX request for fetching user details is made
if(isset($_GET['fetch_user_details'])) {
    // Check if the ID parameter is set in the request
    if(isset($_GET['id'])) {
        $id = $_GET['id'];

        // Prepare and execute a SELECT query to fetch user details
        $sql = "SELECT * FROM employee_details WHERE id = ?";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("i", $id);
            if ($stmt->execute()) {
                $result = $stmt->get_result();
                if ($result->num_rows > 0) {
                    // Fetch user details as an associative array
                    $userDetails = $result->fetch_assoc();
                    // Return user details as JSON
                    echo json_encode($userDetails);
                } else {
                    // No user found with the provided ID
                    echo json_encode(array('error' => 'User not found'));
                }
            } else {
                // Error executing query
                echo json_encode(array('error' => 'Error executing query'));
            }
            $stmt->close();
        } else {
            // Error preparing statement
            echo json_encode(array('error' => 'Error preparing statement'));
        }
    } else {
        // ID parameter not provided
        echo json_encode(array('error' => 'ID parameter not provided'));
    }
    exit; // Exit after handling AJAX request
}


//services list


?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8"/>
  <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
  <meta name="description" content=""/>
  <meta name="author" content=""/>
  <title>Salon - Profile</title>
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

      <div class="row mt-3">
        <!-- <div class="col-lg-4">
           <div class="card profile-card-2">
            <div class="card-img-block">
                <img class="img-fluid" src="https://via.placeholder.com/800x500" alt="Card image cap">
            </div>
            <div class="card-body pt-5">
                <img src="https://via.placeholder.com/110x110" alt="profile-image" class="profile">
                <h5 class="card-title">Mark Stern</h5>
                <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                <div class="icon-block">
                  <a href="javascript:void();"><i class="fa fa-facebook bg-facebook text-white"></i></a>
				  <a href="javascript:void();"> <i class="fa fa-twitter bg-twitter text-white"></i></a>
				  <a href="javascript:void();"> <i class="fa fa-google-plus bg-google-plus text-white"></i></a>
                </div>
            </div>

            <div class="card-body border-top border-light">
                 <div class="media align-items-center">
                   <div>
                       <img src="../assets/images/timeline/html5.svg" class="skill-img" alt="skill img">
                   </div>
                     <div class="media-body text-left ml-3">
                       <div class="progress-wrapper">
                         <p>HTML5 <span class="float-right">65%</span></p>
                         <div class="progress" style="height: 5px;">
                          <div class="progress-bar" style="width:65%"></div>
                         </div>
                        </div>                   
                    </div>
                  </div>
                  <hr>
                  <div class="media align-items-center">
                   <div><img src="../assets/images/timeline/bootstrap-4.svg" class="skill-img" alt="skill img"></div>
                     <div class="media-body text-left ml-3">
                       <div class="progress-wrapper">
                         <p>Bootstrap 4 <span class="float-right">50%</span></p>
                         <div class="progress" style="height: 5px;">
                          <div class="progress-bar" style="width:50%"></div>
                         </div>
                        </div>                   
                    </div>
                  </div>
                   <hr>
                  <div class="media align-items-center">
                   <div><img src="../assets/images/timeline/angular-icon.svg" class="skill-img" alt="skill img"></div>
                     <div class="media-body text-left ml-3">
                       <div class="progress-wrapper">
                         <p>AngularJS <span class="float-right">70%</span></p>
                         <div class="progress" style="height: 5px;">
                          <div class="progress-bar" style="width:70%"></div>
                         </div>
                        </div>                   
                    </div>
                  </div>
                    <hr>
                  <div class="media align-items-center">
                   <div><img src="../assets/images/timeline/react.svg" class="skill-img" alt="skill img"></div>
                     <div class="media-body text-left ml-3">
                       <div class="progress-wrapper">
                         <p>React JS <span class="float-right">35%</span></p>
                         <div class="progress" style="height: 5px;">
                          <div class="progress-bar" style="width:35%"></div>
                         </div>
                        </div>                   
                    </div>
                  </div>
                  
              </div>
        </div> -->

        </div>

        <div class="col-lg-12">
           <div class="card">
            <div class="card-body">
            <ul class="nav nav-tabs nav-tabs-primary top-icon nav-justified">
                <li class="nav-item">
                    <a href="javascript:void();" data-target="#profile" data-toggle="pill" class="nav-link active"><i class="icon-user"></i> <span class="hidden-xs">Profile</span></a>
                </li>
                <!-- <li class="nav-item">
                    <a href="javascript:void();" data-target="#messages" data-toggle="pill" class="nav-link"><i class="icon-envelope-open"></i> <span class="hidden-xs">Messages</span></a>
                </li> -->
                <!-- <li class="nav-item">
                <a href="javascript:void();" data-target="#edit" data-toggle="pill" class="nav-link"><i class="icon-note"></i > <span class="hidden-xs">Edit</span></a>    
                          </li> -->
            </ul>
            <div class="tab-content p-3">
                <div class="tab-pane active" id="profile">
                  <h4 style="text-align:center; text-transform:capitalize">Welcome <?php echo htmlspecialchars($name); ?>! </h4>
                    <h5 class="mb-3">User Profile</h5>

                    <div class="row">
                        <div class="col-md-12">
                      <table class="table">
                    
                    <tr>
                      <th scope="col">id</th>
                      <th scope="col">Name</th>
                      <th scope="col">Mobile</th>
                      <th scope="col">Email</th>
                      <th scope="col">Address</th>
                      <th scope="col">Role</th>
                      <th scope="col">Edit</th>
                    </tr>
                  
                  <tbody>
                   
                      <tr>
                        <td ><?php echo htmlspecialchars($id); ?></td>
                        <td style="text-transform:capitalize"><?php echo htmlspecialchars($name); ?></td>
                        <td><?php echo htmlspecialchars($mobile); ?></td>
                        <td><?php echo htmlspecialchars($email); ?></td>
                        <td style="text-transform:capitalize"><?php echo htmlspecialchars($address); ?></td>
                        <td style="text-transform:capitalize"><?php echo htmlspecialchars($role); ?></td>
                        <td>
                        <a href="#edit" data-toggle="pill" class="edit-link" >
                                <i class="icon-note"></i> <span class="hidden-xs">Edit</span>
                            </a>
                        </td>
                      </tr>
                    <tr>

                    <td>
                    </tr>
                  </tbody>
                      </table>
                        </div>
                       
                       
                    </div>
                    
                   
                    <!--/row-->

        
                    
                </div>
             
                <div class="tab-pane" id="edit">
          
 
        <form id="updateForm" action="profile_update.php" method="post">

    <input type="hidden" id="editUserId" name="id" value="<?php echo htmlspecialchars($id); ?>">
    <div class="form-group row">
        <label class="col-lg-3 col-form-label form-control-label">Name</label>
        <div class="col-lg-9">
            <input class="form-control" type="text" name="name" value="<?php echo htmlspecialchars($name); ?>" readonly>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-lg-3 col-form-label form-control-label">Email</label>
        <div class="col-lg-9">
            <input class="form-control" type="email" name="email" value="<?php echo htmlspecialchars($email); ?>">
        </div>
    </div>
    <div class="form-group row">
        <label class="col-lg-3 col-form-label form-control-label">Mobile</label>
        <div class="col-lg-9">
            <input type="text" class="form-control form-control-rounded" id="input-8" name="mobile" placeholder="Enter Mobile Number" 
           pattern="[0-9]{10}" title="Mobile number must be exactly 10 digits" required>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-lg-3 col-form-label form-control-label">Address</label>
        <div class="col-lg-9">
            <input class="form-control" type="text" name="address" value="<?php echo htmlspecialchars($address); ?>">
        </div>
    </div>
    <div class="form-group row">
        <label class="col-lg-3 col-form-label form-control-label">Role</label>
        <div class="col-lg-9">
            <input class="form-control" type="text" name="role" value="<?php echo htmlspecialchars($role); ?>">
        </div>
    </div>
    <div class="form-group row">
        <div class="col-lg-9 offset-lg-3">
            <input type="submit" class="btn btn-primary" value="Update">
        </div>
    </div>
</form>


                </div>
            </div>

            
        </div>
      </div>
      </div>
        
    </div>

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
	

   
  </div><!--End wrapper-->

  <script>
document.addEventListener('DOMContentLoaded', function() {
    var editButtons = document.querySelectorAll('.edit-link');
    var updateForm = document.getElementById('updateForm');

    editButtons.forEach(function(editButton) {
        editButton.addEventListener('click', function(event) {
            event.preventDefault(); // Prevent default link behavior
            
            // Get data attributes from the edit button
            var id = editButton.dataset.id;

            // AJAX request to fetch details from employee_details table
            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        var response = JSON.parse(xhr.responseText);
                        // Populate form fields with fetched data
                        updateForm.querySelector('#editUserId').value = response.id;
                        updateForm.querySelector('[name="name"]').value = response.name;
                        updateForm.querySelector('[name="email"]').value = response.email;
                        updateForm.querySelector('[name="mobile"]').value = response.mobile;
                        updateForm.querySelector('[name="address"]').value = response.address;
                        updateForm.querySelector('[name="role"]').value = response.role;
                    } else {
                        console.error('Error fetching user details');
                    }
                }
            };
            xhr.open('GET', 'fetch_user_details.php?id=' + id, true);
            xhr.send();
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
