 <!-- Clarita Antoun -->
<?php
session_start(); 
include 'conx.php';

if (!isset($_SESSION['contractor_identity']['id'])) {
    header("Location: logInPage.php");
    exit();
}


$contId = $_SESSION['contractor_identity']['id'];



if(isset($_SESSION['contractor_identity']['fullName'])){
    $table="contractor";
    $id=$_SESSION['contractor_identity']['id'];
    $sql="select contractor.fullName,contractor.email,contractor.phoneNumber,
    address.street,address.city,address.state,address.postalCode,curriculum_vitae.experiences,curriculum_vitae.languages,
    curriculum_vitae.certifications,curriculum_vitae.skills,curriculum_vitae.educations
    from contractor,address,curriculum_vitae where id='$id' and contractor.addressID=address.addressID and 
    curriculum_vitae.cvID=contractor.cvID and contractor.status='accepted'";
  
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">


<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>

/* Base Styles */
body {
    font-family: 'Lato', sans-serif;
    background-image: url('images/ci3.avif');
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    background-attachment: fixed;
    color: #3e0f0f;
    margin: 0;
    padding: 0;
}

/* Header */
.header-area {
    background: #B71C1C;
    padding: 12px 0;
    color: #FFCDD2;
    font-family: 'Poppins', sans-serif;
    border-bottom: 1px solid rgba(255, 0, 0, 0.1);
    font-size: 18px;
}

.header-left a, .header-right ul li a {
    color: #FFCDD2;
    font-weight: 600;
    transition: 0.3s;
    font-size: 17px;
}
.header-left a:hover, .header-right ul li a:hover {
    color: #FFFFFF;
    text-decoration: none;
}

/* Navigation */
.navigation {
    background: #FFFFFF;
    padding: 20px 0;
    box-shadow: 0 4px 30px rgba(0, 0, 0, 0.08);
    border-bottom: 2px solid #f8bbd0;
}

.logo {
    font-family: 'Playfair Display', serif;
    font-size: 40px;
    font-weight: 700;
    color: #B71C1C;
}
.logo span {
    color: #FF5252;
    font-weight: 400;
}

.navbar-nav .nav-link {
    color: #B71C1C !important;
    font-weight: 600;
    padding: 18px 22px !important;
    font-size: 18px;
    position: relative;
}

.navbar-nav .nav-link:hover, 
.navbar-nav .nav-item.active .nav-link {
    color: #FF5252;
}

.navbar-nav .nav-link:hover::after {
    content: '';
    position: absolute;
    bottom: -2px;
    left: 0;
    right: 0;
    height: 2px;
    background: #FF5252;
}

/* Dropdown */
.dropdown-menu {
    background: #FFFFFF;
    border: 1px solid rgba(183, 28, 28, 0.1);
    border-radius: 4px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
}
.dropdown-menu .dropdown-item {
    color: #B71C1C !important;
    padding: 15px 30px;
    font-size: 17px;
}
.dropdown-menu .dropdown-item:hover {
    background: #FFEBEE;
    color: #D32F2F !important;
}

/* CTA Button */
.appoint-btn a {
    background: #D32F2F;
    color: #FFFFFF !important;
    padding: 16px 35px;
    font-size: 18px;
    border-radius: 30px;
    font-weight: 700;
    border: 2px solid transparent;
}
.appoint-btn a:hover {
    background: #B71C1C;
    border-color: #FF5252;
    transform: translateY(-2px);
}

/* Build Button */
.build-project-btn {
    background: linear-gradient(135deg, #e53935, #d32f2f);
    color: white;
    font-size: 24px;
    font-weight: bold;
    padding: 18px 40px;
    border-radius: 8px;
    box-shadow: 0px 5px 15px rgba(211, 47, 47, 0.4);
    text-transform: uppercase;
}
.build-project-btn:hover {
    background: linear-gradient(135deg, #d32f2f, #e53935);
    box-shadow: 0px 8px 20px rgba(211, 47, 47, 0.5);
    transform: scale(1.05);
}

/* Fieldset */
fieldset {
    border: 2px solid #8B4513;
    border-radius: 15px;
    padding: 2.5rem;
    margin: 2rem auto;
    max-width: 850px;
    background-color: rgba(255, 255, 255, 0.5);
    backdrop-filter: blur(10px);
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
}

/* Legend */
legend {
    font-family: 'Playfair Display', serif;
    font-size: 2.2rem;
    font-weight: bold;
    color: black;
}

/* Form Layout */
form {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 2rem;
    font-size: 18px;
}

/* Headings */
h1 {
    grid-column: 1 / -1;
    font-size: 2rem;
    font-weight: bold;
    color: black;
    border-bottom: 2px solid #8B4513;
    padding-bottom: 0.5rem;
}

/* Labels */
label {
    display: block;
    font-size: 18px;
    font-weight: 600;
    margin-bottom: 0.5rem;
    color: black;
}

/* Inputs */
input[type="text"],
input[type="tel"],
input[type="email"],
input[type="password"] {
    width: 100%;
    padding: 14px;
    font-size: 17px;
    border: 2px solid #8B4513;
    border-radius: 8px;
    background-color: rgba(255, 255, 255, 0.6);
    transition: all 0.3s ease;
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
    padding: 14px 35px;
    font-size: 18px;
    font-weight: 600;
    border-radius: 8px;
    border: none;
    cursor: pointer;
    text-transform: uppercase;
}

button[type="submit"]:hover,
.update-profile:hover {
    background: linear-gradient(135deg, #8B4513, #003366);
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0, 51, 102, 0.3);
}

.update-email,
.update-password {
    background: #D9DCE1;
    color: black;
    font-weight: bold;
    margin-top: 1rem;
}

.update-email:hover,
.update-password:hover {
    background: #B76E79;
    color: white;
}

/* Modal */
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

.modal-content {
    position: relative;
    background-color: rgba(255, 255, 255, 0.6);
    backdrop-filter: blur(10px);
    border-radius: 10px;
    margin: 5% auto;
    padding: 2rem;
    width: 600px !important;
    max-width: 95%;
    box-sizing: border-box;
}

/* Modal Heading */
.modal h2 {
    font-family: 'Playfair Display', serif;
    font-size: 1.8rem;
    color: black;
    font-weight: bold;
}

/* Close Button */
.close {
    position: absolute;
    right: 1rem;
    top: 1rem;
    font-size: 28px;
    color: #8B4513;
    cursor: pointer;
}

/* Modal Inputs */
.modal-content form input[type="email"],
.modal-content form input[type="password"] {
    width: 400px !important;
    padding: 14px 22px;
    font-size: 17px;
    border: 2px solid #8B4513;
    border-radius: 8px;
    background-color: rgba(255, 255, 255, 0.5);
}

/* Modal Input Focus */
.modal-content form input:focus {
    border-color: #003366;
    outline: none;
    box-shadow: 0 0 8px rgba(0, 51, 102, 0.2);
}

/* Responsive Tweaks */
@media (max-width: 991px) {
    .navbar-collapse {
        background: #FFFFFF;
        padding: 20px;
        margin-top: 15px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    }
}
@media (max-width: 768px) {
    form {
        grid-template-columns: 1fr;
    }
    fieldset {
        margin: 1rem;
        padding: 1.5rem;
    }
    legend {
        font-size: 1.6rem;
    }
}
@media (max-width: 640px) {
    .modal-content {
        width: 95% !important;
    }
    .modal-content form input {
        width: 90% !important;
    }
}
@media (max-width: 480px) {
    input {
        padding: 12px;
        font-size: 16px;
    }
    button {
        padding: 12px 25px;
        font-size: 16px;
    }
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
              <li class="nav-item active"><a class="nav-link" href="contractorPage.php">Go to dashboard</a></li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <?php echo isset($_SESSION['contractor_identity']['fullName']) ? $_SESSION['contractor_identity']['fullName'] : 'Profile'; ?>
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="userDropdown">
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

              
              </ul>
            </div>
          </nav>
        </div>
      </nav>
    </div>






<?Php

    if($table=="contractor"){
    while($row = $res->fetch_assoc()){
        ?>
        <fieldset>
        <legend>Contractor Profile</legend>
           <form action="updateContractor.php" method="post">
           
       <h1>Personal details:</h1>
        <label>Full name:<input type="text" name="fullName" value="<?php echo $row['fullName']?>"></label><br>
        <label>Email:<input type="text" name="email" size="30%" value="<?php echo $row['email']?>" readonly></label><br>
        <label>Phone number:<input type="tel" name="phoneNumber" value="<?php echo $row['phoneNumber']?>"></label><br>
        <label>Street:
<input type="text" name="street" value="<?php echo $row['street']; ?>"></label><br>

<label>City:
<input type="text" name="city" value="<?php echo $row['city']; ?>"></label><br>

<label>State:
<input type="text" name="state" value="<?php echo $row['state']; ?>"></label><br>

<label>Postal Code:
<input type="text" name="postalCode" value="<?php echo $row['postalCode']; ?>"></label><br>        
        <h1>cv details:</h1>
        <label>Experiences:<input type="text" name="experiences" value="<?php echo $row['experiences']?>"></label><br>
        <label>Languages:<input type="text" name="languages" value="<?php echo $row['languages']?>"></label><br>
        <label>Certifications:<input type="text" name="certifications" value="<?php echo $row['certifications']?>"></label><br>
        <label>Educations:<input type="text" name="educations" value="<?php echo $row['educations']?>"></label><br>
        <label>Skills:<input type="text" name="skills" value="<?php echo $row['skills']?>"></label><br>
        <br>
        <button type="submit" class="update-profile">Update Profile</button><br>
        <button type="button" onclick="openEmailModal()" class="update-email">Update Email</button>
<button type="button" onclick="openPasswordModal()" class="update-password">Update Password</button>

</form>
       </fieldset>
<!-- Modal for Updating Email -->
<div id="emailModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="document.getElementById('emailModal').style.display='none'">&times;</span>
        <h2>Update Email</h2>
        <form action="emailChangeRequest.php" method="POST">
        <input type="hidden" name="userFullName" value="<?php echo $_SESSION['contractor_identity']['fullName'];  ?>">
            <input type="hidden" name="userId" value="<?php echo $_SESSION['contractor_identity']['id']; ?>">
            <input type="hidden" name="userRole" value="contractor">
            <input type="hidden" name="requestType" value="email">
            <label>New Email:</label>
            <label>Email:<input   type="text" size="30%" name="newValue" value="<?php echo $row['email']?>" required></label><br>
            <button type="submit" >Submit Request</button>
        </form>
    </div>
</div>

<!-- Modal for Updating Password -->
<div id="passwordModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="document.getElementById('passwordModal').style.display='none'">&times;</span>
        <h2>Update Password</h2>
        <form action="passwordChangeRequest.php" method="POST">
            <input type="hidden" name="userId" value="<?php echo $_SESSION['contractor_identity']['id']; ?>">
            <input type="hidden" name="userRole" value="contractor">
            <input type="hidden" name="requestType" value="password">
            <label>New Password:</label>
            <input type="text" name="newValue" required><br>
            <button type="submit">Submit Request</button>
        </form>
    </div>
</div>


       <?php
    }
    
    
}?>

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

 