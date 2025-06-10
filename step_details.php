<?php
session_start();

if (!isset($_SESSION['contractor_identity'])) {
    header("Location: logInPage.php");
    exit();
}
include 'conx.php';

// Check if projectID exists in the session
if (!isset($_SESSION['projectID'])) {
    die("Project ID not found in session. Please select a project.");
}

// Retrieve projectID from the session
$projectId = $_SESSION['projectID'];

// Fetch stepNumber from the URL
$stepNumber = $_GET['step'] ?? null;

// Validate stepNumber
if (!$stepNumber || !is_numeric($stepNumber)) {
    die("Invalid Step Number.");
}

// Function to check and update project status if all steps are completed
function updateProjectStatusIfAllStepsCompleted($conn, $projectId) {
    // Check if all steps are completed
    $checkSql = "SELECT COUNT(*) as incomplete 
                 FROM (
                     SELECT s.stepNumber FROM step s
                     LEFT JOIN work_in w ON s.stepNumber = w.stepNumber AND w.projectID = ?
                     UNION
                     SELECT stepNumber FROM work_in WHERE projectID = ? AND stepNumber NOT IN (SELECT stepNumber FROM step)
                 ) AS all_steps
                 LEFT JOIN work_in w ON all_steps.stepNumber = w.stepNumber AND w.projectID = ?
                 WHERE w.stepStatus != 'completed' OR w.stepStatus IS NULL";
    
    $stmt = $conn->prepare($checkSql);
    $stmt->bind_param("iii", $projectId, $projectId, $projectId);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    // If no incomplete steps, update project status
    if ($result['incomplete'] == 0) {
        $updateSql = "UPDATE project SET status = 'completed' WHERE projectID = ?";
        $stmt = $conn->prepare($updateSql);
        $stmt->bind_param("i", $projectId);
        $stmt->execute();
        $stmt->close();
        
        // Also update the session to reflect the change immediately
        $_SESSION['project_status'] = 'completed';
        return true;
    }
    return false;
}

// First try to get step from step table (standard steps)
$stepSql = "SELECT s.stepNumber, s.name, s.details, 
                   COALESCE(w.stepType, 'standard') as stepType,
                   w.professionalID
            FROM step s
            LEFT JOIN work_in w ON s.stepNumber = w.stepNumber AND w.projectID = ?
            WHERE s.stepNumber = ?";
            
$stmt = $conn->prepare($stepSql);
$stmt->bind_param("ii", $projectId, $stepNumber);
$stmt->execute();
$step = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$step) {
    // If not found in standard steps, try custom steps
    $customStepSql = "SELECT stepNumber, stepName AS name, stepDetails AS details, 
                             'custom' AS stepType, professionalID
                      FROM work_in
                      WHERE stepNumber = ? AND projectID = ?";
    $stmt = $conn->prepare($customStepSql);
    $stmt->bind_param("ii", $stepNumber, $projectId);
    $stmt->execute();
    $step = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if (!$step) {
        die("Step not found.");
    }
}

// Ensure professionalID is available if needed
$step['professionalID'] = $step['professionalID'] ?? null;

// Fetch materials for the step
$materialsSql = "
    SELECT wm.id AS assignment_id,
           ml.id AS material_id, 
           ml.title, 
           ml.price AS materialPrice, 
           ml.category,
           ml.supplier, 
           ml.unit_measure AS unit, 
           ml.description,
           wm.quantity, 
           wm.unused_quantity,
           wm.assigned_date,
           wm.is_active,
           wm.deactivated_at
    FROM work_materials wm
    JOIN material_library ml ON wm.materialID = ml.id
    WHERE wm.stepNumber = ? 
    AND wm.projectID = ?
    AND wm.is_active = 1
";
$stmt = $conn->prepare($materialsSql);
$stmt->bind_param("ii", $stepNumber, $projectId);
$stmt->execute();
$materials = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// Calculate materials cost
$materialsCostSql = "
    SELECT 
        SUM(ml.price * wm.quantity) as estimated_cost,
        SUM(ml.price * (wm.quantity - COALESCE(wm.unused_quantity, 0))) as actual_cost,
        SUM(ml.price * COALESCE(wm.unused_quantity, 0)) as unused_cost
    FROM work_materials wm
    JOIN material_library ml ON wm.materialID = ml.id
    WHERE wm.stepNumber = ? 
    AND wm.projectID = ?
    AND wm.is_active = 1
";

$stmt = $conn->prepare($materialsCostSql);
$stmt->bind_param("ii", $stepNumber, $projectId);
$stmt->execute();
$materialsCostResult = $stmt->get_result()->fetch_assoc();
$stmt->close();

$estimated_materials_cost = $materialsCostResult['estimated_cost'] ?? 0;
$actual_materials_cost = $materialsCostResult['actual_cost'] ?? 0;
$unused_materials_cost = $materialsCostResult['unused_cost'] ?? 0;


// Fetch assigned professional
$assignedProfessional = null;
$getProSql = "SELECT p.id, p.fullName, pd.areaOfWork, wi.exactPrice as rate, wi.stepStatus, wi.startDate, wi.endDate, wi.worked_details, wi.paymentStatus
              FROM work_in wi
              JOIN professional p ON wi.professionalID = p.id
              JOIN professional_details pd ON p.id = pd.professionalID
              WHERE wi.projectID = ? AND wi.stepNumber = ?";
$stmt = $conn->prepare($getProSql);
$stmt->bind_param("ii", $projectId, $stepNumber);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $assignedProfessional = $result->fetch_assoc();
}
$stmt->close();

// Calculate labor cost (if professional is assigned)
$labor_cost = 0;
if ($assignedProfessional) {
    $rate = $assignedProfessional['rate'] ?? 0;
    $hours = $assignedProfessional['worked_details'] ?? 1; // Default to 1 hour if not specified
    $labor_cost = $rate * $hours;
}

// Calculate total cost
$total_cost = $actual_materials_cost + $labor_cost;

// Update cost in work_in table if professional is assigned
if ($assignedProfessional) {
    $updateCostSql = "UPDATE work_in SET cost = ? WHERE projectID = ? AND stepNumber = ?";
    $stmt = $conn->prepare($updateCostSql);
    $stmt->bind_param("dii", $total_cost, $projectId, $stepNumber);
    $stmt->execute();
    $stmt->close();
}

// Determine area of work for professional selection
$areaOfWork = ($step['stepType'] === 'custom') ? 'Custom Work' : $step['name'];

// Fetch professionals with matching area of work
$professionalsSql = "
    SELECT p.id, p.fullName, pd.areaOfWork, pd.availibilityStatus, wi.exactPrice AS rate
    FROM professional p
    JOIN professional_details pd ON p.id = pd.professionalID
    LEFT JOIN work_in wi ON p.id = wi.professionalID AND wi.stepNumber = ?
    WHERE (pd.areaOfWork = ? OR ? = 'Custom Work') 
      AND pd.availibilityStatus = 'Available'
      AND (wi.stepNumber IS NULL OR wi.stepNumber = ?)
    ORDER BY 
        CASE 
            WHEN p.id = ? THEN 0 
            WHEN pd.areaOfWork = ? THEN 1 
            ELSE 2 
        END
";
$stmt = $conn->prepare($professionalsSql);
$currentProfessionalId = $assignedProfessional ? $assignedProfessional['id'] : 0;
$stmt->bind_param("isssii", $stepNumber, $areaOfWork, $areaOfWork, $stepNumber, $currentProfessionalId, $areaOfWork);
$stmt->execute();
$professionals = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// Handle professional assignment form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['assign_professional'])) {
    $professionalId = $_POST['professional'] ?? null;
    $stepStatus = $_POST['step_status'] ?? 'pending';
    
    if ($professionalId && is_numeric($professionalId)) {
        // Start transaction
        $conn->begin_transaction();
        
        try {
            // For custom steps (negative stepNumber), temporarily disable foreign key checks
            if ($stepNumber < 0) {
                $conn->query("SET FOREIGN_KEY_CHECKS = 0");
            }
            
            // Free current professional if one exists
            if ($assignedProfessional) {
                $updateCurrentAvailabilitySql = "
                    UPDATE professional_details 
                    SET availibilityStatus = 'Available' 
                    WHERE professionalID = ?
                ";
                $stmt = $conn->prepare($updateCurrentAvailabilitySql);
                $stmt->bind_param("i", $assignedProfessional['id']);
                $stmt->execute();
                $stmt->close();
            }
            
            // Check if this step already has an assignment
            $checkAssignmentSql = "SELECT 1 FROM work_in WHERE stepNumber = ? AND projectID = ?";
            $stmt = $conn->prepare($checkAssignmentSql);
            $stmt->bind_param("ii", $stepNumber, $projectId);
            $stmt->execute();
            $hasAssignment = $stmt->get_result()->num_rows > 0;
            $stmt->close();
            
            if ($hasAssignment) {
                // Update existing assignment
                $updateWorkInSql = "
                    UPDATE work_in 
                    SET professionalID = ?, 
                        stepStatus = ?,
                        startDate = CASE 
                            WHEN ? = 'in_progress' AND startDate IS NULL THEN CURDATE() 
                            ELSE startDate 
                        END
                    WHERE stepNumber = ? AND projectID = ?
                ";
                $stmt = $conn->prepare($updateWorkInSql);
                $stmt->bind_param("issii", $professionalId, $stepStatus, $stepStatus, $stepNumber, $projectId);
            } else {
                // Create new assignment
                $updateWorkInSql = "
                    INSERT INTO work_in 
                    (professionalID, projectID, stepNumber, stepStatus, startDate) 
                    VALUES (?, ?, ?, ?, CASE WHEN ? = 'in_progress' THEN CURDATE() ELSE NULL END)
                ";
                $stmt = $conn->prepare($updateWorkInSql);
                $stmt->bind_param("iiiss", $professionalId, $projectId, $stepNumber, $stepStatus, $stepStatus);
            }
            $stmt->execute();
            $stmt->close();
            
            // Mark new professional as not available
            $updateNewAvailabilitySql = "
                UPDATE professional_details 
                SET availibilityStatus = 'Not Available' 
                WHERE professionalID = ?
            ";
            $stmt = $conn->prepare($updateNewAvailabilitySql);
            $stmt->bind_param("i", $professionalId);
            $stmt->execute();
            $stmt->close();
            
            // For custom steps, re-enable foreign key checks
            if ($stepNumber < 0) {
                $conn->query("SET FOREIGN_KEY_CHECKS = 1");
            }
            
            // Commit transaction
            $conn->commit();
            
            // Refresh the page to show updated information
            header("Location: step_details.php?step=" . $stepNumber);
            exit();
        } catch (Exception $e) {
            // Rollback on error
            $conn->rollback();
            
            // Ensure foreign key checks are re-enabled even if transaction fails
            if ($stepNumber < 0) {
                $conn->query("SET FOREIGN_KEY_CHECKS = 1");
            }
            
            die("Error assigning professional: " . $e->getMessage());
        }
    }
}

// Handle status update form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    $stepStatus = $_POST['step_status'];
    $projectId = $_POST['project_id'] ?? $_SESSION['projectID'];
    
    if ($assignedProfessional) {
        // Start transaction
        $conn->begin_transaction();
        
        try {
            // For custom steps (negative stepNumber), temporarily disable foreign key checks
            if ($stepNumber < 0) {
                $conn->query("SET FOREIGN_KEY_CHECKS = 0");
            }
            
            // Update step status
            $updateWorkInSql = "
                UPDATE work_in 
                SET stepStatus = ?, 
                    startDate = CASE 
                        WHEN ? = 'in_progress' AND startDate IS NULL THEN CURDATE() 
                        ELSE startDate 
                    END,
                    endDate = CASE 
                        WHEN ? = 'completed' THEN CURDATE() 
                        ELSE endDate 
                    END
                WHERE professionalID = ? AND stepNumber = ? AND projectID = ? 
            ";
           
            $stmt = $conn->prepare($updateWorkInSql);
            $stmt->bind_param("sssiii", $stepStatus, $stepStatus, $stepStatus, 
                            $assignedProfessional['id'], $stepNumber, $projectId);
            $stmt->execute();
            
            // Update professional availability if completed
            if ($stepStatus === 'completed') {
                $updateAvailabilitySql = "
                    UPDATE professional_details 
                    SET availibilityStatus = 'Available' 
                    WHERE professionalID = ?
                ";
                $stmt = $conn->prepare($updateAvailabilitySql);
                $stmt->bind_param("i", $assignedProfessional['id']);
                $stmt->execute();
            }
            
            // For custom steps, re-enable foreign key checks
            if ($stepNumber < 0) {
                $conn->query("SET FOREIGN_KEY_CHECKS = 1");
            }
            
            // Commit transaction
            $conn->commit();
            
            // Check if all steps are now completed
            updateProjectStatusIfAllStepsCompleted($conn, $projectId);
            
            // Refresh the page to show updated information
            header("Location: step_details.php?step=" . $stepNumber);
            exit();
        } catch (Exception $e) {
            // Rollback on error
            $conn->rollback();
            
            // Ensure foreign key checks are re-enabled even if transaction fails
            if ($stepNumber < 0) {
                $conn->query("SET FOREIGN_KEY_CHECKS = 1");
            }
            
            die("Error updating status: " . $e->getMessage());
        }
    }
}

// Handle picture upload if form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['upload_picture'])) {
    $projectId = $_POST['project_id'] ?? null;
    $stepNumber = $_POST['step_number'] ?? null;
    $details = $_POST['picture_details'] ?? '';

    if ($projectId && $stepNumber && isset($_FILES['step_picture'])) {
        // Start transaction
        $conn->begin_transaction();
        
        try {
            // For custom steps (negative stepNumber), temporarily disable foreign key checks
            if ($stepNumber < 0) {
                $conn->query("SET FOREIGN_KEY_CHECKS = 0");
            }

            // File upload handling
            $uploadDir = "uploads/step_pictures/";
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            $file = $_FILES['step_picture'];
            $fileName = basename($file['name']);
            $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
            $newFileName = uniqid() . '_' . $fileName;
            $targetFile = $uploadDir . $newFileName;

            // Validate image
            $check = getimagesize($file["tmp_name"]);
            if ($check === false) {
                throw new Exception("File is not an image.");
            }

            // Check file size (5MB max)
            if ($file["size"] > 5000000) {
                throw new Exception("Sorry, your file is too large (max 5MB).");
            }

            // Allow certain file formats
            $allowedExts = ['jpg', 'jpeg', 'png', 'gif'];
            if (!in_array($fileExt, $allowedExts)) {
                throw new Exception("Only JPG, JPEG, PNG & GIF files are allowed.");
            }

            if (move_uploaded_file($file["tmp_name"], $targetFile)) {
                $insertSql = "INSERT INTO step_picture (path, details, stepNumber, projectID, is_active) VALUES (?, ?, ?, ?, 1)";
                $stmt = $conn->prepare($insertSql);
                $stmt->bind_param("ssii", $targetFile, $details, $stepNumber, $projectId);
                
                if (!$stmt->execute()) {
                    throw new Exception("Error saving image to database.");
                }
                $stmt->close();
                
                $_SESSION['success_message'] = "Image uploaded successfully!";
            } else {
                throw new Exception("Error uploading file.");
            }

            // For custom steps, re-enable foreign key checks
            if ($stepNumber < 0) {
                $conn->query("SET FOREIGN_KEY_CHECKS = 1");
            }

            // Commit transaction
            $conn->commit();
        } catch (Exception $e) {
            // Rollback on error
            $conn->rollback();
            
            // Ensure foreign key checks are re-enabled even if transaction fails
            if ($stepNumber < 0) {
                $conn->query("SET FOREIGN_KEY_CHECKS = 1");
            }
            
            // Remove the uploaded file if it exists
            if (isset($targetFile) && file_exists($targetFile)) {
                unlink($targetFile);
            }
            
            $_SESSION['error_message'] = $e->getMessage();
        }
    }
    header("Location: step_details.php?step=$stepNumber");
    exit();
}

// Handle picture deactivation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['deactivate_picture'])) {
    $pictureId = $_POST['picture_id'] ?? null;
    $projectId = $_POST['project_id'] ?? null;
    $stepNumber = $_POST['step_number'] ?? null;

    if ($pictureId && $projectId && $stepNumber) {
        // Update the picture status to inactive
        $deactivateSql = "UPDATE step_picture SET is_active = 0 WHERE stepPictureID = ? AND projectID = ?";
        $stmt = $conn->prepare($deactivateSql);
        $stmt->bind_param("ii", $pictureId, $projectId);
        
        if ($stmt->execute()) {
            $_SESSION['success_message'] = "Picture deactivated successfully!";
        } else {
            $_SESSION['error_message'] = "Error deactivating picture.";
        }
        $stmt->close();
    }
    header("Location: step_details.php?step=$stepNumber");
    exit();
}

// Fetch only active pictures for this step and project
$picturesSql = "SELECT * FROM step_picture WHERE stepNumber = ? AND projectID = ? AND is_active = 1 ORDER BY stepPictureID DESC";
$stmt = $conn->prepare($picturesSql);
$stmt->bind_param("ii", $stepNumber, $projectId);
$stmt->execute();
$pictures = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Step Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
    :root {
        --primary: #1A2A3A;
        --secondary: #f8f9fa;
        --accent: #FFC107;
    }
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: var(--secondary);
    }
    /* Header Top (Dark blue bar with social icons and phone) */
.header-top {
   background: var(--primary);
        padding: 12px 0;
        border-bottom: 1px solid rgba(212, 175, 55, 0.1);
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        z-index: 1030;
        height: 60px;
}

.header-top a {
    color: var(--yellow-main);
    text-decoration: none;
    margin: 0 10px;
}

.header-top span {
    color: white;
}

/* Main Navigation Bar */
.main-navbar {
  background: #fff;
        box-shadow: 0 4px 30px rgba(0, 0, 0, 0.08);
        border-bottom: 2px solid plum;
        position: fixed;
        top: 60px;
        left: 0;
        right: 0;
        z-index: 1020;
        height: 60px;
}

.navbar-brand .logo {
    font-size: 2rem;
    font-weight: 700;
    color: var(--primary);
    text-decoration: none;
}

.navbar-brand .logo span {
    color: var(--yellow-main);
}

.main-navbar .nav-link {
    color: var(--primary) !important;
    font-weight: 500;
    padding: 0.5rem 1rem;
}

.main-navbar .nav-item.active .nav-link {
    color: var(--yellow-main) !important;
}

/* Sidebar */
.sidebar {
        background-color: var(--primary);
        color: white;
        width: 250px;
        height: 100vh;
        position: fixed;
        left: 0;
        top: 120px; /* Below header and nav */
        overflow-y: auto;
        z-index: 1010;
        padding: 20px 0;
        box-shadow: 2px 0 10px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
    }

    .profile-card {
        text-align: center;
        padding: 1.5rem;
        margin-bottom: 1rem;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    .profile-img {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid var(--yellow-main);
        margin-bottom: 1rem;
    }

    .profile-name {
        font-size: 1.2rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
        color: white;
    }

    .profile-role {
        background-color: var(--yellow-main);
        color: var(--primary);
        padding: 0.25rem 0.75rem;
        border-radius: 50px;
        font-size: 0.85rem;
        display: inline-block;
        margin-bottom: 1rem;
    }

    .sidebar .nav-link {
        color: white !important;
        border-radius: 5px;
        margin: 0.3rem 1rem;
        padding: 0.6rem 1rem;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        font-size: 0.9rem;
    }

    .sidebar .nav-link:hover,
    .sidebar .nav-link.active {
        background-color: rgba(255, 193, 7, 0.2);
        transform: translateX(5px);
    }

    .sidebar .nav-link i {
        width: 20px;
        text-align: center;
        margin-right: 0.8rem;
        color: var(--yellow-main);
        font-size: 1rem;
    }

    .sidebar .nav-link.active {
        border-left: 3px solid var(--yellow-main);
        padding-left: calc(1rem - 3px);
    }

    main {
        margin-left: 16.666667%;
    }
    @media (max-width: 767.98px) {
        .sidebar {
        background-color: var(--primary);
        color: white;
        width: 250px;
        height: 100vh;
        position: fixed;
        left: 0;
        top: 120px; /* Below header and nav */
        overflow-y: auto;
        z-index: 1010;
        padding: 20px 0;
        box-shadow: 2px 0 10px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
    }

    .profile-card {
        text-align: center;
        padding: 1.5rem;
        margin-bottom: 1rem;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    .profile-img {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid var(--yellow-main);
        margin-bottom: 1rem;
    }

    .profile-name {
        font-size: 1.2rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
        color: white;
    }

    .profile-role {
        background-color: var(--yellow-main);
        color: var(--primary);
        padding: 0.25rem 0.75rem;
        border-radius: 50px;
        font-size: 0.85rem;
        display: inline-block;
        margin-bottom: 1rem;
    }

    .sidebar .nav-link {
        color: white !important;
        border-radius: 5px;
        margin: 0.3rem 1rem;
        padding: 0.6rem 1rem;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        font-size: 0.9rem;
    }

    .sidebar .nav-link:hover,
    .sidebar .nav-link.active {
        background-color: rgba(255, 193, 7, 0.2);
        transform: translateX(5px);
    }

    .sidebar .nav-link i {
        width: 20px;
        text-align: center;
        margin-right: 0.8rem;
        color: var(--yellow-main);
        font-size: 1rem;
    }

    .sidebar .nav-link.active {
        border-left: 3px solid var(--yellow-main);
        padding-left: calc(1rem - 3px);
    }

        main {
            margin-left: 0;
        }
    }
    .assigned-professional {
        background-color: #fff8e1;
        padding: 15px;
        border-radius: 5px;
        margin-bottom: 20px;
    }
    .status-badge {
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: bold;
    }
    .status-pending {
        background-color: #fff3cd;
        color: #856404;
    }
    .status-in_progress {
        background-color: #cce5ff;
        color: #004085;
    }
    .status-completed {
        background-color: #d4edda;
        color: #155724;
    }
    .custom-step-badge {
        background-color: #e2e3e5;
        color: #383d41;
    }
    .btn-primary {
        background-color: var(--accent);
        border-color: var(--accent);
        color: #000;
    }
    .btn-primary:hover {
        background-color: #FFA000;
        border-color: #FFA000;
    }
    .card-header {
        background-color: var(--accent);
        color: #000;
    }
    .form-control:focus, .form-select:focus {
        border-color: var(--accent);
        box-shadow: 0 0 0 0.25rem rgba(255, 193, 7, 0.25);
    }
    .form-separator {
        margin: 1.5rem 0;
        border-top: 1px solid #dee2e6;
    }
    .professional-area {
        font-size: 0.85em;
        color: #6c757d;
        font-style: italic;
    }
    /* Picture gallery styles */
    .picture-thumbnail {
        height: 200px;
        object-fit: cover;
        width: 100%;
    }
    .picture-card {
        transition: transform 0.2s;
    }
    .picture-card:hover {
        transform: scale(1.02);
    }
    .upload-area {
        border: 2px dashed #dee2e6;
        border-radius: 5px;
        padding: 20px;
        text-align: center;
        cursor: pointer;
        margin-bottom: 15px;
    }
    .upload-area:hover {
        border-color: var(--accent);
    }
    #file-info {
        display: none;
    }
    /* Cost calculation styles */
    .cost-table {
        width: 100%;
        margin-top: 15px;
    }
    .cost-table th {
        text-align: left;
        padding: 8px;
        background-color: #f8f9fa;
    }
    .cost-table td {
        padding: 8px;
        border-bottom: 1px solid #eee;
    }
    .cost-table .total-row {
        font-weight: bold;
        background-color: #f8f9fa;
    }
    .text-danger {
        color: #dc3545 !important;
    }
    .table-success {
        background-color: #d4edda !important;
    }
</style>
</head>
<body>
   <!-- Header Top -->
    <div class="header-top">
        <div class="container">
            <div class="row align-items-center" >
                <div class="col-md-6" style="
     color:#FFC107 ;">
                    <div class="d-flex gap-3">
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>
                <div class="col-md-6 text-end">
                    <div class="d-flex gap-4 justify-content-end">
                        <span><i class="fas fa-map-marker-alt"></i> Lebanon</span>
                        <a href="#" style="
     color:#FFC107 ;" ><i class="fas fa-mobile-alt"></i> +961 81 111 000</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Navigation -->
    <nav class="navbar navbar-expand-lg main-navbar">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <span class="logo">Build<span style="
     color:#FFC107 ;" >Ease</span></span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="mainNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Home</a>
                    </li>
                    
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <?php echo htmlspecialchars($_SESSION['contractor_identity']['fullName'] ?? 'Profile'); ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="contractorProfile.php">Profile</a></li>
                            <li><a class="dropdown-item" href="logOut.php">Sign Out</a></li>
                        </ul>
                    </li>
                  <li class="nav-item active"><a class="nav-link" href="contractDetails.php">contract</a></li>

                   <li class="nav-item active"><a class="nav-link" href="CbrowseProfessionals.php">Browse professionals</a></li>
                   
 <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="feedbackDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Feedback
          </a>
          <ul class="dropdown-menu" aria-labelledby="feedbackDropdown">
            <li><a class="dropdown-item" href="C_browsingFeedback.php">Check Feedback</a></li>
            <li><a class="dropdown-item" href="feedbackContractor.php">Give Feedback</a></li>
          </ul>
        </li>

                </ul>
            </div>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">

            <!-- Sidebar -->
            <div class="sidebar">
                <div class="d-flex flex-column p-3">
                    <div class="text-center mb-4">
                        <img src="https://ui-avatars.com/api/?name=<?= urlencode($_SESSION['contractor_identity']['fullName']) ?>&background=random" 
                             class="rounded-circle profile-img mb-2">
                        <h5><?= htmlspecialchars($_SESSION['contractor_identity']['fullName']) ?></h5>
                        <small>Professional Contractor</small>
                    </div>
                    <ul class="nav nav-pills flex-column">
                        <li class="nav-item">
                            <a href="contractorPage.php" class="nav-link active">
                                <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                            </a>
                        </li>
                        <li class="nav-item mt-3">
                            <a href="contractorProfile.php" class="nav-link">
                                <i class="fas fa-user me-2"></i> My Profile
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="logOut.php" class="nav-link">
                                <i class="fas fa-sign-out-alt me-2"></i> Logout
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            <!-- Main Content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
                <div class="d-flex justify-content-between align-items-center mb-4" >
                    <h2><?= htmlspecialchars($step['name']) ?></h2>
                    <?php if (isset($step['stepType']) && $step['stepType'] === 'custom'): ?>
                        <span class="badge bg-info">Custom Step</span>
                    <?php endif; ?>
                </div>

                <!-- Step Overview -->
                <div class="card mb-4" style="
    margin-top: 60px;">
                    <div class="card-header">
                        <h5>Step Overview</h5>
                    </div>
                    <div class="card-body">
                        <p><strong>Name: </strong> <?= htmlspecialchars($step['name']) ?></p>
                        <p><strong>Details:</strong> <?= htmlspecialchars($step['details']) ?></p>
                        <p><strong>Step Number:</strong> <?= $stepNumber ?></p>
                        <p><strong>Area of Work:</strong> <?= htmlspecialchars($areaOfWork) ?></p>
                    </div>
                </div>

                <!-- Cost Calculation Card -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5>Cost Calculation</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="cost-table">
                                <tr>
                                    <th>Estimated Materials Cost</th>
                                    <td>$<?= number_format($estimated_materials_cost, 2) ?></td>
                                </tr>
                                <tr class="text-danger">
                                    <th>Unused Materials Cost</th>
                                    <td>-$<?= number_format($unused_materials_cost, 2) ?></td>
                                </tr>
                                <tr class="table-success">
                                    <th>Actual Materials Cost</th>
                                    <td>$<?= number_format($actual_materials_cost, 2) ?></td>
                                </tr>
                                <?php if ($assignedProfessional): ?>
                                <tr>
                                    <th>Labor Cost</th>
                                    <td>$<?= number_format($labor_cost, 2) ?></td>
                                </tr>
                                <?php endif; ?>
                                <tr class="total-row">
                                    <th>Total Cost</th>
                                    <td>$<?= number_format($total_cost, 2) ?></td>
                                </tr>
                            </table>
                        </div>
                        <?php if ($assignedProfessional): ?>
                        <div class="alert alert-info mt-3">
                            <i class="fas fa-info-circle"></i> 
                            Labor cost is calculated as: <strong>Hourly Rate ($<?= number_format($assignedProfessional['rate'], 2) ?>) × Hours Worked (<?= $assignedProfessional['worked_details'] ?? 1 ?>)</strong>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                <!-- Payment Status Update Form -->
<?php if ($assignedProfessional): ?>
    <div class="form-separator"></div>
    <form method="POST" action="update_payment_status.php">
        <input type="hidden" name="project_id" value="<?= $projectId ?>">
        <input type="hidden" name="step_number" value="<?= $stepNumber ?>">
        <input type="hidden" name="professional_id" value="<?= $assignedProfessional['id'] ?>">
        
        <div class="mb-3">
            <label for="payment_status" class="form-label">Update Payment Status</label>
            <select id="payment_status" name="payment_status" class="form-select" required 
                <?= $assignedProfessional['paymentStatus'] === 'paid' ? 'disabled' : '' ?>>
                <option value="unpaid" <?= $assignedProfessional['paymentStatus'] === 'unpaid' ? 'selected' : '' ?>>Unpaid</option>
                <option value="paid" <?= $assignedProfessional['paymentStatus'] === 'paid' ? 'selected' : '' ?>>Paid</option>
            </select>
            <?php if ($assignedProfessional['paymentStatus'] === 'paid'): ?>
                <small class="text-muted">Payment status cannot be changed once marked as paid.</small>
            <?php endif; ?>
        </div>
        
        <button type="submit" class="btn btn-primary" 
            <?= $assignedProfessional['paymentStatus'] === 'paid' ? 'disabled' : '' ?>>
            Update Payment Status
        </button>
    </form>
<?php endif; ?>

                <!-- Professional Assignment Section -->
                <div class="card mb-4" style="
    margin-top: 20px;" >
                    <div class="card-header">
                        <h5>Professional Assignment</h5>
                    </div>
                    <div class="card-body">
                        <?php if ($assignedProfessional): ?>
                            <div class="assigned-professional">
                                <h6>Assigned Professional</h6>
                                <p><strong>Name:</strong> <?= htmlspecialchars($assignedProfessional['fullName']) ?></p>
                                <p><strong>Area of Work:</strong> <?= htmlspecialchars($assignedProfessional['areaOfWork']) ?></p>
                                <p><strong>Hourly Rate:</strong> $<?= number_format($assignedProfessional['rate'], 2) ?></p>
                                <p><strong>Hours Worked:</strong> <?= $assignedProfessional['worked_details'] ?? 'Not specified' ?></p>
                                <p><strong>Status:</strong> 
                                    <span class="status-badge status-<?= $assignedProfessional['stepStatus'] ?>">
                                        <?= ucfirst(str_replace('_', ' ', $assignedProfessional['stepStatus'])) ?>
                                    </span>
                                </p>
                                <p><strong>Start Date:</strong> <?= $assignedProfessional['startDate'] ?? 'Not started' ?></p>
                                <p><strong>End Date:</strong> <?= $assignedProfessional['endDate'] ?? 'Not completed' ?></p>
                            </div>

                            <!-- Status update form -->
                            <form method="POST">
                                <input type="hidden" name="project_id" value="<?= $projectId ?>">
                                <input type="hidden" name="update_status" value="1">
                                <div class="mb-3">
                                    <label for="step_status" class="form-label">Update Step Status</label>
                                    <select id="step_status" name="step_status" class="form-select" required>
                                        <option value="pending" <?= $assignedProfessional['stepStatus'] === 'pending' ? 'selected' : '' ?>>Pending</option>
                                        <option value="in_progress" <?= $assignedProfessional['stepStatus'] === 'in_progress' ? 'selected' : '' ?>>In Progress</option>
                                        <option value="completed" <?= $assignedProfessional['stepStatus'] === 'completed' ? 'selected' : '' ?>>Completed</option>
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-primary">Update Status</button>
                            </form>

                            <!-- Change professional form -->
                            <div class="form-separator"></div>
                            <form method="POST">
                                <input type="hidden" name="project_id" value="<?= $projectId ?>">
                                <input type="hidden" name="assign_professional" value="1">
                                <div class="mb-3">
                                    <label for="professional" class="form-label">Change Professional</label>
                                    <select id="professional" name="professional" class="form-select" required>
                                        <option value="">Select a professional</option>
                                        <?php foreach ($professionals as $professional): ?>
                                            <option value="<?= $professional['id'] ?>" <?= $professional['id'] == $assignedProfessional['id'] ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($professional['fullName']) ?> 
                                                <span class="professional-area">(<?= htmlspecialchars($professional['areaOfWork']) ?>)</span>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-warning">Change Professional</button>
                            </form>

                        <?php elseif ($step['stepType'] === 'custom'): ?>
                            <div class="alert alert-danger">
                                <i class="fas fa-exclamation-circle"></i> 
                                This custom step doesn't have a professional assigned. This shouldn't happen as professionals are assigned during step creation.
                            </div>

                        <?php else: ?>
                            <form method="POST">
                                <input type="hidden" name="project_id" value="<?= $projectId ?>">
                                <input type="hidden" name="assign_professional" value="1">

                                <div class="mb-3">
                                    <label for="professional" class="form-label">Assign Professional</label>
                                    <?php if (empty($professionals)): ?>
                                        <p class="text-danger">No professionals available for this step.</p>
                                        <select id="professional" name="professional" class="form-select" disabled>
                                            <option value="">No professionals available</option>
                                        </select>
                                    <?php else: ?>
                                        <select id="professional" name="professional" class="form-select" required>
                                            <option value="">Select a professional</option>
                                            <?php foreach ($professionals as $professional): ?>
                                                <option value="<?= $professional['id'] ?>">
                                                    <?= htmlspecialchars($professional['fullName']) ?> 
                                                    <span class="professional-area">(<?= htmlspecialchars($professional['areaOfWork']) ?>)</span>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    <?php endif; ?>
                                </div>

                                <div class="mb-3">
                                    <label for="step_status" class="form-label">Initial Step Status</label>
                                    <select id="step_status" name="step_status" class="form-select" required>
                                        <option value="pending">Pending</option>
                                        <option value="in_progress">In Progress</option>
                                    </select>
                                </div>

                                <button type="submit" class="btn btn-primary" <?= empty($professionals) ? 'disabled' : '' ?>>
                                    Assign Professional
                                </button>
                            </form>
                        <?php endif; ?>

                        <div class="mt-3">
                            <a href="project_details.php?id=<?= $projectId ?>" class="btn btn-secondary">Back to Project</a>
                        </div>
                    </div>
                </div>

                <!-- Materials Section -->
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Materials</h5>
                        <a href="add_material.php?project=<?= $projectId ?>&step=<?= $stepNumber ?>" 
                           class="btn btn-sm btn-success">
                            <i class="fas fa-plus me-1"></i> Assign Material
                        </a>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($materials)): ?>
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Material</th>
                                            <th>Category</th>
                                            <th>Price</th>
                                            <th>Quantity</th>
                                            <th>Unused</th>
                                            <th>Unit</th>
                                            <th>Supplier</th>
                                            <th>Assigned Date</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($materials as $material): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($material['title']) ?></td>
                                            <td><?= htmlspecialchars($material['category']) ?></td>
                                            <td>$<?= number_format($material['materialPrice'], 2) ?></td>
                                            <td><?= htmlspecialchars($material['quantity']) ?></td>
                                            <td><?= htmlspecialchars($material['unused_quantity'] ?? 0) ?></td>
                                            <td><?= htmlspecialchars($material['unit']) ?></td>
                                            <td><?= htmlspecialchars($material['supplier']) ?></td>
                                            <td><?= htmlspecialchars($material['assigned_date']) ?></td>
                                            <td>
                                                <a href="edit_material_assignment.php?id=<?= $material['assignment_id'] ?>&project=<?= $projectId ?>&step=<?= $stepNumber ?>"
                                                   class="btn btn-sm btn-primary">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <a href="remove_material_assignment.php?id=<?= $material['assignment_id'] ?>&project=<?= $projectId ?>&step=<?= $stepNumber ?>" 
                                                   class="btn btn-sm btn-danger"
                                                   onclick="return confirm('Are you sure you want to remove this material assignment?')">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <div class="alert alert-info">No materials assigned to this step.</div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Step Pictures Section -->
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Step Pictures</h5>
                        <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#uploadPictureModal">
                            <i class="fas fa-plus me-1"></i> Upload Picture
                        </button>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($pictures)): ?>
                            <div class="row">
                                <?php foreach ($pictures as $picture): ?>
                                    <div class="col-md-4 mb-4">
                                        <div class="card h-100 picture-card">
                                            <img src="<?= htmlspecialchars($picture['path']) ?>" class="card-img-top picture-thumbnail" alt="Step picture">
                                            <div class="card-body">
                                                <p class="card-text"><?= htmlspecialchars($picture['details']) ?></p>
                                                <div class="d-flex justify-content-between">
                                                    <a href="<?= htmlspecialchars($picture['path']) ?>" target="_blank" class="btn btn-sm btn-primary">
                                                        <i class="fas fa-expand"></i> View
                                                    </a>
                                                    <form method="POST" class="d-inline">
                                                        <input type="hidden" name="picture_id" value="<?= $picture['stepPictureID'] ?>">
                                                        <input type="hidden" name="project_id" value="<?= $projectId ?>">
                                                        <input type="hidden" name="step_number" value="<?= $stepNumber ?>">
                                                        <button type="submit" name="deactivate_picture" class="btn btn-sm btn-danger"
                                                                onclick="return confirm('Are you sure you want to deactivate this picture?')">
                                                            <i class="fas fa-eye-slash"></i> Deactivate
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <div class="alert alert-info">No pictures uploaded for this step yet.</div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Upload Picture Modal -->
                <div class="modal fade" id="uploadPictureModal" tabindex="-1" aria-labelledby="uploadPictureModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form method="POST" enctype="multipart/form-data">
                                <input type="hidden" name="project_id" value="<?= $projectId ?>">
                                <input type="hidden" name="step_number" value="<?= $stepNumber ?>">
                                <input type="hidden" name="upload_picture" value="1">
                                
                                <div class="modal-header">
                                    <h5 class="modal-title" id="uploadPictureModalLabel">Upload Step Picture</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="upload-area" onclick="document.getElementById('step_picture').click()">
                                        <i class="fas fa-cloud-upload-alt fa-3x mb-3" style="color: var(--accent);"></i>
                                        <p class="mb-2">Click to browse or drag and drop</p>
                                        <small class="text-muted">Supports JPG, PNG, GIF (Max 5MB)</small>
                                        <input type="file" id="step_picture" name="step_picture" class="d-none" required>
                                    </div>
                                    <div id="file-info" class="mb-3">
                                        <i class="fas fa-file-image me-2"></i>
                                        <span id="file-name"></span>
                                        <small id="file-size" class="text-muted ms-2"></small>
                                    </div>
                                    <div class="mb-3">
                                        <label for="picture_details" class="form-label">Description</label>
                                        <textarea class="form-control" id="picture_details" name="picture_details" rows="3" placeholder="Enter picture description..."></textarea>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Upload Picture</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Feedback Section -->
                <?php if ($assignedProfessional): ?>
                <div class="card mb-4">
                    <div class="card-header" style="background-color: var(--accent); color: #000;">
                        <h5 class="mb-0">
                            <i class="fas fa-comment-alt me-2"></i> Professional Feedback
                        </h5>
                    </div>
                    <div class="card-body">
                        <form action="con_pro_submit_feedback.php" method="POST" class="needs-validation" novalidate>
                            <input type="hidden" name="stepNumber" value="<?= $stepNumber ?>">
                            <input type="hidden" name="projectID" value="<?= $projectId ?>">
                            <input type="hidden" name="professionalID" value="<?= $assignedProfessional['id'] ?>">
                            
                            <!-- Rating Section -->
                            <div class="mb-4">
                                <label class="form-label fw-bold">Rating</label>
                                <div class="rating-container">
                                    <div class="rating-stars d-flex justify-content-between mb-2">
                                        <?php for ($i = 1; $i <= 5; $i++): ?>
                                            <div class="star-option text-center">
                                                <input class="form-check-input" type="radio" 
                                                       name="rating" id="rating<?= $i ?>" 
                                                       value="<?= $i ?>" required
                                                       <?= $i == 3 ? 'checked' : '' ?>>
                                                <label class="star-label" for="rating<?= $i ?>">
                                                    <div class="stars mb-1">
                                                        <?php for ($j = 1; $j <= 5; $j++): ?>
                                                            <i class="<?= $j <= $i ? 'fas' : 'far' ?> fa-star"></i>
                                                        <?php endfor; ?>
                                                    </div>
                                                    <span class="rating-text"><?= 
                                                        $i == 1 ? 'Poor' : 
                                                        ($i == 2 ? 'Fair' : 
                                                        ($i == 3 ? 'Good' : 
                                                        ($i == 4 ? 'Very Good' : 'Excellent')))
                                                    ?></span>
                                                </label>
                                            </div>
                                        <?php endfor; ?>
                                    </div>
                                </div>
                                <div class="invalid-feedback">Please select a rating</div>
                            </div>
                            
                            <!-- Comment Section -->
                            <div class="mb-4">
                                <label for="comment" class="form-label fw-bold">
                                    <i class="fas fa-edit me-1"></i> Comments
                                </label>
                                <textarea class="form-control feedback-textarea" id="comment" name="comment" rows="4" 
                                          placeholder="Provide specific feedback about the professional's work..." 
                                          required></textarea>
                                <div class="invalid-feedback">Please provide your feedback</div>
                                <small class="text-muted">
                                    <i class="fas fa-info-circle me-1"></i> Your feedback helps improve our service quality
                                </small>
                            </div>
                            
                            <!-- Submission Button -->
                            <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                                <button type="submit" class="btn btn-primary px-4 py-2 submit-feedback-btn">
                                    <i class="fas fa-paper-plane me-2"></i> Submit Feedback
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                <?php endif; ?>
            </main>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Show selected file info
        document.getElementById('step_picture').addEventListener('change', function(e) {
            const fileInfo = document.getElementById('file-info');
            const fileName = document.getElementById('file-name');
            const fileSize = document.getElementById('file-size');
            
            if (this.files.length > 0) {
                const file = this.files[0];
                fileName.textContent = file.name;
                fileSize.textContent = formatFileSize(file.size);
                fileInfo.style.display = 'block';
            } else {
                fileInfo.style.display = 'none';
            }
        });

        function formatFileSize(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
        }
    </script>
</body>
</html>