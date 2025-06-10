<?php
session_start();
include 'conx.php';
if (!isset($_SESSION['homeOwner_identity']['id'])) {
  header("Location: logInPage.php");
  exit();
}
$sql1 = "SELECT id, fullName FROM professional where professional.status='accepted'";
$result1 = $conn->query($sql1);
$sql2 = "SELECT id, fullName FROM contractor where contractor.status='accepted'";
$result2 = $conn->query($sql2);
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
        --primary-color: #1A2A3A;
        --accent-color: brown;
        --light-accent: rgba(165, 42, 42, 0.1);
        --text-color: #4a4a4a;
        --light-bg: #f9f7f5;
        --card-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
    }

    body {
        font-family: 'Poppins', sans-serif;
        background-color: white;
        color: var(--text-color);
        line-height: 1.6;
    }

    .feedback-section {
        padding: 5rem 0;
        background: linear-gradient(135deg, #f9f9f9 0%, #f1f1f1 100%);
    }

    .section-title {
        font-family: 'Playfair Display', serif;
        color: var(--primary-color);
        margin-bottom: 3rem;
        position: relative;
        text-align: center;
    }

    .section-title:after {
        content: '';
        display: block;
        width: 80px;
        height: 3px;
        background: var(--accent-color);
        margin: 15px auto;
    }

    .feedback-card {
        background: white;
        border-radius: 12px;
        box-shadow: var(--card-shadow);
        border: none;
        overflow: hidden;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        height: 100%;
    }

    .feedback-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
    }

    .card-header {
        background: var(--primary-color) !important;
        color: white !important;
        padding: 1.5rem;
        font-family: 'Playfair Display', serif;
        font-weight: 600;
        letter-spacing: 0.5px;
        border-bottom: 3px solid var(--accent-color);
    }

    .card-body {
        padding: 2rem;
    }

    .form-label {
        font-weight: 500;
        color: var(--primary-color);
        margin-bottom: 0.5rem;
    }

    .form-control, .form-select {
        border-radius: 8px;
        padding: 12px 15px;
        border: 1px solid #e0e0e0;
        background-color: #f8f8f8;
        transition: all 0.3s;
    }

    .form-control:focus, .form-select:focus {
        border-color: var(--accent-color);
        box-shadow: 0 0 0 0.25rem rgba(165, 42, 42, 0.25);
        background-color: white;
    }

    textarea.form-control {
        min-height: 120px;
        resize: vertical;
    }

    .rating-container {
        margin: 1.5rem 0;
        text-align: center;
    }

    .rating-stars {
        display: inline-block;
        font-size: 0;
    }

    .star {
        font-size: 28px;
        color: #ddd;
        cursor: pointer;
        transition: color 0.2s;
        display: inline-block;
        margin: 0 5px;
    }

    .star:hover, .star.active {
        color: #FFD700;
    }

    .rating-text {
        margin-top: 10px;
        font-size: 14px;
        color: var(--text-color);
    }

    .btn-submit {
        background: var(--primary-color);
        color: white;
        border: none;
        padding: 12px 30px;
        border-radius: 8px;
        font-weight: 600;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        transition: all 0.3s;
        box-shadow: 0 4px 15px rgba(26, 42, 58, 0.2);
    }

    .btn-submit:hover {
        background: var(--accent-color);
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(165, 42, 42, 0.3);
        color: white;
    }

    .icon-wrapper {
        display: inline-block;
        width: 40px;
        height: 40px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 50%;
        text-align: center;
        line-height: 40px;
        margin-right: 10px;
    }

    .divider {
        margin: 2rem 0;
        border-top: 1px dashed #e0e0e0;
    }

    @media (max-width: 768px) {
        .feedback-card {
            margin-bottom: 2rem;
        }
    }

    /* Keep original navbar styles */
    .header-area {
        background: #1A2A3A;
        padding: 12px 0;
        color: brown;
        font-family: 'Poppins', sans-serif;
        border-bottom: 1px solid rgba(212, 175, 55, 0.1);
    }

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
        color: brown;
        font-weight: 400;
    }
    /* Import classic font */
@import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500&family=Lato:wght@300;400&display=swap');

/* Section Headers */
h2, h3 {
    font-family: 'Playfair Display', serif;
    color:brown; /* Muted orange for header text */
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
   
}

/* Empty State */
.text-danger {
    color: #b87d7d !important; /* Dusty rose */
    font-style: italic;
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

    <section class="feedback-section">
        <div class="container">
            <h2 class="section-title">Share Your Experience</h2>
<p class="text-center mb-5" style="max-width: 700px; margin: 0 auto;">Your feedback helps us maintain quality standards and improve our services.</p>
            <div class="row g-4">
                <!-- Professional Feedback Card -->
                <div class="col-lg-6">
                    <div class="feedback-card">
                        <div class="card-header">
                            <div class="d-flex align-items-center">
                                <div class="icon-wrapper">
                                    <i class="fas fa-drafting-compass"></i>
                                </div>
                                <span>Professional Feedback</span>
                            </div>
                        </div>
                        <div class="card-body">
                            <form action="HsubmitFeedback.php" method="POST">
                                <div class="mb-4">
                                    <label for="professional" class="form-label">Select Professional</label>
                                    <select name="professionalId" id="professional" class="form-select" required>
                                        <option value="">-- Choose a professional --</option>
                                        <?php while ($row1 = mysqli_fetch_assoc($result1)): ?>
                                            <option value="<?php echo $row1['id']; ?>">
                                                <?php echo ucwords($row1['fullName']); ?>
                                            </option>
                                        <?php endwhile; ?>
                                    </select>
                                </div>

                                <div class="mb-4">
                                    <label for="commentProf" class="form-label">Your Feedback</label>
                                    <textarea name="Pcomment" id="commentProf" class="form-control" rows="4" placeholder="Tell us about your experience with this professional..." required></textarea>
                                </div>

                                <div class="rating-container">
                                    <div class="mb-2">Rate your experience</div>
                                    <div class="rating-stars" id="professionalStars">
                                        <span class="star" data-value="1">☆</span>
                                        <span class="star" data-value="2">☆</span>
                                        <span class="star" data-value="3">☆</span>
                                        <span class="star" data-value="4">☆</span>
                                        <span class="star" data-value="5">☆</span>
                                    </div>
                                    <div class="rating-text" id="professionalRatingText">Click to rate</div>
                                    <input type="hidden" name="professional_rating" id="professionalRating">
                                </div>

                                <div class="text-end mt-4">
                                    <button type="submit" class="btn btn-submit">Submit Feedback</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Contractor Feedback Card -->
                <div class="col-lg-6">
                    <div class="feedback-card">
                        <div class="card-header">
                            <div class="d-flex align-items-center">
                                <div class="icon-wrapper">
                                    <i class="fas fa-hard-hat"></i>
                                </div>
                                <span>Contractor Feedback</span>
                            </div>
                        </div>
                        <div class="card-body">
                            <form action="HsubmitFeedback.php" method="POST">
                                <div class="mb-4">
                                    <label for="contractor" class="form-label">Select Contractor</label>
                                    <select name="contractorId" id="contractor" class="form-select" required>
                                        <option value="">-- Choose a contractor --</option>
                                        <?php while ($row2 = mysqli_fetch_assoc($result2)): ?>
                                            <option value="<?php echo $row2['id']; ?>">
                                                <?php echo ucwords($row2['fullName']); ?>
                                            </option>
                                        <?php endwhile; ?>
                                    </select>
                                </div>

                                <div class="mb-4">
                                    <label for="commentCont" class="form-label">Your Feedback</label>
                                    <textarea name="Ccomment" id="commentCont" class="form-control" rows="4" placeholder="Tell us about your experience with this contractor..." required></textarea>
                                </div>

                                <div class="rating-container">
                                    <div class="mb-2">Rate your experience</div>
                                    <div class="rating-stars" id="contractorStars">
                                        <span class="star" data-value="1">☆</span>
                                        <span class="star" data-value="2">☆</span>
                                        <span class="star" data-value="3">☆</span>
                                        <span class="star" data-value="4">☆</span>
                                        <span class="star" data-value="5">☆</span>
                                    </div>
                                    <div class="rating-text" id="contractorRatingText">Click to rate</div>
                                    <input type="hidden" name="contractor_rating" id="contractorRating">
                                </div>

                                <div class="text-end mt-4">
                                    <button type="submit" class="btn btn-submit">Submit Feedback</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Scripts -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Enhanced Star Rating Functionality
        function initializeRating(containerId, hiddenInputId, textElementId) {
            const stars = document.querySelectorAll(`#${containerId} .star`);
            const ratingTexts = [
                "Poor",
                "Fair",
                "Good",
                "Very Good",
                "Excellent"
            ];

            stars.forEach(star => {
                star.addEventListener('click', () => {
                    const value = parseInt(star.dataset.value);
                    
                    // Update stars
                    stars.forEach((s, index) => {
                        s.classList.toggle('active', index < value);
                        s.textContent = index < value ? '★' : '☆';
                    });
                    
                    // Update hidden input
                    document.getElementById(hiddenInputId).value = value;
                    
                    // Update rating text
                    document.getElementById(textElementId).textContent = ratingTexts[value - 1];
                });

                // Hover effect
                star.addEventListener('mouseover', () => {
                    const hoverValue = parseInt(star.dataset.value);
                    stars.forEach((s, index) => {
                        s.style.color = index < hoverValue ? '#FFD700' : '#ddd';
                    });
                });

                star.addEventListener('mouseout', () => {
                    const currentValue = document.getElementById(hiddenInputId).value || 0;
                    stars.forEach((s, index) => {
                        s.style.color = index < currentValue ? '#FFD700' : '#ddd';
                    });
                });
            });
        }

        // Initialize both rating systems
        document.addEventListener('DOMContentLoaded', () => {
            initializeRating('professionalStars', 'professionalRating', 'professionalRatingText');
            initializeRating('contractorStars', 'contractorRating', 'contractorRatingText');
        });
    </script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  </body>
</html>