
<!-- Clarita Antoun -->
<?php
session_start();
ob_start();
if (!isset($_SESSION['professional_identity'])) {
    header("Location: logInPage.php");
    exit();
}
include 'conx.php';

if(isset($_SESSION['professional_identity']['id'])) {
    $id = $_SESSION['professional_identity']['id'];
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

$age = $_POST['age'];
$price = $_POST['price'];
$availibilityStatus = $_POST['availibilityStatus'];
$areaOfWork = $_POST['areaOfWork'];
$startDate = $_POST['startDate'];
    

 
    $sql = "UPDATE professional SET fullName = '$fullName', email = '$email',age='$age', phoneNumber = '$phoneNumber' WHERE id = '$id'";
    $conn->query($sql);

    $sql = "UPDATE curriculum_vitae
    INNER JOIN professional ON professional.cvID = curriculum_vitae.cvID
    SET curriculum_vitae.experiences = '$experiences',
        curriculum_vitae.languages = '$languages',
        curriculum_vitae.certifications = '$certifications',
        curriculum_vitae.educations = '$educations',
        curriculum_vitae.skills = '$skills'
    WHERE professional.id = '$id'";
$conn->query($sql);

$sql = "UPDATE address
INNER JOIN professional ON professional.addressID = address.addressID
SET street = '$street',
    city = '$city',
    state = '$state',
    postalCode = '$postalCode'
WHERE professional.id = '$id'";
    $conn->query($sql);


    $sql = "UPDATE professional_details
INNER JOIN professional ON professional.id = professional_details.professionalID
SET areaOfWork = '$areaOfWork',
    price = '$price',
    startDate = '$startDate',
    availibilityStatus = '$availibilityStatus'
WHERE professional.id = '$id'";
    $conn->query($sql);

    header('location:professionalPage.php');
    exit;
}
?>
 -->


 <?php
session_start();
ob_start();
if (!isset($_SESSION['professional_identity'])) {
    header("Location: logInPage.php");
    exit();
}
include 'conx.php';

if (isset($_SESSION['professional_identity']['id'])) {
    $id = $_SESSION['professional_identity']['id'];
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
    $age = $_POST['age'];
    $price = $_POST['price'];
    $availibilityStatus = $_POST['availibilityStatus'];
    $areaOfWork = $_POST['areaOfWork'];
    $startDate = $_POST['startDate'];

    // Update professional details
    $sql = "UPDATE professional SET fullName = '$fullName', email = '$email', age = '$age', phoneNumber = '$phoneNumber' WHERE id = '$id'";
    if (!$conn->query($sql)) {
        echo "Error updating professional: " . $conn->error;
    }

    // Update curriculum_vitae details
    $sql = "UPDATE curriculum_vitae
            INNER JOIN professional ON professional.cvID = curriculum_vitae.cvID
            SET curriculum_vitae.experiences = '$experiences',
                curriculum_vitae.languages = '$languages',
                curriculum_vitae.certifications = '$certifications',
                curriculum_vitae.educations = '$educations',
                curriculum_vitae.skills = '$skills'
            WHERE professional.id = '$id'";
    if (!$conn->query($sql)) {
        echo "Error updating curriculum_vitae: " . $conn->error;
    }

    // Update address details
    $sql = "UPDATE address
            INNER JOIN professional ON professional.addressID = address.addressID
            SET address.street = '$street', address.city = '$city', address.state = '$state', address.postalCode = '$postalCode'
            WHERE professional.id = '$id'";
    if (!$conn->query($sql)) {
        echo "Error updating address: " . $conn->error;
    }

    // Update professional_details
    $sql = "UPDATE professional_details
            INNER JOIN professional ON professional.id = professional_details.professionalID
            SET professional_details.areaOfWork = '$areaOfWork',
                professional_details.price = '$price',
                professional_details.startDate = '$startDate',
                professional_details.availibilityStatus = '$availibilityStatus'
            WHERE professional.id = '$id'";
    if (!$conn->query($sql)) {
        echo "Error updating professional_details: " . $conn->error;
    }

    header('location: professionalPage.php');
    exit;
}
?>
