<?php
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

$projectSql = "
    SELECT p.*, c.fullName AS contractorName, h.fullName AS homeownerName, 
           h.phoneNumber AS homeownerPhone, a.street, a.city, a.state, a.postalCode
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

if (!isset($project['budget']) || !isset($project['exactCost']) || $project['budget'] <= 0) {
    die("Error: Invalid budget or exact cost.");
}

$startDate = new DateTime($project['startDate']);
$currentDate = new DateTime('now');
$totalDuration = (int) $project['exactDuration'];
$elapsedTime = $startDate->diff($currentDate)->days;
$progressPercentage = 0;
if ($totalDuration > 0) {
    $progressPercentage = min(($elapsedTime / $totalDuration) * 100, 100);
}

// Calculate budget utilization
$costPercentage = ($project['exactCost'] / $project['budget']) * 100;
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
                p.fullName AS professionalName,
                pd.areaOfWork AS professionalArea,
                TRUE AS is_custom
            FROM work_in w
            LEFT JOIN professional p ON w.professionalID = p.id
            LEFT JOIN professional_details pd ON p.id = pd.professionalID
            WHERE w.projectID = ? AND w.stepName IS NOT NULL
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
    SELECT ml.title, ml.price AS materialPrice, s.name AS stepName
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
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Project Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
:root {
    --primary: #1A2A3A;
    --secondary: #f8f9fa;
    --accent: #FFC107;
    --yellow-main: #FFC107;
    --yellow-dark: #E0A800;
    --header-height: 120px; /* Combined height of header-top and main-navbar */
    --sidebar-width: 250px;
}

body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background-color: var(--secondary);
    padding-top: var(--header-height);
    margin: 0;
}

/* Dark Blue Header Top with Yellow Text */
.header-top {
    background-color: var(--primary);
    color: var(--yellow-main);
    padding: 8px 0;
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    z-index: 1030;
    height: 40px;
    border-bottom: 2px solid var(--yellow-main);
}

.header-top a {
    color: var(--yellow-main);
    text-decoration: none;
    margin: 0 10px;
}

/* Main Navigation - Below Header */
.main-navbar {
    background-color: white;
    position: fixed;
    top: 40px; /* Height of header-top */
    left: 0;
    right: 0;
    z-index: 1020;
    height: 60px;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    border-bottom: 2px solid var(--yellow-main);
}

.navbar-brand .logo {
    font-family: 'Playfair Display', serif;
    font-size: 2rem;
    font-weight: 700;
    color: var(--primary);
}

.navbar-brand .logo span {
    color: var(--yellow-main);
}

.main-navbar .nav-link {
    color: var(--primary) !important;
    font-weight: 500;
    padding: 0.5rem 1rem;
}

.main-navbar .nav-link:hover,
.main-navbar .nav-link.active {
    color: var(--yellow-dark) !important;
}

.main-navbar .dropdown-menu {
    border: none;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

.main-navbar .dropdown-item {
    padding: 0.5rem 1.5rem;
    color: var(--primary) !important;
}

.main-navbar .dropdown-item:hover {
    background-color: rgba(255, 193, 7, 0.1);
}

/* Dark Blue Sidebar with Yellow Text */
.sidebar {
    background-color: var(--primary);
    color: white;
    height: calc(100vh - var(--header-height));
    width: var(--sidebar-width);
    position: fixed;
    left: 0;
    top: var(--header-height); /* Starts below main navigation */
    bottom: 0;
    overflow-y: auto;
    z-index: 1010;
    padding: 20px 0;
    border-right: 2px solid var(--yellow-main);
}

.sidebar-content {
    padding: 20px;
}

.profile-img {
    width: 100px;
    height: 100px;
    object-fit: cover;
    margin-bottom: 15px;
    border: 3px solid var(--yellow-main);
}

.sidebar .nav-pills {
    flex-direction: column;
}

.sidebar .nav-link {
    color: white !important;
    border-radius: 5px;
    margin: 5px 10px;
    padding: 12px 15px !important;
    font-weight: 500;
}

.sidebar .nav-link i {
    margin-right: 10px;
    color: var(--yellow-main);
}

.sidebar .nav-link:hover, 
.sidebar .nav-link.active {
    background-color: var(--yellow-main);
    color: var(--primary) !important;
}

.sidebar .nav-link.active i,
.sidebar .nav-link:hover i {
    color: var(--primary);
}

/* Main Content - Starts below header and beside sidebar */
main {
    margin-left: var(--sidebar-width);
    padding: 30px;
    margin-top: calc(var(--header-height) + 20px);
    min-height: calc(100vh - var(--header-height));
}

/* Responsive Adjustments */
@media (max-width: 991.98px) {
    .sidebar {
        transform: translateX(-100%);
    }
    
    .sidebar.show {
        transform: translateX(0);
    }
    
    main {
        margin-left: 0;
    }
}

/* Keep all other existing styles exactly the same */
.card {
    border: none;
    box-shadow: 0 0 10px rgba(0,0,0,0.05);
}

.card-header {
    background-color: white;
    border-bottom: 1px solid rgba(0,0,0,0.05);
    color: var(--primary);
}

.table th {
    background-color: rgba(0,0,0,0.02);
    color: var(--primary);
}

.btn,
.btn-primary,
.btn-success,
.btn-info,
.btn-warning,
.btn-danger {
    background-color: var(--yellow-main) !important;
    border-color: var(--yellow-main) !important;
    color: #212529 !important;
}

.progress-bar {
    background-color: var(--yellow-main) !important;
}

.badge {
    background-color: var(--yellow-main) !important;
    color: #212529 !important;
}
</style>
</head>
<body>
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

                    <li class="nav-item">
                        <a class="nav-link" href="contractorPage.php">Browse Projects</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
    
    <div class="container-fluid">
        <div class="row">
           <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 d-md-block sidebar p-0">
                <div class="d-flex flex-column p-3">
                    <div class="text-center mb-4">
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
    <a href="add_material.php?id=<?= $projectId ?>" class="nav-link">
        <i class="fas fa-boxes me-2"></i> Materials
    </a>
</li>
                        <li class="nav-item">
                            <a href="#feedbacks" class="nav-link" data-bs-toggle="tab">
                                <i class="fas fa-comment-alt me-2"></i> Feedbacks
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
                <h2 class="mb-4"><?= htmlspecialchars($project['name']) ?></h2>
                
                <!-- Project Overview -->
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header">
                                <h5>Project Overview</h5>
                            </div>
                            <div class="card-body">
                                <!-- Budget Gauge Row -->
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <div class="gauge-container">
                                            <canvas id="budgetGauge"></canvas>
                                        </div>
                                        <div class="text-center mt-2">
                                            <span class="badge bg-primary">Budget: $<?= number_format($project['budget'], 2) ?></span>
                                            <span class="badge bg-<?= ($costPercentage >= 90) ? 'danger' : (($costPercentage >= 80) ? 'warning' : 'success') ?> ms-2">
                                                Cost: $<?= number_format($project['exactCost'], 2) ?>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <!-- Timeline Progress Mini Chart -->
                                        <div class="mb-3">
                                            <p class="mb-1"><strong>Timeline Progress:</strong> <?= round($progressPercentage, 2) ?>%</p>
                                            <div class="progress" style="height: 20px;">
                                                <div class="progress-bar" role="progressbar" 
                                                     style="width: <?= $progressPercentage ?>%" 
                                                     aria-valuenow="<?= $progressPercentage ?>" 
                                                     aria-valuemin="0" 
                                                     aria-valuemax="100"></div>
                                            </div>
                                        </div>
                                        <p><strong>Duration:</strong> <?= htmlspecialchars($project['exactDuration']) ?> days</p>
                                        <p><strong>Elapsed Time:</strong> <?= $elapsedTime ?> days</p>
                                    </div>
                                </div>
                                
                                <!-- Project Status Row -->
                                <div class="row mt-3">
                                    <div class="col-md-6">
                                        <p><strong>Status:</strong> 
                                            <?php if ($project['status'] === 'active'): ?>
                                                <span class="badge bg-success">Active</span>
                                            <?php elseif ($project['status'] === 'completed'): ?>
                                                <span class="badge bg-primary">Completed</span>
                                            <?php else: ?>
                                                <span class="badge bg-warning">Pending</span>
                                            <?php endif; ?>
                                        </p>
                                        <p><strong>Start Date:</strong> <?= htmlspecialchars($project['startDate']) ?></p>
                                    </div>
                                    <div class="col-md-6">
                                        <p><strong>Location:</strong><br>
                                            <?= htmlspecialchars($project['street']) ?>,<br>
                                            <?= htmlspecialchars($project['city']) ?>, <?= htmlspecialchars($project['state']) ?>
                                        </p>
                                    </div>
                                </div>
                <!-- Add Custom Step Form -->
                <div class="card mb-4">
                    <div class="card-header">
                        <button class="btn btn-primary btn-sm" type="button" data-bs-toggle="collapse" data-bs-target="#addCustomStepForm">
                            <i class="fas fa-plus me-1"></i> Add Custom Step
                        </button>
                    </div>
                    <div class="collapse" id="addCustomStepForm">
                        <div class="card-body">
                            <form action="add_custom_step.php" method="POST">
                                <input type="hidden" name="project_id" value="<?= $projectId ?>">
                                
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="step_name" class="form-label">Step Name</label>
                                        <input type="text" class="form-control" id="step_name" name="step_name" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="step_details" class="form-label">Details</label>
                                        <input type="text" class="form-control" id="step_details" name="step_details">
                                    </div>
                                </div>
                                
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="professional_id" class="form-label">Assign Professional</label>
                                        <select class="form-select" id="professional_id" name="professional_id" required>
                                            <option value="">Select Professional</option>
                                            <?php while($prof = $professionals->fetch_assoc()): ?>
                                                <option value="<?= $prof['id'] ?>">
                                                    <?= htmlspecialchars($prof['fullName']) ?> 
                                                    <span class="professional-area">(<?= htmlspecialchars($prof['areaOfWork']) ?>)</span>
                                                </option>
                                            <?php endwhile; ?>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="exact_price" class="form-label">Price</label>
                                        <input type="number" step="0.01" class="form-control" id="exact_price" name="exact_price" required>
                                    </div>
                                </div>
                                
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-save me-1"></i> Add Custom Step
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                
 <!-- Steps/Phases -->
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Steps/Phases</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Step Name</th>
                                        <th>Status</th>
                                        <th>Details</th>
                                        <th>Assigned Professional</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
    <?php foreach ($steps as $step): 
        $stepStatus = $step['stepStatus'] ?? 'pending';
    ?>
    <tr class="<?= ($step['is_custom']) ? 'table-info' : '' ?>">
        <td>
            <?= htmlspecialchars($step['name']) ?>
            <?php if ($step['is_custom']): ?>
                <span class="badge bg-info ms-1">Custom</span>
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
                <span class="professional-area">(<?= htmlspecialchars($step['professionalArea'] ?? 'Not specified') ?>)</span>
            <?php else: ?>
                Not assigned
            <?php endif; ?>
        </td>
        <td>
            <a href="step_details.php?step=<?= $step['stepNumber'] ?>&project=<?= $projectId ?>" 
               class="btn btn-primary btn-sm">
                View Details
            </a>
            <?php if ($step['is_custom']): ?>
                <a href="delete_custom_step.php?id=<?= $projectId ?>&step=<?= $step['stepNumber'] ?>" 
   class="btn btn-danger btn-sm ms-1"
   onclick="return confirm('Are you sure you want to delete this custom step?')">
    <i class="fas fa-trash"></i>
</a>
            <?php endif; ?>
        </td>
    </tr>
    <?php endforeach; ?>
</tbody>
                            </table>
                        </div>
                    </div>
                </div>
                
               <!-- Materials -->
<div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Materials</h5>
        <a href="add_material.php?id=<?= $projectId ?>" class="btn btn-sm btn-success">
            <i class="fas fa-plus me-1"></i> Add Material
        </a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Material</th>
                        <th>Price</th>
                        <th>Step</th>
                        <th>Quantity</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($materials)): ?>
                        <?php foreach ($materials as $material): ?>
                        <tr>
                            <td><?= htmlspecialchars($material['title']) ?></td>
                            <td>$<?= number_format($material['materialPrice'], 2) ?></td>
                            <td><?= htmlspecialchars($material['stepName']) ?></td>
                            <td><?= htmlspecialchars($material['quantity'] ?? 1) ?></td>
                            <td>
                                <a href="edit_material.php?project=<?= $projectId ?>&id=<?= $material['id'] ?>" 
                                   class="btn btn-sm btn-primary">
                                    <i class="fas fa-edit"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center text-muted">No materials assigned to this project</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
                
                <!-- Project Pictures -->
                <?php if (!empty($pictures)): ?>
                <div class="card mb-4">
                    <div class="card-header">
                        <h5>Project Pictures</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <?php foreach ($pictures as $picture): ?>
                            <div class="col-md-4 mb-3">
                                <img src="<?= htmlspecialchars($picture['paths']) ?>" class="img-fluid rounded" alt="<?= htmlspecialchars($picture['details']) ?>">
                                <p class="mt-2 text-muted"><?= htmlspecialchars($picture['details']) ?></p>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            </main>
        </div>
    </div>
            </main>
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
                backgroundColor: ['#198754', '#6c757d'],
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
                ctx.fillStyle = '#495057';
                
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
    const cost = <?= $project['exactCost'] ?>;
    const costPercentage = Math.min((cost / budget) * 100, 100);
    const gaugeColor = '<?= $gaugeColor ?>';

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
        }
    });

    // Add text in center of gauge
    const gaugeText = costPercentage.toFixed(1) + '%';
    const centerText = {
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
            ctx.fillText(gaugeText, textX, textY - 10);
            
            ctx.restore();
        }
    };
</script>
</body>
</html>