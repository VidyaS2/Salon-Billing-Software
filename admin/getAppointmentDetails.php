<?php
include '../connect.php';

// Check if eventId is set and not empty
if(isset($_GET['eventId']) && !empty($_GET['eventId'])) {
    // Sanitize eventId to prevent SQL injection
    $eventId = mysqli_real_escape_string($conn, $_GET['eventId']);

    // Query to fetch appointment details based on eventId
    $sql = "SELECT * FROM appointments WHERE id = '$eventId'";
    $result = mysqli_query($conn, $sql);

    // Check if query executed successfully
    if($result) {
        // Check if at least one row is returned
        if(mysqli_num_rows($result) > 0) {
            // Fetch appointment details
            $appointmentDetails = mysqli_fetch_assoc($result);

            // Output appointment details as JSON
            echo json_encode($appointmentDetails);
        } else {
            echo json_encode(['error' => 'No appointment details found for this event.']);
        }
    } else {
        echo json_encode(['error' => mysqli_error($conn)]);
    }
} else {
    echo json_encode(['error' => 'Event ID not provided.']);
}

// Close database connection
mysqli_close($conn);

?>
