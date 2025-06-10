 <!-- clarita antoun -->

<?php
session_start(); // Start session if not started
if (!isset($_SESSION['professional_identity']['id'])) {
    header("Location: logInPage.php");
    exit();


}

include 'conx.php';
$professionalID=$_SESSION['professional_identity']['id'];
$sql="select f.*,cont.fullName from cont_pro_feedback as f,contractor as cont where f.contractorID=cont.id and professionalID='$professionalID' and cont.status='accepted' ORDER BY f.rating DESC";
$result=$conn->query($sql);
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

#feedbackSection {
    background: linear-gradient(135deg, #2c3e50, #3498db) !important;
    border-radius: 20px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.2);
}

#feedbackSection h2 {
    font-size: 2.8rem !important;
    color: #fff !important;
    text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
    letter-spacing: 1px;
}

#feedbackSection p.text-muted {
    font-size: 1.4rem;
    color: #ecf0f1 !important;
}

.feedback-card {
    background: rgba(255,255,255,0.95) !important;
    border: none !important;
    border-radius: 15px !important;
    transition: transform 0.3s ease;
}

.feedback-card:hover {
    transform: translateY(-5px) scale(1.02);
    box-shadow: 0 8px 25px rgba(0,0,0,0.2);
}

.feedback-card h5 {
    font-size: 1.8rem !important;
    color: #2c3e50 !important;
    font-weight: 700 !important;
    margin-bottom: 0.5rem !important;
}

.feedback-card .text-warning {
    font-size: 1.8rem !important;
    letter-spacing: 3px;
}

.feedback-card p.text-muted {
    font-size: 1.4rem !important;
    color: #34495e !important;
    line-height: 1.6;
}

.feedback-card small.text-muted {
    font-size: 1.2rem !important;
    color: #7f8c8d !important;
    display: block;
    margin-top: 1rem;
}

.rounded-circle {
    border: 3px solid #3498db !important;
    padding: 3px;
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

/* Responsive adjustments */
@media (max-width: 768px) {
    #feedbackSection h2 {
        font-size: 2.2rem !important;
    }
    
    .feedback-card h5 {
        font-size: 1.6rem !important;
    }
    
    .feedback-card p.text-muted {
        font-size: 1.2rem !important;
    }
}
.rounded-circle {
  width: 70px;
  height: 70px;
  object-fit: cover;
}

.bg-warning {
  width: 100px;
  height: 100px;
  font-weight: bold;
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

.feedback-card {
  transition: transform 0.3s ease;
  border: 1px solid #e0e0e0;
  background: #fff;
  display: flex;
  flex-direction: column;
  min-height: 100%;
  color: #222;
}

.feedback-card:hover {
  transform: translateY(-4px);
  box-shadow: 0 12px 24px rgba(0, 0, 0, 0.1);
}

.feedback-card .fa-star,
.feedback-card .fa-star-o {
  font-size: 1.2rem;
  color: #f4c150;
}

.feedback-card .comment {
  color: #333;
  font-size: 1.1rem;
  line-height: 1.6;
}

@media (max-width: 768px) {
  .feedback-card {
    padding: 1.2rem;
  }
}

</style>



  </head>
  <body >
    
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
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ml-auto">
                <li class="nav-item active"><a class="nav-link" href="professionalPage.php">Go to dashboard</a></li>
                

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
<br><br>
<br>
<br>
<br>




<div class="container py-5 bg-dark text-white" id="feedbackSection">
  <div class="text-center mb-5">
    <h2 class="fw-bold">Contractors feedbacks</h2>
    <p class="text-muted">Feedbacks from Contractors who worked with 
      <?php echo htmlspecialchars($_SESSION['professional_identity']['fullName']); ?>
    </p>
  </div>
<div class="row row-cols-1 row-cols-md-2 g-4">
  <?php while($row = $result->fetch_assoc()) { ?>
  <div class="col">
    <div class="feedback-card bg-white p-4 rounded-4 shadow-sm h-100 border-0">
      <div class="d-flex align-items-start mb-3">
        <img src="https://cdn-icons-png.flaticon.com/512/149/149071.png" 
             class="rounded-circle me-3" 
             alt="User Avatar" 
             width="65" 
             height="65">
        <div>
          <h5 class="fw-bold mb-1 text-dark" style="font-size: 1.4rem;"><?php echo htmlspecialchars($row['fullName']); ?></h5>
          <div class="text-warning fs-5">
            <?php
              $fullStars = (int)$row['rating'];
              $emptyStars = 5 - $fullStars;
              echo str_repeat('<i class="fas fa-star"></i>', $fullStars);
              echo str_repeat('<i class="far fa-star"></i>', $emptyStars);
            ?>
          </div>
        </div>
      </div>

      <div class="comment mb-3" style="font-size: 1.1rem; color: #333;">
        <?php echo nl2br(htmlspecialchars($row['comment'])); ?>
      </div>

      <small class="text-muted mt-auto" style="font-size: 1rem;">
        <i class="far fa-calendar-alt me-1"></i> 
        <?php echo $row['date']; ?>
      </small>
    </div>
  </div>
  <?php } ?>
</div>


  
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  </body>
</html>

 







