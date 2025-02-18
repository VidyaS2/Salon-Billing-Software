// Initialize company details
$companyName = $address = $mobile = $email = '';
$logoPath = ''; // Variable to store logo path

// Fetch company details from the business table
$sql = "SELECT name, address, mobile, email, logo FROM business LIMIT 1";

$result = mysqli_query($conn, $sql);

// Debug: Check if the query executed successfully
if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}

if (mysqli_num_rows($result) > 0) {
    // Fetch data from the result set
    $row = mysqli_fetch_assoc($result);
    $companyName = $row['name'];
    $address = $row['address'];
    $mobile = $row['mobile'];
    $email = $row['email'];
    $logoPath = $row['logo']; // Fetch the logo path

    // Debug: Display fetched data
    // Debug statement for logo path
} else {
    // Handle case where no data is found (optional)
    $companyName = 'Company Name Not Found';
    $address = 'Address Not Found';
    $mobile = 'Mobile Not Found';
    $email = 'Email Not Found';

    // Debug: Indicate no data found
    echo "No company data found\n";
}