<?php
// Start session
session_start();

// Include database connection
include("../connect.php");
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Processing form data when form is submitted
if (isset($_POST['package'])) {
    // Escape user inputs for security (assuming you're not using prepared statements here for simplicity)
    $packageName = $conn->real_escape_string($_POST['packageName']);
    $packageAmount = $conn->real_escape_string($_POST['packageAmount']);
    $services = $_POST['services']; // Array of service names

    // Insert package into packages table
    $insertPackageSql = "INSERT INTO packages (packageName, packageAmount) VALUES ('$packageName', '$packageAmount')";

    if ($conn->query($insertPackageSql) === TRUE) {
        $package_id = $conn->insert_id; // Get the auto-generated package ID

        // Insert services into package_services table
        foreach ($services as $service_name) {
            $insertPackageServiceSql = "INSERT INTO package_services (package_id, service_name) VALUES ('$package_id', '$service_name')";
            $conn->query($insertPackageServiceSql);
        }

        // Close the database connection
        mysqli_close($conn);

        // Redirect to packagedetails.php with a success message or any additional data if needed
        header("Location: packagedetails.php?package_id=$package_id");
        exit();
    } else {
        echo "Error: " . $insertPackageSql . "<br>" . $conn->error;
    }
}
?>
