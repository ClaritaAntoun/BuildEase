
<!-- Clarita Antoun -->
<?php
session_start();
include 'conx.php';




if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $homeOwnerID = $_SESSION['homeOwner_identity']['id'];
    $name = $_POST['projectName'];
    $budget = $_POST['budget'];
    $estimatedDuration = $_POST['estimatedDuration'];
    $startDate = $_POST['startDate'];
    $addressInput = $_POST['address'];
    $contractorID = $_POST['contractorID'];

    $today = date('Y-m-d');

    if ($startDate < $today) {
        $_SESSION['errors']['date']="❌ Start date cannot be in the past.";
    }
    if ($estimatedDuration==0) {
        $_SESSION['errors']['date']="❌ Estimated Duration cannot be 0.";
    }
    
    $addressParts = explode("/", $addressInput);
    if (count($addressParts) !== 4) {
        $_SESSION['errors']['address']=" ❌ Invalid address format. Use: Street/City/State/PostalCode";
    }

    
if ($budget <= 0) {
    $_SESSION['errors']['budget'] = "❌ Budget must be greater than zero.";
}

if (!empty($_SESSION['errors'])) {
    header("Location: start_project.php");
    exit();
}
    list($street, $city, $state, $postalCode) = $addressParts;

    $stmt = $conn->prepare("INSERT INTO Address (street, city, state, postalCode) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $street, $city, $state, $postalCode);
    $stmt->execute();
    $addressID = $stmt->insert_id;

    
    $stmt = $conn->prepare("INSERT INTO Project (name, budget, startDate, estimatedDuration, status, contractorID, addressID) 
                        VALUES (?, ?, ?, ?, 'pending', ?, ?)");
$stmt->bind_param("sdssii", $name, $budget, $startDate, $estimatedDuration, $contractorID, $addressID);

    $stmt->execute();
    $projectID = $stmt->insert_id;

    
    $stmt = $conn->prepare("INSERT INTO Creates (projectID, homeOwnerID) VALUES (?, ?)");
    $stmt->bind_param("ii", $projectID, $homeOwnerID);
    $stmt->execute();


    header("Location: homeOwnerPage.php?");
    exit();
} else {
    echo "Invalid request method.";
}
?>
