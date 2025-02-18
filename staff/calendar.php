<?php

session_start();

include '../connect.php';

$sql = "SELECT appointment_date, name, id, service, status FROM appointments";

$result = $conn->query($sql);

$appointments = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        if (isset($row['id'])) {
            $appointment = [
                'id' => $row['id'], // Include appointment ID
                'title' => $row['name'],
                'start' => $row['appointment_date'],
                'end' => $row['appointment_date'],
                'status' => $row['status'] // Include appointment status
            ];
            $appointments[] = $appointment;
        } else {
            // Handle cases where 'id' is not set in the database query result
        }
    }
}

// Close database connection
$conn->close();

// Encode appointments array to JSON
$appointmentsJson = json_encode($appointments);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Salon - Calendar</title>
    <!-- loader-->
    <link href="../assets/css/pace.min.css" rel="stylesheet" />
    <script src="../assets/js/pace.min.js"></script>
    <!--favicon-->
    <link rel="icon" href="../assets/images/favicon.ico" type="image/x-icon">
    <!--Full Calendar Css-->
    <link href="../assets/plugins/fullcalendar/css/fullcalendar.min.css" rel='stylesheet' />
    <!-- simplebar CSS-->
    <link href="../assets/plugins/simplebar/css/simplebar.css" rel="stylesheet" />
    <!-- Bootstrap core CSS-->
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet" />
    <!-- animate CSS-->
    <link href="../assets/css/animate.css" rel="stylesheet" type="text/css" />
    <!-- Icons CSS-->
    <link href="../assets/css/icons.css" rel="stylesheet" type="text/css" />
    <!-- Sidebar CSS-->
    <link href="../assets/css/sidebar-menu.css" rel="stylesheet" />
    <!-- Custom Style-->
    <link href="../assets/css/app-style.css" rel="stylesheet" />

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

                <div class="mt-3">
                    <div id='calendar'></div>
                </div>

            </div>
            <!-- End container-fluid-->
            <!-- <div id="eventDetailsModal" class="modal fade" role="dialog">
                <div class="modal-dialog">
                   
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" style="color: black;">Event Details</h4>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>
                        <div class="modal-body">
                            <div class="modal-body">
                                <p id="name" style="color: black;"></p>
                                <p id="date" style="color: black;"></p>

                                <p id="mobile" style="color: black;"></p>
                                <p id="email" style="color: black;"></p>
                                <p id="service" style="color: black;"></p>
                            </div>

                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-success" id="approveButton">Approve</button>
                            <button type="button" class="btn btn-danger" id="cancelButton">Cancel</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div> -->
        </div><!-- End content-wrapper-->

        <!--Start Back To Top Button-->
        <a href="javaScript:void();" class="back-to-top"><i class="fa fa-angle-double-up"></i> </a>
        <!--End Back To Top Button-->

        <!--Start footer-->
        <?php include 'footer.php'; ?>
        <!--End footer-->

    </div><!-- End wrapper-->

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

    <!-- FullCalendar JS -->
    <script src='https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js'></script>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.js'></script>

    <script>
$(document).ready(function() {
    $('#calendar').fullCalendar({
        header: {
            left: 'prev,next today',
            center: 'title',
            right: 'month,agendaWeek,agendaDay'
        },
        // Pass appointments data to FullCalendar
        events: <?php echo json_encode($appointments); ?>,
        eventRender: function(event, element) {
            // Define colors based on event status
            var eventColor = '#ffc107'; // Default color (warning color)
            if (event.status === 'approved') {
                eventColor = '#28a745'; // Green color for approved events
            }
            
            // Set background color of the event element
            element.css('background-color', eventColor);
            
            // Add click event listener to the event title
            element.find('.fc-title').click(function() {
                var eventId = event.id; // Access event ID from the event object
                // Send AJAX request to fetch appointment details
                $.ajax({
                    url: 'getAppointmentDetails.php',
                    type: 'GET',
                    data: {
                        eventId: eventId
                    },
                    success: function(response) {
                        console.log(response); // Log the response to check if the data is correct
                        // Populate the modal with fetched appointment details
                        $('#eventDetailsModal').modal('show');
                        // Update modal content with appointment details
                        $('#name').text(response.name);
                        $('#date').text(response.appointment_date);
                        $('#mobile').text(response.mobile);
                        $('#email').text(response.email);
                        $('#service').text(response.service);
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                        alert('Error occurred while fetching appointment details.');
                    }
                });
            });
        }
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