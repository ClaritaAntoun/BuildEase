<?php
session_start();
if (!isset($_SESSION['contractor_identity'])) {
    header("Location: logInPage.php");
    exit();
}
include 'conx.php';

// Get contractor details
$contractor_id = $_SESSION['contractor_identity']['id'];
$contractor_sql = "SELECT c.*, a.street, a.city, a.state, a.postalCode 
                   FROM contractor c 
                   LEFT JOIN address a ON c.addressID = a.addressID 
                   WHERE c.id = ?";
$stmt = $conn->prepare($contractor_sql);
$stmt->bind_param("i", $contractor_id);
$stmt->execute();
$contractor = $stmt->get_result()->fetch_assoc();
$stmt->close();

// Get contractor's projects
$projects_sql = "SELECT p.projectID, p.name, p.status, p.startDate, p.exactDuration, 
                        h.fullName as homeownerName, h.phoneNumber as homeownerPhone,
                        a.street, a.city, a.state, a.postalCode
                 FROM project p
                 JOIN creates c ON p.projectID = c.projectID
                 JOIN homeowner h ON c.homeOwnerID = h.id
                 JOIN address a ON p.addressID = a.addressID
                 WHERE p.contractorID = ?";

$stmt = $conn->prepare($projects_sql);
$stmt->bind_param("i", $contractor_id);
$stmt->execute();
$projects_result = $stmt->get_result();

// Categorize projects
$active_projects = [];
$completed_projects = [];
$pending_projects = [];

while ($project = $projects_result->fetch_assoc()) {
    switch ($project['status']) {
        case 'active':
            $active_projects[] = $project;
            break;
        case 'completed':
            $completed_projects[] = $project;
            break;
        case 'pending':
        default:
            $pending_projects[] = $project;
    }
}
$stmt->close();

// Get contractor's contracts
$contracts_sql = "SELECT * FROM contract WHERE contractorID = ?";
$stmt = $conn->prepare($contracts_sql);
$stmt->bind_param("i", $contractor_id);
$stmt->execute();
$contracts_result = $stmt->get_result();

// Categorize contracts with case-insensitive status check
$active_contracts = [];
$completed_contracts = [];

while ($contract = $contracts_result->fetch_assoc()) {
    $status = strtolower($contract['status']);
    if ($status === 'active') {
        $active_contracts[] = $contract;
    } elseif ($status === 'completed') {
        $completed_contracts[] = $contract;
    }
}
$stmt->close();

/// Get materials for active projects
$materials = [];
if (!empty($active_projects)) {
    $project_ids = array_column($active_projects, 'projectID');
    $placeholders = implode(',', array_fill(0, count($project_ids), '?'));
    $types = str_repeat('i', count($project_ids));
    
    // Updated query to use material_library and work_materials
    $materials_sql = "SELECT ml.id, ml.title, ml.category, ml.price, ml.supplier, 
                             ml.unit_measure as unit, ml.description,
                             wm.quantity, wm.assigned_date as deliveryDate,
                             p.name as projectName, s.name as stepName
                      FROM work_materials wm
                      JOIN material_library ml ON wm.materialID = ml.id
                      JOIN project p ON wm.projectID = p.projectID
                      JOIN step s ON wm.stepNumber = s.stepNumber
                      WHERE wm.projectID IN ($placeholders)";
    
    $stmt = $conn->prepare($materials_sql);
    $stmt->bind_param($types, ...$project_ids);
    $stmt->execute();
    $materials_result = $stmt->get_result();
    $materials = $materials_result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
}

// Get feedbacks for completed projects
$feedbacks = [];
if (!empty($completed_projects)) {
    $project_ids = array_column($completed_projects, 'projectID');
    $placeholders = implode(',', array_fill(0, count($project_ids), '?'));
    $types = str_repeat('i', count($project_ids));
    
    // Using ho_cont_feedback table which links homeowners to contractors
    $feedbacks_sql = "SELECT f.*, p.name as projectName, h.fullName as homeownerName
                      FROM ho_cont_feedback f
                      JOIN homeowner h ON f.homeOwnerID = h.id
                      JOIN creates c ON h.id = c.homeOwnerID
                      JOIN project p ON c.projectID = p.projectID
                      WHERE p.projectID IN ($placeholders)";
    
    $stmt = $conn->prepare($feedbacks_sql);
    $stmt->bind_param($types, ...$project_ids);
    $stmt->execute();
    $feedbacks_result = $stmt->get_result();
    $feedbacks = $feedbacks_result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contractor Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
    :root {
        --primary: #1A2A3A;
        --secondary: #f8f9fa;
        --yellow-main: #FFC107;
        --header-height: 120px;
        --sidebar-width: 250px;
    }
    
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: var(--secondary);
        padding-top: var(--header-height);
        margin: 0;
    }
    
    /* Header Top Styles */
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
        font-family: 'Poppins', sans-serif;
        transition: color 0.3s ease;
    }
    
    .header-top a:hover {
        color: #fff;
    }
    
    /* Main Navigation Styles */
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
        font-family: 'Playfair Display', serif;
        font-size: 2rem;
        font-weight: 700;
        color: var(--primary);
    }
    
    .logo span {
        color: var(--yellow-main);
        font-weight: 400;
    }
    
    .nav-link {
        color: var(--primary) !important;
        font-weight: 500;
        padding: 0.5rem 1.5rem !important;
        transition: all 0.3s ease;
    }
    
    .nav-link:hover,
    .nav-link.active {
        color: var(--yellow-main) !important;
    }
    
    /* Sidebar Styles */
    .sidebar {
        background-color: var(--primary);
        color: white;
        min-height: calc(100vh - var(--header-height));
        width: var(--sidebar-width);
        position: fixed;
        left: 0;
        top: var(--header-height);
        bottom: 0;
        overflow-y: auto;
        z-index: 1010;
        transition: all 0.3s;
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
    
    .sidebar .nav-pills {
        flex-direction: column;
    }
    
    .sidebar .nav-link {
        color: white !important;
        border-radius: 5px;
        margin: 5px 0;
        padding: 12px 15px !important;
        display: flex;
        align-items: center;
        transition: all 0.2s;
    }
    
    .sidebar .nav-link i {
        margin-right: 10px;
        width: 20px;
        text-align: center;
    }
    
    .sidebar .nav-link:hover, 
    .sidebar .nav-link.active {
        background-color: rgba(255,255,255,0.1);
    }
    
    /* Main Content Styles */
    main {
        margin-left: var(--sidebar-width);
        padding: 20px;
        margin-top: 60px;
    }
    
    /* Tab Content Styles */
    .tab-content {
        padding: 20px 0;
    }
    
    /* Card Styles */
    .card {
        margin-bottom: 20px;
        border: none;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
    
    .card-header {
        background-color: rgba(0,0,0,0.03);
        border-bottom: 1px solid rgba(0,0,0,0.125);
    }
    
    /* Table Styles */
    .table {
        margin-bottom: 0;
    }
    
    .table-striped tbody tr:nth-of-type(odd) {
        background-color: rgba(255, 193, 7, 0.1);
    }
    
    /* Status Badges */
    .badge {
        padding: 6px 10px;
        font-weight: 500;
        font-size: 0.85rem;
    }
    
    /* Button Styles */
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
    
    .btn:hover,
    .btn:focus,
    .btn-primary:hover,
    .btn-primary:focus,
    .btn-success:hover,
    .btn-success:focus,
    .btn-info:hover,
    .btn-info:focus,
    .btn-warning:hover,
    .btn-warning:focus,
    .btn-danger:hover,
    .btn-danger:focus {
        background-color: var(--yellow-main) !important;
        opacity: 0.9;
        border-color: var(--yellow-main) !important;
    }
    
    /* Contract Modal Styles */
    .contract-modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0,0,0,0.7);
        z-index: 1050;
        justify-content: center;
        align-items: center;
    }
    
    .contract-box {
        background: white;
        border-radius: 10px;
        width: 90%;
        max-width: 800px;
        padding: 25px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.3);
        max-height: 80vh;
        overflow-y: auto;
        position: relative;
    }
    
    .contract-header {
        border-bottom: 2px solid #eee;
        padding-bottom: 15px;
        margin-bottom: 20px;
    }
    
    .contract-item {
        margin-bottom: 20px;
        padding: 20px;
        border-radius: 8px;
        background-color: #f8f9fa;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }
    
    .contract-status {
        display: inline-block;
        padding: 5px 15px;
        border-radius: 20px;
        font-weight: bold;
        font-size: 0.9rem;
        margin-bottom: 10px;
    }
    
    .status-active {
        background-color: #d4edda;
        color: #155724;
    }
    
    .status-completed {
        background-color: #cce5ff;
        color: #004085;
    }
    
    .close-btn {
        position: absolute;
        top: 15px;
        right: 15px;
        background: none;
        border: none;
        font-size: 1.5rem;
        cursor: pointer;
        color: #6c757d;
    }
    
    /* Responsive Adjustments */
    @media (max-width: 991.98px) {
        :root {
            --sidebar-width: 220px;
        }
    }
    
    @media (max-width: 767.98px) {
        :root {
            --header-height: 140px;
        }
        
        .sidebar {
            width: 100%;
            position: static;
            min-height: auto;
            top: auto;
        }
        
        .main-navbar {
            top: 80px;
        }
        
        main {
            margin-left: 0;
            margin-top: 20px;
        }
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

            </div>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 d-md-block sidebar p-0">
                <div class="d-flex flex-column p-3">
                    <div class="text-center mb-4">
                        <img src="https://ui-avatars.com/api/?name=<?= urlencode($contractor['fullName']) ?>&background=random" 
                             class="rounded-circle profile-img mb-2">
                        <h5><?= htmlspecialchars($contractor['fullName']) ?></h5>
                        <small>Professional Contractor</small>
                    </div>
                    
                    <ul class="nav nav-pills flex-column">
                        <li class="nav-item">
                            <a href="#dashboard" class="nav-link active" data-bs-toggle="tab">
                                <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#active" class="nav-link" data-bs-toggle="tab">
                                <i class="fas fa-hammer me-2"></i> Active Projects
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#pending" class="nav-link" data-bs-toggle="tab">
                                <i class="fas fa-clock me-2"></i> Pending Projects
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#completed" class="nav-link" data-bs-toggle="tab">
                                <i class="fas fa-check-circle me-2"></i> Completed Projects
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
                <div class="tab-content">
                    <!-- Dashboard Tab -->
                    <div class="tab-pane fade show active" id="dashboard">
                        <h2 class="mb-4">Contractor Dashboard</h2>
                        
                        <div class="row mb-4">
                            <div class="col-md-4">
                                <div class="card text-white bg-primary mb-3">
                                    <div class="card-body">
                                        <h5 class="card-title">Active Projects</h5>
                                        <h2 class="card-text"><?= count($active_projects) ?></h2>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card text-white bg-warning mb-3">
                                    <div class="card-body">
                                        <h5 class="card-title">Pending Projects</h5>
                                        <h2 class="card-text"><?= count($pending_projects) ?></h2>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card text-white bg-success mb-3">
                                    <div class="card-body">
                                        <h5 class="card-title">Completed Projects</h5>
                                        <h2 class="card-text"><?= count($completed_projects) ?></h2>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-header">
                                <h5>Recent Activity</h5>
                            </div>
                            <div class="card-body">
                                <div class="list-group">
                                    <?php if (!empty($active_projects)): ?>
                                        <a href="#" class="list-group-item list-group-item-action">
                                            <div class="d-flex w-100 justify-content-between">
                                                <h6 class="mb-1"><?= htmlspecialchars($active_projects[0]['name']) ?></h6>
                                                <small class="text-muted">Active</small>
                                            </div>
                                            <p class="mb-1">Currently working on this project</p>
                                        </a>
                                    <?php endif; ?>
                                    <?php if (!empty($pending_projects)): ?>
                                        <a href="#" class="list-group-item list-group-item-action">
                                            <div class="d-flex w-100 justify-content-between">
                                                <h6 class="mb-1"><?= htmlspecialchars($pending_projects[0]['name']) ?></h6>
                                                <small class="text-muted">Pending</small>
                                            </div>
                                            <p class="mb-1">Awaiting your approval</p>
                                        </a>
                                    <?php endif; ?>
                                    <?php if (!empty($completed_projects)): ?>
                                        <a href="#" class="list-group-item list-group-item-action">
                                            <div class="d-flex w-100 justify-content-between">
                                                <h6 class="mb-1"><?= htmlspecialchars($completed_projects[0]['name']) ?></h6>
                                                <small class="text-muted">completed</small>
                                            </div>
                                            <p class="mb-1">Done</p>
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Active Projects Tab -->
                   <div class="tab-pane fade" id="active">
    <h2 class="mb-4">Active Projects</h2>
    
    <?php if (!empty($active_projects)): ?>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>#</th> <!-- Added numbering column -->
                        <th>Project Name</th>
                        <th>Homeowner</th>
                        <th>Location</th>
                        <th>Start Date</th>
                        <th>Duration</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $counter = 1; // Initialize counter
                    foreach ($active_projects as $project): 
                    ?>
                    <tr>
                        <td><?= $counter++ ?></td> <!-- Display and increment counter -->
                        <td><?= htmlspecialchars($project['name']) ?></td>
                        <td>
                            <?= htmlspecialchars($project['homeownerName']) ?><br>
                            <small><?= htmlspecialchars($project['homeownerPhone']) ?></small>
                        </td>
                        <td>
                            <?= htmlspecialchars($project['street']) ?><br>
                            <?= htmlspecialchars($project['city']) ?>, 
                            <?= htmlspecialchars($project['state']) ?>
                            <?= htmlspecialchars($project['postalCode']) ?>
                        </td>
                        <td><?= htmlspecialchars($project['startDate']) ?></td>
                        <td><?= htmlspecialchars($project['exactDuration']) ?></td>
                        <td><span class="badge bg-success">Active</span></td>
                        <td>
                            <a href="project_details.php?id=<?= $project['projectID'] ?>" 
                               class="btn btn-sm btn-primary">
                                <i class="fas fa-eye"></i> View
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="alert alert-info">No active projects found.</div>
    <?php endif; ?>
</div>

                    <!-- Pending Projects Tab -->
                   <div class="tab-pane fade" id="pending">
    <h2 class="mb-4">Pending Projects</h2>
    
    <?php if (!empty($pending_projects)): ?>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>#</th> <!-- Added numbering column -->
                        <th>Project Name</th>
                        <th>Homeowner</th>
                        <th>Location</th>
                        <th>Proposed Start</th>
                        <th>Duration</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $counter = 1; // Initialize counter
                    foreach ($pending_projects as $project): 
                    ?>
                    <tr>
                        <td><?= $counter++ ?></td> <!-- Display and increment counter -->
                        <td><?= htmlspecialchars($project['name']) ?></td>
                        <td>
                            <?= htmlspecialchars($project['homeownerName']) ?><br>
                            <small><?= htmlspecialchars($project['homeownerPhone']) ?></small>
                        </td>
                        <td>
                            <?= htmlspecialchars($project['street']) ?><br>
                            <?= htmlspecialchars($project['city']) ?>, 
                            <?= htmlspecialchars($project['state']) ?>
                            <?= htmlspecialchars($project['postalCode']) ?>
                        </td>
                        <td><?= htmlspecialchars($project['startDate']) ?></td>
                        <td><?= htmlspecialchars($project['exactDuration']) ?></td>
                        <td><span class="badge bg-warning">Pending</span></td>
                        <td>
                            <a href="accept_project.php?id=<?= $project['projectID'] ?>" 
                               class="btn btn-sm btn-success">
                                <i class="fas fa-check"></i> Accept
                            </a>
                            <a href="reject_project.php?id=<?= $project['projectID'] ?>" 
                               class="btn btn-sm btn-danger">
                                <i class="fas fa-times"></i> Reject
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="alert alert-info">No pending projects found.</div>
    <?php endif; ?>
</div>

                    <!-- Completed Projects Tab -->
                   <div class="tab-pane fade" id="completed">
    <h2 class="mb-4">Completed Projects</h2>
    
    <?php if (!empty($completed_projects)): ?>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>#</th> <!-- Added numbering column -->
                        <th>Project Name</th>
                        <th>Homeowner</th>
                        <th>Location</th>
                        <th>Start Date</th>
                        <th>Duration</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $counter = 1; // Initialize counter
                    foreach ($completed_projects as $project): 
                    ?>
                    <tr>
                        <td><?= $counter++ ?></td> <!-- Display and increment counter -->
                        <td><?= htmlspecialchars($project['name']) ?></td>
                        <td>
                            <?= htmlspecialchars($project['homeownerName']) ?><br>
                            <small><?= htmlspecialchars($project['homeownerPhone']) ?></small>
                        </td>
                        <td>
                            <?= htmlspecialchars($project['street']) ?><br>
                            <?= htmlspecialchars($project['city']) ?>, 
                            <?= htmlspecialchars($project['state']) ?>
                            <?= htmlspecialchars($project['postalCode']) ?>
                        </td>
                        <td><?= htmlspecialchars($project['startDate']) ?></td>
                        <td><?= htmlspecialchars($project['exactDuration']) ?></td>
                        <td><span class="badge bg-primary">Completed</span></td>
                        <td>
                            <a href="completed_project_details.php?id=<?= $project['projectID'] ?>" 
                               class="btn btn-sm btn-primary">
                                <i class="fas fa-eye"></i> View
                            </a>
                           
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="alert alert-info">No completed projects found.</div>
    <?php endif; ?>
</div>
                    
                   <!-- Materials Tab -->
<div class="tab-pane fade" id="materials">
    <h2 class="mb-4">Materials</h2>
    
    <?php if (!empty($materials)): ?>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Material</th>
                        <th>Category</th>
                        <th>Project</th>
                        <th>Step</th>
                        <th>Quantity</th>
                        <th>Unit</th>
                        <th>Price</th>
                        <th>Supplier</th>
                        <th>Delivery Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($materials as $material): ?>
                    <tr>
                        <td><?= htmlspecialchars($material['title']) ?></td>
                        <td><?= htmlspecialchars($material['category']) ?></td>
                        <td><?= htmlspecialchars($material['projectName']) ?></td>
                        <td><?= htmlspecialchars($material['stepName']) ?></td>
                        <td><?= htmlspecialchars($material['quantity']) ?></td>
                        <td><?= htmlspecialchars($material['unit']) ?></td>
                        <td>$<?= number_format($material['price'], 2) ?></td>
                        <td><?= htmlspecialchars($material['supplier']) ?></td>
                        <td><?= htmlspecialchars($material['deliveryDate']) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="alert alert-info">No materials assigned to your active projects.</div>
    <?php endif; ?>
</div>
                    
                    <!-- Feedbacks Tab -->
                    <div class="tab-pane fade" id="feedbacks">
                        <h2 class="mb-4">Project Feedbacks</h2>
                        
                        <?php if (!empty($feedbacks)): ?>
                            <div class="row">
                                <?php foreach ($feedbacks as $feedback): ?>
                                <div class="col-md-6 mb-4">
                                    <div class="card h-100">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between mb-3">
                                                <h5 class="card-title"><?= htmlspecialchars($feedback['projectName']) ?></h5>
                                                <div>
                                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                                        <i class="fas fa-star<?= $i <= $feedback['rating'] ? '' : '-empty' ?> text-warning"></i>
                                                    <?php endfor; ?>
                                                </div>
                                            </div>
                                            <h6 class="card-subtitle mb-2 text-muted">
                                                From: <?= htmlspecialchars($feedback['homeownerName']) ?>
                                            </h6>
                                            <p class="card-text"><?= htmlspecialchars($feedback['comments']) ?></p>
                                            <small class="text-muted">
                                                Posted on: <?= date('M d, Y', strtotime($feedback['feedbackDate'])) ?>
                                            </small>
                                        </div>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <div class="alert alert-info">No feedbacks received yet for your completed projects.</div>
                        <?php endif; ?>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Contract Modals -->
    <!-- Active Contracts Modal -->
    <div class="contract-modal" id="activeContractsModal">
        <div class="contract-box">
            <button class="close-btn" onclick="closeModal('activeContractsModal')">&times;</button>
            <div class="contract-header">
                <h3>Active Contracts</h3>
                <p class="text-muted">Your currently active work agreements</p>
            </div>
            
            <?php if (!empty($active_contracts)): ?>
                <?php foreach ($active_contracts as $contract): ?>
                <div class="contract-item">
                    <span class="contract-status status-active">Active</span>
                    <h4>Contract #<?= htmlspecialchars($contract['contractID']) ?></h4>
                    
                    <div class="contract-dates">
                        <div class="contract-date-item">
                            <small>Start Date</small>
                            <p><?= date('M d, Y', strtotime($contract['startDate'])) ?></p>
                        </div>
                        <div class="contract-date-item">
                            <small>End Date</small>
                            <p><?= date('M d, Y', strtotime($contract['endDate'])) ?></p>
                        </div>
                    </div>
                    
                    <div class="contract-salary">
                        Salary: $<?= number_format($contract['salary'], 2) ?>
                    </div>
                    
                    <div class="contract-terms">
                        <h5>Contract Terms</h5>
                        <p><?= nl2br(htmlspecialchars($contract['details'])) ?></p>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="alert alert-info">You currently have no active contracts.</div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Completed Contracts Modal -->
    <div class="contract-modal" id="completedContractsModal">
        <div class="contract-box">
            <button class="close-btn" onclick="closeModal('completedContractsModal')">&times;</button>
            <div class="contract-header">
                <h3>Completed Contracts</h3>
                <p class="text-muted">Your past successfully completed work agreements</p>
            </div>
            
            <?php if (!empty($completed_contracts)): ?>
                <?php foreach ($completed_contracts as $contract): ?>
                <div class="contract-item">
                    <span class="contract-status status-completed">Completed</span>
                    <h4>Contract #<?= htmlspecialchars($contract['contractID']) ?></h4>
                    
                    <div class="contract-dates">
                        <div class="contract-date-item">
                            <small>Start Date</small>
                            <p><?= date('M d, Y', strtotime($contract['startDate'])) ?></p>
                        </div>
                        <div class="contract-date-item">
                            <small>End Date</small>
                            <p><?= date('M d, Y', strtotime($contract['endDate'])) ?></p>
                        </div>
                    </div>
                    
                    <div class="contract-salary">
                        Salary: $<?= number_format($contract['salary'], 2) ?>
                    </div>
                    
                    <div class="contract-terms">
                        <h5>Contract Terms</h5>
                        <p><?= nl2br(htmlspecialchars($contract['details'])) ?></p>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="alert alert-info">You haven't completed any contracts yet.</div>
            <?php endif; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Activate tab based on URL hash
        document.addEventListener('DOMContentLoaded', function() {
            if (window.location.hash) {
                const tab = new bootstrap.Tab(document.querySelector(`a[href="${window.location.hash}"]`));
                tab.show();
            }
        });
        
        // Function to open modal
        function openModal(modalId) {
            document.getElementById(modalId).style.display = 'flex';
            document.body.style.overflow = 'hidden'; // Prevent scrolling when modal is open
        }

        // Function to close modal
        function closeModal(modalId) {
            document.getElementById(modalId).style.display = 'none';
            document.body.style.overflow = 'auto'; // Re-enable scrolling
        }

        // Event listeners for contract buttons
        document.getElementById('viewActiveContracts').addEventListener('click', function(e) {
            e.preventDefault();
            openModal('activeContractsModal');
        });

        document.getElementById('viewCompletedContracts').addEventListener('click', function(e) {
            e.preventDefault();
            openModal('completedContractsModal');
        });

        // Close modal when clicking outside the box
        window.addEventListener('click', function(event) {
            if (event.target.classList.contains('contract-modal')) {
                closeModal(event.target.id);
            }
        });
    </script>
</body>
</html>