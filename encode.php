<?php
// encode.php

require 'config.php';

function encryptURL($url, $key) {
    $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));
    $encrypted = openssl_encrypt($url, 'aes-256-cbc', $key, 0, $iv);
    return base64_encode($encrypted . '::' . $iv);
}

// Example usage
$original_url = "https://example.com/path/to/resource?query=param";
$encrypted_url = encryptURL($original_url, ENCRYPTION_KEY);

echo "Original URL: " . $original_url . "\n";
echo "Encrypted URL: " . $encrypted_url . "\n";
?>