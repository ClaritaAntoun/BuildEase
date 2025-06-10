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

// Verify assignment exists
$stmt = $conn->prepare("SELECT wm.*, ml.title, ml.category, ml.price, ml.supplier, ml.unit_measure, ml.description, ml.contractorID
                       FROM work_materials wm
                       JOIN material_library ml ON wm.materialID = ml.id
                       WHERE wm.id = ? AND wm.projectID = ? AND wm.stepNumber = ?");
$stmt->bind_param("iii", $assignmentId, $projectId, $stepNumber);
$stmt->execute();
$assignment = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$assignment) {
    die("No assignment found with ID $assignmentId for Project $projectId, Step $stepNumber");
}

// Verify material belongs to current contractor
if ($assignment['contractorID'] != $_SESSION['contractor_identity']['id']) {
    die("You don't have permission to edit this material assignment");
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $quantity = (int)($_POST['quantity'] ?? 0);
    $title = trim($_POST['title'] ?? '');
    $category = trim($_POST['category'] ?? '');
    $price = (float)($_POST['price'] ?? 0);
    $supplier = trim($_POST['supplier'] ?? '');
    $unit_measure = trim($_POST['unit_measure'] ?? '');
    $description = trim($_POST['description'] ?? '');
    
    $errors = [];
    
    if ($quantity < 1) {
        $errors[] = "Quantity must be at least 1";
    }
    if (empty($title)) {
        $errors[] = "Title is required";
    }
    if (empty($category)) {
        $errors[] = "Category is required";
    }
    if ($price <= 0) {
        $errors[] = "Price must be greater than 0";
    }
    
    if (empty($errors)) {
        // Begin transaction
        $conn->begin_transaction();
        
        try {
            // Update work materials
            $updateWorkSql = "UPDATE work_materials 
                             SET quantity = ?, assigned_date = CURRENT_DATE()
                             WHERE id = ? AND projectID = ? AND stepNumber = ?";
            $stmt = $conn->prepare($updateWorkSql);
            $stmt->bind_param("iiii", $quantity, $assignmentId, $projectId, $stepNumber);
            $stmt->execute();
            $stmt->close();
            
            // Commit transaction
            $conn->commit();
            
            header("Location: step_details.php?step=$stepNumber");
            exit();
        } catch (Exception $e) {
            // Rollback transaction on error
            $conn->rollback();
            $error = "Failed to update: " . $e->getMessage();
        }
    } else {
        $error = implode("<br>", $errors);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Material Assignment - BuildEase</title>
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
        --header-height: 100px;
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
        border-bottom: 2px solid var(--yellow-main);
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
        border-bottom: 2px solid var(--yellow-main);
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

    /* Main Content */
    main {
        margin-left: var(--sidebar-width);
        padding: 30px;
        margin-top: 0;
        min-height: calc(100vh - var(--header-height));
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
    
    /* Form specific styles */
    .card-header {
        background-color: var(--primary);
        color: white;
    }
    
    .card-header h4 {
        color: var(--yellow-main);
    }
    </style>
</head>
<body>
    <!-- Header and Navigation -->
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
                    <li class="nav-item active"><a class="nav-link" href="contractDetails.php">Contract</a></li>

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
                    <div class="row justify-content-center">
                        <div class="col-md-8">
                            <div class="card">
                                <div class="card-header">
                                    <h4>Edit Material Assignment</h4>
                                </div>
                                <div class="card-body">
                                    <?php if (isset($error)): ?>
                                        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                                    <?php endif; ?>
                                    
                                    <form method="POST">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="title" class="form-label">Material Title</label>
                                                    <input type="text" id="title" name="title" class="form-control" 
                                                           value="<?= htmlspecialchars($assignment['title']) ?>" readonly>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="category" class="form-label">Category</label>
                                                    <input type="text" id="category" name="category" class="form-control" 
                                                           value="<?= htmlspecialchars($assignment['category']) ?>" readonly>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label for="price" class="form-label">Unit Price ($)</label>
                                                    <input type="number" id="price" name="price" class="form-control" 
                                                           step="0.01" min="0.01" value="<?= htmlspecialchars($assignment['price']) ?>" readonly>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label for="quantity" class="form-label">Quantity</label>
                                                    <input type="number" id="quantity" name="quantity" class="form-control" 
                                                           min="1" value="<?= htmlspecialchars($assignment['quantity']) ?>" required>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label for="unit_measure" class="form-label">Unit of Measure</label>
                                                    <input type="text" id="unit_measure" name="unit_measure" class="form-control" 
                                                           value="<?= htmlspecialchars($assignment['unit_measure'] ?? '') ?>" readonly>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label for="supplier" class="form-label">Supplier</label>
                                            <input type="text" id="supplier" name="supplier" class="form-control" 
                                                   value="<?= htmlspecialchars($assignment['supplier'] ?? '') ?>" readonly>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label for="description" class="form-label">Description</label>
                                            <textarea id="description" name="description" class="form-control" rows="3" readonly><?= 
                                                htmlspecialchars($assignment['description'] ?? '') ?></textarea>
                                        </div>
                                        
                                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                            <button type="submit" class="btn btn-primary me-md-2">Update Assignment</button>
                                            <a href="step_details.php?step=<?= $stepNumber ?>" class="btn btn-secondary">Cancel</a>
                                            <a href="add_material.php?step=<?= $stepNumber ?>" class="btn btn-secondary">Back</a>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>