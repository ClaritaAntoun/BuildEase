<!-- Clarita Antoun -->
<?php
session_start(); // Start session if not started
include 'conx.php';
if (!isset($_SESSION['professional_identity']['id'])) {
    header("Location: logInPage.php");
    exit();
}



$professionalID = $_SESSION['professional_identity']['id'];
$query = "SELECT startDate, availibilityStatus, price, priceDetails 
          FROM professional_details 
          WHERE professionalID = '$professionalID'";
$result = $conn->query($query);
$row = $result->fetch_assoc();

$showModal = false;

if (empty($row['startDate']) || empty($row['availibilityStatus']) || empty($row['price']) || empty($row['priceDetails'])) {
    $showModal = true;
}

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
 body {
    font-family: 'Playfair Display', serif;
    background: #f8f9fa;
    color: #495057;
}

/* Header Top Bar */
.header-area {
    background: #1A2A3A; /* Deep navy blue */
    padding: 12px 0;
    color: #6a5acd; /* Medium purple */
    font-family: 'Poppins', sans-serif;
    border-bottom: 1px solid rgba(106, 90, 205, 0.1);
}

.header-left a, .header-right ul li a {
    color: #6a5acd; /* Medium purple */
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
    border-bottom: 2px solid #9370db; /* Light purple */
}

.logo {
    font-family: 'Playfair Display', serif;
    font-size: 36px;
    font-weight: 700;
    color: #1A2A3A;
    letter-spacing: 0.5px;
}
.logo span {
    color: #6a5acd; /* Medium purple */
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
    color: #4169e1; /* Royal blue */
}

.navbar-nav .nav-link:hover::after {
    content: '';
    position: absolute;
    bottom: -2px;
    left: 0;
    right: 0;
    height: 2px;
    background: #6a5acd; /* Medium purple */
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
    color: #6a5acd !important; /* Medium purple */
}

/* Call to Action Button */
.appoint-btn a {
    background: #6a5acd; /* Medium purple */
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
    border-color: #4169e1; /* Royal blue */
    transform: translateY(-2px);
}

/* Responsive Adjustments */
@media (max-width: 991px) {
    .navbar-collapse {
        background: #FFFFFF;
        padding: 20px;
        margin-top: 15px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    }
}

.build-project-btn {
    background: linear-gradient(135deg, #6a5acd, #4169e1); /* Purple to Blue */
    color: white;
    font-size: 22px;
    font-weight: bold;
    padding: 15px 35px;
    border-radius: 8px;
    box-shadow: 0px 5px 15px rgba(106, 90, 205, 0.4);
    transition: all 0.3s ease;
    text-transform: uppercase;
    display: inline-block;
}

.build-project-btn:hover {
    background: linear-gradient(135deg, #4169e1, #6a5acd);
    box-shadow: 0px 8px 20px rgba(106, 90, 205, 0.5);
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
.form-label {
    font-weight: bold;
    color: #333; /* dark gray */
    font-size: 1.2rem; /* adjust as needed */
}

.custom-underline {
font-size: 30px;
  text-decoration: underline;
  text-decoration-color:rgb(49, 91, 153); /* Bootstrap primary blue */
}
.form-control {
  font-size: 1.4rem; /* Increase this value as needed */
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
            <li><a class="dropdown-item" href="C_P_browsingFeedback.php"> Contractors feedbacks</a></li>
            <li><a class="dropdown-item" href="HO_P_browsingFeedback.php"> Home owners feedbacks</a></li>
          </ul>
        </li>

                <li class="nav-item active"><a class="nav-link" href="#">Current Projects</a></li>
               
               
              </ul>
            </div>
          </nav>
        </div>
      </nav>
    </div>


    <?php if ($showModal): ?>
<div class="modal fade show" id="completeProfileModal" tabindex="-1" style="display:block; background: rgba(0,0,0,0.6);">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="save_professional_details.php" method="POST">
        <div class="modal-header">
          <h5 class="modal-title">Complete Your Profile</h5>
           <?php

 if(isset($_SESSION['error'])){
                    echo "<p class='text-danger text-center'>".$_SESSION['error']."</p>" ;
                    unset($_SESSION['error']);
                }
?>
        </div>
        <div class="modal-body">
          <div class="mb-3">
          
            <label class="form-label">Start Date</label>
            <input type="date" name="startDate" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Availability Status</label>
            <input type="text" name="availibilityStatus" class="form-control" required>
          </div>
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

    <div class="container mt-5">
    <div class="row align-items-center">
   
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

  
        <div class="col-md-6 d-flex flex-column justify-content-center">
  <form action="update_professional_details.php" method="POST">
    <div >
    <h5 class="text-dark fs-3 custom-underline"><b>Update Your Additional Details:</b></h5>

      <?php
      if (isset($_SESSION['error'])) {
        echo "<p class='text-danger text-center'>" . $_SESSION['error'] . "</p>";
        unset($_SESSION['error']);
      }
      ?>
    </div>

    <div >
      <div class="mb-3">
        <label class="form-label fw-bold text-dark fs-5">Start Date</label>
        <input type="date" name="startDate" class="form-control" required
               value="<?php echo $row['startDate'] ?? ''; ?>">
      </div>

      <div class="mb-3">
        <label class="form-label fw-bold text-dark fs-5">Price</label>
        <input type="number" name="price" class="form-control" required
               value="<?php echo $row['price'] ?? ''; ?>">
      </div>

      <div class="mb-3">
        <label class="form-label fw-bold text-dark fs-5">Price Details</label>
        <textarea name="priceDetails" class="form-control" required><?php echo $row['priceDetails'] ?? ''; ?></textarea>
      </div>
    </div>

    <div class="modal-footer">
      <button type="submit" class="btn btn-primary">Save</button>
    </div>
  </form>
</div>



<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  </body>
</html>

 