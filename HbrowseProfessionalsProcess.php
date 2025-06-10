<!-- CLARITA ANTOUN -->
<?php
 session_start();
 include 'conx.php';
 if (!isset($_SESSION['homeOwner_identity']['id'])) {
  header("Location: logInPage.php");
  exit();
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
  @media (max-width: 768px) {
    .table-responsive table {
        font-size: 0.9rem;
    }
    .table-responsive th, 
    .table-responsive td {
        padding: 0.5rem;
    }
}

#cvModal .modal-content {
    background-color:white;  /* Milky white background with 80% opacity */
    border-radius: 8px;  /* Optional: To give the modal rounded corners */
}


.modal-backdrop {
    background-color: rgba(255, 255, 255, 0.5);  /* Milky white overlay behind the modal */
}

.back-button {
  display: flex;
  align-items: center;
  gap: 8px;
  /* Keep other styles from Option 1 */
}
        body {
            font-family: 'Poppins', sans-serif;
            background: #f8f9fa;
            color: #495057;
            background: linear-gradient(135deg,grey,white);
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
            color: brown;
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

        .professionals-header .input-group {
            width: 100%;
            max-width: 450px;
            margin: 20px auto;
        }

        .professionals-header .dropdown {
            margin-left: 15px;
        }

        .card-glass {
            background: rgba(255, 255, 255, 0.8);
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
        .card {
    background-color:transparent; /* Adjust opacity by changing the alpha value (0.0 to 1.0) */
    border: 1px solid #ccc;  /* Optional: Add a border for the card */
    border-radius: 8px;  /* Optional: Add rounded corners */
    padding: 20px;  /* Optional: Add padding inside the card */
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);  /* Optional: Add shadow for better visibility */
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

    <button onclick="history.back()" class="back-button">
  <i class="fas fa-arrow-left"></i> Back
</button>
   
    <?php
$sql1 = ""; 
$sql2 = ""; 

if (isset($_GET['filter'])) {
    $filter = $_GET['filter'];

    if (is_array($filter) && isset($filter['areaOfWork'])) {
        $area = $filter['areaOfWork'];
        $sql1 = "SELECT p.*, pd.areaOfWork as areaOfWork, pd.price,pd.priceDetails
                FROM professional p
                JOIN professional_details pd ON p.id = pd.professionalID
                WHERE pd.areaOfWork = '$area' and p.status='accepted'";

    } elseif ($filter === 'rating') {
        $sql2 = "SELECT p.*, 
       pd.areaOfWork,pd.price,pd.priceDetails,
       AVG(ho.rating) AS avg_rating
FROM professional p
JOIN professional_details pd ON p.id = pd.professionalID
JOIN ho_pro_feedback ho ON p.id = ho.professionalID
WHERE p.status='accepted'
GROUP BY p.id
ORDER BY avg_rating DESC;
";
        
    }
}


// Ensure that we have a valid query before executing
if (!empty($sql1)) {
    $result1 = $conn->query($sql1);
    // Display results for areaOfWork filter
    if ($result1 && $result1->num_rows > 0) { ?>

    <div class="table-responsive">
        <table class="table table-bordered align-middle text-center">
            <thead class="table-dark">
                <tr>
                <th>#</th>
                    <th>Full Name</th>
                   
                    <th>Contact</th>
                     <th>Area of Work</th>
                     <th>Price</th>
                      <th>Email</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                 $counter=0;
                  while ($row1 = $result1->fetch_assoc()) { ?>
                    <tr>
                    <td><?php echo $counter+=1;; ?></td>
                        <td><?php echo htmlspecialchars($row1['fullName']); ?></td>
           <td><?php echo "<b>Phone number: </b>". ucwords($row1['phoneNumber'])."<br><b>Email: </b>".ucwords($row1['email']); ?></td>

                        <td style="color: red; font-weight: bold;"><?php echo htmlspecialchars($row1['areaOfWork']); ?></td>
                       <td>
    <?php 
    if ($row1['price'] == 0) {
        echo '<span style="color: green;">Not set yet</span>';
    } else {
        echo '<span style="color: green;">' . $row1['price'] . ' (' . $row1['priceDetails'] . ')</span>';
    }
    ?>
</td>



                        <td><?php echo $row1['email']; ?></td>
                        <td>
                            <!-- Feedback Button -->
                            <a href="#" class="btn btn-outline-secondary btn-sm view-feedback-btn"
                               data-professional-id="<?php echo $row1['id']; ?>"
                               data-bs-toggle="modal" data-bs-target="#feedbackModal">
                                Check Feedback
                            </a>

                            <!-- CV Button -->
                            <a href="#" class="btn btn-outline-secondary btn-sm view-cv-btn"
                               data-professional-id="<?php echo $row1['id']; ?>"
                               data-bs-toggle="modal" data-bs-target="#cvModal">
                                View CV
                            </a>
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
       echo '<div class="alert alert-warning text-center" role="alert">No professionals found for the selected area.</div>';

    }
}
?>


        <div class="row">
      
        <?php
if (!empty($sql2)) {
    $result2 = $conn->query($sql2);
    if ($result2 && $result2->num_rows > 0) {
        ?>
        <div class="table-responsive" style="overflow-x: auto; max-width: 100%;">
        <div class="table-responsive" style="overflow-x: auto; max-width: 100%;">
    <table class="table table-striped table-hover align-middle" style="margin-left: 20px; table-layout: fixed; width: 100%;">
        <thead class="table-dark">
            <tr>
                <th style="width: 5%; min-width: 50px;">#</th>
                <th style="width: 5%;">Full Name</th>
                <th style="width: 15%;">Contact</th>
                <th style="width: 8%;">Area of Work</th>
 <th style="width: 5%;">Price</th>
                <th style="width: 5%;">Rating(average)</th>
                <th style="width: 5%;">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $counter = 0;
            while ($row2 = $result2->fetch_assoc()) {
            ?>
            <tr>
                <td style="width: 5%; min-width: 50px; padding-left: 10px;"><?php echo $counter += 1; ?></td>
                <td style="width: 5%;"><?php echo htmlspecialchars($row2['fullName']); ?></td>
     <td><?php echo "<b>Phone number: </b>". ucwords($row2['phoneNumber'])."<br><b>Email: </b>".ucwords($row2['email']); ?></td>

                <td style="width: 8%;"><?php echo htmlspecialchars($row2['areaOfWork']); ?></td>
               <td>
    <?php 
    if ($row2['price'] == 0) {
        echo '<span style="color: green;">Not set yet</span>';
    } else {
        echo '<span style="color: green;">' . $row2['price'] . ' (' . $row2['priceDetails'] . ')</span>';
    }
    ?>
</td>


               <td style="width: 5%; color: red; font-weight: bold;"><?php echo htmlspecialchars($row2['avg_rating']); ?></td>

                <td style="width: 5%;">
                    <a href="#" class="btn btn-outline-secondary view-cv-btn" 
                       data-professional-id="<?php echo $row2['id']; ?>" 
                       data-bs-toggle="modal" 
                       data-bs-target="#cvModal"
                       style="white-space: nowrap;">View CV</a>
                </td>
            </tr>
            <?php
            }
            ?>
        </tbody>
    </table>
</div>
        </div>

        <!-- CV Modal (only one, reused) -->
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
        echo "<p>No professionals found with ratings.</p>";
    }
}

$conn->close();
?>

           
        </div>
    </section>
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
   // Use event delegation to handle clicks on dynamically added .view-feedback-btn elements
$(document).on('click', '.view-feedback-btn', function () {
    var professionalId = $(this).data('professional-id');

    // Set loading text in the modal
    $('#feedbackDetails').text('Loading feedback...');

    // Make AJAX request to get feedback data
    $.post('HgetFeedbackDetails.php', { professional_id: professionalId }, function (response) {
        $('#feedbackDetails').html(response); // Display the feedback in the modal
    }).fail(function () {
        $('#feedbackDetails').text('Error loading feedback.'); // Show error message if the request fails
    });
});
</script>
  </body>
</html>




