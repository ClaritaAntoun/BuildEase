<?php
session_start();
if (!isset($_SESSION['professional_identity']['id'])) {
    header("Location: logInPage.php");
    exit();
}

include 'conx.php';
$professionalID = $_SESSION['professional_identity']['id'];
$sql = "select f.*, ho.fullName
        from ho_pro_feedback as f, homeowner as ho 
        where f.homeOwnerID = ho.id and professionalID = '$professionalID' and ho.status = 'accepted'
        ORDER BY f.rating DESC";
$result = $conn->query($sql);
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>BuildEase - Client Feedback</title>

    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/custom.css">
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" crossorigin="anonymous"/>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    
    <style>
         body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
            color: #333; /* Darker text for better contrast */
        }
        
        .feedback-container {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
        }
        
        .feedback-header {
            border-bottom: 2px solid rgba(106, 90, 205, 0.2);
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        
        .feedback-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
            border-left: 4px solid #6a5acd;
            margin-bottom: 25px;
        }
        
        .feedback-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(106, 90, 205, 0.15);
        }
        
        .user-avatar {
            width: 70px; /* Slightly larger avatar */
            height: 70px;
            object-fit: cover;
            border: 2px solid #6a5acd;
        }
        
        .rating-stars {
            color: #ffc107;
            font-size: 1.4rem; /* Larger stars */
        }
        
        .feedback-date {
            color: #555; /* Darker text for better visibility */
            font-size: 1rem; /* Increased from 0.85rem */
        }
        
        .feedback-content {
            color: #333; /* Darker text */
            line-height: 1.7; /* More spacing between lines */
            font-size: 1.1rem; /* Increased from default */
        }
        
        .no-feedback {
            background: white;
            border-radius: 12px;
            padding: 40px; /* More padding */
            text-align: center;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }
        
        .section-title {
            position: relative;
            display: inline-block;
            color: #1A2A3A;
            font-size: 2.5rem; /* Larger title */
            font-weight: 700;
            margin-bottom: 20px;
        }
        
        .section-title:after {
            content: '';
            position: absolute;
            width: 50%;
            height: 4px; /* Thicker underline */
            background: #6a5acd;
            bottom: -15px; /* More space below */
            left: 0;
            border-radius: 3px;
        }
        
        .header-text {
            font-size: 1.2rem; /* Larger description text */
            color: #555; /* Slightly darker for better contrast */
        }
        
        .user-name {
            font-size: 1.5rem; /* Larger name */
            font-weight: 600;
            color: #1A2A3A; /* Dark navy for better contrast */
            margin-bottom: 5px;
        }
        
        .no-feedback h4 {
            font-size: 1.8rem;
            color: #1A2A3A;
            margin-bottom: 15px;
        }
        
        .no-feedback p {
            font-size: 1.2rem;
            color: #555;
        }
        
        .feedback-icon {
            font-size: 4rem; /* Much larger icon */
            margin-bottom: 20px;
            color: #6a5acd;
        }
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
                                    <li><a class="dropdown-item" href="C_P_browsingFeedback.php">Contractors feedbacks</a></li>
                                    <li><a class="dropdown-item" href="HO_P_browsingFeedback.php">Home owners feedbacks</a></li>
                                </ul>
                            </li>
                            <li class="nav-item active"><a class="nav-link" href="#">Current Projects</a></li>
                        </ul>
                    </div>
                </nav>
            </div>
        </nav>
    </div>

    <div class="container py-5 my-5">
        <div class="feedback-container p-4 p-md-5">
            <div class="feedback-header text-center">
                <h1 class="section-title mb-3">Client Feedback</h1>
                <p class="text-muted">Reviews from homeowners who have worked with <?php echo htmlspecialchars($_SESSION['professional_identity']['fullName']); ?></p>
            </div>
            
            <?php if ($result->num_rows > 0) { ?>
                <div class="row">
                    <?php while($row = $result->fetch_assoc()) { ?>
                        <div class="col-lg-8 mx-auto">
                            <div class="feedback-card p-4">
                                <div class="d-flex">
                                    <img src="https://cdn-icons-png.flaticon.com/512/149/149071.png" 
                                         class="user-avatar rounded-circle me-4" 
                                         alt="User Avatar">
                                    
                                    <div class="flex-grow-1">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <h5 class="mb-0"><?php echo htmlspecialchars($row['fullName']); ?></h5>
                                            <div class="rating-stars">
                                                <?php echo str_repeat('★', (int)$row['rating']); ?>
                                                <?php echo str_repeat('☆', 5 - (int)$row['rating']); ?>
                                            </div>
                                        </div>
                                        <p class="feedback-content mb-3"><?php echo htmlspecialchars($row['comment']); ?></p>
                                        <span class="feedback-date">
                                            <i class="far fa-calendar-alt me-1"></i>
                                            <?php echo date('F j, Y', strtotime($row['date'])); ?>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            <?php } else { ?>
                <div class="no-feedback">
                    <i class="far fa-comment-dots fa-3x mb-3" style="color: #6a5acd;"></i>
                    <h4>No Feedback Yet</h4>
                    <p class="text-muted">You haven't received any feedback from homeowners yet.</p>
                </div>
            <?php } ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>