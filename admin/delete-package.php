<?php
// Start session (if needed)
session_start();

// Include database connection
include("../connect.php");

// Ensure package_id is provided and is numeric
if (isset($_GET['package_id']) && is_numeric($_GET['package_id'])) {
    $packageId = $_GET['package_id'];

    // Delete package from packages table
    $deletePackageSql = "DELETE FROM packages WHERE id = $packageId";
    if ($conn->query($deletePackageSql) === TRUE) {
        // Delete associated services from package_services table
        $deleteServicesSql = "DELETE FROM package_services WHERE package_id = $packageId";
        if ($conn->query($deleteServicesSql) === TRUE) {
            // Redirect back to packagedetails.php or any desired page
            header("Location: packagedetails.php");
            exit();
        } else {
            echo "Error deleting services: " . $conn->error;
        }
    } else {
        echo "Error deleting package: " . $conn->error;
    }

    // Close database connection
    $conn->close();
} else {
    echo "Invalid package ID";
}
?>
