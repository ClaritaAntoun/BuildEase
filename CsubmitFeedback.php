<!-- Clarita Antoun -->
<?php
session_start();
include 'conx.php';

if (!isset($_SESSION['contractor_identity']['id'])) {
    header("Location: logInPage.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $ContId = $_SESSION['contractor_identity']['id'];

 
    if (
        isset($_POST['professionalId']) &&
        isset($_POST['comment']) &&
        isset($_POST['professional_rating'])
    ) {
        $profID = $_POST['professionalId'];
        $comment = $_POST['comment'];
        $rating = $_POST['professional_rating'];

        $stmt = $conn->prepare("INSERT INTO cont_pro_feedback (rating, comment, contractorID, professionalID) 
                                VALUES (?, ?, ?, ?)");
        $stmt->bind_param("isii", $rating, $comment, $ContId, $profID);
        $stmt->execute();
        $stmt->close();

       header('Location: feedbackContractor.php');
        exit();
    } else {
        echo "<script>alert('Error: Missing feedback data.'); window.location.href='feedbackContractor.php';</script>";
        exit();
    }
}
?>

?>
