<?php
session_start(); 
include 'conx.php';
if (!isset($_SESSION['professional_identity']['id'])) {
    header("Location: logInPage.php");
    exit();
}

$professional_id = $_SESSION['professional_identity']['id'];
$professional_sql = "SELECT p.*, pd.areaOfWork 
                     FROM professional p 
                     LEFT JOIN professional_details pd ON p.id = pd.professionalID
                     WHERE p.id = ?";
$stmt = $conn->prepare($professional_sql);
$stmt->bind_param("i", $professional_id);
$stmt->execute();
$professional = $stmt->get_result()->fetch_assoc();
$stmt->close();





$stmt = $conn->prepare("SELECT startDate, availibilityStatus, price, priceDetails FROM professional_details WHERE professionalID = ?");
$stmt->bind_param("i", $professional_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();


$showModal = false;

if ( empty($row['price']) || empty($row['priceDetails'])) {
    $showModal = true;
}
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>BuildEase - Professional Dashboard</title>

    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/custom.css">
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" crossorigin="anonymous"/>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        /* Add this to your existing CSS */
.modal {
    z-index: 99999 !important;
}
.modal-backdrop {
    z-index: 9999 !important;
}
:root {
    --primary: #1A2A3A;
    --accent: #6a5acd; /* Purple accent */
    --light: #f8f9fa;
    --dark: #212529;
}

body {
    font-family: 'Playfair Display', serif;
    background: #f8f9fa;
    color: #495057;
    margin: 0;
    padding: 0;
}

/* Header Top Bar */
.header-area {
    background: var(--primary);
    padding: 12px 0;
    color: var(--accent);
    font-family: 'Poppins', sans-serif;
    border-bottom: 1px solid rgba(106, 90, 205, 0.1);
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

.navbar-nav .nav-link {
    color: var(--primary) !important;
    font-weight: 500;
    padding: 15px 20px !important;
    transition: 0.3s;
    font-size: 16px;
    position: relative;
}

.navbar-nav .nav-link:hover, 
.navbar-nav .nav-item.active .nav-link {
    color: #4169e1;
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
    z-index: 1000;
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

/* Main Content */
.main-content {
    margin-left: 250px;
    padding: 20px;
}

/* 3D Card Gallery */
.card-3d {
    position: relative;
    width: 500px;
    height: 300px;
    transform-style: preserve-3d;
    transform: perspective(800px);
    animation: autoRun3d 20s linear infinite;
    will-change: transform;
}

.card-3d div {
    position: absolute;
    width: 500px;
    height: 300px;
    background-color: rgb(199, 199, 199);
    border: solid 2px lightgray;
    border-radius: 0.5rem;
    top: 50%;
    left: 50%;
    transform-origin: center center;
    animation: animateBrightness 20s linear infinite;
    transition-duration: 200ms;
    will-change: transform, filter;
}

.card-3d:hover {
    animation-play-state: paused !important;
}

.card-3d:hover div {
    animation-play-state: paused !important;
}

.card-3d div:nth-child(1) {
    transform: translate(-50%, -50%) rotateY(0deg) translateZ(150px);
    animation-delay: -0s;
}

.card-3d div:nth-child(2) {
    transform: translate(-50%, -50%) rotateY(36deg) translateZ(150px);
    animation-delay: -2s;
}

.card-3d div:nth-child(3) {
    transform: translate(-50%, -50%) rotateY(72deg) translateZ(150px);
    animation-delay: -4s;
}

.card-3d div:nth-child(4) {
    transform: translate(-50%, -50%) rotateY(108deg) translateZ(150px);
    animation-delay: -6s;
}

.card-3d div:nth-child(5) {
    transform: translate(-50%, -50%) rotateY(144deg) translateZ(150px);
    animation-delay: -8s;
}

.card-3d div:nth-child(6) {
    transform: translate(-50%, -50%) rotateY(180deg) translateZ(150px);
    animation-delay: -10s;
}

.card-3d div:nth-child(7) {
    transform: translate(-50%, -50%) rotateY(216deg) translateZ(150px);
    animation-delay: -12s;
}

.card-3d div:nth-child(8) {
    transform: translate(-50%, -50%) rotateY(252deg) translateZ(150px);
    animation-delay: -14s;
}

.card-3d div:nth-child(9) {
    transform: translate(-50%, -50%) rotateY(288deg) translateZ(150px);
    animation-delay: -16s;
}

.card-3d div:nth-child(10) {
    transform: translate(-50%, -50%) rotateY(324deg) translateZ(150px);
    animation-delay: -18s;
}

.card-3d {
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    gap: 10px;
    perspective: 1000px;
}

.card-3d div {
    width: 400px;
    height: 200px;
    background: #ddd;
    border-radius: 10px;
    box-shadow: 0 5px 10px rgba(0, 0, 0, 0.3);
    transform: rotateY(10deg) rotateX(5deg);
    transition: transform 0.3s ease;
    overflow: hidden;
}

.card-3d div img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.card-3d div:hover {
    transform: rotateY(0deg) rotateX(0deg) scale(1.1);
}

.card-3d div:hover img {
    transform: scale(1.1);
}

/* Animations */
@keyframes autoRun3d {
    from {
        transform: perspective(800px) rotateY(-360deg);
    }
    to {
        transform: perspective(800px) rotateY(0deg);
    }
}

@keyframes animateBrightness {
    10% {
        filter: brightness(1);
    }
    50% {
        filter: brightness(0.1);
    }
    90% {
        filter: brightness(1);
    }
}

/* Responsive Adjustments */
@media (max-width: 991px) {
    .navbar-collapse {
        background: #FFFFFF;
        padding: 20px;
        margin-top: 15px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    }
    
    .sidebar {
        width: 100%;
        position: relative;
        height: auto;
        padding-top: 0;
    }
    
    .main-content {
        margin-left: 0;
    }
}

@media (max-width: 768px) {
    .card-3d {
        width: 100%;
        height: auto;
    }
    
    .card-3d div {
        width: 100%;
        height: auto;
    }
}

.modal {
    z-index: 99999 !important;
}
.modal-backdrop {
    z-index: 9999 !important;
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
                <li class="nav-item active"><a class="nav-link" href="index.php">Home</a></li>
                
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="professionalPage.php" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
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
                <a class="nav-link active" href="professionalPage.php">
                    <i class="fas fa-tachometer-alt"></i> Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="prof-activeProjects.php">
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
    <?php if ($showModal): ?>
<!-- Modal -->
<div class="modal fade" id="completeProfileModal" tabindex="-1" aria-labelledby="completeProfileModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="save_professional_details.php" method="POST">
        <div class="modal-header">
          <h5 class="modal-title" id="completeProfileModalLabel">Complete Your Profile</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">Price</label>
            <input type="number" name="price" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Price Details</label>
            <textarea name="priceDetails" class="form-control" required></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Save</button>
        </div>
      </form>
    </div>
  </div>
</div>
<?php endif; ?>
    <!-- Main Content -->
    <div class="main-content">
        <div class="container-fluid mt-5">
            <div class="row">
                <!-- Welcome Section -->
                <div class="col-lg-6 mb-5">
                    <div class="card shadow-sm p-4 h-100">
                        <h1 class="mb-4" style="font-size: 2.5rem; font-weight: 800; color: #1A2A3A;">Welcome to Your Professional Dashboard</h1>
                        <p class="lead mb-4" style="font-size: 1.4rem; font-weight: 500; color: #4a5568;">Manage all your projects and profile from this powerful centralized dashboard.</p>
                        <div class="d-flex gap-3 mt-4 flex-wrap">
                            <a href="prof-activeProjects.php" class="btn btn-lg btn-primary" style="font-size: 1.2rem; font-weight: 600;">
                                <i class="fas fa-hammer me-2"></i> View Active Projects
                            </a>
                            <a href="prof-completedProjects.php" class="btn btn-lg btn-outline-primary" style="font-size: 1.2rem; font-weight: 600;">
                                <i class="fas fa-check-circle me-2"></i> Completed Projects
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Image Gallery Section -->
                <div class="col-lg-6 mb-5">
                    <div class="card shadow-sm p-4 h-100">
                        <h2 class="mb-4" style="font-size: 2rem; font-weight: 700; color: #1A2A3A;">Project Gallery</h2>
                        <div class="card-3d">
                            <div><img src="images/b1.jpg" class="img-fluid" alt="Project Image"></div>
                            <div><img src="images/b4.jpg" class="img-fluid" alt="Project Image"></div>
                            <div><img src="images/ci3.avif" class="img-fluid" alt="Project Image"></div>
                            <div><img src="images/ca1.avif" class="img-fluid" alt="Project Image"></div>
                        </div>
                    </div>
                </div>

                <!-- Update Details Form -->
                <div class="col-12">
                    <div class="card shadow-sm p-4">
                       <form action="update_professional_details.php" method="POST">
    <h2 class="mb-4" style="font-size: 2rem; font-weight: 700; color: #1A2A3A;">Update Your Professional Details</h2>

    <?php
    if (isset($_SESSION['error'])) {
        echo "<p class='text-danger text-center' style='font-size: 1.2rem; font-weight: 500;'>" . $_SESSION['error'] . "</p>";
        unset($_SESSION['error']);
    }
    ?>

    <div class="row">
        <div class="col-md-4 mb-3">
            <label class="form-label" style="font-size: 1.3rem; font-weight: 600; color: #2d3748;">Price ($)</label>
            <input type="number" name="price" class="form-control form-control-lg" required
                   value="<?php echo htmlspecialchars($row['price'] ?? '', ENT_QUOTES); ?>">
        </div>

        <div class="col-md-4 mb-3">
            <label class="form-label" style="font-size: 1.3rem; font-weight: 600; color: #2d3748;">Price Details</label>
            <textarea name="priceDetails" class="form-control form-control-lg" required style="min-height: 100px;"><?php echo htmlspecialchars($row['priceDetails'] ?? '', ENT_QUOTES); ?></textarea>
        </div>
<div style="margin-bottom: 1rem;">
    <label style="font-size: 1.5rem; font-weight: 600; color: #2d3748;">Availability Status</label><br>
  <?php
$currentStatus = isset($row['availibilityStatus']) ? $row['availibilityStatus'] : '';
?>

<select name="availibilityStatus" required style="font-size: 1.3rem; padding: 0.5rem; width: 100%;">
    <option value="">
        <?php
        if ($currentStatus == '') {
            echo 'Add your availability status' ;
        } 
        ?>
    </option>

    <?php
    if ($currentStatus == 'Busy') {
        echo '<option value="Busy" selected>Busy</option>';
    } else {
        echo '<option value="Busy">Busy</option>';
    }

    if ($currentStatus == 'Available') {
        echo '<option value="Available" selected>Available</option>';
    } else {
        echo '<option value="Available">Available</option>';
    }

    if ($currentStatus == 'Not Available') {
        echo '<option value="Not Available" selected>Not Available</option>';
    } else {
        echo '<option value="Not Available">Not Available</option>';
    }
    ?>
</select>

</div>
    </div>

    <div class="text-end mt-3">
        <button type="submit" class="btn btn-primary px-4 py-2" style="font-size: 1.2rem; font-weight: 600;">
            Save Changes
        </button>
    </div>
</form>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php if ($showModal): ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var myModal = new bootstrap.Modal(document.getElementById('completeProfileModal'));
    myModal.show();
});
</script>
<?php endif; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  </body>
</html>