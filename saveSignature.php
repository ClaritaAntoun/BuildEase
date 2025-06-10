

<!-- Clarita Antoun -->
<?php
session_start();
include 'conx.php';

// Get posted data
$contract_id = $_POST['contractID'] ?? null;
$signature = $_POST['signature'] ?? null;

if ($contract_id && $signature) {
    $image_data = base64_decode(explode(',', $signature)[1]);

    // Ensure the folder exists
    if (!is_dir('signatures')) {
        mkdir('signatures', 0755, true);
    }

    // Save the signature file
    $filename = "signatures/contract_{$contract_id}.png";
    file_put_contents($filename, $image_data);

    // Update the database
    $stmt = $conn->prepare("UPDATE contract SET signature = ? WHERE contractID = ?");
    $stmt->bind_param("si", $filename, $contract_id);
    $stmt->execute();

    echo "Signature saved!";
    
} else {
    echo "Missing contract ID or signature.";
}
?>
