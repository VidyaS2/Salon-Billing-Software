<?php
include("../connect.php");

// Check if all required data is received via POST request
if(isset($_POST['name'], $_POST['appointment_date'], $_POST['status'])) {
    // Sanitize data to prevent SQL injection
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $appointment_date = mysqli_real_escape_string($conn, $_POST['appointment_date']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);

    // Update status in the database
    $sql = "UPDATE appointments SET status = '$status' WHERE name = '$name' AND appointment_date = '$appointment_date'";
    if ($conn->query($sql) === TRUE) {
        // Status updated successfully, redirect to appointment.php
        header("Location: appointment.php");
        exit(); // Ensure script stops execution after redirect
    } else {
        echo "Error updating status: " . $conn->error;
    }
} else {
    echo "Invalid request";
}

$conn->close();
?>