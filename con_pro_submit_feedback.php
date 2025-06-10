<?php
session_start();
if (!isset($_SESSION['contractor_identity'])) {
    header("Location: logInPage.php");
    exit();
}

include 'conx.php';

// Check if form was submitted
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $_SESSION['error'] = "Invalid request method.";
    header("Location: " . ($_SERVER['HTTP_REFERER'] ?? 'index.php'));
    exit();
}

// Validate required POST data
if (!isset($_POST['professional_id'], $_POST['rating'], $_POST['comment'])) {
    $_SESSION['error'] = "All fields are required.";
    header("Location: " . ($_SERVER['HTTP_REFERER'] ?? 'index.php'));
    exit();
}

// Sanitize and validate input
$professionalID = (int)$_POST['professional_id'];
$rating = (float)$_POST['rating'];
$comment = $conn->real_escape_string(trim($_POST['comment']));
$contractorID = (int)$_SESSION['contractor_identity']['id'];

// Validate rating range
if ($rating < 1 || $rating > 5) {
    $_SESSION['error'] = "Rating must be between 1 and 5.";
    header("Location: " . ($_SERVER['HTTP_REFERER'] ?? 'index.php'));
    exit();
}

// Check if professional exists
$checkProfessional = $conn->prepare("SELECT id FROM professionals WHERE id = ?");
$checkProfessional->bind_param("i", $professionalID);
$checkProfessional->execute();
if ($checkProfessional->get_result()->num_rows === 0) {
    $_SESSION['error'] = "Invalid professional selected.";
    header("Location: " . ($_SERVER['HTTP_REFERER'] ?? 'index.php'));
    exit();
}
$checkProfessional->close();

// Check if feedback already exists from this contractor to this professional
$checkFeedback = $conn->prepare("SELECT id FROM cont_pro_feedback WHERE contractorID = ? AND professionalID = ?");
$checkFeedback->bind_param("ii", $contractorID, $professionalID);
$checkFeedback->execute();
if ($checkFeedback->get_result()->num_rows > 0) {
    $_SESSION['error'] = "You've already submitted feedback for this professional.";
    header("Location: " . ($_SERVER['HTTP_REFERER'] ?? 'index.php'));
    exit();
}
$checkFeedback->close();

// Insert feedback
$insertFeedback = $conn->prepare("
    INSERT INTO cont_pro_feedback 
    (rating, comment, date, contractorID, professionalID)
    VALUES (?, ?, CURDATE(), ?, ?)
");

$insertFeedback->bind_param("dsii", $rating, $comment, $contractorID, $professionalID);

if ($insertFeedback->execute()) {
    $_SESSION['success'] = "Feedback submitted successfully!";
} else {
    $_SESSION['error'] = "Failed to submit feedback: " . $conn->error;
}

$insertFeedback->close();
$conn->close();

// Redirect back to previous page
header("Location: " . ($_SERVER['HTTP_REFERER'] ?? 'index.php'));
exit();
?>