<?php
// Start session
session_start();

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include database connection
include("connect.php");

// Check if the form is submitted
if (isset($_POST['aptform'])) {
    // Get form values
    $date = date('Y-m-d', strtotime($_POST['date'])); // Format date as yyyy-mm-dd
    $name = $_POST['name'];
    $mobile = $_POST['mobile'];
    $email = $_POST['email'];
    $status = 'pending'; // Set default status
    $service = $_POST['service'];

    // Prepare the SQL statement for the appointments table
    $stmt_appointments = $conn->prepare("INSERT INTO appointments (appointment_date, name, mobile, email, service) VALUES (?, ?, ?, ?, ?)");
    $stmt_appointments->bind_param("sssss", $date, $name, $mobile, $email, $service);

    // Execute the prepared statement
    if (!$stmt_appointments->execute()) {
        echo "Error inserting into appointments table: " . $stmt_appointments->error;
        exit();
    }

    // Redirect to appointment.php after successful insertion
    header("Location: appointment.php");
    exit();
} else {
    echo "Invalid request";
}

// Close the prepared statement
$stmt_appointments->close();

// Close the database connection
$conn->close();
?>
