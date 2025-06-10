
<!-- Clarita Antoun -->
<?php
session_start();
ob_start();
if (!isset($_SESSION['contractor_identity'])) {
    header("Location: logInPage.php");
    exit();
}
include 'conx.php';

if (isset($_SESSION['contractor_identity']['id'])) {
    $id = $_SESSION['contractor_identity']['id'];
    $fullName = $_POST['fullName'];
    $email = $_POST['email'];
    $phoneNumber = $_POST['phoneNumber'];
    $street = $_POST['street'];
    $city = $_POST['city'];
    $state = $_POST['state'];
    $postalCode = $_POST['postalCode'];

    $experiences = $_POST['experiences'];
    $languages = $_POST['languages'];
    $certifications = $_POST['certifications'];
    $educations = $_POST['educations'];
    $skills = $_POST['skills'];

    $sql = "UPDATE contractor 
            SET fullName = '$fullName', 
                email = '$email', 
                phoneNumber = '$phoneNumber' 
            WHERE id = '$id'";
    if (!$conn->query($sql)) {
        echo "Error updating contractor: " . $conn->error;
    }

    $sql = "UPDATE curriculum_vitae
            INNER JOIN contractor ON contractor.cvID = curriculum_vitae.cvID
            SET experiences = '$experiences',
                languages = '$languages',
                certifications = '$certifications',
                educations = '$educations',
                skills = '$skills'
            WHERE contractor.id = '$id'";
    if (!$conn->query($sql)) {
        echo "Error updating CV: " . $conn->error;
    }

    $sql = "UPDATE address
            INNER JOIN contractor ON contractor.addressID = address.addressID
            SET address.street = '$street',
                address.city = '$city',
                address.state = '$state',
                address.postalCode = '$postalCode'
            WHERE contractor.id = '$id'";
    if (!$conn->query($sql)) {
        echo "Error updating address: " . $conn->error;
    }

    header('Location: contractorPage.php');
    exit;
}
?>
