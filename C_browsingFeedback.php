<!-- clarita antoun -->
<?php
session_start(); 
if (!isset($_SESSION['contractor_identity']['id'])) {
    header("Location: logInPage.php");
    exit();


}

include 'conx.php';
$contractorID=$_SESSION['contractor_identity']['id'];
$sql="select f.*,ho.fullName from ho_cont_feedback as f,homeowner as ho where f.homeOwnerID=ho.id  and contractorID='$contractorID' and ho.status='accepted' ORDER BY f.rating DESC";
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


/* Call to Action Button */
.appoint-btn a {
    background: #D32F2F;
    color: #FFFFFF !important;
    padding: 14px 30px;
    border-radius: 30px;
    font-weight: 600;
    transition: 0.3s;
    letter-spacing: 0.5px;
    border: 2px solid transparent;
}
.appoint-btn a:hover {
    background: #B71C1C;
    color: #FFFFFF !important;
    border-color: #FF5252;
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


.feedback-card {
    border: 1px solid rgba(255,255,255,0.1);
    transition: all 0.3s ease;
}

.feedback-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    background: rgba(255,255,255,0.05) !important;
}

.rounded-3 {
    border-radius: 1rem !important;
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
    }  /* Responsive Adjustments */
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

#feedbackSection {
    background: linear-gradient(135deg, #1A2A3A 0%, #2C3E50 100%) !important;
    border-radius: 15px;
    margin-top: 30px;
    margin-bottom: 50px;
    padding: 40px !important;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
}

#feedbackSection h2 {
    font-size: 2.5rem;
    font-weight: 800 !important;
    margin-bottom: 20px;
    color: #FFC107;
    text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.3);
}

#feedbackSection p.text-muted {
    font-size: 1.2rem;
    font-weight: 500;
    color: rgba(255, 255, 255, 0.7) !important;
}

.feedback-card {
    border: 1px solid rgba(255, 255, 255, 0.1);
    transition: all 0.3s ease;
    background: rgba(255, 255, 255, 0.03) !important;
    padding: 25px !important;
}

.feedback-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
    background: rgba(255, 255, 255, 0.08) !important;
    border-color: rgba(255, 193, 7, 0.3);
}

.feedback-card h5 {
    font-size: 1.4rem !important;
    font-weight: 700 !important;
    color: white !important;
    margin-bottom: 10px !important;
}

.feedback-card p {
    font-size: 1.1rem !important;
    font-weight: 500 !important;
    line-height: 1.6;
    color: rgba(255, 255, 255, 0.9) !important;
}

.feedback-card small {
    font-size: 0.95rem !important;
    font-weight: 500 !important;
}

.text-warning {
    font-size: 1.3rem;
    letter-spacing: 2px;
}

.rounded-circle {
    border: 2px solid #FFC107;
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
            
     <div class="collapse navbar-collapse justify-content-end" id="mainNav">

                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="contractorPage.php">Go to dashboard</a>
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


     
               
             

</div>
<br><br>
<br>
<br>
<br>




<div class="container py-5 bg-dark text-white" id="feedbackSection">
  <div class="text-center mb-5">
    <h2 class="fw-bold">FEEDBACK</h2>
    <p class="text-muted">Feedbacks from Home owners who worked with 
      <?php echo htmlspecialchars($_SESSION['contractor_identity']['fullName']); ?>
    </p>
  </div>

  <div class="row g-4">
    <?php while($row = $result->fetch_assoc()) { ?>
    <!-- Feedback Item -->
    <div class="col-12"> <!-- Full width column -->
      <div class="feedback-card bg-secondary bg-opacity-10 p-4 rounded-3 mb-3 shadow-sm">
        <div class="d-flex align-items-start">
          <img src="https://cdn-icons-png.flaticon.com/512/149/149071.png" 
               class="rounded-circle me-4" 
               alt="User Avatar" 
               width="70" 
               height="70">
          
          <div class="flex-grow-1">
            <div class="d-flex justify-content-between align-items-center mb-2">
              <h5 class="mb-0"><?php echo htmlspecialchars($row['fullName']); ?></h5>
              <div class="text-warning">
                <?php echo str_repeat('★', (int)$row['rating']); ?>
              </div>
            </div>
            <p class="mb-0 text-muted"><?php echo htmlspecialchars($row['comment']); ?></p>
            <small class="text-muted d-block mt-2">
              <?php echo date('M j, Y', strtotime($row['date'])); ?>
            </small>
          </div>
        </div>
      </div>
    </div>
    <?php } ?>
  </div>
</div>

  
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  </body>
</html>

 







