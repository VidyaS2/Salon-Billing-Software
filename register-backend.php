<?php
// Start session
session_start();

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include database connection
include("connect.php");

// Check if the form is submitted
if (isset($_POST['register'])) {
    // Get user input
    $name = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];
    $role = $_POST['role'];

    // Validate and sanitize user input
    $name = mysqli_real_escape_string($conn, $name);
    $email = mysqli_real_escape_string($conn, $email);
    $password = mysqli_real_escape_string($conn, $password);
    $role = mysqli_real_escape_string($conn, $role);

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    // Prepare the SQL query
    $sql = "INSERT INTO register (username, email, password, role) VALUES ('$name', '$email', '$hashed_password', '$role')";

    // Execute the query
    if (mysqli_query($conn, $sql)) {
        // Check if the user role is admin
        if ($role === 'admin') {
            // Redirect to the business form page for admin
            header("Location: business-form.php?success=Registration successful. Please fill in your business details.");
        } else {
            // Redirect to a success page for non-admin users
            header("Location: index.php?success=Registration successful. You can now log in.");
        }
        exit();
    } else {
        // Capture the specific error
        $error_message = mysqli_error($conn);
        header("Location: register.php?error=Registration failed: $error_message");
        exit();
    }
}

// Close the database connection
mysqli_close($conn);
?>
