<!-- Clarita Antoun -->
<?php
 session_start();
 include 'conx.php';
 if (!isset($_SESSION['homeOwner_identity']['id'])) {
    header("Location: logInPage.php");
    exit();
  }
  $homeOwnerId = $_SESSION['homeOwner_identity']['id'];

  if (isset($_SESSION['alert_message'])) {
    echo "<script>alert('" . $_SESSION['alert_message'] . "');</script>";
    unset($_SESSION['alert_message']); 
}
  
$sqlPending = "SELECT project.*, address.*, HomeOwner.fullName AS homeOwnerName, Contractor.fullName AS contractorName
               FROM project
               JOIN address ON project.addressID = address.addressID
               JOIN Creates ON project.projectID = Creates.projectID
               JOIN HomeOwner ON Creates.homeOwnerID = HomeOwner.id
               LEFT JOIN Contractor ON project.contractorID = Contractor.id
               WHERE Creates.homeOwnerID = ? AND project.status = 'pending'";

$stmtPending = $conn->prepare($sqlPending);
$stmtPending->bind_param("i", $homeOwnerId);
$stmtPending->execute();
$resultPending = $stmtPending->get_result();

$sqlAccepted = "SELECT project.*, address.*, HomeOwner.fullName AS homeOwnerName, Contractor.fullName AS contractorName
                FROM project
                JOIN address ON project.addressID = address.addressID
                JOIN Creates ON project.projectID = Creates.projectID
                JOIN HomeOwner ON Creates.homeOwnerID = HomeOwner.id
                LEFT JOIN Contractor ON project.contractorID = Contractor.id
                WHERE Creates.homeOwnerID = ? AND project.status = 'active'";

$stmtAccepted = $conn->prepare($sqlAccepted);
$stmtAccepted->bind_param("i", $homeOwnerId);
$stmtAccepted->execute();
$resultAccepted = $stmtAccepted->get_result();

$sqlRejected = "SELECT project.*, address.*, HomeOwner.fullName AS homeOwnerName, Contractor.fullName AS contractorName
                FROM project
                JOIN address ON project.addressID = address.addressID
                JOIN Creates ON project.projectID = Creates.projectID
                JOIN HomeOwner ON Creates.homeOwnerID = HomeOwner.id
                LEFT JOIN Contractor ON project.contractorID = Contractor.id 
                -- even if  contID  is null project will be fetched
                WHERE Creates.homeOwnerID = ? AND project.status = 'rejected'";

$stmtRejected = $conn->prepare($sqlRejected);
$stmtRejected->bind_param("i", $homeOwnerId);
$stmtRejected->execute();
$resultRejected = $stmtRejected->get_result();
 ?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>BuildEase - Premium Home Construction</title>

    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/custom.css">
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" crossorigin="anonymous"/>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
     
@import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500&family=Lato:wght@300;400&display=swap');
.table {
    background-color: rgba(0, 0, 0, 0.9); 
    border: 1px solid brown; 
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
    font-family: 'Lato', sans-serif;
    backdrop-filter: blur(5px);
    font-size: 1rem; /* increased font size */
}

.table thead {
    background: linear-gradient(45deg, #1a1a1a, #333);
    border-bottom: 2px solid brown;
}

.table thead th {
    font-family: 'Playfair Display', serif;
    color: whitesmoke; 
    font-weight: 500;
    letter-spacing: 0.05em;
    border-right: 1px solid rgba(255, 165, 23, 0.3);
    font-size: 1.2rem; /* larger headers */
}

.table tbody td {
    color: #f5f5f5;
    background-color: rgba(0, 0, 0, 0.7);
    vertical-align: middle;
    border-color: rgba(255, 165, 23, 0.2);
    position: relative;
    font-size: 1rem; /* body font size */
}

/* Action Buttons */
.btn-primary {
    background-color:brown; 
    border-color: brown;
    color: #fff;
    padding: 6px 18px;
    border-radius: 20px;
    font-size: 0.9em;
}

.btn-warning {
    background-color: #333; /* Dark grey for warning */
    border-color: #666;
    color: #fff;
    padding: 6px 18px;
    border-radius: 20px;
    font-size: 0.9em;
}

.btn-primary:hover {
    background-color: brown;
    border-color: brown;
}

.btn-warning:hover {
    background-color: #666;
    border-color: #777;
}

/* Section Headers */
h2, h3 {
    font-family: 'Playfair Display', serif;
    color:brown; 
    margin: 2rem 0 1.5rem;
    position: relative;
    padding-left: 1.5rem;
}

h2:before, h3:before {
    content: '';
    position: absolute;
    left: 0;
    bottom: -4px;
    width: 40px;
    height: 2px;
    background:brown; 
}

/* Empty State */
.text-danger {
    color: #b87d7d !important; /* Dusty rose */
    font-style: italic;
}
    body {
        font-family: 'Playfair Display', serif;
        background: #f8f9fa;
        color: #495057;
    }

    /* Header Top Bar */
    .header-area {
        background: #1A2A3A; /* Deep navy blue */
        padding: 12px 0;
        color:brown;
        font-family: 'Poppins', sans-serif;
        border-bottom: 1px solid rgba(212, 175, 55, 0.1);
    }

    .header-left a, .header-right ul li a {
        color:brown;
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
        border-bottom: 2px solid plum;
    }

    .logo {
        font-family: 'Playfair Display', serif;
        font-size: 36px;
        font-weight: 700;
        color: #1A2A3A;
        letter-spacing: 0.5px;
    }
    .logo span {
        color:brown;
        font-weight: 400;
    }

    .navbar-nav .nav-link {
        color: #1A2A3A !important;
        font-weight: 500;
        padding: 15px 20px !important;
        transition: 0.3s;
        font-size: 16px;
        position: relative;
    }

    .navbar-nav .nav-link:hover, 
    .navbar-nav .nav-item.active .nav-link {
        color:yellow;
    }

    .navbar-nav .nav-link:hover::after {
        content: '';
        position: absolute;
        bottom: -2px;
        left: 0;
        right: 0;
        height: 2px;
        background:brown;
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
        color: #1A2A3A !important;
        padding: 12px 25px;
        font-size: 15px;
        transition: 0.3s;
    }
    .dropdown-menu .dropdown-item:hover {
        background: #F8F9FA;
        color:brown !important;
    }

    /* Call to Action Button */
    .appoint-btn a {
        background:brown;
        color: #1A2A3A !important;
        padding: 14px 30px;
        border-radius: 30px;
        font-weight: 600;
        transition: 0.3s;
        letter-spacing: 0.5px;
        border: 2px solid transparent;
    }
    .appoint-btn a:hover {
        background: #1A2A3A;
        color: #FFFFFF !important;
        border-color:yellow;
        transform: translateY(-2px);
    }

   
    @media (max-width: 991px) {
        .navbar-collapse {
            background: #FFFFFF;
            padding: 20px;
            margin-top: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }
    }
    .build-project-btn {
    background: linear-gradient(135deg, #f5a623, #ff8c00);
    color: white;
    font-size: 22px;
    font-weight: bold;
    padding: 15px 35px;
    border-radius: 8px;
    box-shadow: 0px 5px 15px rgba(245, 166, 35, 0.4);
    transition: all 0.3s ease;
    text-transform: uppercase;
    display: inline-block;
}

.build-project-btn:hover {
    background: linear-gradient(135deg, #ff8c00, #f5a623);
    box-shadow: 0px 8px 20px rgba(245, 166, 35, 0.5);
    transform: scale(1.05);
}

/* From Uiverse.io by musashi-13 */ 
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

.card-3d {
  position: relative;
  width: 500px;
  height:300px;
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


</style>



  </head>
  <body>
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
    <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
        <?php echo isset($_SESSION['homeOwner_identity']['fullName']) ? $_SESSION['homeOwner_identity']['fullName'] : 'Profile'; ?>
    </a>
    <ul class="dropdown-menu" aria-labelledby="userDropdown">
        <li><a class="dropdown-item" href="homeOwnerProfile.php">Profile</a></li>
        <li><a class="dropdown-item" href="logOut.php">Sign Out</a></li>
    </ul>
</li>



               <li class="nav-item active"><a class="nav-link" href="HbrowseProfessionals.php">Browse professionals</a></li>
               <li class="nav-item active"><a class="nav-link" href="feedbackHomeOwner.php">Give your feedback</a></li>
                
               
              </ul>
            </div>
          </nav>
        </div>
      </nav>
    </div>

    <div class="container mt-5">
    <div class="row align-items-center">
        <!-- Carousel Column -->
        <div class="col-md-6">
            <div class="card-3d">
                <div><img src="images/b1.jpg" class="img-fluid" alt=""></div>
                <div><img src="images/b4.jpg" class="img-fluid" alt=""></div>
                <div><img src="images/ci3.avif" class="img-fluid" alt=""></div>
                <div><img src="images/ca1.avif" class="img-fluid" alt=""></div>
                <div><img src="images/e3.jpg" class="img-fluid" alt=""></div>
                <div><img src="images/g1.jpeg" class="img-fluid" alt=""></div>
                <div><img src="images/l3.webp" class="img-fluid" alt=""></div>
                <div><img src="images/p2.jpeg" class="img-fluid" alt=""></div>
                <div><img src="images/pa2.jpeg" class="img-fluid" alt=""></div>
                <div><img src="images/t3.jpg" class="img-fluid" alt=""></div>
            </div>
        </div>

        <!-- Text Column -->
        <div class="col-md-6 d-flex flex-column justify-content-center">
            <h2>Welcome to your Dashboard</h2>
            <p>Discover the best construction professionals and build your dream home with ease.</p>
            <div class="text-center mt-4">
                <a href="start_project.php" class="btn btn-primary build-project-btn">
                    <i class="fas fa-hammer me-2"></i> Build a Project
                </a>
            </div>
        </div>
    </div>
</div>

   <br>
   <br>
   <br>
   <br> <br>
   <br>
<div class="container mt-5">
 <h2>📋 Pending Projects</h2>
    <table class="table table-pending">
    <thead class="table-dark">
      <tr>
        <th>#</th>
        <th>Project Name</th>
        <th>Address</th>
        <th>Budget</th>
        <th>Start Date</th>
        <th>Contractor</th>
        <th>Status</th>
      </tr>
    </thead>
    <tbody>
      <?php
      $counter=1;
      if ($resultPending->num_rows > 0) {
          while ($row = $resultPending->fetch_assoc()) {
      ?>
        <tr>
          <td><?php echo $counter++;?></td>
          <td><?= htmlspecialchars($row['name']) ?></td>
         <td><?php echo htmlspecialchars($row['street'])."/".htmlspecialchars($row['city'])."/".htmlspecialchars($row['state'])."/".htmlspecialchars($row['postalCode'])  ?></td> 
          <td>$<?= htmlspecialchars($row['budget']) ?></td>
          <td><?= htmlspecialchars($row['startDate']) ?></td>
          <td><?= htmlspecialchars($row['contractorName'] ?? 'No contractor assigned yet') ?></td>
          <td><?= htmlspecialchars(ucfirst($row['status'])) ?></td>
        </tr>
      <?php
          }
      } else {
      ?>
        <tr>
          <td colspan="5" class="text-center text-danger">No pending projects found.</td>
        </tr>
      <?php
      }
      ?>
    </tbody>
  </table>

    <!-- Accepted Projects Section -->
    <h3>✔️ Accepted Projects</h3>
    <table class="table table-accepted">
              <thead class="table-dark">
            <tr>
              <th>#</th>
                <th>Name</th>
                <th>Address</th>
                <th>Budget</th>
                <th>Start Date</th>
                <th>Status</th>
                <th>Contractor</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $counter=1;
            if ($resultAccepted->num_rows > 0) {
                while ($row = $resultAccepted->fetch_assoc()): ?>
                    <tr>
                      <td><?php echo $counter++;?></td>
                        <td><?= htmlspecialchars($row['name']) ?></td>
                        <td><?php echo htmlspecialchars($row['street'])."/".htmlspecialchars($row['city'])."/".htmlspecialchars($row['state'])."/".htmlspecialchars($row['postalCode'])  ?></td> 
                        <td>$<?= htmlspecialchars($row['budget']) ?></td>
                        <td><?= htmlspecialchars($row['startDate']) ?></td>
                        <td><?= htmlspecialchars(ucfirst($row['status'])) ?></td>
                        <td><?= htmlspecialchars($row['contractorName']) ?></td>
                        <td>
                            <a href="ho_checks_projectFlow.php?projectID=<?= $row['projectID'] ?>" class="btn btn-primary btn-sm">View Details</a>
                        </td>
                    </tr>
                <?php endwhile;
            } else {
                echo "<tr><td colspan='6' class='text-center text-danger'>No accepted projects.</td></tr>";
            } ?>
        </tbody>
    </table>

    <!-- Rejected Projects Section -->
    <h3>❌ Rejected Projects</h3>
    <table class="table table-rejected">
        <thead class="table-dark">
            <tr>
              <th>#</th>
                <th>Name</th>
                <th>Address</th>
                <th>Budget</th>
                <th>Start Date</th>
                <th>Status</th>
                <th>Contractor</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($resultRejected->num_rows > 0) {
              $counter=1;
                while ($row = $resultRejected->fetch_assoc()): ?>
                    <tr>
                      <td><?php echo $counter++;?></td>
                        <td><?= htmlspecialchars($row['name']) ?></td>
                        <td><?php echo htmlspecialchars($row['street'])."/".htmlspecialchars($row['city'])."/".htmlspecialchars($row['state'])."/".htmlspecialchars($row['postalCode'])  ?></td> 
                        <td>$<?= htmlspecialchars($row['budget']) ?></td>
                        <td><?= htmlspecialchars($row['startDate']) ?></td>
                        <td><?= htmlspecialchars(ucfirst($row['status'])) ?></td>
                        <td><?= htmlspecialchars($row['contractorName']) ?></td>
                        <td>
                            <a href="reassign_contractor.php?projectID=<?= $row['projectID'] ?>" class="btn btn-warning btn-sm">Reassign Contractor</a>
                        </td>
                    </tr>
                <?php endwhile;
            } else {
                echo "<tr><td colspan='6' class='text-center text-danger'>No rejected projects.</td></tr>";
            } ?>
        </tbody>
    </table>

</div>


























<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  </body>
</html>

 