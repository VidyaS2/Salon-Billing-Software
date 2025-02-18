<?php
include '../connect.php';

// Perform SQL query to retrieve appointments
$sql = "SELECT appointment_date, name FROM appointments";
$result = $conn->query($sql);

$appointments = [];

if ($result->num_rows > 0) {
  while($row = $result->fetch_assoc()) {
    $appointment = [
      'title' => $row['name'], // Set the appointment name as the title
      'start' => $row['appointment_date'], // Assuming 'appointment_date' is the column name in your database
      'end' => $row['appointment_date'] // If appointments have an end time, you can set it here as well
    ];
    $appointments[] = $appointment;
  }
}

// Close database connection
$conn->close();

// Send JSON response
header('Content-Type: application/json');
echo json_encode($appointments);
?>
