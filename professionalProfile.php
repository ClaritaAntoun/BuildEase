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

if(isset($_SESSION['professional_identity']['fullName'])){
    $table="professional";
    $id=$_SESSION['professional_identity']['id'];
    $sql="select professional.fullName,professional.email,professional.phoneNumber,professional.age,professional_details.areaOfWork,professional_details.availibilityStatus,
    professional_details.startDate,professional_details.price,
    address.street,address.city,address.state,address.postalCode,curriculum_vitae.experiences,curriculum_vitae.languages,
    curriculum_vitae.certifications,curriculum_vitae.skills,curriculum_vitae.educations
    from professional,professional_details,address,curriculum_vitae where id='$id' and professional.addressID=address.addressID and 
    curriculum_vitae.cvID=professional.cvID and professional.id=professional_details.professionalID";
}

$res=$conn->query($sql);


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
.modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0,0,0,0.5);
    align-items: center;
    justify-content: center;
}

.modal-content {
    position: relative;
    background: white;
    padding: 2rem;
    border-radius: 8px;
    max-width: 500px;
    margin: 1rem;
}
/* Fieldset Styles */
fieldset {
    border: 2px solid #8B4513; /* Muted red (brown) */
    border-radius: 15px;
    padding: 2rem;
    margin: 2rem auto;
    max-width: 800px;
    background: #F5F9FF; /* Subtle blue-tinted background */
    box-shadow: 0 10px 30px rgba(0, 51, 102, 0.1);
}

/* Legend */
legend {
    font-family: 'Playfair Display', serif;
 color: black;
        font-weight: bold;
    font-size: 2rem;
    padding: 0 1rem;
    letter-spacing: 0.05em;
}

/* Form Layout */
form {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1.5rem;
    font-family: 'Lato', sans-serif;
}

/* Heading */
h1 {
    grid-column: 1 / -1;
   color: black;
        font-weight: bold;/* Dark blue */
    font-family: 'Playfair Display', serif;
    border-bottom: 2px solid #8B4513; /* Muted red */
    padding-bottom: 0.5rem;
    margin-bottom: 1.5rem;
    font-size: 1.8rem;
}

/* Labels */
label {
    display: block;
    margin-bottom: 0.5rem;
   color: black;
        font-weight: bold;
    font-weight: 500;
}

/* Inputs */
input[type="text"],
input[type="tel"],
input[type="email"],
input[type="password"] {
    width: 100%;
    padding: 12px;
    border: 2px solid #8B4513;
    border-radius: 8px;
    background: #FFFFFF;
    transition: all 0.3s ease;
    font-size: 16px;
}

/* Input Focus */
input:focus {
    border-color: #003366;
    box-shadow: 0 0 8px rgba(0, 51, 102, 0.2);
    outline: none;
}

/* Buttons */
button[type="submit"],
.update-profile,
.update-email,
.update-password {
    background: linear-gradient(135deg, #003366, #8B4513);
    color: white;
    padding: 12px 30px;
    border: none;
    border-radius: 8px;
    font-size: 16px;
    cursor: pointer;
    transition: all 0.3s ease;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    
}

button[type="submit"]:hover,
.update-profile:hover {
    background: linear-gradient(135deg, #8B4513, #003366);
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0, 51, 102, 0.3);
}

.update-email,
.update-password {
    background: #D9DCE1; /* Light muted blue */
        color: black;
        font-weight: bold;
    margin-top: 1rem;
}

.update-email:hover,
.update-password:hover {
    background: #B76E79;
    color: white;
}

/* Modal Overlay */
.modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    z-index: 1000;
}

/* Modal Box */
.modal-content {
    position: relative;
    background: #F5F9FF; /* Blue-tinted */
    margin: 5% auto;
    padding: 2rem;
    width: 600px !important;
    max-width: 95%;
    border-radius: 10px;
    animation: modalSlide 0.3s ease;
    box-sizing: border-box;
}

/* Modal Heading */
.modal h2 {
  color: black;
        font-weight: bold;
    margin-bottom: 1.5rem;
    font-family: 'Playfair Display', serif;
}

/* Close Button */
.close {
    position: absolute;
    right: 1rem;
    top: 1rem;
    color: #8B4513;
    font-size: 24px;
    cursor: pointer;
}

/* Modal Inputs */
.modal-content form input[type="email"],
.modal-content form input[type="password"] {
    width: 400px !important;
    max-width: 100%;
    display: block;
    margin: 10px auto 20px auto;
    box-sizing: border-box;
    padding: 12px 20px;
    border: 2px solid #8B4513;
    border-radius: 8px;
    font-size: 16px;
    
}

/* Modal Input Focus */
.modal-content form input[type="email"]:focus,
.modal-content form input[type="password"]:focus {
    border-color: #003366;
    outline: none;
    box-shadow: 0 0 8px rgba(0, 51, 102, 0.2);
}

/* Modal Responsiveness */
@media (max-width: 640px) {
    .modal-content {
        width: 95% !important;
    }

    .modal-content form input[type="email"],
    .modal-content form input[type="password"] {
        width: 90% !important;
    }
}

/* Responsive Tweaks */
@media (max-width: 768px) {
    form {
        grid-template-columns: 1fr;
    }
    
    fieldset {
        margin: 1rem;
        padding: 1.5rem;
    }
    
    legend {
        font-size: 1.5rem;
    }
}

@media (max-width: 480px) {
    input {
        padding: 10px;
    }
    
    button {
        padding: 10px 20px;
        font-size: 14px;
    }
}
/* Transparent fieldset */
fieldset {
    background-color: rgba(255, 255, 255, 0.15); /* Semi-transparent white */
    backdrop-filter: blur(8px); /* Glass effect */
    border: 2px solid #8B4513;
    border-radius: 15px;
    padding: 2rem;
    margin: 2rem auto;
    max-width: 800px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
}

/* Transparent modal */
.modal-content {
    background-color: rgba(255, 255, 255, 0.1); /* Transparent with blur */
    backdrop-filter: blur(10px);
    border-radius: 10px;
    margin: 5% auto;
    padding: 2rem;
    width: 600px !important;
    max-width: 95%;
    box-sizing: border-box;
    animation: modalSlide 0.3s ease;
}
/* Body background image */
body {
    background-image: url('images/b4.jpg'); /* Replace with actual image path or URL */
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    background-attachment: fixed;
    font-family: 'Lato', sans-serif;
    margin: 0;
    padding: 0;
}
fieldset,
.modal-content {
    background-color: rgba(255, 255, 255, 0.6); /* Milky white */
    backdrop-filter: blur(10px); /* Frosted glass effect */
    -webkit-backdrop-filter: blur(10px); /* Safari support */
    border-radius: 15px;
    border: 2px solid #8B4513;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
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
              <li class="nav-item active"><a class="nav-link" href="professionalPage.php">Go To Dashboard</a></li>
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
<?php

    if($table=="professional"){
        while($row = $res->fetch_assoc()){
            ?>
           <fieldset>
            <legend>Professional Profile</legend>
          
           <form action="updateProfessional.php" method="post">
           <h1>Personal details:</h1>
            <label>Full name:<input type="text" name="fullName" value="<?php echo $row['fullName']?>"></label><br>
            <label>Email:<input type="text" size="30%" name="email" value="<?php echo $row['email']?>" readonly></label><br>
            <label>Age:<input type="number" name="age" value="<?php echo $row['age']?>"></label><br>
    
            <label>Phone number:<input type="tel" name="phoneNumber" value="<?php echo $row['phoneNumber']?>"></label><br>
            <label>Street:
    <input type="text" name="street" value="<?php echo $row['street']; ?>"></label><br>
    <label>City:
    <input type="text" name="city" value="<?php echo $row['city']; ?>"></label><br>
    <label>State:
    <input type="text" name="state" value="<?php echo $row['state']; ?>"></label><br>
    <label>Postal Code:<br>
    <input type="text" name="postalCode" value="<?php echo $row['postalCode']; ?>"></label><br>
            <h1>Professional details:</h1>
            <label>availibilityStatus:<input type="text" name="availibilityStatus" value="<?php echo $row['availibilityStatus']?>"></label><br>
            <label>Area Of Work:<input type="text" name="areaOfWork" value="<?php echo $row['areaOfWork']?>"></label><br>
            <label>Start Date:<input type="date" name="startDate" value="<?php echo $row['startDate']?>"></label><br>
            <label>Price:<input type="text" name="price" value="<?php echo $row['price']?>"></label><br>
            <h1>cv details:</h1>
            <label>Experiences:<br>
    <textarea name="experiences" rows="5" cols="60"><?php echo $row['experiences']; ?></textarea></label><br>
    
    <label>Languages:<br>
    <textarea name="languages" rows="5" cols="60"><?php echo $row['languages']; ?></textarea></label><br>
    
    <label>Certifications:<br>
    <textarea name="certifications" rows="5" cols="60"><?php echo $row['certifications']; ?></textarea></label><br>
    
    <label>Educations:<br>
    <textarea name="educations" rows="5" cols="60"><?php echo $row['educations']; ?></textarea></label><br>
    
    <label>Skills:<br>
    <textarea name="skills" rows="5" cols="60"><?php echo $row['skills']; ?></textarea></label><br>
    
            <br>
            <button type="submit" class="update-profile">Update Profile</button><br>
            <button type="button" onclick="openEmailModal()" class="update-email">Update Email</button>
    <button  type="button" onclick="openPasswordModal()" class="update-password">Update Password</button>
    </form>
           </fieldset>
    <!-- Modal for Updating Email -->
    <div id="emailModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="document.getElementById('emailModal').style.display='none'">&times;</span>
            <h2>Update Email</h2>
            <form action="emailChangeRequest.php" method="POST">
            <input type="hidden" name="userFullName" value="<?php echo $_SESSION['professional_identity']['fullName']; ?>">
                <input type="hidden" name="userId" value="<?php echo $_SESSION['professional_identity']['id']; ?>">
                <input type="hidden" name="userRole" value="professional">
                <input type="hidden" name="requestType" value="email">
                <label>New Email:</label>
                <label>Email:<input   type="text" size="30%" name="newValue" value="<?php echo $row['email']?>" required></label><br>
                <button type="submit">Submit Request</button>
            </form>
        </div>
    </div>
    
    <!-- Modal for Updating Password -->
    <div id="passwordModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="document.getElementById('passwordModal').style.display='none'">&times;</span>
            <h2>Update Password</h2>
            <form action="passwordChangeRequest.php" method="POST">
                <input type="hidden" name="userId" value="<?php echo $_SESSION['professional_identity']['id']; ?>">
                <input type="hidden" name="userRole" value="professional">
                <input type="hidden" name="requestType" value="password">
                <label>New Password:</label>
                <input type="text" name="newValue" required><br>
                <button type="submit">Submit Request</button>
            </form>
        </div>
    </div>
          
           
            <?php
        }
    }
       ?>
       <script>

function openEmailModal() {
    document.getElementById('emailModal').style.display = "block";
}

function openPasswordModal() {
    document.getElementById('passwordModal').style.display = "block";
}

// Close Modals when clicking outside of the modal content
window.onclick = function(event) {
    if (event.target == document.getElementById('emailModal')) {
        document.getElementById('emailModal').style.display = "none";
    } else if (event.target == document.getElementById('passwordModal')) {
        document.getElementById('passwordModal').style.display = "none";
    }
}

</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  </body>
</html>
