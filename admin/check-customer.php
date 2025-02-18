<?php
header('Content-Type: application/json');

session_start();

// Include database connection
include("../connect.php");

if (isset($_GET['mobile'])) {
    $mobile = $_GET['mobile'];

    // Prepare and bind
    $stmt = $conn->prepare("SELECT * FROM customer_details WHERE mobile = ?");
    $stmt->bind_param("s", $mobile);

    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $customer = $result->fetch_assoc();
        echo json_encode(['exists' => true, 'customer' => $customer]);
    } else {
        echo json_encode(['exists' => false]);
    }

    $stmt->close();
}

$conn->close();
?>
