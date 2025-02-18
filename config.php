<?php
// config.php

// Function to generate a random 32-byte key
function generateEncryptionKey() {
    return bin2hex(openssl_random_pseudo_bytes(16)); // 16 bytes * 2 (hex) = 32 characters
}

// Path to the file where the encryption key will be stored
$key_file = 'encryption_key.txt';

// Check if the key file exists
if (!file_exists($key_file)) {
    // Generate a new key
    $encryption_key = generateEncryptionKey();
    // Save the key to the file
    file_put_contents($key_file, $encryption_key);
} else {
    // Read the key from the file
    $encryption_key = file_get_contents($key_file);
}

// Define the encryption key constant
define('ENCRYPTION_KEY', $encryption_key);

?>
