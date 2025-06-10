<!-- Clarita Antoun -->
<?php
session_start();
include 'conx.php';
$sql1 = "SELECT id, fullName FROM professional where professional.status='accepted'";
$result1 = $conn->query($sql1);

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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
    :root {
        --primary-color: #2A4D6E;
        --accent-color: #4A90E2;
        --background-color: rgba(255, 255, 255, 0.8);
        --nav-bg: #1A2A3A;
        --nav-accent: #FFC107;
    }

    body {
        font-family: 'Poppins', sans-serif;
        background-color: #f8f9fa;
        color: #495057;
        padding-top: 120px;
    }

    /* Header Top Styles */
    .header-top {
        background: var(--nav-bg);
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
        color: var(--nav-accent);
        font-family: 'Poppins', sans-serif;
        transition: color 0.3s ease;
    }
    
    .header-top a:hover {
        color: #fff;
    }

    /* Main Navigation */
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
        color: var(--nav-bg);
    }
    
    .logo span {
        color: var(--nav-accent);
        font-weight: 400;
    }
    
    .nav-link {
        color: var(--nav-bg) !important;
        font-weight: 500;
        padding: 0.5rem 1.5rem !important;
        transition: all 0.3s ease;
    }
    
    .nav-link:hover,
    .nav-link.active {
        color: var(--nav-accent) !important;
    }

    /* Form Styling */
    .feedback-form-container {
        max-width: 800px;
        margin: 40px auto;
        padding: 30px;
        background: white;
        border-radius: 10px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }

    .form-title {
        color: var(--nav-bg);
        font-family: 'Playfair Display', serif;
        font-weight: 600;
        margin-bottom: 30px;
        padding-bottom: 15px;
        border-bottom: 2px solid var(--nav-accent);
    }

    .form-group {
        margin-bottom: 20px;
    }

    label {
        font-weight: 500;
        color: var(--nav-bg);
        margin-bottom: 8px;
        display: block;
    }

    select, textarea {
        width: 100%;
        padding: 12px 15px;
        border: 2px solid #e0e0e0;
        border-radius: 6px;
        font-size: 16px;
        transition: all 0.3s;
    }

    select:focus, textarea:focus {
        border-color: var(--nav-accent);
        box-shadow: 0 0 0 3px rgba(255, 193, 7, 0.2);
        outline: none;
    }

    textarea {
        min-height: 150px;
        resize: vertical;
    }

    .rating-container {
        margin: 20px 0;
    }

    .rating-title {
        font-weight: 500;
        margin-bottom: 10px;
        color: var(--nav-bg);
    }

    .star-rating {
        font-size: 28px;
        margin-bottom: 20px;
    }

    .star {
        color: #ddd;
        cursor: pointer;
        transition: all 0.2s;
        margin-right: 5px;
    }

    .star:hover, .star.active {
        color: var(--nav-accent);
    }

    .submit-btn {
        background: var(--nav-bg);
        color: white;
        border: none;
        padding: 12px 30px;
        font-size: 16px;
        font-weight: 500;
        border-radius: 6px;
        cursor: pointer;
        transition: all 0.3s;
        width: 100%;
    }

    .submit-btn:hover {
        background: #0e1a2b;
        transform: translateY(-2px);
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        body {
            padding-top: 140px;
        }
        
        .main-navbar {
            top: 80px;
        }
        
        .feedback-form-container {
            padding: 20px;
            margin: 20px;
        }
    }
    /* Add these styles to your existing CSS */
.carousel-container {
    background: var(--background-color);
    border-radius: 10px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

.carousel-item img {
    object-fit: cover;
    border-radius: 8px;
}

/* Remove max-width from form container */
.feedback-form-container {
    max-width: none;

}

/* Responsive adjustments */
@media (max-width: 992px) {
    .row {
        flex-direction: column;
    }
    
    .carousel-container {
        margin-top: 20px;
    }
    
    .carousel-inner {
        height: 400px;
    }
}

.feedback-form-container, 
.carousel-container {
  display: flex;
  flex-direction: column;
  justify-content: flex-start; /* align content at top */

  margin-top: 100px;
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
                </ul>
            </div>
        </div>
    </nav>

<!-- Modified Container Section -->
<div class="container">
<div class="row g-4 equal-height-row">
  
        <!-- Feedback Form Column -->
        <div class="col-lg-6">
            <div class="feedback-form-container">
                <h2 class="form-title">Professional Feedback</h2>
                 <form action="CsubmitFeedback.php" method="POST" class="needs-validation" novalidate>
                <div class="form-group">
                    <label for="professional">Select Professional</label>
                    <select name="professionalId" id="professional" class="form-control" required>
                        <option value="">-- Select a professional --</option>
                        <?php while ($row1 = mysqli_fetch_assoc($result1)): ?>
                            <option value="<?php echo $row1['id']; ?>">
                                <?php echo ucwords($row1['fullName']); ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="comment">Your Feedback</label>
                    <textarea name="comment" id="comment" class="form-control" 
                              placeholder="Describe your experience working with this professional..." 
                              required></textarea>
                </div>
                
                <div class="rating-container">
                    <div class="rating-title">Rating</div>
                    <div class="star-rating" id="professionalStars">
                        <span class="star" data-value="1">☆</span>
                        <span class="star" data-value="2">☆</span>
                        <span class="star" data-value="3">☆</span>
                        <span class="star" data-value="4">☆</span>
                        <span class="star" data-value="5">☆</span>
                    </div>
                    <input type="hidden" name="professional_rating" id="professionalRating">
                </div>
                
                <button type="submit" class="submit-btn">Submit Feedback</button>
            </form>
            </div>
        </div>




<div class="col-lg-6">
    <div class="carousel-container p-3">
        <div id="autoCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="2000">
            <div class="carousel-inner ratio ratio-16x9">
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
</div>

<br>
<br>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    // Professional Rating
    document.querySelectorAll('#professionalStars .star').forEach(star => {
        star.addEventListener('click', () => {
            const value = parseInt(star.dataset.value);
            document.querySelectorAll('#professionalStars .star').forEach((s, index) => {
                s.classList.toggle('active', index < value);
                s.textContent = index < value ? '★' : '☆';
            });
            document.getElementById('professionalRating').value = value;
        });
    });

    // Form validation
    (function() {
        'use strict';
        var forms = document.querySelectorAll('.needs-validation');
        Array.prototype.slice.call(forms)
            .forEach(function(form) {
                form.addEventListener('submit', function(event) {
                    if (!form.checkValidity()) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                    form.classList.add('was-validated');
                }, false);
            });
    })();
    </script>
  </body>
</html>