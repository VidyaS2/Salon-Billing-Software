<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include database connection
include("connect.php");

// Check if the form is submitted
if (isset($_POST['employee'])) {
    // Get user input
    $companyName = isset($_POST['name']) ? mysqli_real_escape_string($conn, $_POST['name']) : '';
    $email = isset($_POST['email']) ? mysqli_real_escape_string($conn, $_POST['email']) : '';
    $mobile = isset($_POST['mobile']) ? mysqli_real_escape_string($conn, $_POST['mobile']) : '';
    $address = isset($_POST['address']) ? mysqli_real_escape_string($conn, $_POST['address']) : '';

    // Handle file upload
    if (isset($_FILES['logo'])) {
        $logo = $_FILES['logo']['name'];
        $logoTmpName = $_FILES['logo']['tmp_name'];

        // Move uploaded file to uploads directory
        $uploadDir = 'uploads/';
        $logoPath = $uploadDir . $logo;

        if (move_uploaded_file($logoTmpName, $logoPath)) {
            // File moved successfully, proceed with database insertion
            $sql = "INSERT INTO business (name, email, mobile, address, logo) VALUES ('$companyName', '$email', '$mobile', '$address', '$logo')";

            if (mysqli_query($conn, $sql)) {
                // Redirect to success page
                header("Location: index.php?success=Business registered successfully.");
                exit();
            } else {
                // SQL query execution failed
                $error_message = mysqli_error($conn);
                header("Location: business-form.php?error=Registration failed: $error_message");
                exit();
            }
        } else {
            // File upload failed
            header("Location: business-form.php?error=Error uploading file.");
            exit();
        }
    } else {
        // No logo file uploaded
        header("Location: business-form.php?error=Please upload a logo file.");
        exit();
    }
}

// Close database connection
mysqli_close($conn);
?>
