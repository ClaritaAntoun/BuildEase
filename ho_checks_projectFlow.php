<!-- Clarita Antoun -->
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
$sql = "SELECT name FROM project WHERE projectID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $projectID);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$stmt->close();


$sql_check_project = "SELECT * FROM work_in WHERE projectID = '$projectID'";
$result_check = $conn->query($sql_check_project);

$project_exists = $result_check->num_rows > 0; // Returns true if project exists, false otherwise
if (!$project_exists) {
    $_SESSION['alert_message'] = 'No data entered by the assigned contractor';
    header("Location: homeOwnerPage.php");
    exit();
}

 ?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>BuildEase - Project Details</title>
    <!-- Gantt CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/frappe-gantt/0.5.0/frappe-gantt.css" />
 <link rel="stylesheet" href="https://unpkg.com/frappe-gantt@0.6.0/dist/frappe-gantt.css">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/custom.css">
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" crossorigin="anonymous"/>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        .btn-highlight-cost {
    background-color: #28a745; /* green */
    border: 2px solid #218838;
    color: white;
    font-weight: bold;
    font-size: 18px;
    box-shadow: 0 0 10px rgba(40, 167, 69, 0.6);
    transition: transform 0.2s ease-in-out;
}

.btn-highlight-cost:hover {
    transform: scale(1.05);
}

    :root {
        --primary-color: #800020; /* Burgundy */
        --secondary-color: #1A2A3A; /* Navy blue */
        --accent-color: #D4AF37; /* Gold */
        --light-bg: #f8f9fa;
        --dark-text: #1A2A3A;
    }

    body {
        font-family: 'Poppins', sans-serif;
        background-color: #f8f9fa;
        color: #495057;
        line-height: 1.6;
    }

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

    /* Main Content Container */
    .main-container {
        background: white;
        border-radius: 8px;
        box-shadow: 0 0 20px rgba(0,0,0,0.05);
        padding: 30px;
        margin-top: 30px;
        margin-bottom: 50px;
    }

    /* Project Header */
    .project-header {
        border-bottom: 2px solid var(--primary-color);
        padding-bottom: 20px;
        margin-bottom: 30px;
    }

    .project-title {
        font-family: 'Playfair Display', serif;
        color: var(--primary-color);
        font-size: 2.2rem;
        margin-bottom: 10px;
    }

    /* Contractor Info Box */
    .contractor-info-box {
        background: var(--secondary-color);
        color: white;
        border-left: 4px solid var(--primary-color);
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 30px;
    }

    .contractor-info-box h4 {
        color: var(--accent-color);
        margin-bottom: 15px;
    }

    /* Gantt Chart Container */
    .gantt-container {
        width: 100%;
        overflow-x: auto;
        margin: 30px 0;
    }

    #gantt {
        width: 100%;
        min-width: 800px;
        height: 600px;
        background: white;
        border-radius: 8px;
        box-shadow: 0 0 15px rgba(0,0,0,0.1);
    }

    /* Legend Styles */
    .gantt-legend {
        background: white;
        border: 1px solid #e0e0e0;
        padding: 15px;
        border-radius: 8px;
        margin: 20px 0;
        width: 100%;
    }

    .legend-items {
        display: flex;
        gap: 25px;
        flex-wrap: wrap;
    }

    .legend-item {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .color-box {
        width: 20px;
        height: 20px;
        border-radius: 4px;
    }

    /* Button Styles */
    .btn-primary-custom {
        background: var(--primary-color);
        color: white;
        border: none;
        padding: 10px 25px;
        border-radius: 6px;
        transition: all 0.3s;
    }

    .btn-primary-custom:hover {
        background: #600018;
        color: white;
        transform: translateY(-2px);
    }

    .btn-ai {
        background: linear-gradient(135deg, #f5a623, #f54123);
        color: white;
        border: none;
        padding: 12px 30px;
        border-radius: 6px;
        font-weight: 600;
        transition: all 0.3s;
        box-shadow: 0 4px 15px rgba(245, 166, 35, 0.3);
    }

    .btn-ai:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(245, 166, 35, 0.4);
    }

    /* Responsive Adjustments */
    @media (max-width: 768px) {
        .project-title {
            font-size: 1.8rem;
        }
        
        .gantt-legend {
            width: 100%;
        }
        
        .legend-items {
            flex-direction: column;
            gap: 10px;
        }
        
        #gantt {
            height: 400px;
        }
    }

    /* Gantt Chart Bar Styles */
    .gantt .bar-wrapper.status-completed .bar {
        fill: #4CAF50 !important;
        stroke: #4CAF50 !important;
    }

    .gantt .bar-wrapper.status-ongoing .bar {
        fill: #FFA500 !important;
        stroke: #FFA500 !important;
    }

    .gantt .bar-wrapper.status-pending .bar {
        fill: #9E9E9E !important;
        stroke: #9E9E9E !important;
    }

    .gantt .bar-label {
        font-size: 12px !important;
        font-weight: 500 !important;
        dominant-baseline: middle !important;
        fill: black !important;
    }
      .shadow-hover {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .shadow-hover:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.12);
    }
    .step-section {
        background: rgba(255,255,255,0.9);
        backdrop-filter: blur(10px);
    }
    .rounded-1 {
        border-radius: 0.75rem;
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
   
    <div class="container main-container">
        <div class="project-header">
            <h1 class="project-title"><?php echo htmlspecialchars($row['name']) ?></h1>
            
            <div class="text-center mb-4">
                <a href="generate.php?projectID=<?php echo $projectID; ?>" class="btn btn-ai">
                    🚀 Generate Your Own Home Design with AI
                </a>
            </div>
        </div>

        <?php
        

        $sql2 = "SELECT c.fullName AS contractorName, c.email AS contractorEmail, c.phoneNumber AS contractorPhone
                FROM contractor c
                JOIN project p ON c.id = p.contractorID
                WHERE p.projectID = ?";

        $stmt2 = mysqli_prepare($conn, $sql2);
        mysqli_stmt_bind_param($stmt2, "i", $projectID);
        mysqli_stmt_execute($stmt2);
        $result2 = mysqli_stmt_get_result($stmt2);
        $row2 = mysqli_fetch_assoc($result2);

      $sql3 = "SELECT contractorPrice FROM project WHERE projectID = ?";
$stmt3 = $conn->prepare($sql3);
$stmt3->bind_param("i", $projectID); 
$stmt3->execute();
$result3 = $stmt3->get_result();

if ($result3->num_rows > 0) {
    $contractorPrice = $result3->fetch_assoc()['contractorPrice'];
}
$stmt3->close();

        ?>

    <div class="contractor-info-box">
    <h4><i class="fas fa-hard-hat"></i> Contractor Information</h4>
    <div class="row">
        <div class="col-md-4">
            <p><strong>Name:</strong> <?php echo htmlspecialchars($row2['contractorName']); ?></p>
        </div>
        <div class="col-md-4">
            <p><strong>Email:</strong> <?php echo htmlspecialchars($row2['contractorEmail']); ?></p>
        </div>
        <div class="col-md-4">
            <p><strong>Phone:</strong> <?php echo htmlspecialchars($row2['contractorPhone']); ?></p>
        </div>
     <div class="alert alert-warning p-3 text-center">
    <h5 class="mb-0">
        💰 <strong class="text-success" style="font-size: 24px;">
            <?php echo htmlspecialchars(number_format((float)$contractorPrice, 2)) . " USD"; ?>
        </strong>
    </h5>
    <small class="text-muted">This is the total amount the contractor is requesting for this project.</small>
</div>

    </div>
</div>



        <h3><i class="fas fa-chart-gantt"></i> Project Timeline</h3>
        <div class="gantt-container">
            <div id="gantt"></div>
        </div>
<?php
echo '<div class="container mt-4">';


$query = "SELECT sp.stepNumber, st.name, sp.path, sp.details
          FROM step_picture sp
          JOIN step st ON sp.stepNumber = st.stepNumber
          WHERE sp.projectID = ?
          AND sp.is_active = 1
          ORDER BY sp.stepNumber";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $projectID);
$stmt->execute();
$result = $stmt->get_result();

$currentStep = null;

while ($row = $result->fetch_assoc()) {
    $stepNumber = $row['stepNumber'];
    $stepName = $row['name'];
    $path = $row['path'];
    $details = $row['details'];

    if ($currentStep !== $stepNumber) {
        if ($currentStep !== null) {
            echo '</div></div>'; 
        }
        echo '<div class="step-section mb-5 p-4 shadow-sm rounded-3">';
echo '<h3 class="mb-4 border-bottom pb-2" style="color:brown;">Step ' . $stepNumber . ': ' . htmlspecialchars($stepName) . '</h3>';
        echo '<div class="row g-4">';
        $currentStep = $stepNumber;
    }


    echo '<div class="col-md-4 col-lg-3">
            <div class="card h-100 border-0 shadow-hover">
                <img src="' . htmlspecialchars($path) . '" 
                     class="card-img-top img-fluid rounded-1" 
                     alt="Step ' . $stepNumber . ' image" 
                     style="height: 200px; object-fit: cover;">
                <div class="card-body">
                    <p class="card-text text-muted small mb-0">' . htmlspecialchars($details) . '</p>
                </div>
            </div>
          </div>';
}


if ($currentStep !== null) {
    echo '</div></div>';
}

echo '</div>'; 
?>


        <div class="text-center mt-4">
            <a href="HO_updateProjectDetails.php?projectID=<?php echo $projectID; ?>" class="btn btn-primary-custom">
                <i class="fas fa-edit"></i> Update Project Details
            </a>
      
       
          <a href="projectCost.php?projectID=<?php echo $projectID; ?>" class="btn btn-highlight-cost">
    <i class="fas fa-coins"></i> Check Project Cost
</a>


        </div>
    </div>
<script src="https://unpkg.com/frappe-gantt@0.6.0/dist/frappe-gantt.min.js"></script>
<script>
fetch("ho_gantDataGetting.php?projectID=<?php echo $projectID ?>")
  .then(res => res.json())
  .then(data => {
    const tasks = data.map((item, i) => ({
      id: `task-${i}`,
      name: item.task,
      start: item.start,
      end: item.end,
      progress: item.status === "completed" ? 100 : item.status === "ongoing" ? 50 : 0,
      custom_class: `status-${item.status}`,
      details: `Assigned to: ${item.assignedTo}`
    }));

    new Gantt("#gantt", tasks, {
      view_mode: "Month",
      date_format: "YYYY-MM-DD",
      custom_popup_html: task => `
        <div class="gantt-tooltip">
          <h5>${task.name}</h5>
          <p>${task.details}</p>
          <p>Start: ${moment(task.start).format('MMM D, YYYY')}</p>
          <p>End: ${moment(task.end).format('MMM D, YYYY')}</p>
          <p>Status: ${task.custom_class.replace('status-', '')}</p>
        </div>
      `
    });
  })
  .catch(err => console.error("Fetch Error:", err));
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- Gantt JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/frappe-gantt/0.5.0/frappe-gantt.min.js"></script>

<!-- Moment.js for date formatting -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
  </body>
</html>

 