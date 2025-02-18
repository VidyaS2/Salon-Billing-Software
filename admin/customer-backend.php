<?php
// Start session
session_start();

// Include database connection
include("../connect.php");

// Initialize variables for messages
$success_message = "";
$error_message = "";

// Fetch amount from customer_details table
$fetch_sql = "SELECT amount FROM customer_details";
$result = mysqli_query($conn, $fetch_sql);
$sub_total = 0; // Initialize sub total
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $sub_total += $row['amount'];
    }
} else {
    // Error occurred while fetching amount
    $error_message = "Error: " . mysqli_error($conn);
    header("Location: customer.php?error=$error_message");
    exit();
}

// Fetch stylist name from customer_details table
$stylist_name = "";
$fetch_stylist_sql = "SELECT stylist FROM customer_details LIMIT 1"; // Assuming only one stylist is needed
$stylist_result = mysqli_query($conn, $fetch_stylist_sql);
if ($stylist_result && mysqli_num_rows($stylist_result) > 0) {
    $stylist_row = mysqli_fetch_assoc($stylist_result);
    $stylist_name = $stylist_row['stylist'];
}

// Check if the form is submitted
if (isset($_POST['customer'])) {
    // Get user input
    $name = $_POST['name'];
    $email = $_POST['email'];
    $mobile = $_POST['mobile'];
    $stylist = $_POST['stylist'];
    $services = $_POST['services']; // This is now an array
    $service_amounts = $_POST['service_amounts']; // Array of service amounts
    $service_qtys = $_POST['service_qty']; // Array of service quantities
    $service_totals = $_POST['service_total']; // Array of service totals
    $amount = $_POST['amount'];

    // Prepare the SQL statement to insert data into the customer_details table
    $sql = "INSERT INTO customer_details (name, email, mobile, stylist, amount) VALUES (?, ?, ?, ?, ?)";
    $stmt = mysqli_stmt_init($conn);

    if (mysqli_stmt_prepare($stmt, $sql)) {
        // Bind parameters
        mysqli_stmt_bind_param($stmt, "ssssd", $name, $email, $mobile, $stylist, $amount);

        // Execute the statement
        if (mysqli_stmt_execute($stmt)) {
            // Get the last inserted customer ID
            $customer_id = mysqli_insert_id($conn);

            // Prepare the SQL statement to insert data into the services table
            $service_sql = "INSERT INTO services (customer_id, service_name, service_amount, service_qty, service_total) VALUES (?, ?, ?, ?, ?)";
            $service_stmt = mysqli_stmt_init($conn);

            if (mysqli_stmt_prepare($service_stmt, $service_sql)) {
                // Loop through services array
                for ($i = 0; $i < count($services); $i++) {
                    // Bind parameters
                    mysqli_stmt_bind_param($service_stmt, "isddd", $customer_id, $services[$i], $service_amounts[$i], $service_qtys[$i], $service_totals[$i]);

                    // Execute the statement
                    if (!mysqli_stmt_execute($service_stmt)) {
                        // Error occurred while executing the statement
                        $error_message = "Error: " . mysqli_error($conn);
                        header("Location: customer.php?error=$error_message");
                        exit();
                    }
                }
            } else {
                // Error occurred while preparing the service statement
                $error_message = "Error: " . mysqli_error($conn);
                header("Location: customer.php?error=$error_message");
                exit();
            }

            // All data inserted successfully
            header("Location: customer.php?success=Registration successful");
            exit();
        } else {
            // Error occurred while executing the statement
            $error_message = "Error: " . mysqli_error($conn);
            header("Location: customer.php?error=$error_message");
            exit();
        }
    } else {
        // Error occurred while preparing the statement
        $error_message = "Error: " . mysqli_error($conn);
        header("Location: customer.php?error=$error_message");
        exit();
    }

    // Close the statements
    mysqli_stmt_close($stmt);
    mysqli_stmt_close($service_stmt);
}

// Initialize response array
$response = array('exists' => false);

// Check if mobile number is provided
if (isset($_GET['mobile'])) {
    // Get mobile number from GET parameters
    $mobile = $_GET['mobile'];

    // Query the database to check if the mobile number exists
    $query = "SELECT name, email FROM your_table_name WHERE mobile = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $mobile);
    $stmt->execute();
    $result = $stmt->get_result();

    // Prepare response data
    $response = array();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $response['exists'] = true;
        $response['name'] = $row['name'];
        $response['email'] = $row['email'];
    } else {
        $response['exists'] = false;
    }

    // Send JSON response
    header('Content-Type: application/json');
    echo json_encode($response);

    // Close the database connection
    $stmt->close();
    $conn->close();
} else {
    // Invalid request
    echo "Invalid request";
} 


// Close the database connection
mysqli_close($conn);
?>
