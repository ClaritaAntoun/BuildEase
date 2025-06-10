<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Create upload folders if they don't exist
if (!file_exists('project_images')) {
    mkdir('project_images', 0755, true);
}
if (!file_exists('project_pictures')) {
    mkdir('project_pictures', 0755, true);
}
session_start();
include 'conx.php';

if (!isset($_SESSION['contractor_identity'])) {
    header("Location: logInPage.php");
    exit();
}

$projectId = $_GET['id'] ?? ($_SESSION['projectID'] ?? null);

if (!$projectId || !is_numeric($projectId)) {
    die("Invalid Project ID.");
}

$_SESSION['projectID'] = $projectId;

// Handle image upload
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Main project image upload
    if (isset($_POST['upload_image'])) {
        if (!empty($_FILES['project_image']['name'])) {
            $targetDir = "project_images/";
            $fileName = uniqid() . '_' . basename($_FILES["project_image"]["name"]);
            $targetFilePath = $targetDir . $fileName;
            
            // Check if image file
            $check = getimagesize($_FILES["project_image"]["tmp_name"]);
            if ($check !== false) {
                // Check file size (5MB max)
                if ($_FILES['project_image']['size'] <= 5 * 1024 * 1024) {
                    // Move the file
                    if (move_uploaded_file($_FILES["project_image"]["tmp_name"], $targetFilePath)) {
                        // Update database
                        $updateStmt = $conn->prepare("UPDATE project SET imageGenerated = ? WHERE projectID = ?");
                        $updateStmt->bind_param("si", $targetFilePath, $projectId);
                        if ($updateStmt->execute()) {
                            $_SESSION['success_message'] = "Image uploaded successfully!";
                        } else {
                            unlink($targetFilePath); // Delete if DB fails
                            $_SESSION['error_message'] = "Database error. Please try again.";
                        }
                        $updateStmt->close();
                    } else {
                        $_SESSION['error_message'] = "Sorry, there was an error uploading your file.";
                    }
                } else {
                    $_SESSION['error_message'] = "File is too large (max 5MB).";
                }
            } else {
                $_SESSION['error_message'] = "File is not an image.";
            }
        } else {
            $_SESSION['error_message'] = "Please select an image file.";
        }
    }
    
    // Project pictures upload
    if (isset($_POST['add_project_picture'])) {
        if (!empty($_FILES['picture_path']['name']) && !empty($_POST['picture_details'])) {
            $targetDir = "project_pictures/";
            $fileName = uniqid() . '_' . basename($_FILES["picture_path"]["name"]);
            $targetFilePath = $targetDir . $fileName;
            $details = $_POST['picture_details'];
            
            // Same checks as above
            $check = getimagesize($_FILES["picture_path"]["tmp_name"]);
            if ($check !== false && $_FILES['picture_path']['size'] <= 5 * 1024 * 1024) {
                if (move_uploaded_file($_FILES["picture_path"]["tmp_name"], $targetFilePath)) {
                    $insertStmt = $conn->prepare("INSERT INTO project_picture (paths, details, projectID) VALUES (?, ?, ?)");
                    $insertStmt->bind_param("ssi", $targetFilePath, $details, $projectId);
                    if ($insertStmt->execute()) {
                        $_SESSION['success_message'] = "Picture added successfully!";
                    } else {
                        unlink($targetFilePath);
                        $_SESSION['error_message'] = "Database error. Please try again.";
                    }
                    $insertStmt->close();
                } else {
                    $_SESSION['error_message'] = "Error uploading picture.";
                }
            } else {
                $_SESSION['error_message'] = "Invalid picture (must be image under 5MB).";
            }
        } else {
            $_SESSION['error_message'] = "Please select a picture and enter details.";
        }
    }
    
    header("Location: project_details.php?id=" . $projectId);
    exit();
}

// Calculate total cost from work_in table
$costSql = "SELECT 
                SUM(wi.cost) AS total_cost,
                p.contractorPrice,
                p.websiteCommission
            FROM work_in wi
            LEFT JOIN project p ON wi.projectID = p.projectID
            WHERE wi.projectID = ?";
$costStmt = $conn->prepare($costSql);
$costStmt->bind_param("i", $projectId);
$costStmt->execute();
$costResult = $costStmt->get_result()->fetch_assoc();
$costStmt->close();

$totalCost = $costResult['total_cost'] ?? 0;
$contractorPrice = $costResult['contractorPrice'] ?? 0;
$websiteCommission = $costResult['websiteCommission'] ?? 0;

// Optional: Calculate net profit or total revenue
$netProfit = $contractorPrice - $totalCost;
$totalRevenue = $contractorPrice + $websiteCommission;

$projectSql = "
    SELECT p.*, c.fullName AS contractorName, h.fullName AS homeownerName, 
           h.phoneNumber AS homeownerPhone, a.street, a.city, a.state, a.postalCode,
           p.contractorPrice, p.websiteCommission
    FROM project p
    JOIN contractor c ON p.contractorID = c.id
    JOIN creates cr ON p.projectID = cr.projectID
    JOIN homeowner h ON cr.homeOwnerID = h.id
    JOIN address a ON p.addressID = a.addressID
    WHERE p.projectID = ?
";
$stmt = $conn->prepare($projectSql);
$stmt->bind_param("i", $projectId);
$stmt->execute();
$project = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$project) {
    die("Project not found.");
}

if (!isset($project['budget']) || $project['budget'] <= 0) {
    die("Error: Invalid budget.");
}

// Calculate duration based on actual dates if available
$startDate = new DateTime($project['startDate']);
$currentDate = new DateTime('now');

if ($project['status'] === 'completed' && !empty($project['endDate'])) {
    $endDate = new DateTime($project['endDate']);
    $totalDuration = $startDate->diff($endDate)->days;
    $elapsedTime = $totalDuration; // For completed projects, elapsed = total
} else {
    $totalDuration = (int) $project['estimatedDuration'];
    $elapsedTime = $startDate->diff($currentDate)->days;
}

$progressPercentage = ($totalDuration > 0) ? min(($elapsedTime / $totalDuration) * 100, 100) : 0;

// Add this new code to calculate time difference and message
$timeDifference = $elapsedTime - $totalDuration;
$timelineMessage = '';

if ($timeDifference > 0 && $project['status'] !== 'completed') {
    $timelineMessage = '<div class="alert alert-warning mt-2">Project is taking ' . $timeDifference . ' days longer than estimated!</div>';
} elseif ($project['status'] === 'completed') {
    if ($timeDifference > 0) {
        $timelineMessage = '<div class="alert alert-warning mt-2">Project took ' . $timeDifference . ' days longer than estimated</div>';
    } else {
        $timelineMessage = '<div class="alert alert-success mt-2">Project completed on time!</div>';
    }
}

// Calculate budget utilization
$costPercentage = ($totalCost / $project['budget']) * 100;
$gaugeColor = '#28a745'; // Green by default
if ($costPercentage >= 90) {
    $gaugeColor = '#dc3545'; // Red
} elseif ($costPercentage >= 80) {
    $gaugeColor = '#ffc107'; // Yellow
}

// Fetch all standard steps with their status for this project
$standardStepsSql = "SELECT 
                s.stepNumber, 
                s.name, 
                s.details,
                w.stepStatus,
                w.cost,  -- 👈 Added cost
                p.fullName AS professionalName,
                pd.areaOfWork AS professionalArea,
                FALSE AS is_custom
            FROM step s
            LEFT JOIN work_in w ON s.stepNumber = w.stepNumber AND w.projectID = ?
            LEFT JOIN professional p ON w.professionalID = p.id
            LEFT JOIN professional_details pd ON p.id = pd.professionalID
            ORDER BY s.stepNumber";

$stmt = $conn->prepare($standardStepsSql);
$stmt->bind_param("i", $projectId);
$stmt->execute();
$standardSteps = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// Fetch all custom steps for this project
$customStepsSql = "SELECT 
                w.stepNumber,
                w.stepName AS name,
                w.stepDetails AS details,
                w.stepStatus,
                w.cost,  -- 👈 Added cost
                p.fullName AS professionalName,
                pd.areaOfWork AS professionalArea,
                TRUE AS is_custom
            FROM work_in w
            LEFT JOIN professional p ON w.professionalID = p.id
            LEFT JOIN professional_details pd ON p.id = pd.professionalID
            WHERE w.projectID = ? AND w.stepName IS NOT NULL AND w.is_active = 1
            ORDER BY w.stepNumber";

$stmt = $conn->prepare($customStepsSql);
$stmt->bind_param("i", $projectId);
$stmt->execute();
$customSteps = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// Combine both results
$steps = array_merge($standardSteps, $customSteps);

// Calculate step completion data
$totalSteps = count($steps);
$completedSteps = 0;
foreach ($steps as $step) {
    if (($step['stepStatus'] ?? '') === 'completed') {
        $completedSteps++;
    }
}
// Fetch materials for the project
$materialsSql = "
    SELECT ml.id, ml.title, ml.price AS materialPrice, s.name AS stepName, wm.quantity
    FROM work_materials wm
    JOIN material_library ml ON wm.materialID = ml.id
    JOIN step s ON wm.stepNumber = s.stepNumber
    WHERE wm.projectID = ?
";
$stmt = $conn->prepare($materialsSql);
$stmt->bind_param("i", $projectId);
$stmt->execute();
$materials = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// Fetch project pictures
$picturesSql = "SELECT paths, details FROM project_picture WHERE projectID = ?";
$stmt = $conn->prepare($picturesSql);
$stmt->bind_param("i", $projectId);
$stmt->execute();
$pictures = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// Fetch all available professionals (status = accepted and availability = Available)
$professionals = $conn->query("
    SELECT p.id, p.fullName, pd.areaOfWork 
    FROM professional p
    JOIN professional_details pd ON p.id = pd.professionalID
    WHERE p.status = 'accepted' AND pd.availibilityStatus = 'Available'
");?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Project Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        /* Keep original header, navbar and sidebar styles exactly as they were */
        :root {
            --primary: #1A2A3A;
            --secondary: #f8f9fa;
            --accent: #FFC107;
            --yellow-main: #FFC107;
            --yellow-dark: #E0A800;
            --header-height: 80px;
            --sidebar-width: 230px;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding-top: var(--header-height);
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
            height: calc(100vh - var(--header-height));
            width: var(--sidebar-width);
            position: fixed;
            left: 0;
            top: var(--header-height);
            bottom: 0;
            overflow-y: auto;
            z-index: 1010;
            padding: 20px 0;
        }

        .sidebar-content {
            padding: 20px;
        }

        .profile-img {
            width: 100px;
            height: 100px;
            object-fit: cover;
            margin-bottom: 15px;
        }

        .sidebar h5 {
            color: white;
            text-align: center;
            margin-bottom: 5px;
        }

        .sidebar small {
            color: #adb5bd;
            display: block;
            text-align: center;
            margin-bottom: 25px;
        }

        .sidebar .nav-link {
            color: white;
            padding: 10px 15px;
            border-radius: 0;
            margin: 5px 0;
        }

        .sidebar .nav-link.active {
            background-color: var(--yellow-main);
            color: var(--primary) !important;
            font-weight: bold;
        }

        .sidebar .nav-link i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }

        /* New styles for project details content only */
        .project-content {
            margin-left: var(--sidebar-width);
            padding: 30px;
            margin-top: 20px;
        }

        .project-card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
            margin-bottom: 25px;
            transition: transform 0.3s ease;
        }

        .project-card:hover {
            transform: translateY(-3px);
        }

        .project-card-header {
            background-color: white;
            border-bottom: 1px solid rgba(0,0,0,0.05);
            padding: 15px 20px;
            font-weight: 600;
            border-radius: 10px 10px 0 0 !important;
        }

        .project-image {
            width: 100%;
            height: 300px;
            object-fit: cover;
            border-radius: 8px;
        }

        .image-placeholder {
            height: 300px;
            background-color: #f8f9fa;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
        }

        .gauge-container {
            position: relative;
            height: 120px;
            margin-bottom: 20px;
        }

        .info-item {
            padding: 10px 0;
            border-bottom: 1px solid rgba(0,0,0,0.05);
        }

        .info-item:last-child {
            border-bottom: none;
        }

        .info-label {
            font-weight: 600;
            color: var(--primary);
        }

        .info-value {
            color: #6c757d;
        }

        .picture-card {
            height: 100%;
            transition: transform 0.3s ease;
        }

        .picture-card:hover {
            transform: scale(1.02);
        }

        .picture-card img {
            height: 200px;
            object-fit: cover;
            border-radius: 8px 8px 0 0;
        }

        .step-badge {
            font-size: 0.8rem;
            padding: 0.3em 0.6em;
        }

        .step-row {
            transition: background-color 0.2s ease;
        }

        .step-row:hover {
            background-color: rgba(255, 193, 7, 0.05);
        }

        .material-row {
            border-left: 3px solid var(--yellow-main);
        }

        .status-badge {
            padding: 0.5em 0.8em;
            border-radius: 20px;
            font-weight: 500;
        }

        .status-active {
            background-color: rgba(25, 135, 84, 0.1);
            color: #198754;
        }

        .status-completed {
            background-color: rgba(13, 110, 253, 0.1);
            color: #0d6efd;
        }

        .status-pending {
            background-color: rgba(255, 193, 7, 0.1);
            color: var(--yellow-main);
        }

        .custom-step {
            background-color: rgba(255, 193, 7, 0.05);
        }

        .progress-thin {
            height: 8px;
        }

        @media (max-width: 991.98px) {
            .sidebar {
                width: 0;
                transform: translateX(-100%);
                transition: transform 0.3s ease;
            }
            
            .sidebar.show {
                width: var(--sidebar-width);
                transform: translateX(0);
            }
            
            .project-content {
                margin-left: 0;
            }
        }
    </style>
</head>
<body>
    <!-- Keep the original header and navigation exactly as it was -->
    <!-- Header Top -->
    <div class="header-top">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <div class="d-flex gap-3">
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>
                <div class="col-md-6 text-end">
                    <div class="d-flex gap-4 justify-content-end">
                        <span><i class="fas fa-map-marker-alt"></i> Lebanon</span>
                        <a href="#"><i class="fas fa-mobile-alt"></i> +961 81 111 000</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Navigation -->
    <nav class="navbar navbar-expand-lg main-navbar">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <span class="logo">Build<span>Ease</span></span>
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
            <!-- Keep the original sidebar exactly as it was -->
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 d-md-block sidebar p-0">
                <div class="d-flex flex-column p-3">
                    <div class="text-center mb-4 mt-5">
                        <img src="https://ui-avatars.com/api/?name=<?= urlencode($project['contractorName']) ?>&background=random" 
                             class="rounded-circle profile-img mb-2">
                        <h5><?= htmlspecialchars($project['contractorName']) ?></h5>
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

            <!-- Project Content Area - This is where we apply the new styles -->
            <main class="col-md-9 ms-sm-auto col-lg-10 project-content">
                <?php if (isset($_SESSION['success_message'])): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?= $_SESSION['success_message'] ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    <?php unset($_SESSION['success_message']); ?>
                <?php endif; ?>
                
                <?php if (isset($_SESSION['error_message'])): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?= $_SESSION['error_message'] ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    <?php unset($_SESSION['error_message']); ?>
                <?php endif; ?>
                
                <h2 class="mb-4"><?= htmlspecialchars($project['name']) ?></h2>
                
                <!-- Project Image Section -->
                <div class="project-card mb-4">
                   
                </div>

                <!-- Project Overview -->
                <div class="row">
                    <!-- Left Column -->
                    <div class="col-lg-8">
                        <!-- Budget and Timeline -->
                        <div class="project-card mb-4">
                            
                        </div>
                        
                        <!-- Project Details -->
                        <div class="project-card mb-4">
                            <div class="project-card-header">
                                <h5 class="mb-0">Project Details</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="info-item">
                                            <span class="info-label"> Contractor:</span>
                                            <span class="info-value"><?= htmlspecialchars($project['contractorName']) ?></span>
                                        </div>
                                        <div class="info-item">
                                            <span class="info-label"> Contractor Price:</span>
                                            <span class="info-value">$<?= number_format($contractorPrice, 2) ?></span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="info-item">
                                            <span class="info-label"> Homeowner:</span>
                                            <span class="info-value"><?= htmlspecialchars($project['homeownerName']) ?></span>
                                        </div>
                                        <div class="info-item">
                                            <span class="info-label"> Homeowner Phone:</span>
                                            <span class="info-value"><?= htmlspecialchars($project['homeownerPhone']) ?></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="info-item">
                                    <span class="info-label"> Location:</span>
                                    <span class="info-value">
                                        <?= htmlspecialchars($project['street']) ?>, 
                                        <?= htmlspecialchars($project['city']) ?>, 
                                        <?= htmlspecialchars($project['state']) ?>
                                    </span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label"> Total Cost:</span>
                                    <span class="info-value">$<?= number_format($totalCost, 2) ?></span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label"> Website Commission:</span>
                                    <span class="info-value">$<?= number_format($websiteCommission, 2) ?></span>
                                </div>
                                
                            </div>
                        </div>
                    </div>
                    
                    <!-- Right Column -->
                    <div class="col-lg-4">
                        <!-- Step Completion -->
                        <div class="project-card mb-4">
                            
                        </div>
                        
                        <!-- Financial Summary -->
                        <div class="project-card mb-4">
                            
                        </div>
                    </div>
                </div>
                
                <!-- Steps/Phases -->
                <div class="project-card mb-4">
                    <div class="project-card-header">
                        <h5 class="mb-0">Project Steps</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
    <tr>
        <th>#</th>
        <th>Step Name</th>
        <th>Status</th>
        <th>Details</th>
        <th>Assigned Professional</th>
        <th>Cost</th> <!-- NEW COLUMN -->
    </tr>
</thead>
                                <tbody>
                                    <?php 
                                    $counter = 1;
                                    foreach ($steps as $step): 
                                        $stepStatus = $step['stepStatus'] ?? 'pending';
                                    ?>
                                    <tr class="step-row <?= ($step['is_custom']) ? 'custom-step' : '' ?>">
                                        <td><?= $counter++ ?></td>
                                        <td>
                                            <?= htmlspecialchars($step['name']) ?>
                                            <?php if ($step['is_custom']): ?>
                                                <span class="step-badge bg-info ms-1">Custom</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if ($stepStatus === 'in_progress'): ?>
                                                <span class="badge bg-warning">In Progress</span>
                                            <?php elseif ($stepStatus === 'completed'): ?>
                                                <span class="badge bg-success">Completed</span>
                                            <?php else: ?>
                                                <span class="badge bg-secondary">Pending</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?= htmlspecialchars($step['details']) ?></td>
                                        <td>
                                            <?php if (!empty($step['professionalName'])): ?>
                                                <?= htmlspecialchars($step['professionalName']) ?>
                                                <span class="text-muted">(<?= htmlspecialchars($step['professionalArea'] ?? 'Not specified') ?>)</span>
                                            <?php else: ?>
                                                Not assigned
                                            <?php endif; ?>
                                        </td>
                                        <td>$<?= number_format($step['cost'] ?? 0, 2) ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                
                <!-- Materials -->
                <div class="project-card mb-4">
                    <div class="project-card-header">
                        <h5 class="mb-0">Materials</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Material</th>
                                        <th>Price</th>
                                        <th>Step</th>
                                        <th>Quantity</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($materials)): ?>
                                        <?php foreach ($materials as $material): ?>
                                        <tr class="material-row">
                                            <td><?= htmlspecialchars($material['title'] ?? 'N/A') ?></td>
                                            <td>$<?= isset($material['materialPrice']) ? number_format($material['materialPrice'], 2) : '0.00' ?></td>
                                            <td><?= htmlspecialchars($material['stepName'] ?? 'Not assigned') ?></td>
                                            <td><?= htmlspecialchars($material['quantity'] ?? 1) ?></td>
                                        </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="4" class="text-center text-muted">No materials assigned to this project</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                
                <!-- Project Pictures -->
                <div class="project-card mb-4">
                    <div class="project-card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Project Pictures</h5>
                        <button class="btn btn-sm btn-success" type="button" data-bs-toggle="collapse" data-bs-target="#addPictureForm">
                            <i class="fas fa-plus me-1"></i> Add Picture
                        </button>
                    </div>
                    
                    <div class="collapse" id="addPictureForm">
                        <div class="card-body">
                            <form action="" method="post" enctype="multipart/form-data">
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Select Picture (max 5MB)</label>
                                        <input type="file" class="form-control" name="picture_path" accept="image/*" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Description</label>
                                        <input type="text" class="form-control" name="picture_details" required>
                                    </div>
                                </div>
                                <button type="submit" name="add_project_picture" class="btn btn-primary">
                                    <i class="fas fa-save me-1"></i> Save Picture
                                </button>
                            </form>
                        </div>
                    </div>
                    
                    <div class="card-body">
                        <?php if (!empty($pictures)): ?>
                            <div class="row">
                                <?php foreach ($pictures as $picture): ?>
                                <div class="col-md-4 mb-3">
                                    <div class="card picture-card">
                                        <img src="<?= htmlspecialchars($picture['paths']) ?>" class="card-img-top">
                                        <div class="card-body">
                                            <p class="card-text"><?= htmlspecialchars($picture['details']) ?></p>
                                        </div>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <div class="text-center py-4 bg-light rounded">
                                <i class="fas fa-images fa-3x text-muted mb-2"></i>
                                <p class="text-muted">No pictures added yet</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                 <!-- Materials -->
                <div class="project-card mb-4">
                 <form method="POST" action="update_project_status.php">
    <input type="hidden" name="project_id" value="<?= $projectId ?>">
    
    <select name="new_status" class="form-select" required>
        <option value="">Select Status</option>
        <option value="active">Active</option>
        <option value="completed">Completed</option>
    </select>

    <button type="submit" class="btn btn-primary mt-2">Update Status</button>
</form>
                </div>
            </main>
        </div>
    </div>

    <!-- Upload Image Modal -->
    <div class="modal fade" id="uploadImageModal" tabindex="-1" aria-labelledby="uploadImageModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="uploadImageModalLabel">Upload Project Image</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="" method="post" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="project_image" class="form-label">Select Image (max 5MB)</label>
                            <input type="file" class="form-control" id="project_image" name="project_image" accept="image/*" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" name="upload_image" class="btn btn-primary">Upload Image</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
    // Calculate step completion data
    const steps = <?= json_encode($steps) ?>;
    const totalSteps = steps.length;
    const completedSteps = steps.filter(step => step.stepStatus === 'completed').length;
    const stepProgressPercentage = (completedSteps / totalSteps) * 100;

    // Progress Chart - Now based on step completion
    const progressCtx = document.getElementById('progressChart').getContext('2d');
    const progressChart = new Chart(progressCtx, {
        type: 'doughnut',
        data: {
            labels: ['Completed Steps', 'Remaining Steps'],
            datasets: [{
                data: [completedSteps, totalSteps - completedSteps],
                backgroundColor: ['#198754', '#ecf0f1'],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom'
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const label = context.label || '';
                            const value = context.raw;
                            const percentage = Math.round((value / totalSteps) * 100);
                            return `${label}: ${value} (${percentage}%)`;
                        }
                    }
                }
            }
        },
        plugins: [{
            id: 'centerText',
            beforeDraw(chart) {
                const width = chart.width;
                const height = chart.height;
                const ctx = chart.ctx;
                
                ctx.restore();
                const fontSize = (height / 5).toFixed(2);
                ctx.font = `bold ${fontSize}px Arial`;
                ctx.textBaseline = 'middle';
                ctx.fillStyle = '#2c3e50';
                
                const text = `${Math.round(stepProgressPercentage)}%`;
                const textX = Math.round((width - ctx.measureText(text).width) / 2);
                const textY = height / 2;
                
                ctx.fillText(text, textX, textY);
                ctx.save();
            }
        }]
    });

    // Budget Gauge Chart
    const budgetGaugeCtx = document.getElementById('budgetGauge').getContext('2d');
    const budget = <?= $project['budget'] ?>;
    const cost = <?= $totalCost ?>;
    const costPercentage = Math.min((cost / budget) * 100, 100);
    const gaugeColor = '<?= $gaugeColor ?>';

    if (budget > 0) {
        const budgetGauge = new Chart(budgetGaugeCtx, {
            type: 'doughnut',
            data: {
                labels: ['Used Budget', 'Remaining Budget'],
                datasets: [{
                    data: [costPercentage, 100 - costPercentage],
                    backgroundColor: [gaugeColor, '#e9ecef'],
                    borderWidth: 0
                }]
            },
            options: {
                circumference: 180,
                rotation: -90,
                cutout: '70%',
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const value = context.parsed;
                                let label = context.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                if (context.dataIndex === 0) {
                                    label += '$' + (budget * value / 100).toLocaleString(undefined, {maximumFractionDigits: 2});
                                } else {
                                    label += '$' + (budget * (100 - value) / 100).toLocaleString(undefined, {maximumFractionDigits: 2});
                                }
                                return label;
                            }
                        }
                    }
                },
                animation: {
                    animateScale: true,
                    animateRotate: true
                }
            },
            plugins: [{
                id: 'centerText',
                afterDatasetsDraw(chart, args, options) {
                    const {ctx, chartArea: {left, right, top, bottom, width, height}} = chart;
                    
                    ctx.save();
                    const textX = Math.round((left + right) / 2);
                    const textY = Math.round((top + bottom) / 2);
                    
                    // Percentage text
                    ctx.font = 'bold 20px Arial';
                    ctx.fillStyle = gaugeColor;
                    ctx.textAlign = 'center';
                    ctx.textBaseline = 'middle';
                    ctx.fillText(costPercentage.toFixed(1) + '%', textX, textY - 10);
                    
                    // Cost text below
                    ctx.font = '12px Arial';
                    ctx.fillText('$' + cost.toLocaleString(), textX, textY + 15);
                    
                    ctx.restore();
                }
            }]
        });
    } else {
        document.getElementById('budgetGauge').style.display = 'none';
        document.querySelector('.gauge-container').innerHTML = '<div class="alert alert-warning">No budget set for this project</div>';
    }
    </script>
</body>
</html>