<!-- Clarita Antoun -->
<?php
session_start();
include 'conx.php';

if (!isset($_SESSION['homeOwner_identity']['id'])) {
    header("Location: logInPage.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $HoId = $_SESSION['homeOwner_identity']['id'];

 
    if (
        isset($_POST['professionalId']) &&
        isset($_POST['Pcomment']) &&
        isset($_POST['professional_rating'])
    ) {
        $profID = $_POST['professionalId'];
        $comment = $_POST['Pcomment'];
        $rating = $_POST['professional_rating'];

        $stmt = $conn->prepare("INSERT INTO ho_pro_feedback (rating, comment, homeOwnerID, professionalID) 
                                VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssii", $rating, $comment, $HoId, $profID);
        $stmt->execute();
        $stmt->close();

        header('Location: homeOwnerPage.php');
        exit();
    }

    if (
        isset($_POST['contractorId']) &&
        isset($_POST['Ccomment']) &&
        isset($_POST['contractor_rating'])
    ) {
        $contID = $_POST['contractorId'];
        $comment = $_POST['Ccomment'];
        $rating = $_POST['contractor_rating'];

        $stmt = $conn->prepare("INSERT INTO ho_cont_feedback (rating, comment, homeOwnerID, contractorID) 
                                VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssii", $rating, $comment, $HoId, $contID);
        $stmt->execute();
        $stmt->close();

        header('Location: feedbackHomeOwner.php');
        exit();
    }

    // Feedback for contractor
    if (
        isset($_POST['contractorId']) &&
        isset($_POST['Ccomment']) &&
        isset($_POST['contractor_rating'])
    ) {
        $contID = $_POST['contractorId'];
        $comment = $_POST['Ccomment'];
        $rating = $_POST['contractor_rating'];

        $stmt = $conn->prepare("INSERT INTO ho_cont_feedback (rating, comment, homeOwnerID, contractorID) 
                                VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssii", $rating, $comment, $HoId, $contID);
        $stmt->execute();
        $stmt->close();

        header('Location: feedbackHomeOwner.php');
        exit();
    }

   
        echo "<script>alert('Error: Missing feedback data.'); window.location.href='feedbackHomeOwner.php';</script>";
        exit();
    }

?>
