<!-- CLARITA ANTOUN -->
<?php
 session_start();
 include 'conx.php';
 if (!isset($_SESSION['homeOwner_identity']['id'])) {
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
  .carousel-inner img {
    height: 400px;
    width: 500px;          /* Adjust as needed */
    object-fit: cover;      /* Makes images fill the box without stretching */
  }
      #cvModal .modal-content {
    background-color:white;  /* Milky white background with 80% opacity */
    border-radius: 8px;  /* Optional: To give the modal rounded corners */
}

/* Optional: You can also make the modal overlay (background behind the modal) milky */
.modal-backdrop {
    background-color: rgba(255, 255, 255, 0.5);  /* Milky white overlay behind the modal */
}
      body {
      margin: 0;
      padding: 0;
      height: 100vh;
      background: white;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      color: black;
    }

        /* Header Top Bar */
        .header-area {
            background: #1A2A3A;
            padding: 12px 0;
            color: brown;
            font-family: 'Poppins', sans-serif;
            border-bottom: 1px solid rgba(212, 175, 55, 0.1);
        }

        .header-left a, .header-right ul li a {
            color: brown;
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
            border-bottom: 2px solid brown;
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
        }

        .navbar-nav .nav-link:hover, 
        .navbar-nav .nav-item.active .nav-link {
            color: yellow;
        }

        /* Dropdown Menu */
        .dropdown-menu {
            background: #FFFFFF;
            border: 1px solid rgba(26, 42, 58, 0.1);
            border-radius: 4px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .dropdown-menu .dropdown-item {
            color: #1A2A3A !important;
            padding: 12px 25px;
            font-size: 15px;
            transition: 0.3s;
        }

        .dropdown-menu .dropdown-item:hover {
            background: #F8F9FA;
            color: brown !important;
        }

        /* Professionals Section */
        .professionals-header {
            margin-bottom: 40px;
            text-align: center;
        }

        .professionals-header h2 {
            font-size: 2.5rem;
            color: #1A2A3A;
        }
        .card {
    background-color:white; /* Adjust opacity by changing the alpha value (0.0 to 1.0) */
    border: 1px solid #ccc;  /* Optional: Add a border for the card */
    border-radius: 8px;  /* Optional: Add rounded corners */
    padding: 20px;  /* Optional: Add padding inside the card */
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);  /* Optional: Add shadow for better visibility */
}
        .professionals-header .input-group {
            width: 100%;
            max-width: 450px;
            margin: 20px auto;
        }

        .professionals-header .dropdown {
            margin-left: 15px;
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

        /* Buttons */
        .btn-appoint {
            background: brown;
            color: #1A2A3A !important;
            padding: 14px 30px;
            border-radius: 30px;
            font-weight: 600;
            transition: 0.3s;
            letter-spacing: 0.5px;
        }

        .btn-appoint:hover {
            background: #1A2A3A;
            color: #FFFFFF !important;
            border-color: yellow;
            transform: translateY(-2px);
        }
        .dropdown-submenu:hover > .submenu-right {
    display: block;
    top: 0;
    left: 100%;
    margin-top: -1px;
}

.dropdown-submenu .submenu-right {
    display: none;
    position: absolute;
    left: 100%;
    top: 0;
    margin-top: 0;
    margin-left: 0;
    border-top-left-radius: 0;
    border-bottom-left-radius: 0;
}
body {
    font-family: 'Playfair Display', serif;
    background: white; /* Light red background */
    color: #3e0f0f;
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
<br>
<div class="container my-4 d-flex justify-content-center">
  <div id="autoCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="2000" style="width: 1000px;">
    <div class="carousel-inner">
      <div class="carousel-item active">
        <img src="images/ca1.avif" class="d-block w-100" alt="Slide 1">
      </div>
      <div class="carousel-item">
        <img src="images/c3.avif" class="d-block w-100" alt="Slide 2">
      </div>
      <div class="carousel-item">
        <img src="images/ca3.jpeg" class="d-block w-100" alt="Slide 3">
      </div>
      <div class="carousel-item">
        <img src="images/t1.jpeg" class="d-block w-100" alt="Slide 4">
      </div>
      <div class="carousel-item">
        <img src="images/s1.webp" class="d-block w-100" alt="Slide 5">
      </div>
      <div class="carousel-item">
        <img src="images/t3.jpg" class="d-block w-100" alt="Slide 6">
      </div>
    </div>
  </div>
</div>
<br>

    <br>

   
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
        <li><a class="dropdown-item" href="HbrowseProfessionalsProcess.php?filter=rating">By Rating</a></li>
        <li class="dropdown-submenu position-relative">
    <a class="dropdown-item dropdown-toggle" href="#">By Area of Work</a>
    <ul class="dropdown-menu submenu-right">
        <?php
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo '<li><a class="dropdown-item" href="HbrowseProfessionalsProcess.php?filter[areaOfWork]=' . urlencode($row['name']) . '">' . htmlspecialchars($row['name']) . '</a></li>';
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
       $sql = "SELECT professional.*, professional_details.areaOfWork, professional_details.price,professional_details.priceDetails
       FROM professional
       JOIN professional_details ON professional.id = professional_details.professionalID 
       WHERE professional.status='accepted'";

            $result = $conn->query($sql);

       
             
             
              if ($result->num_rows > 0) { ?>
                  <div class="table-responsive">
                      <table class="table table-bordered align-middle text-center">
                          <thead class="table-dark">
                              <tr>
                              <th>#</th>
                                  <th>Full Name</th>
                                  <th>Contact</th>
                                  <th>Area of Work</th>
                                  <th>Price</th>
                                  <th>Actions</th>
                              </tr>
                          </thead>
                          <tbody>
                              <?php 
                              $counter=0;
                              while ($row = $result->fetch_assoc()) { ?>
                                  <tr>
                                  <td><?php echo $counter+=1;; ?></td>
                                      <td><?php echo ucwords($row['fullName']); ?></td>
                                     <td><?php echo "<b>Phone number: </b>". ucwords($row['phoneNumber'])."<br><b>Email: </b>".ucwords($row['email']); ?></td>

                                      <td><?php echo $row['areaOfWork']; ?></td>
                                     <td>
    <?php 
    if ($row['price'] == 0) {
        echo '<span style="color: green;">Not set yet</span>';
    } else {
        echo '<span style="color: green;">' . $row['price'] . ' (' . $row['priceDetails'] . ')</span>';
    }
    ?>
</td>


                                      <td>
                                          <!-- Feedback Button -->
                                          <a href="#" class="btn btn-outline-secondary btn-sm view-feedback-btn" data-professional-id="<?php echo $row['id']; ?>" data-bs-toggle="modal" data-bs-target="#feedbackModal">Check Feedback</a>
              
                                          <!-- CV Button -->
                                          <a href="#" class="btn btn-outline-secondary btn-sm view-cv-btn" data-professional-id="<?php echo $row['id']; ?>" data-bs-toggle="modal" data-bs-target="#cvModal">View CV</a>
                                      </td>
                                  </tr>
                              <?php } ?>
                          </tbody>
                      </table>
                  </div>
              
                  <!-- Feedback Modal -->
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
              
                  <!-- CV Modal -->
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
              
              <?php
              } else {
                  echo "<p>No records found!</p>";
              }
              
              $conn->close();
              ?>
              
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

    $.post('HgetFeedbackDetails.php', { professional_id: professionalId }, function (response) {
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
