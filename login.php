<?php
// Start session
session_start();

// Enable error reporting for debugging (optional)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include database connection
include("connect.php");

// Check if the form is submitted
if (isset($_POST['login'])) {
    // Get user input
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Validate and sanitize user input (prevent SQL injection)
    $email = mysqli_real_escape_string($conn, $email);
    $password = mysqli_real_escape_string($conn, $password);

    // Prepare the SQL query to fetch the user based on email
    $sql = "SELECT * FROM register WHERE email = ?";
    $stmt = mysqli_stmt_init($conn);

    if (mysqli_stmt_prepare($stmt, $sql)) {
        // Bind parameters for the prepared statement
        mysqli_stmt_bind_param($stmt, "s", $email);

        // Execute the prepared statement
        mysqli_stmt_execute($stmt);

        // Get the result set from the executed statement
        $result = mysqli_stmt_get_result($stmt);

        // Check if a row with the given email exists
        if ($row = mysqli_fetch_assoc($result)) {
            // Verify the password
            if (password_verify($password, $row['password'])) {
                // Password is correct, set session variables
                $_SESSION['username'] = $row['username'];
                $_SESSION['role'] = $row['role']; // Assuming 'role' column in the table

                // Redirect based on user role
                if ($_SESSION['role'] === 'admin') {
                    header("Location: admin/dashboard.php?success=Login successful");
                    exit();
                } elseif ($_SESSION['role'] === 'staff') {
                    header("Location: staff/dashboard.php?success=Login successful");
                    exit();
                } else {
                    // Invalid role (handle as needed)
                    header("Location: index.php?error=Invalid role");
                    exit();
                }
            } else {
                // Incorrect password
                header("Location: index.php?error=Incorrect password");
                exit();
            }
        } else {
            // Email not found
            header("Location: index.php?error=Email not found");
            exit();
        }

        // Close the prepared statement
        mysqli_stmt_close($stmt);
    } else {
        // SQL statement preparation failed
        $error_message = mysqli_stmt_error($stmt);
        header("Location: index.php?error=SQL statement preparation failed: $error_message");
        exit();
    }
}

// Close the database connection
mysqli_close($conn);
?>
