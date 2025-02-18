<?php
// Include your database connection code
include '../connect.php';

// Check if eventId is set and not empty
if(isset($_GET['eventId']) && !empty($_GET['eventId'])) {
    // Sanitize eventId to prevent SQL injection
    $eventId = mysqli_real_escape_string($conn, $_GET['eventId']);

    // Query to fetch customer details based on eventId
    $sql = "SELECT * FROM appointments WHERE id = '$eventId'";
    $result = mysqli_query($conn, $sql);

    // Check if query executed successfully
    if($result) {
        // Check if at least one row is returned
        if(mysqli_num_rows($result) > 0) {
            // Fetch customer details
            $customerDetails = mysqli_fetch_assoc($result);

            // Construct HTML markup for displaying customer details
            $html = '<p><strong>Name:</strong> ' . $customerDetails['name'] . '</p>';
            $html .= '<p><strong>Email:</strong> ' . $customerDetails['email'] . '</p>';
            $html .= '<p><strong>Mobile:</strong> ' . $customerDetails['mobile'] . '</p>';
            // Add more details as needed

            // Output HTML markup
            echo $html;
        } else {
            echo "No customer details found for this event.";
        }
    } else {
        echo "Error: " . mysqli_error($conn);
    }
} else {
    echo "Error: Event ID not provided.";
}

// Close database connection
mysqli_close($conn);
?>
