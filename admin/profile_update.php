<?php
session_start();

include '../connect.php'; // Include your database connection file

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect the updated user details from the form
    $id = $_POST['id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $mobile = $_POST['mobile'];
    $address = $_POST['address'];
    $role = $_POST['role'];

    // Update the user details in the database
    $sql = "UPDATE employee_details SET name=?, email=?, mobile=?, address=?, role=? WHERE id=?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("sssssi", $name, $email, $mobile, $address, $role, $id);
        if ($stmt->execute()) {
            // Redirect back to the profile page
            header("Location: profile.php");
            exit;
        } else {
            // Handle database error
            echo "Error updating user details: " . $stmt->error;
            exit;
        }
    } else {
        // Handle database error
        echo "Error preparing statement: " . $conn->error;
        exit;
    }
} else {
    // If the form is not submitted via POST method, redirect back to the profile page
    header("Location: profile.php");
    exit;
}
?>
