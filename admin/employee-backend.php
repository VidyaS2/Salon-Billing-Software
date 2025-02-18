<?php
// Start session
session_start();

// Include database connection
include("../connect.php");

// Check if the form is submitted
if (isset($_POST['employee'])) {
    // Get user input and sanitize it
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $mobile = mysqli_real_escape_string($conn, $_POST['mobile']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $role = mysqli_real_escape_string($conn, $_POST['role']);

    // Prepare the SQL statement to insert data into the database
    $sql = "INSERT INTO employee_details (name, email, mobile, address, role) VALUES (?, ?, ?, ?, ?)";
    $stmt = mysqli_stmt_init($conn);

    if (mysqli_stmt_prepare($stmt, $sql)) {
        // Bind parameters
        mysqli_stmt_bind_param($stmt, "sssss", $name, $email, $mobile, $address, $role);

        // Execute the statement
        if (mysqli_stmt_execute($stmt)) {
            // Data inserted successfully
            header("Location: employees.php?success=Registration successful");
            exit();
        } else {
            // Error occurred while executing the statement
            header("Location: employees.php?error=Not Registered: " . mysqli_stmt_error($stmt));
            exit();
        }
    } else {
        // Error occurred while preparing the statement
        header("Location: employees.php?error=Preparation failed: " . mysqli_error($conn));
        exit();
    }

    // Close the statement
    mysqli_stmt_close($stmt);
}

// Close the database connection
mysqli_close($conn);
?>
