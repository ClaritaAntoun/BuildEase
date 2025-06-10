
<!-- Clarita Antoun -->
<?php
session_start();
ob_start();
include 'conx.php';

if (!isset($_SESSION['homeOwner_identity'])) {
    header("Location: logInPage.php");
    exit();
}

$id = $_SESSION['homeOwner_identity']['id'];

// Handle profile update POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullName   = $_POST['fullName'];
    $email      = $_POST['email'];
    $phone      = $_POST['phoneNumber'];
    $street     = $_POST['street'];
    $city       = $_POST['city'];
    $state      = $_POST['state'];
    $postalCode = $_POST['postalCode'];

    // Update homeowner details
    $stmt = $conn->prepare("UPDATE homeowner SET fullName = ?, email = ?, phoneNumber = ? WHERE id = ?");
    $stmt->bind_param("sssi", $fullName, $email, $phone, $id);
    $stmt->execute();
    $stmt->close();

    // Update address details
    $stmt = $conn->prepare("
        UPDATE address 
        INNER JOIN homeowner ON homeowner.addressID = address.addressID 
        SET street = ?, city = ?, state = ?, postalCode = ? 
        WHERE homeowner.id = ?
    ");
    $stmt->bind_param("ssssi", $street, $city, $state, $postalCode, $id);
    $stmt->execute();
    $stmt->close();

    header('Location: homeOwnerPage.php');
    exit();
}

// Fetch homeowner and address data
$sql = "SELECT * FROM homeowner, address 
        WHERE homeowner.addressID = address.addressID 
        AND homeowner.id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$stmt->close();
?>