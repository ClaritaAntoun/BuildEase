
    <?php
session_start();
include 'conx.php';
if (!isset($_SESSION['homeOwner_identity']['id'])) {
    header("Location: logInPage.php");
    exit();
}
$homeOwnerId = $_SESSION['homeOwner_identity']['id'];
  
$projectID = isset($_GET['projectID']) ? $_GET['projectID'] : null;

if (!$projectID) {
    echo "Project ID not provided.";
    exit;
}

$sqlProject = "SELECT contractorPrice, websiteCommission, budget, exactCost, name FROM project WHERE projectID = ?";
$stmt = $conn->prepare($sqlProject);
$stmt->bind_param("i", $projectID);
$stmt->execute();
$projectData = $stmt->get_result()->fetch_assoc();
$stmt->close();

$contractorPrice = $projectData['contractorPrice'] ?? 0;
$websiteCommission = $projectData['websiteCommission'] ?? 0;
$budget = $projectData['budget'] ?? 0;
$exactCost = $projectData['exactCost'] ?? 0;
$projectName = $projectData['name'] ?? "Project";
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>BuildEase - Project Cost Summary: <?php echo htmlspecialchars($projectName); ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        
    /* Header Styles */
    .header-area {
        background: var(--secondary-color);
        padding: 12px 0;
        color: white;
        border-bottom: 1px solid rgba(212, 175, 55, 0.1);
    }

    .header-left a, .header-right ul li a {
        color: white;
        font-weight: 500;
        transition: 0.3s;
        font-size: 15px;
    }

    .header-left a:hover, .header-right ul li a:hover {
        color: var(--accent-color);
    }

    /* Navigation Bar */
    .navigation {
        background: #FFFFFF;
        padding: 15px 0;
        box-shadow: 0 4px 30px rgba(0, 0, 0, 0.08);
        border-bottom: 2px solid var(--primary-color);
    }

    .logo {
        font-family: 'Playfair Display', serif;
        font-size: 32px;
        font-weight: 700;
        color: var(--secondary-color);
    }
    .logo span {
        color: var(--primary-color);
        font-weight: 400;
    }

    .navbar-nav .nav-link {
        color: var(--secondary-color) !important;
        font-weight: 500;
        padding: 10px 15px !important;
        transition: 0.3s;
    }

    .navbar-nav .nav-link:hover, 
    .navbar-nav .nav-item.active .nav-link {
        color: var(--primary-color) !important;
    }

    .navbar-nav .nav-link:hover::after {
        content: '';
        position: absolute;
        bottom: -2px;
        left: 0;
        right: 0;
        height: 2px;
        background: var(--primary-color);
    }
        :root {
            --primary: #800020; /* Burgundy */
            --secondary: #1A2A3A; /* Navy blue */
            --accent: #D4AF37; /* Gold */
            --light: #f8f9fa;
            --success: #28a745;
            --danger: #dc3545;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            color: #495057;
            min-height: 100vh;
            padding-bottom: 3rem;
        }
        
        .header-area {
            background: var(--secondary);
            padding: 12px 0;
            color: white;
            border-bottom: 1px solid rgba(212, 175, 55, 0.1);
        }
        
        .navigation {
            background: #FFFFFF;
            padding: 15px 0;
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.08);
            border-bottom: 2px solid var(--primary);
        }
        
        .logo {
            font-family: 'Playfair Display', serif;
            font-size: 32px;
            font-weight: 700;
            color: var(--secondary);
        }
        
        .logo span {
            color: var(--primary);
            font-weight: 400;
        }
        
        .cost-summary-container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.1);
            padding: 2.5rem;
            margin: 2rem auto;
            max-width: 1000px;
            position: relative;
        }
        
        .back-button {
            position: absolute;
            top: 30px;
            left: 30px;
            background: var(--secondary);
            color: white;
            border: none;
            border-radius: 50px;
            padding: 10px 20px;
            font-weight: 500;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
        }
        
        .back-button:hover {
            background: var(--primary);
            transform: translateX(-5px);
        }
        
        .summary-header {
            text-align: center;
            margin-bottom: 2.5rem;
            padding-top: 20px;
        }
        
        .summary-header h2 {
            font-family: 'Playfair Display', serif;
            color: var(--primary);
            font-size: 2.5rem;
            margin-bottom: 0.5rem;
        }
        
        .project-name {
            color: var(--secondary);
            font-weight: 600;
            font-size: 1.2rem;
        }
        
        .summary-header::after {
            content: '';
            display: block;
            width: 100px;
            height: 4px;
            background: var(--accent);
            margin: 1rem auto;
            border-radius: 2px;
        }
        
        .cost-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        
        .cost-card {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            transition: all 0.3s ease;
            border: 1px solid #e0e0e0;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            position: relative;
            overflow: hidden;
        }
        
        .cost-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        }
        
        .cost-card::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 60px;
            height: 60px;
            background: rgba(212, 175, 55, 0.1);
            border-radius: 0 0 0 100%;
        }
        
        .card-title {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 1rem;
            font-weight: 600;
            color: var(--secondary);
        }
        
        .card-title i {
            font-size: 1.5rem;
            color: var(--accent);
            width: 40px;
            height: 40px;
            background: rgba(212, 175, 55, 0.1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .card-value {
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            color: var(--primary);
        }
        
        .card-description {
            color: #6c757d;
            font-size: 0.9rem;
        }
        
        .budget-status-section {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            margin: 2rem 0;
            box-shadow: 0 5px 20px rgba(0,0,0,0.05);
            border-top: 4px solid var(--accent);
        }
        
        .budget-status-title {
            text-align: center;
            font-weight: 600;
            margin-bottom: 1.5rem;
            color: var(--secondary);
            font-size: 1.2rem;
        }
        
        .budget-difference {
            text-align: center;
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 1rem;
        }
        
        .under-budget {
            color: var(--success);
        }
        
        .over-budget {
            color: var(--danger);
        }
        
        .status-explanation {
            text-align: center;
            max-width: 600px;
            margin: 0 auto;
            font-size: 1.1rem;
        }
        
        .progress-section {
            margin-top: 2rem;
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        }
        
        .progress-title {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.8rem;
            font-weight: 500;
        }
        
        .progress-bar-container {
            height: 20px;
            background: #e9ecef;
            border-radius: 10px;
            overflow: hidden;
            margin-bottom: 0.5rem;
            box-shadow: inset 0 1px 3px rgba(0,0,0,0.1);
        }
        
        .progress-bar {
            height: 100%;
            border-radius: 10px;
            background: linear-gradient(90deg, var(--primary), var(--accent));
            transition: width 1s ease;
        }
        
        .progress-labels {
            display: flex;
            justify-content: space-between;
            font-size: 0.9rem;
            color: #6c757d;
        }
        
        .commission-highlight {
            background: rgba(106, 90, 205, 0.08);
            border-left: 4px solid var(--accent);
            border-radius: 8px;
            padding: 1.5rem;
            margin-top: 2rem;
            display: flex;
            align-items: center;
            gap: 20px;
        }
        
        .commission-icon {
            font-size: 2.5rem;
            color: var(--accent);
            min-width: 60px;
            text-align: center;
        }
        
        .commission-text h5 {
            color: var(--secondary);
            margin-bottom: 0.5rem;
        }
        
        .footer {
            text-align: center;
            margin-top: 3rem;
            color: #6c757d;
            font-size: 0.9rem;
        }
        
        @media (max-width: 768px) {
            .cost-summary-container {
                padding: 1.5rem;
            }
            
            .back-button {
                position: relative;
                top: 0;
                left: 0;
                margin-bottom: 20px;
                display: inline-flex;
            }
            
            .summary-header {
                padding-top: 0;
            }
            
            .summary-header h2 {
                font-size: 2rem;
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
                            <li class="nav-item active"><a class="nav-link" href="HbrowseProfessionals.php">Browse Professionals</a></li>
                            <li class="nav-item active"><a class="nav-link" href="feedbackHomeOwner.php">Feedback</a></li>
                        </ul>
                    </div>
                </nav>
            </div>
        </nav>
    </div>

    <div class="cost-summary-container">
        <!-- Go Back Button -->
        <a href="homeOwnerPage.php" class="back-button">
            <i class="fas fa-arrow-left"></i> Go Back
        </a>
        
        <div class="summary-header">
            <h2>Project Cost Summary</h2>
            <div class="project-name"><?php echo htmlspecialchars($projectName); ?></div>
        </div>
        
        <div class="cost-grid">
            <div class="cost-card">
                <div class="card-title">
                    <i class="fas fa-wallet"></i>
                    <span>Project Budget</span>
                </div>
                <div class="card-value">$<?php echo number_format($budget, 2); ?></div>
                <div class="card-description">Total allocated budget for the project</div>
            </div>
            
            <div class="cost-card">
                <div class="card-title">
                    <i class="fas fa-file-invoice-dollar"></i>
                    <span>Final Cost</span>
                </div>
                <div class="card-value">$<?php echo number_format($exactCost, 2); ?></div>
                <div class="card-description">Actual cost of the completed project</div>
            </div>
            
            <div class="cost-card">
                <div class="card-title">
                    <i class="fas fa-hard-hat"></i>
                    <span>Contractor Price</span>
                </div>
                <div class="card-value">$<?php echo number_format($contractorPrice, 2); ?></div>
                <div class="card-description">Price requested by the contractor</div>
            </div>
            
            <div class="cost-card">
                <div class="card-title">
                    <i class="fas fa-handshake"></i>
                    <span>Website Commission</span>
                </div>
                <div class="card-value">$<?php echo number_format($websiteCommission, 2); ?></div>
                <div class="card-description">Commission fee for the platform</div>
            </div>
        </div>
        
        <div class="budget-status-section">
            <div class="budget-status-title">Budget Status</div>
            <?php
            $budgetDifference = $budget - $exactCost;
            $budgetStatus = $budgetDifference >= 0 ? "Under Budget" : "Over Budget";
            $colorClass = $budgetDifference >= 0 ? "under-budget" : "over-budget";
            $icon = $budgetDifference >= 0 ? "fa-check-circle" : "fa-exclamation-circle";
            ?>
            
            <div class="budget-difference <?php echo $colorClass; ?>">
                <i class="fas <?php echo $icon; ?>"></i>
                $<?php echo number_format(abs($budgetDifference), 2); ?> 
                <?php echo $budgetStatus; ?>
            </div>
            
            <div class="status-explanation">
                <?php if($budgetDifference >= 0): ?>
                    Great news! Your project came in under budget by $<?php echo number_format($budgetDifference, 2); ?>.
                <?php else: ?>
                    Your project exceeded the budget by $<?php echo number_format(abs($budgetDifference), 2); ?>.
                <?php endif; ?>
            </div>
        </div>
        
        <div class="progress-section">
            <div class="progress-title">
                <span>Budget Utilization</span>
                <span><?php echo number_format(($exactCost / $budget) * 100, 1); ?>%</span>
            </div>
            <div class="progress-bar-container">
                <div class="progress-bar" style="width: <?php echo min(($exactCost / $budget) * 100, 100); ?>%"></div>
            </div>
            <div class="progress-labels">
                <span>$0</span>
                <span>Budget: $<?php echo number_format($budget, 2); ?></span>
            </div>
        </div>
        
        <div class="commission-highlight">
            <div class="commission-icon">
                <i class="fas fa-info-circle"></i>
            </div>
            <div class="commission-text">
                <h5>About Project Costs</h5>
                <p class="mb-0">The final project cost includes contractor fees, materials, professional services, and our platform commission. Detailed breakdowns you should contact the contractor.</p>
            </div>
        </div>
        
        <div class="text-center mt-4">
            <a href="homeOwnerPage.php" class="btn btn-primary btn-lg">
                <i class="fas fa-tachometer-alt me-2"></i> Return to Dashboard
            </a>
        </div>
    </div>
    
    <div class="footer container">
        <p>BuildEase Project Management System &copy; <?php echo date('Y'); ?></p>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Animation for progress bars
        document.addEventListener('DOMContentLoaded', function() {
            const progressBars = document.querySelectorAll('.progress-bar');
            progressBars.forEach(bar => {
                const width = bar.style.width;
                bar.style.width = '0';
                setTimeout(() => {
                    bar.style.width = width;
                }, 500);
            });
        });
    </script>
</body>
</html>