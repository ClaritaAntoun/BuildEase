<?php
session_start();
if (!isset($_SESSION['professional_identity']['id'])) {
    header("Location: logInPage.php");
    exit();
}

include 'conx.php';
$professional_id = $_SESSION['professional_identity']['id'];

// Get active projects (with at least one in_progress step)
$active_sql = "SELECT 
                p.projectID, 
                p.name as projectName, 
                p.startDate,
                p.budget,
                a.street, 
                a.city, 
                a.state, 
                a.postalCode,
                c.fullName as contractorName
             FROM project p
             LEFT JOIN address a ON p.addressID = a.addressID
             LEFT JOIN contractor c ON p.contractorID = c.id
             LEFT JOIN work_in wi ON p.projectID = wi.projectID
             WHERE wi.professionalID = ? AND wi.stepStatus = 'pending' AND wi.is_active = 1
             GROUP BY p.projectID
             ORDER BY p.startDate DESC";

$stmt = $conn->prepare($active_sql);
$stmt->bind_param("i", $professional_id);
$stmt->execute();
$active_projects = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// Get professional details for sidebar
$professional_sql = "SELECT p.*, a.street, a.city, a.state, a.postalCode, pd.areaOfWork 
                     FROM professional p 
                     LEFT JOIN address a ON p.addressID = a.addressID
                     LEFT JOIN professional_details pd ON p.id = pd.professionalID
                     WHERE p.id = ?";
$stmt = $conn->prepare($professional_sql);
$stmt->bind_param("i", $professional_id);
$stmt->execute();
$professional = $stmt->get_result()->fetch_assoc();
$stmt->close();
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Pending Projects - BuildEase</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
    :root {
        --primary: #1A2A3A;
        --accent: #6a5acd;
        --light: #f8f9fa;
        --dark: #212529;
    }

    body {
        font-family: 'Poppins', sans-serif;
        background-color: #f8f9fa;
        color: #495057;
        padding-left: 250px; /* Add padding to prevent sidebar overlap */
    }

    /* Header Top Bar */
    .header-area {
        background: var(--primary);
        padding: 12px 0;
        color: var(--accent);
        font-family: 'Poppins', sans-serif;
        border-bottom: 1px solid rgba(106, 90, 205, 0.1);
        position: fixed;
        width: 100%;
        top: 0;
        z-index: 1000;
    }

    .header-left a, .header-right ul li a {
        color: var(--accent);
        font-weight: 500;
        transition: 0.3s;
        font-size: 15px;
    }
    .header-left a:hover, .header-right ul li a:hover {
        color: #FFFFFF;
        text-decoration: none;
    }

    /* Navigation Bar */
    .navigation {
        background: #FFFFFF;
        padding: 20px 0;
        box-shadow: 0 4px 30px rgba(0, 0, 0, 0.08);
        border-bottom: 2px solid #9370db;
        position: fixed;
        width: 100%;
        top: 56px; /* Adjust based on header height */
        z-index: 1000;
    }

    .logo {
        font-family: 'Playfair Display', serif;
        font-size: 36px;
        font-weight: 700;
        color: var(--primary);
        letter-spacing: 0.5px;
    }
    .logo span {
        color: var(--accent);
        font-weight: 400;
    }

    .navbar-nav {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        width: 100%;
    }

    .navbar-nav .nav-link {
        color: var(--primary) !important;
        font-weight: 500;
        padding: 15px 20px !important;
        transition: 0.3s;
        font-size: 16px;
        position: relative;
        margin: 0 5px;
    }

    .navbar-nav .nav-link:hover, 
    .navbar-nav .nav-item.active .nav-link {
        color: var(--accent) !important;
    }

    .navbar-nav .nav-link:hover::after {
        content: '';
        position: absolute;
        bottom: -2px;
        left: 0;
        right: 0;
        height: 2px;
        background: var(--accent);
    }

    /* Dropdown Menu */
    .dropdown-menu {
        background: #FFFFFF;
        border: 1px solid rgba(26, 42, 58, 0.1);
        border-radius: 4px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        margin-top: 10px !important;
    }
    .dropdown-menu .dropdown-item {
        color: var(--primary) !important;
        padding: 12px 25px;
        font-size: 15px;
        transition: 0.3s;
    }
    .dropdown-menu .dropdown-item:hover {
        background: #F8F9FA;
        color: var(--accent) !important;
    }

    /* Sidebar Styles */
    .sidebar {
        background-color: var(--primary);
        color: white;
        height: 100vh;
        position: fixed;
        left: 0;
        top: 0;
        width: 250px;
        overflow-y: auto;
        padding-top: 120px; /* Space for header */
        z-index: 900; /* Lower than header */
    }

    .profile-card {
        text-align: center;
        padding: 1.5rem;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    .profile-img {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid var(--accent);
        margin-bottom: 1rem;
    }

    .profile-name {
        font-size: 1.2rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
    }

    .profile-role {
        background-color: var(--accent);
        color: white;
        padding: 0.25rem 0.75rem;
        border-radius: 50px;
        font-size: 0.85rem;
        display: inline-block;
        margin-bottom: 1rem;
    }

    .sidebar .nav-link {
        color: white !important;
        border-radius: 5px;
        margin: 0.25rem 1rem;
        padding: 0.75rem 1rem;
        transition: all 0.3s ease;
    }

    .sidebar .nav-link:hover,
    .sidebar .nav-link.active {
        background-color: rgba(106, 90, 205, 0.3);
        color: white !important;
    }

    .sidebar .nav-link i {
        width: 20px;
        text-align: center;
        margin-right: 0.5rem;
        color: var(--accent);
    }

    .sidebar .nav-link.active {
        border-left: 3px solid var(--accent);
        padding-left: calc(1rem - 3px);
    }

    /* Main Content Styles */
    .main-content {
        margin-top: 120px; /* Space for fixed header and navigation */
        padding: 20px;
        width: calc(100% - 250px); /* Account for sidebar width */
        margin-left: auto; /* Push content to the right of sidebar */
    }

    /* Project Card Styles */
    .project-card {
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        margin-bottom: 30px;
        border: none;
    }

    .project-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
    }

    .project-header {
        background-color: var(--primary);
        color: white;
        padding: 15px 20px;
    }

    .project-body {
        padding: 20px;
    }

    .project-footer {
        background-color: #f8f9fa;
        padding: 15px 20px;
        border-top: 1px solid #eee;
    }

    .badge-active {
        background-color: #17a2b8;
        color: white;
    }

    .badge-pending {
        background-color: #ffc107;
        color: #212529;
    }

    .section-title {
        font-family: 'Playfair Display', serif;
        font-weight: 700;
        color: var(--primary);
        margin-bottom: 30px;
        position: relative;
    }

    .section-title:after {
        content: '';
        position: absolute;
        left: 0;
        bottom: -10px;
        width: 50px;
        height: 3px;
        background-color: var(--accent);
    }

    .empty-state {
        text-align: center;
        padding: 50px 20px;
        background-color: white;
        border-radius: 10px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
    }

    .empty-state i {
        font-size: 60px;
        color: #6c757d;
        margin-bottom: 20px;
    }

    .btn-accent {
        background-color: var(--accent);
        color: white;
        border: none;
    }

    .btn-accent:hover {
        background-color: #5a4cb3;
        color: white;
    }

    .project-meta {
        display: flex;
        align-items: center;
        margin-bottom: 10px;
    }

    .project-meta i {
        margin-right: 10px;
        color: var(--accent);
        width: 20px;
        text-align: center;
    }

    .step-item {
        padding: 8px 0;
        border-bottom: 1px solid #eee;
    }

    .step-status {
        float: right;
        font-size: 12px;
        padding: 3px 8px;
        border-radius: 10px;
    }

    .step-completed {
        background-color: #d4edda;
        color: #155724;
    }

    .step-ongoing {
        background-color: #fff3cd;
        color: #856404;
    }

    .step-pending {
        background-color: #f8d7da;
        color: #721c24;
    }

    /* Responsive adjustments */
    @media (max-width: 991px) {
        body {
            padding-left: 0;
        }
        
        .sidebar {
            width: 100%;
            position: relative;
            height: auto;
            padding-top: 0;
            margin-top: 120px;
        }
        
        .main-content {
            width: 100%;
            margin-left: 0;
            margin-top: 20px;
        }
        
        .header-area, .navigation {
            position: relative;
            top: auto;
        }
    }
</style>
</head>
<body>
    <!-- Header -->
    <div class="header" id="header">
        <header class="header-area">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-md-3 text-left">
                        <div class="header-left">
                            <a href="#"><i class="fab fa-facebook-f"></i></a>
                            <a href="#"><i class="fab fa-twitter"></i></a>
                            <a href="#"><i class="fab fa-linkedin-in"></i></a>
                        </div>
                    </div>
                    <div class="col-md-9 text-right">
                        <div class="header-right">
                            <ul class="list-inline mb-0">
                                <li class="list-inline-item"><i class="fas fa-map-marker-alt"></i> Lebanon</li>
                                <li class="list-inline-item"><i class="fas fa-mobile-alt"></i> <a href="#">+961 81 111 000</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <nav class="navigation">
            <div class="container">
                <nav class="navbar navbar-expand-lg">
                    <a class="navbar-brand" href="index.php">
                        <div class="logo">Build<span>Ease</span></div>
                    </a>
                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarNav">
                        <ul class="navbar-nav ml-auto">
                            <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                            
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <?php echo isset($_SESSION['professional_identity']['fullName']) ? $_SESSION['professional_identity']['fullName'] : 'Profile'; ?>
                                </a>
                                <ul class="dropdown-menu" aria-labelledby="userDropdown">
                                    <li><a class="dropdown-item" href="professionalProfile.php">Profile</a></li>
                                    <li><a class="dropdown-item" href="logOut.php">Sign Out</a></li>
                                </ul>
                            </li>

                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="feedbackDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    Check feedbacks
                                </a>
                                <ul class="dropdown-menu" aria-labelledby="feedbackDropdown">
                                    <li><a class="dropdown-item" href="C_P_browsingFeedback.php">Contractors feedbacks</a></li>
                                    <li><a class="dropdown-item" href="HO_P_browsingFeedback.php">Home owners feedbacks</a></li>
                                </ul>
                            </li>

                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="projectsDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    Projects
                                </a>
                                <ul class="dropdown-menu" aria-labelledby="projectsDropdown">
                                    <li><a class="dropdown-item" href="prof-completedProjects.php">Completed projects</a></li>
                                    <li><a class="dropdown-item" href="prof-activeProjects.php">Active projects</a></li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </nav>
            </div>
        </nav>
    </div>

    <!-- Sidebar -->
    <div class="sidebar">
        <div class="profile-card">
            <img src="https://ui-avatars.com/api/?name=<?= urlencode($professional['fullName']) ?>&background=random&color=fff" 
                 class="profile-img" alt="Profile Image">
            <h5 class="profile-name"><?= htmlspecialchars($professional['fullName']) ?></h5>
            <span class="profile-role"><?= htmlspecialchars($professional['areaOfWork'] ?? 'Professional') ?></span>
        </div>

        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link" href="professionalPage.php">
                    <i class="fas fa-tachometer-alt"></i> Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" href="prof-activeProjects.php">
                    <i class="fas fa-hammer"></i> Active Projects
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="prof-completedProjects.php">
                    <i class="fas fa-check-circle"></i> Completed Projects
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="prof-PendingProjects.php">
                    <i class="fas fa-tachometer-alt"></i> Pending Projects
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="professionalProfile.php">
                    <i class="fas fa-user"></i> My Profile
                </a>
            </li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="container mt-5">
            <div class="row">
                <div class="col-12">
                    <h1 class="section-title">Pending Projects</h1>
                    <p class="text-center text-muted">Here are  the projects you will work on</p>
                    
                    <?php if (!empty($active_projects)): ?>
                        <div class="row">
                            <?php foreach ($active_projects as $project): 
                                // Get all steps (both in_progress and pending) for this project
                                $steps_sql = "SELECT wi.stepNumber, 
       COALESCE(s.name, wi.stepName, 'Custom Step') AS stepName,
       wi.stepStatus, 
       wi.startDate, 
       wi.endDate 
FROM work_in wi
LEFT JOIN step s ON wi.stepNumber = s.stepNumber
WHERE wi.projectID = ? 
  AND wi.professionalID = ? 
  AND wi.is_active = 1
  AND (wi.stepStatus = 'in_progress' OR wi.stepStatus = 'pending')
ORDER BY wi.stepNumber";
                                $stmt = $conn->prepare($steps_sql);
                                $stmt->bind_param("ii", $project['projectID'], $professional_id);
                                $stmt->execute();
                                $steps_result = $stmt->get_result();
                                $steps = $steps_result->fetch_all(MYSQLI_ASSOC);
                                $stmt->close();
                                
                                // Determine the overall project status
                                $has_in_progress = false;
                                $has_pending = false;
                                foreach ($steps as $step) {
                                    if ($step['stepStatus'] == 'in_progress') {
                                        $has_in_progress = true;
                                    } elseif ($step['stepStatus'] == 'pending') {
                                        $has_pending = true;
                                    }
                                }
                                
                                $project_status = $has_in_progress ? 'Active' : 'Pending';
                                $badge_class = $has_in_progress ? 'badge-active' : 'badge-pending';
                            ?>
                                <div class="col-lg-6">
                                    <div class="card project-card">
                                        <div class="project-header d-flex justify-content-between align-items-center">
                                            <h5 class="mb-0"><?= htmlspecialchars($project['projectName']) ?></h5>
                                            <span class="badge <?= $badge_class ?>"><?= $project_status ?></span>
                                        </div>
                                        
                                        <div class="project-body">
                                            <div class="project-meta">
                                                <i class="fas fa-user-tie"></i>
                                                <div>
                                                    <h6 class="mb-0">Contractor</h6>
                                                    <p class="mb-0"><?= htmlspecialchars($project['contractorName']) ?></p>
                                                </div>
                                            </div>
                                            
                                            <div class="project-meta">
                                                <i class="fas fa-map-marker-alt"></i>
                                                <div>
                                                    <h6 class="mb-0">Location</h6>
                                                    <p class="mb-0">
                                                        <?= htmlspecialchars($project['street']) ?>, 
                                                        <?= htmlspecialchars($project['city']) ?>, 
                                                        <?= htmlspecialchars($project['state']) ?>
                                                    </p>
                                                </div>
                                            </div>
                                            
                                            <div class="row mt-3">
                                                <div class="col-md-6">
                                                    <div class="project-meta">
                                                        <i class="fas fa-calendar-alt"></i>
                                                        <div>
                                                            <h6 class="mb-0">Start Date</h6>
                                                            <p class="mb-0"><?= date('M d, Y', strtotime($project['startDate'])) ?></p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="project-meta">
                                                        <i class="fas fa-money-bill-wave"></i>
                                                        <div>
                                                            <h6 class="mb-0">Budget</h6>
                                                            <p class="mb-0">$<?= number_format($project['budget'], 2) ?></p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <!-- Steps List -->
                                            <div class="mt-3">
                                                <h6>Steps:</h6>
                                                <?php if (!empty($steps)): ?>
                                                    <div class="steps-list">
                                                        <?php foreach ($steps as $step): ?>
                                                            <div class="step-item">
                                                                <?= htmlspecialchars($step['stepName']) ?>
                                                                <span class="step-status 
                                                                    <?= $step['stepStatus'] == 'completed' ? 'step-completed' : 
                                                                       ($step['stepStatus'] == 'in_progress' ? 'step-ongoing' : 'step-pending') ?>">
                                                                    <?= ucfirst(str_replace('_', ' ', $step['stepStatus'])) ?>
                                                                </span>
                                                            </div>
                                                        <?php endforeach; ?>
                                                    </div>
                                                <?php else: ?>
                                                    <p class="text-muted">No steps assigned yet</p>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        
                                        <div class="project-footer d-flex justify-content-between align-items-center">
                                            <div>
                                                <small class="text-muted">Started: <?= htmlspecialchars($project['startDate']) ?></small>
                                            </div>
                                            <?php if (!empty($steps)): ?>
                                                <div class="dropdown">
                                                    <button class="btn btn-sm btn-accent dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                                        View Details <i class="fas fa-arrow-right ms-1"></i>
                                                    </button>
                                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                        <?php foreach ($steps as $step): ?>
                                                            <li>
                                                               <a class="dropdown-item" href="project_management.php?project=<?= $project['projectID'] ?>&step=<?= $step['stepNumber'] ?>">
                                                            </li>
                                                        <?php endforeach; ?>
                                                    </ul>
                                                </div>
                                            <?php else: ?>
                                                <a href="project_management.php?project=<?= $project['projectID'] ?>" class="btn btn-sm btn-accent">
                                                    View Details <i class="fas fa-arrow-right ms-1"></i>
                                                </a>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="empty-state">
                            <i class="fas fa-folder-open"></i>
                            <h3>No Active Projects</h3>
                            <p class="text-muted">You don't have any active projects at the moment.</p>
                            <a href="professionalPage.php" class="btn btn-accent mt-3">Back to Dashboard</a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Toggle sidebar on mobile
        document.addEventListener('DOMContentLoaded', function() {
            const toggleBtn = document.querySelector('.toggle-btn');
            const sidebar = document.querySelector('.sidebar');
            const mainContent = document.querySelector('.main-content');

            if (toggleBtn) {
                toggleBtn.addEventListener('click', function() {
                    sidebar.classList.toggle('active');
                    mainContent.classList.toggle('active');
                });
            }

            // Auto-hide sidebar on small screens when clicking a link
            const navLinks = document.querySelectorAll('.nav-link');
            navLinks.forEach(link => {
                link.addEventListener('click', function() {
                    if (window.innerWidth < 768) {
                        if (sidebar) sidebar.classList.remove('active');
                        if (mainContent) mainContent.classList.remove('active');
                    }
                });
            });
        });
    </script>
</body>
</html>