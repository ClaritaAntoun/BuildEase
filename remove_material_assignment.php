<?php
session_start();
include 'conx.php';

if (!isset($_SESSION['contractor_identity'])) {
    header("Location: logInPage.php");
    exit();
}

// Get and validate parameters
$assignmentId = (int)($_GET['id'] ?? 0);
$projectId = (int)($_GET['project'] ?? 0);
$stepNumber = (int)($_GET['step'] ?? 0);

if ($assignmentId <= 0 || $projectId <= 0 || $stepNumber <= 0) {
    die("Invalid parameters - all must be positive integers");
}

// Verify assignment belongs to contractor and get material details
$verifySql = "SELECT wm.id, ml.title, ml.category, wm.quantity
             FROM work_materials wm
             JOIN material_library ml ON wm.materialID = ml.id
             WHERE wm.id = ? AND wm.projectID = ? AND wm.stepNumber = ?
             AND ml.contractorID = ? AND wm.is_active = 1";
$stmt = $conn->prepare($verifySql);
$stmt->bind_param("iiii", $assignmentId, $projectId, $stepNumber, $_SESSION['contractor_identity']['id']);
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();

if ($result->num_rows === 0) {
    die("Material assignment not found or not authorized");
}

$material = $result->fetch_assoc();

// Handle removal (deactivation)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Soft delete (deactivate)
    $deactivateSql = "UPDATE work_materials 
                     SET is_active = 0, deactivated_at = CURRENT_TIMESTAMP()
                     WHERE id = ?";
    
    $stmt = $conn->prepare($deactivateSql);
    $stmt->bind_param("i", $assignmentId);
    
    if ($stmt->execute()) {
        header("Location: step_details.php?step=$stepNumber&removed=1");
        exit();
    } else {
        die("Failed to deactivate material assignment: " . $stmt->error);
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Deactivate Material Assignment</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .confirmation-card {
            border-left: 5px solid #dc3545;
        }
        .material-details {
            background-color: #f8f9fa;
            border-radius: 5px;
            padding: 15px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card confirmation-card">
                    <div class="card-header bg-danger text-white">
                        <h4><i class="fas fa-exclamation-triangle"></i> Confirm Deactivation</h4>
                    </div>
                    <div class="card-body">
                        <div class="material-details">
                            <h5>Material Assignment Details</h5>
                            <ul class="list-unstyled">
                                <li><strong>Material:</strong> <?= htmlspecialchars($material['title']) ?></li>
                                <li><strong>Category:</strong> <?= htmlspecialchars($material['category']) ?></li>
                                <li><strong>Quantity:</strong> <?= htmlspecialchars($material['quantity']) ?></li>
                                <li><strong>Project:</strong> <?= htmlspecialchars($projectId) ?></li>
                                <li><strong>Step:</strong> <?= htmlspecialchars($stepNumber) ?></li>
                            </ul>
                        </div>
                        
                        <div class="alert alert-warning">
                            <i class="fas fa-info-circle"></i> This will deactivate but preserve the material assignment in the system.
                        </div>
                        
                        <form method="POST">
                            <div class="d-flex justify-content-between">
                                <a href="step_details.php?step=<?= $stepNumber ?>" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> Cancel
                                </a>
                                <button type="submit" class="btn btn-danger">
                                    <i class="fas fa-ban"></i> Confirm Deactivation
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/js/all.min.js"></script>
</body>
</html>