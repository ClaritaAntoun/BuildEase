<!-- CLARITA ANTOUN -->
<?php
 session_start();
 include 'conx.php';
 if (!isset($_SESSION['contractor_identity']['id'])) {
 header("Location: logInPage.php");
exit();
 }
$sql = "SELECT distinct areaOfWork as name FROM professional_details";
$result = $conn->query($sql);

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
:root {
    --primary: #1A2A3A;
    --secondary: #f8f9fa;
    --yellow-main: #FFC107;
    --header-height: 120px;
    --sidebar-width: 250px;

    /* Optional: Base font size */
    font-size: 18px; /* Increase from default 16px */
}

body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background-color: var(--secondary);
    padding-top: var(--header-height);
    margin: 0;
    font-size: 1.125rem; /* 18px */
}

.nav-link,
.card-title,
.card-text,
.input-group-text,
.professionals-header h2,
.cv-section h6,
.info-item,
.appoint-btn a {
    font-size: 1.125rem; /* Apply larger font size */
}

/* Adjust spacing between navbar items */
.navbar-nav .nav-item {
    margin-left: 15px;
}

/* For mobile view */
@media (max-width: 991.98px) {
    .navbar-nav {
        padding-top: 15px;
    }
    .navbar-nav .nav-item {
        margin-left: 0;
        margin-bottom: 10px;
    }
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

/* Modal Content */
#cvModal .modal-content {
    background-color:white;  
    border-radius: 8px;  
}

/* Modal Backdrop */
.modal-backdrop {
    background-color: rgba(255, 255, 255, 0.5); 
}

.card {
    background-color: transparent; 
    border: 1px solid #ccc; 
    border-radius: 8px;  
    padding: 20px; 
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);  
}

.card-glass {
    background: transparent(255, 255, 255, 0.8);
    border-radius: 10px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    transition: 0.3s ease-in-out;
}

.card-glass:hover {
    transform: translateY(-5px);
    box-shadow: 0 12px 24px rgba(0, 0, 0, 0.15);
}

.card-glass .card-body {
    padding: 30px;
}

.professionals-header {
    margin-bottom: 40px;
    text-align: center;
}

.professionals-header h2 {
    font-size: 2.5rem;
    color: #1A2A3A;
}

.professionals-header .input-group {
    width: 100%;
    max-width: 450px;
    margin: 20px auto;
}

.professionals-header .dropdown {
    margin-left: 15px;
}

.personal-section {
    margin-bottom: 20px;
}

.personal-section .card-title {
    font-size: 1.3rem;
    font-weight: 600;
    color: #1A2A3A;
}

.personal-section .card-text {
    color: #6c757d;
}

.cv-section h6 {
    font-size: 1.1rem;
    font-weight: 500;
    color: #1A2A3A;
}

.info-item {
    margin: 8px 0;
    color: #6c757d;
}

.info-item strong {
    color: #1A2A3A;
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
                        <span><i class="fas fa-mobile-alt"></i> Lebanon</span>
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

             
                    <ul class="navbar-nav ms-auto justify-content-end">


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

    <br>
    <button onclick="history.back()" class="back-button">
  <i class="fas fa-arrow-left"></i> Back
</button>
   
<!-- Professionals Section -->
<section id="professionals" class="mb-5">
    <div class="professionals-header">
        <h2>Browse Professionals</h2>
        <div class="d-flex">
        <div class="dropdown">
    <button class="btn btn-secondary dropdown-toggle" type="button" id="filterDropdown" data-bs-toggle="dropdown" aria-expanded="false">
        Filter
    </button>
    <ul class="dropdown-menu" aria-labelledby="filterDropdown">
        <li><a class="dropdown-item" href="CbrowseProfessionalsProcess.php?filter=rating">By Rating</a></li>
        <li class="dropdown-submenu position-relative">
    <a class="dropdown-item dropdown-toggle" href="#">By Area of Work</a>
    <ul class="dropdown-menu submenu-right">
        <?php
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo '<li><a class="dropdown-item" href="CbrowseProfessionalsProcess.php?filter[areaOfWork]=' . urlencode($row['name']) . '">' . htmlspecialchars($row['name']) . '</a></li>';
            }
        }
        ?>
    </ul>
</li>

    </ul>
</div>

        </div>
    </div>
</section>
        <div class="row">
            <?php
       $sql = "SELECT professional.id, professional.fullName,professional.phoneNumber,professional.email,   professional_details.areaOfWork
       FROM professional
       JOIN professional_details ON professional.id = professional_details.professionalID";

            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) { ?>
            <div class="table-responsive ">
            <table class="table table-striped table-bordered align-middle text-center">
                <thead class="table-dark">
                    <tr>
                    <th>#</th>
                        <th>Name</th>
                        <th>Contact</th>
                        <th>Area of Work</th>
                        <th>Feedback</th>
                        <th>CV</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                $counter=0;
                while ($row = $result->fetch_assoc()) { ?>
                    <tr>
                    <td><?php echo $counter+=1; ?></td>
                        <td><?php echo ucwords($row['fullName']); ?></td>
                         <td><?php echo "<b>Phone number: </b>". ucwords($row['phoneNumber'])."<br><b>Email: </b>".ucwords($row['email']); ?></td>
                        <td><?php echo $row['areaOfWork']; ?></td>
                        <td>
                            <a href="#" class="btn btn-outline-secondary view-feedback-btn" data-professional-id="<?php echo $row['id']; ?>" data-bs-toggle="modal" data-bs-target="#feedbackModal">Check Feedback</a>
                        </td>
                        <td>
                            <a href="#" class="btn btn-outline-secondary view-cv-btn" data-professional-id="<?php echo $row['id']; ?>" data-bs-toggle="modal" data-bs-target="#cvModal">View CV</a>
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
           
            <div class="d-flex justify-content-between mt-3">
              


<div class="modal fade" id="feedbackModal" tabindex="-1" aria-labelledby="feedbackModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="feedbackModalLabel">Professional Feedback</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="feedbackDetails">
        <p>Loading feedback...</p>
      </div>
    </div>
  </div>
</div>                      
                
                <div class="modal fade" id="cvModal" tabindex="-1" aria-labelledby="cvModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="cvModalLabel">Professional CV</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="cvDetails">
        <p>Loading CV...</p>
      </div>
    </div>
  </div>
</div>

            </div>
    </div>
</div>

                <?php }
            } else {
                echo "<p>No records found!</p>";
            }

            $conn->close();
            ?>
        </div>
    </section>
    <script>
      // Enable Bootstrap dropdown functionality
      var dropdowns = document.querySelectorAll('.dropdown-toggle');
      dropdowns.forEach(function (dropdown) {
          dropdown.addEventListener('click', function (e) {
              e.preventDefault();
              var menu = dropdown.nextElementSibling;
              var isShown = menu.classList.contains('show');
              // Hide all dropdowns
              document.querySelectorAll('.dropdown-menu').forEach(function (menu) {
                  menu.classList.remove('show');
              });
              // Toggle this dropdown
              if (!isShown) {
                  menu.classList.add('show');
              }
          });
      });
      
    </script>
  
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
   // Use event delegation to handle clicks on dynamically added .view-cv-btn elements
$(document).on('click', '.view-cv-btn', function () {
var professionalId = $(this).data('professional-id');

$('#cvDetails').text('Loading...');

$.post('getCvDetails.php', { professional_id: professionalId }, function (response) {
    $('#cvDetails').html(response);
}).fail(function () {
    $('#cvDetails').text('Error loading CV.');
});
});


</script>

<script>
    $(document).on('click', '.view-feedback-btn', function () {
    var professionalId = $(this).data('professional-id');
    console.log('Professional ID:', professionalId); // Log ID for debugging

    $('#feedbackDetails').text('Loading feedback...');

    $.post('CgetFeedbackDetails.php', { professional_id: professionalId }, function (response) {
        $('#feedbackDetails').html(response);
    }).fail(function () {
        $('#feedbackDetails').text('Error loading feedback.');
    });
});

</script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Enable nested dropdowns
    var dropdownElements = [].slice.call(document.querySelectorAll('.dropdown-submenu'));
    
    dropdownElements.forEach(function(el) {
        el.addEventListener('mouseover', function() {
            this.querySelector('.dropdown-menu').classList.add('show');
        });
        
        el.addEventListener('mouseout', function() {
            this.querySelector('.dropdown-menu').classList.remove('show');
        });
    });
});
</script>
  </body>
</html>
