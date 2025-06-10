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

// Fetch project basic info
$projectSql = "SELECT name FROM project WHERE projectID = ?";
$stmt = $conn->prepare($projectSql);
$stmt->bind_param("i", $projectId);
$stmt->execute();
$project = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$project) {
    die("Project not found.");
}

// Fetch categories - both standard and custom
$categoriesSql = "SELECT name FROM step 
                  UNION 
                  SELECT stepName as name FROM work_in 
                  WHERE projectID = ? AND stepType = 'custom' 
                  GROUP BY name";
$stmt = $conn->prepare($categoriesSql);
$stmt->bind_param("i", $projectId);
$stmt->execute();
$categories = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// Fetch all steps (standard + custom) for a project
$allStepsSql = "
    SELECT 
        w.stepNumber, 
        CASE 
            WHEN w.stepType = 'custom' THEN w.stepName
            ELSE s.name
        END AS stepName,
        w.stepStatus,
        w.stepType AS type
    FROM work_in w
    LEFT JOIN step s ON w.stepNumber = s.stepNumber AND w.stepType = 'standard'
    WHERE w.projectID = ? AND w.is_active = '1'  
    ORDER BY stepName
";

// Prepare and execute the statement
$stmt = $conn->prepare($allStepsSql);
$stmt->bind_param("i", $projectId); // Bind projectId twice
$stmt->execute();
$allSteps = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();




// Fetch all materials from library for this contractor (original query maintained)
$materialsSql = "SELECT * FROM material_library WHERE contractorID = ? ";
$stmt = $conn->prepare($materialsSql);
$stmt->bind_param("i", $_SESSION['contractor_identity']['id']);
$stmt->execute();
$materials = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// Fetch materials already assigned to this project using work_materials (updated to handle custom steps)
$assignedMaterialsSql = "SELECT 
                        wm.id, wm.quantity, wm.assigned_date, wm.unused_quantity,
                        ml.id AS materialID, ml.title AS materialName, 
                        ml.category, ml.price,
                        s.stepNumber, 
                        CASE 
                            WHEN w.stepType = 'custom' THEN w.stepName
                            ELSE s.name
                        END AS stepName
                    FROM work_materials wm
                    JOIN material_library ml ON wm.materialID = ml.id
                    LEFT JOIN step s ON wm.stepNumber = s.stepNumber
                    LEFT JOIN work_in w ON wm.stepNumber = w.stepNumber AND w.projectID = ?
                    WHERE wm.projectID = ?";
$stmt = $conn->prepare($assignedMaterialsSql);
$stmt->bind_param("ii", $projectId, $projectId);
$stmt->execute();
$assignedMaterials = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Material Management - BuildEase</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
   :root {
    --primary: #1A2A3A;
    --secondary: #f8f9fa;
    --accent: #FFC107;
    --yellow-main: #FFC107;
    --yellow-dark: #E0A800;
    --header-height: 100px; /* Reduced from 120px to eliminate gap */
    --sidebar-width: 250px;
}

body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background-color: var(--secondary);
    padding-top: var(--header-height);
    margin: 0;
}

/* Header and Navigation */
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
}

.header-top a,
.header-top i,
.header-top span {
    color: var(--yellow-main) !important;
}

.main-navbar {
    background-color: white;
    position: fixed;
    top: 40px;
    left: 0;
    right: 0;
    z-index: 1020;
    height: 60px;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        border-bottom: 2px solid plum;
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
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid var(--yellow-main);
        margin-bottom: 1rem;
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

/* Main Content */
main {
    margin-left: var(--sidebar-width);
    padding: 30px;
    margin-top: 0; /* Removed margin-top to eliminate gap */
    min-height: calc(100vh - var(--header-height));
}

/* Material Management Specific Styles */
.material-tabs .nav-link {
    color: var(--yellow-main);
    font-weight: 500;
}

.material-tabs .nav-link.active {
    color: var(--yellow-main);
    background-color: var(--secondary);
}

.material-card {
    border-left: 4px solid var(--yellow-main);
    transition: all 0.2s;
}

.material-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 5px 15px #FFC107;
}

.step-selector {
    border: 1px solid #dee2e6;
    border-radius: 5px;
    padding: 10px;
    margin-bottom: 15px;
}

.logo {
    font-weight: 700;
    color: var(--primary);
}

.logo span {
    color: var(--yellow-main);
}

.nav-link.active {
    background-color: var(--yellow-main);
    color: var(--primary) !important;
}

.nav-link:hover {
    color: var(--yellow-main) !important;
}

.btn-primary {
    background-color: var(--yellow-main);
    border-color: var(--yellow-dark);
    color: var(--primary);
}

.btn-primary:hover {
    background-color: var(--yellow-dark);
    border-color: var(--yellow-dark);
    color: var(--primary);
}

.profile-img {
    width: 80px;
    height: 80px;
    object-fit: cover;
    border: 2px solid var(--yellow-main);
}

/* Custom step styling */
.step-type-toggle {
    margin-bottom: 10px;
}

.custom-step-fields {
    display: none;
    margin-top: 10px;
}

/* New style for custom step indicator */
.custom-step-indicator {
    color: #6c757d;
    font-style: italic;
}
    </style>
</head>
<body>
    <!-- Header and Navigation -->
    <div class="header-top">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6"  >
                    <div class="d-flex gap-3"  >
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>
                <div class="col-md-6 text-end">
                    <div class="d-flex gap-4 justify-content-end">
                        <span style="
     color:white ;"><i class="fas fa-map-marker-alt"></i> Lebanon</span>
                        <a href="#" style="
     color:#FFC107 ;"><i class="fas fa-mobile-alt" ></i> +961 81 111 000</a>
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


                    <li class="nav-item">
                        <a class="nav-link" href="contractorPage.php">Browse Projects</a>
                    </li>
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
            <div class="col-md-3 col-lg-2 d-md-block sidebar p-0">
                <div class="d-flex flex-column p-3">
                    <div class="text-center mb-4">
                        <img src="https://ui-avatars.com/api/?name=<?= urlencode($_SESSION['contractor_identity']['fullName'] ?? 'Contractor') ?>&background=random" 
                             class="rounded-circle profile-img mb-2">
                        <h5><?= htmlspecialchars($_SESSION['contractor_identity']['fullName'] ?? 'Contractor') ?></h5>
                        <small>Professional Contractor</small>
                    </div>
                    
                    <ul class="nav nav-pills flex-column">
                        <li class="nav-item">
                            <a href="contractorPage.php" class="nav-link">
                                <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="project_details.php?id=<?= $projectId ?>" class="nav-link">
                                <i class="fas fa-project-diagram me-2"></i> Project Details
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="add_material.php?id=<?= $projectId ?>" class="nav-link active">
                                <i class="fas fa-boxes me-2"></i> Materials
                            </a>
                        </li>
                        <li class="nav-item">
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
                <div class="container">
                    <h2 class="mb-4">Material Management</h2>
                    <p class="text-muted">Project: <?= htmlspecialchars($project['name']) ?></p>

                    <ul class="nav nav-tabs material-tabs mb-4">
                        <li class="nav-item">
                            <a class="nav-link active" data-bs-toggle="tab" href="#material-library">Material Library</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#add-material">Add New Material</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#assigned-materials">Assigned Materials</a>
                        </li>
                    </ul>

                    <div class="tab-content">
                        <!-- Material Library Tab -->
                       <div class="tab-pane fade show active" id="material-library">
    <div class="row mb-4">
        <div class="col-md-6">
            <input type="text" class="form-control" id="material-search" placeholder="Search materials...">
        </div>
        <div class="col-md-6">
            <select class="form-select" name="category" required>
                <option value="">Select category</option>
                <?php foreach ($categories as $category): ?>
                    <option value="<?= htmlspecialchars($category['name']) ?>">
                        <?= htmlspecialchars($category['name']) ?>
                        <?php if(strpos($category['name'], '(Custom)') === false): ?>
                            (Standard)
                        <?php endif; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>

    <div class="row" id="material-container">
        <?php foreach ($materials as $material): 
            // Get feedback for this material if it exists
            $feedback_sql = "SELECT wm.feedback 
                            FROM work_materials wm
                            WHERE wm.materialID = ? 
                            AND wm.projectID = ?
                            AND wm.feedback IS NOT NULL
                            LIMIT 1";
            $stmt = $conn->prepare($feedback_sql);
            $stmt->bind_param("ii", $material['id'], $projectId);
            $stmt->execute();
            $feedback_result = $stmt->get_result();
            $has_feedback = $feedback_result->num_rows > 0;
            $feedback = $has_feedback ? $feedback_result->fetch_assoc()['feedback'] : null;
            $stmt->close();
        ?>
        <div class="col-md-4 mb-4 material-item" data-category="<?= htmlspecialchars($material['category']) ?>">
            <div class="card material-card h-100">
                <div class="card-body">
                    <h5 class="card-title"><?= htmlspecialchars($material['title']) ?></h5>
                    <p class="card-text text-muted"><?= htmlspecialchars($material['category']) ?></p>
                    <p class="card-text"><strong>Price:</strong> $<?= number_format($material['price'], 2) ?></p>
                    
                    <!-- Feedback display (collapsible) -->
                    <?php if ($has_feedback): ?>
                    <div class="feedback-section mt-2">
                        <button class="btn btn-sm btn-outline-info feedback-toggle" type="button" 
                                data-bs-toggle="collapse" data-bs-target="#feedback-<?= $material['id'] ?>">
                            <i class="fas fa-comment me-1"></i> View Feedback
                        </button>
                        <div class="collapse mt-2" id="feedback-<?= $material['id'] ?>">
                            <div class="card card-body bg-light">
                                <p class="mb-0"><strong>Previous Feedback:</strong></p>
                                <p class="mb-0"><?= htmlspecialchars($feedback) ?></p>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <div class="step-selector mt-2">
                        <!-- Standard Step Selection -->
                        <div class="standard-step-section">
                            <select class="form-select form-select-sm step-assignment">
                                <option value="">Select step</option>
                                <?php foreach ($allSteps as $step): ?>
                                    <option value="<?= $step['stepNumber'] ?>" 
                                            data-step-type="<?= $step['type'] ?>">
                                        <?= ($step['type'] === 'custom') ? 
                                            htmlspecialchars($step['stepName']) : 
                                            'Step '.$step['stepNumber'].': '.htmlspecialchars($step['stepName']) ?>
                                        <?php if ($step['type'] === 'custom'): ?>
                                            <span class="custom-step-indicator"> (Custom)</span>
                                        <?php endif; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="mt-2">
                            <input type="number" class="form-control form-control-sm quantity-input" 
                                   placeholder="Quantity" min="1" value="1">
                        </div>
                        
                        <!-- Feedback input field -->
                       
                        
                        <button class="btn btn-sm btn-primary mt-2 assign-btn" 
                                data-material-id="<?= $material['id'] ?>">
                            <i class="fas fa-plus-circle me-1"></i> Assign to Step
                        </button>

                        <button class="btn btn-sm btn-outline-secondary mt-2 edit-material-btn" 
        data-material-id="<?= $material['id'] ?>"
        data-bs-toggle="modal" data-bs-target="#editMaterialModal">
    <i class="fas fa-edit me-1"></i> Edit
</button>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

                        <!-- Add New Material Tab -->
                        <div class="tab-pane fade" id="add-material">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title">Add to Material Library</h4>
                                    <form id="add-material-form" action="save_material.php" method="POST">
                                        <input type="hidden" name="project_id" value="<?= $projectId ?>">
                                        <input type="hidden" name="contractor_id" value="<?= $_SESSION['contractor_identity']['id'] ?>">
                                        
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <label class="form-label">Material Name</label>
                                                <input type="text" name="material_name" class="form-control" required>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Category</label>
                                                <select class="form-select" name="category" required>
                                                    <option value="">Select category</option>
                                                    <?php foreach ($categories as $category): ?>
                                                        <option value="<?= htmlspecialchars($category['name']) ?>">
                                                            <?= htmlspecialchars($category['name']) ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                    <!-- Add custom steps as categories -->
                                                    <?php foreach ($customSteps as $step): ?>
                                                        <option value="<?= htmlspecialchars($step['name']) ?>">
                                                            <?= htmlspecialchars($step['name']) ?> (Custom)
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>
                                        
                                        <div class="row mb-3">
                                            <div class="col-md-4">
                                                <label class="form-label">Unit Price ($)</label>
                                                <input type="number" step="0.01" name="unit_price" class="form-control" required>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label">Supplier</label>
                                                <input type="text" name="supplier" class="form-control">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label">Unit of Measure</label>
                                                <input type="text" name="unit_measure" class="form-control" placeholder="e.g., kg, m, pieces">
                                            </div>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label class="form-label">Description</label>
                                            <textarea class="form-control" name="description" rows="3"></textarea>
                                        </div>
                                        
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-plus-circle me-2"></i> Add to Library
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Assigned Materials Tab -->
                        <div class="tab-pane fade" id="assigned-materials">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Step</th>
                                            <th>Material</th>
                                            <th>Quantity</th>
                                            <th>Unit Price</th>
                                            <th>Total</th>
                                            <th>Assigned Date</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($assignedMaterials as $assignment): ?>
                                        <tr>
                                            <td>
                                                <?= $assignment['stepNumber'] ? ($assignment['stepNumber'] == 0 ? 'Custom Step' : 'Step '.$assignment['stepNumber']) : 'Not assigned' ?>: 
                                                <?= htmlspecialchars($assignment['stepName']) ?>
                                            </td>
                                            <td><?= htmlspecialchars($assignment['materialName']) ?></td>
                                            <td>
                                                <?= $assignment['quantity'] ?> 
                                                <?php if ($assignment['unused_quantity'] > 0): ?>
                                                    (<?= $assignment['unused_quantity'] ?> unused)
                                                <?php endif; ?>
                                            </td>
                                            <td>$<?= number_format($assignment['price'], 2) ?></td>
                                            <td>$<?= number_format($assignment['quantity'] * $assignment['price'], 2) ?></td>
                                            <td><?= date('Y-m-d', strtotime($assignment['assigned_date'])) ?></td>
                                            <td>
                                                <a href="edit_material_assignment.php?id=<?= $assignment['id'] ?>&project=<?= $projectId ?>&step=<?= $assignment['stepNumber'] ?>" 
                                                   class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <a href="remove_material_assignment.php?id=<?= $assignment['id'] ?>&project=<?= $projectId ?>&step=<?= $assignment['stepNumber'] ?>" 
                                                   class="btn btn-sm btn-outline-danger"
                                                   onclick="return confirm('Are you sure you want to remove this material assignment?')">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                                <?php if ($assignment['unused_quantity'] < $assignment['quantity']): ?>
                                                    <button class="btn btn-sm btn-outline-warning mark-unused-btn" 
                                                            data-assignment-id="<?= $assignment['id'] ?>"
                                                            data-max-quantity="<?= $assignment['quantity'] - $assignment['unused_quantity'] ?>">
                                                        <i class="fas fa-minus-circle"></i> Mark Unused
                                                    </button>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Unused Quantity Modal -->
    <div class="modal fade" id="unusedQuantityModal" tabindex="-1" aria-labelledby="unusedQuantityModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="unusedQuantityModalLabel">Mark Materials as Unused</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="unusedQuantityForm">
                        <input type="hidden" id="assignmentId" name="assignmentId">
                        <div class="mb-3">
                            <label for="unusedQuantity" class="form-label">Quantity to mark as unused</label>
                            <input type="number" class="form-control" id="unusedQuantity" name="unusedQuantity" min="1" value="1">
                            <small class="text-muted" id="maxQuantityHint">Maximum: <span id="maxQuantity">0</span></small>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="saveUnusedQuantity">
                        <i class="fas fa-save me-1"></i> Save
                    </button>
                </div>
            </div>
        </div>
    </div>


<!-- Edit Material Modal -->
<div class="modal fade" id="editMaterialModal" tabindex="-1" aria-labelledby="editMaterialModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editMaterialModalLabel">Edit Material</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editMaterialForm">
                    <input type="hidden" id="editMaterialId" name="material_id">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Material Name</label>
                            <input type="text" name="title" class="form-control" id="editMaterialTitle" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Category</label>
                            <select class="form-select" name="category" id="editMaterialCategory" required>
                                <option value="">Select category</option>
                                <?php foreach ($categories as $category): ?>
                                    <option value="<?= htmlspecialchars($category['name']) ?>">
                                        <?= htmlspecialchars($category['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label">Unit Price ($)</label>
                            <input type="number" step="0.01" name="price" class="form-control" id="editMaterialPrice" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Supplier</label>
                            <input type="text" name="supplier" class="form-control" id="editMaterialSupplier">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Unit of Measure</label>
                            <input type="text" name="unit_measure" class="form-control" id="editMaterialUnitMeasure" placeholder="e.g., kg, m, pieces">
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea class="form-control" name="description" id="editMaterialDescription" rows="3"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="saveMaterialChanges">
                    <i class="fas fa-save me-1"></i> Save Changes
                </button>
            </div>
        </div>
    </div>
</div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
   

    // Handle material assignment to steps (updated)
    document.querySelectorAll('.assign-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const stepSelector = this.closest('.step-selector');
            const isCustom = stepSelector.querySelector('input[id^="custom"]:checked');
            const materialId = this.getAttribute('data-material-id');
            const quantityInput = stepSelector.querySelector('.quantity-input');
            
            let stepNumber = null;
            let stepName = null;
            let stepDetails = null;
            
            if (isCustom) {
                stepName = stepSelector.querySelector('.custom-step-name').value;
                stepDetails = stepSelector.querySelector('.custom-step-details').value;
                
                if (!stepName) {
                    alert('Please enter a custom step name');
                    return;
                }
            } else {
               // In your assign-btn click handler, replace the step selection logic with:
const stepSelect = stepSelector.querySelector('.step-assignment');
stepNumber = stepSelect.value;
const stepType = stepSelect.options[stepSelect.selectedIndex].getAttribute('data-step-type');

if (!stepNumber) {
    alert('Please select a step');
    return;
}

// Get the step name from the selected option
stepName = stepSelect.options[stepSelect.selectedIndex].text;
if (stepType === 'custom') {
    stepName = stepName.replace(' (Custom)', '').trim();
}
            }
            
            if (quantityInput.value === '' || parseInt(quantityInput.value) < 1) {
                alert('Please enter a valid quantity (minimum 1)');
                return;
            }
            
            // Show loading state
            const originalText = this.innerHTML;
            this.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Assigning...';
            this.disabled = true;
            
            // Prepare form data
            const formData = new FormData();
            formData.append('project_id', <?= $projectId ?>);
            formData.append('material_id', materialId);
            formData.append('quantity', quantityInput.value);
            formData.append('is_custom', isCustom ? '1' : '0');
            
            if (isCustom) {
                formData.append('custom_step_name', stepName);
                formData.append('custom_step_details', stepDetails);
            } else {
                formData.append('step_number', stepNumber);
                if (stepName) {
                    formData.append('custom_step_name', stepName);
                }
            }
            
            // AJAX call to assign material to step
            fetch('assign_material.php', {
                method: 'POST',
                body: formData
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(err => { throw err; });
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    alert('Material assigned successfully!');
                    location.reload(); // Refresh to show the new assignment
                } else {
                    throw new Error(data.message || 'Unknown error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error: ' + error.message);
            })
            .finally(() => {
                this.innerHTML = originalText;
                this.disabled = false;
            });
        });
    });

    // Handle form submission for new material (maintained)
    document.getElementById('add-material-form').addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Show loading state
        const submitBtn = this.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Saving...';
        submitBtn.disabled = true;
        
        const formData = new FormData(this);
        
        fetch('save_material.php', {
            method: 'POST',
            body: formData
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(err => { throw err; });
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                alert('Material added to library successfully!');
                location.reload(); // Refresh to show the new material
            } else {
                throw new Error(data.message || 'Unknown error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error: ' + error.message);
        })
        .finally(() => {
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        });
    });

    // Handle mark unused button clicks (maintained)
    document.querySelectorAll('.mark-unused-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const assignmentId = this.getAttribute('data-assignment-id');
            const maxQuantity = this.getAttribute('data-max-quantity');
            
            document.getElementById('assignmentId').value = assignmentId;
            document.getElementById('unusedQuantity').value = 1;
            document.getElementById('unusedQuantity').max = maxQuantity;
            document.getElementById('maxQuantity').textContent = maxQuantity;
            
            const modal = new bootstrap.Modal(document.getElementById('unusedQuantityModal'));
            modal.show();
        });
    });

    // Save unused quantity (maintained)
    document.getElementById('saveUnusedQuantity').addEventListener('click', function() {
        const assignmentId = document.getElementById('assignmentId').value;
        const unusedQuantity = parseInt(document.getElementById('unusedQuantity').value);
        const maxQuantity = parseInt(document.getElementById('unusedQuantity').max);
        
        if (!unusedQuantity || isNaN(unusedQuantity) || unusedQuantity < 1 || unusedQuantity > maxQuantity) {
            alert(`Please enter a valid quantity between 1 and ${maxQuantity}`);
            return;
        }
        
        // Show loading state
        const saveBtn = document.getElementById('saveUnusedQuantity');
        const originalText = saveBtn.innerHTML;
        saveBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Saving...';
        saveBtn.disabled = true;
        
        fetch('mark_unused_materials.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                assignment_id: assignmentId,
                unused_quantity: unusedQuantity
            })
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(err => { throw err; });
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                // Close modal and refresh
                bootstrap.Modal.getInstance(document.getElementById('unusedQuantityModal')).hide();
                location.reload();
            } else {
                throw new Error(data.message || 'Unknown error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error: ' + error.message);
        })
        .finally(() => {
            saveBtn.innerHTML = originalText;
            saveBtn.disabled = false;
        });
    });

    // Search and filter functionality (maintained)
    document.getElementById('material-search').addEventListener('input', filterMaterials);
    document.querySelector('select[name="category"]').addEventListener('change', filterMaterials);

    function filterMaterials() {
        const searchTerm = document.getElementById('material-search').value.toLowerCase();
        const filterCategory = document.querySelector('select[name="category"]').value;
        
        document.querySelectorAll('.material-item').forEach(item => {
            const title = item.querySelector('.card-title').textContent.toLowerCase();
            const category = item.getAttribute('data-category');
            const matchesSearch = title.includes(searchTerm);
            const matchesCategory = filterCategory === '' || category === filterCategory;
            
            if (matchesSearch && matchesCategory) {
                item.style.display = 'block';
            } else {
                item.style.display = 'none';
            }
        });
    }

    // Initialize tooltips (maintained)
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Auto-focus search field when tab changes (maintained)
    document.querySelectorAll('.material-tabs .nav-link').forEach(tab => {
        tab.addEventListener('shown.bs.tab', function (event) {
            if (event.target.getAttribute('href') === '#material-library') {
                document.getElementById('material-search').focus();
            }
        });
    });



    // Handle edit material button clicks
document.querySelectorAll('.edit-material-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        const materialId = this.getAttribute('data-material-id');
        
        // Find the material in the materials array
        const material = <?= json_encode($materials) ?>.find(m => m.id == materialId);
        
        if (material) {
            // Populate the form with the material data
            document.getElementById('editMaterialId').value = material.id;
            document.getElementById('editMaterialTitle').value = material.title;
            document.getElementById('editMaterialCategory').value = material.category;
            document.getElementById('editMaterialPrice').value = material.price;
            document.getElementById('editMaterialSupplier').value = material.supplier || '';
            document.getElementById('editMaterialUnitMeasure').value = material.unit_measure || '';
            document.getElementById('editMaterialDescription').value = material.description || '';
        }
    });
});

// Handle save changes button
document.getElementById('saveMaterialChanges').addEventListener('click', function() {
    const form = document.getElementById('editMaterialForm');
    const formData = new FormData(form);
    
    // Show loading state
    const saveBtn = document.getElementById('saveMaterialChanges');
    const originalText = saveBtn.innerHTML;
    saveBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Saving...';
    saveBtn.disabled = true;
    
    fetch('update_material.php', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            return response.json().then(err => { throw err; });
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            // Close modal and refresh
            bootstrap.Modal.getInstance(document.getElementById('editMaterialModal')).hide();
            location.reload();
        } else {
            throw new Error(data.message || 'Unknown error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error: ' + error.message);
    })
    .finally(() => {
        saveBtn.innerHTML = originalText;
        saveBtn.disabled = false;
    });
});
    </script>
</body>
</html>