<!-- Clarita Antoun -->
<?php
 session_start();
 include 'conx.php';
 if (!isset($_SESSION['homeOwner_identity']['id'])) {
    header("Location: logInPage.php");
    exit();
  }
  $homeOwnerId = $_SESSION['homeOwner_identity']['id'];




if(isset($_SESSION['homeOwner_identity']['id'])){
    $table="homeowner";
     $id=$_SESSION['homeOwner_identity']['id'];
     $sql="select homeowner.fullName,homeowner.email,homeowner.phoneNumber,
     address.street,address.city,address.state,address.postalCode
      FROM homeowner
JOIN address ON homeowner.addressID = address.addressID
WHERE homeowner.id = '$id'";
$res=$conn->query($sql);
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
    <!-- Add this to your Google Fonts link -->
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
/* Import classic font */
@import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500&family=Lato:wght@300;400&display=swap');

.table {
    background-color: rgba(0, 0, 0, 0.9); /* Black background with transparency */
    border: 1px solid brown; /* Subtle muted orange border */
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
    font-family: 'Lato', sans-serif;
    backdrop-filter: blur(5px);
}

.table thead {
    background: linear-gradient(45deg, #1a1a1a, #333); /* Dark gradient */
    border-bottom: 2px solid brown; /* Muted orange border */
}

.table thead th {
    font-family: 'Playfair Display', serif;
    color:brown; /* Muted orange text */
    font-weight: 500;
    letter-spacing: 0.05em;
    border-right: 1px solid brown;
}

.table tbody td {
    color: #f5f5f5; /* Light text color for contrast */
    background-color: rgba(0, 0, 0, 0.7); /* Dark background */
    vertical-align: middle;
    border-color: brown;
    position: relative;
}

.table tbody tr:hover td {
    background-color: brown; /* Muted orange hover effect */
    transform: translateX(4px);
    transition: all 0.3s ease;
}

/* Status Badges */
.table tbody td:nth-child(5) { 
    font-weight: 500;
}

.table tbody td:nth-child(5):before {
    content: '';
    position: absolute;
    left: -8px;
    top: 50%;
    transform: translateY(-50%);
    width: 4px;
    height: 60%;
    border-radius: 2px;
}

/* Pending */
.table-pending tbody td:nth-child(5):before {
    background: brown; /* Muted orange */
}

/* Accepted */
.table-accepted tbody td:nth-child(5):before {
    background: #1a1a1a; /* Dark grey for accepted */
}

/* Rejected */
.table-rejected tbody td:nth-child(5):before {
    background: #b87d7d; /* Dusty rose for rejected */
}

/* Action Buttons */
.btn-primary {
    background-color: brown; /* Muted orange */
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
    background-color:brown;
    border-color: brown;
}

.btn-warning:hover {
    background-color: #666;
    border-color: #777;
}

/* Section Headers */
h2, h3 {
    font-family: 'Playfair Display', serif;
    color: brown; /* Muted orange for header text */
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
    background:brown; /* Accent muted orange */
}

/* Empty State */
.text-danger {
    color: #b87d7d !important; /* Dusty rose */
    font-style: italic;
}
    body {
        font-family: 'Playfair Display', serif;
        background: #f8f9fa;
        color: black;
        font-weight: bold;
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
input[type="text"],
input[type="tel"],
input[type="email"],
input[type="password"] {
    width: 100%;
    padding: 12px;
    border: 2px solid #8B4513;
    border-radius: 8px;
    background: rgba(255, 255, 255, 0.7); /* Milky white */
    transition: all 0.3s ease;
    font-size: 16px;
    color: black;
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
    background-image: url('images/h1.jpg'); /* Replace with actual image path or URL */
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    background-attachment: fixed;
    font-family: 'Lato', sans-serif;
    margin: 0;
    padding: 0;
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
                  <li class="nav-item active"><a class="nav-link" href="homeOwnerPage.php">Go to dashboard</a></li>
              
                

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






<?php

    if($table=="homeowner"){
    while($row = $res->fetch_assoc()){
        ?>
        
       <fieldset>
        <legend>Home owner Profile</legend>
      
       <form action="updateHomeOwner.php" method="post" >
        <h1>Personal details:</h1>
        <label>Full name:<input type="text" name="fullName" value="<?php echo $row['fullName']?> " ></label><br>
        <label>Email:<input   type="text" size="30%" name="email" value="<?php echo $row['email']?>" readonly></label><br>
        <label>Phone number:<input type="tel" name="phoneNumber" value="<?php echo $row['phoneNumber']?>"></label><br>
        <label>Street:<br>
<input type="text" name="street" value="<?php echo $row['street']; ?>"></label><br>

<label>City:<br>
<input type="text" name="city" value="<?php echo $row['city']; ?>"></label><br>

<label>State:<br>
<input type="text" name="state" value="<?php echo $row['state']; ?>"></label><br>

<label>Postal Code:<br>
<input type="text" name="postalCode" value="<?php echo $row['postalCode']; ?>"></label><br>
        <br>
        <button type="submit" class="update-profile">Update Profile</button><br>
        <button type="button" onclick="openEmailModal()" class="update-email">Update Email</button>
<button type="button" onclick="openPasswordModal()" class="update-password" >Update Password</button>


</form>
       </fieldset>
<!-- Modal for Updating Email -->
<div id="emailModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="document.getElementById('emailModal').style.display='none'">&times;</span>
        <h2>Update Email</h2>
        <form action="emailChangeRequest.php" method="POST"> <!-- Lowercase filename -->
            <input type="hidden" name="userFullName" 
                   value="<?php echo htmlspecialchars($row['fullName']); ?>"> <!-- Use row data -->
            <input type="hidden" name="userId" 
                   value="<?php echo htmlspecialchars($_SESSION['homeOwner_identity']['id']); ?>">
            <input type="hidden" name="userRole" value="homeowner">
            <input type="hidden" name="requestType" value="email"> <!-- Added requestType -->
            
            <label>New Email:
                <input type="email" name="newValue" 
                       value="<?php echo htmlspecialchars($row['email']); ?>" 
                       required>
            </label><br>
            <input type="submit" value="Submit Request"> <!-- Fixed typo -->
        </form>
    </div>
</div>


       <!-- Modal for Updating Password -->
<div id="passwordModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="document.getElementById('passwordModal').style.display='none'">&times;</span>
        <h2>Update Password</h2>
        <form action="passwordChangeRequest.php" method="POST">
            <input type="hidden" name="userFullName" 
                   value="<?php echo htmlspecialchars($row['fullName']); ?>"> <!-- Added -->
            <input type="hidden" name="userId" 
                   value="<?php echo htmlspecialchars($_SESSION['homeOwner_identity']['id']); ?>">
            <input type="hidden" name="userRole" value="homeowner">
            <input type="hidden" name="requestType" value="password">
            <label>New Password:</label>
            <input type="password" name="newValue" required> <!-- Changed to password type -->
            <input type="submit" value="Submit Request">
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

 