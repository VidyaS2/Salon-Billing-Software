<?php
// decode.php

require 'config.php';

function decryptURL($encoded_url, $key) {
    list($encrypted_data, $iv) = explode('::', base64_decode($encoded_url), 2);
    return openssl_decrypt($encrypted_data, 'aes-256-cbc', $key, 0, $iv);
}

// Example usage
$encrypted_url = "your-encrypted-url"; // Replace with an actual encrypted URL
$decrypted_url = decryptURL($encrypted_url, ENCRYPTION_KEY);

echo "Encrypted URL: " . $encrypted_url . "\n";
echo "Decrypted URL: " . $decrypted_url . "\n";
?>