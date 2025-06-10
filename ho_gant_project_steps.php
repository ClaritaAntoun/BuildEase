
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
$stmt->bind_param('i', $projectID); // 'i' for integer
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$stmt->close();

?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Project Gantt Chart</title>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>

  <link rel="stylesheet" href="https://unpkg.com/frappe-gantt@0.6.0/dist/frappe-gantt.css">
  <link rel="stylesheet" href="css/bootstrap.min.css">
  <link rel="stylesheet" href="css/custom.css">
  <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" crossorigin="anonymous"/>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<style>
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
        color: #495057;
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


    .gantt-legend {
        margin: 20px 0;
        padding: 15px;
        background: #f8f9fa;
        border-radius: 8px;
        border: 1px solid #dee2e6;
        width: fit-content;
    }

    .legend-items {
        display: flex;
        gap: 15px;
        margin-top: 10px;
    }

    .legend-item {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .color-box {
        width: 15px;
        height: 15px;
        display: inline-block;
    }

    #gantt {
        width: 100%;
        height: 600px;
        background-color: #f0f0f0;
        margin-top: 20px;
    }

    /* Updated Gantt Container */
    #gantt {
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 8px 30px rgba(0,0,0,0.12);
        padding: 25px;
        margin: 30px 0;
        overflow-x: auto;
    }

    /* Improved task bar styles */
    .gantt .bar {
        rx: 4px; /* Rounded corners */
        filter: drop-shadow(2px 2px 4px rgba(0,0,0,0.1));
        transition: background-color 0.3s ease;
    }

    /* Task Bar Status Styling */
    .gantt .bar.completed {
        background-color: #4CAF50; /* Green for completed */
    }

    .gantt .bar.ongoing {
        background-color:brown; /* Orange for ongoing */
    }

    .gantt .bar.pending {
        background-color: #9E9E9E; /* Gray for pending */
    }

    /* Parent Task Styling */
    .gantt .bar-group[data-task-level="0"] .bar {
        height: 28px !important;
        font-weight: 600;
    }

    /* Child Task Styling */
    .gantt .bar-group[data-task-level="1"] .bar {
        height: 22px !important;
        margin-left: 20px;
    }

    /* Timeline Header */
    .gantt .grid-header {
        font-family: 'Poppins', sans-serif;
        font-weight: 500;
        color: #2c3e50;
    }

    /* Improved Legend */
    .gantt-legend {
        background: #ffffff;
        border: 1px solid #e0e0e0;
        padding: 15px 25px;
        border-radius: 8px;
        margin: 20px 0;
    }

    .legend-items {
        display: flex;
        gap: 25px;
        margin-top: 15px;
    }

    .legend-item {
        display: flex;
        align-items: center;
        gap: 10px;
        font-family: 'Poppins', sans-serif;
    }

    .color-box {
        width: 20px;
        height: 20px;
        border-radius: 4px;
    }


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
/* Add these styles */
.gantt-tooltip {
    background: #fff;
    padding: 15px;
    border-radius: 6px;
    box-shadow: 0 3px 15px rgba(0,0,0,0.2);
    font-family: 'Poppins', sans-serif;
}

.gantt-tooltip h5 {
    color: brown;
    margin-bottom: 8px;
    font-size: 16px;
}

.gantt .bar-label {
    font-size: 12px !important;
    font-weight: 500 !important;
    dominant-baseline: middle !important;
    fill: black !important;
}

.gantt .bar-group {
    margin-bottom: 8px !important;
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
              <li class="nav-item active"><a class="nav-link" href="homeOwnerPage.php">Go To dashboard</a></li>
                <li class="nav-item active"><a class="nav-link" href="index.php">Home</a></li>
                

                <li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
        <?php echo isset($_SESSION['homeOwner_identity']['fullName']) ? $_SESSION['homeOwner_identity']['fullName'] : 'Profile'; ?>
    </a>
    <ul class="dropdown-menu" aria-labelledby="userDropdown">
        <li><a class="dropdown-item" href="profile.php">Profile</a></li>
        <li><a class="dropdown-item" href="logOut.php">Sign Out</a></li>
    </ul>
</li>



               <li class="nav-item active"><a class="nav-link" href="HbrowseProfessionals.php">Browse professionals</a></li>
               <li class="nav-item active"><a class="nav-link" href="feedbackHomeOwner.php">Give your feedback</a></li>
                <li class="nav-item dropdown">
                  <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown">Your projects</a>
                  <div class="dropdown-menu">
                    <a class="dropdown-item" href="#">Complete Projects</a>
                    <a class="dropdown-item" href="#">Uncomplete Projects</a>
                  </div>
                </li>
               
              </ul>
            </div>
          </nav>
        </div>
      </nav>
    </div>



    <h2><?php echo htmlspecialchars($row['name']) ?> Gantt Chart</h2>

<div class="gantt-legend">
  <strong>Project Status Legend:</strong>
  <div class="legend-items">
      <div class="legend-item">
          <span class="color-box" style="background-color: #4CAF50"></span>
          Completed (100%)
      </div>
      <div class="legend-item">
          <span class="color-box" style="background-color: #FF9800"></span>
          Ongoing (50%)
      </div>
      <div class="legend-item">
          <span class="color-box" style="background-color: #9E9E9E"></span>
          Pending (0%)
      </div>
  </div>
</div>

<div id="gantt"></div>

<script src="https://unpkg.com/frappe-gantt@0.6.0/dist/frappe-gantt.min.js"></script>
<script>
fetch("ho_gantDataGetting.php?projectID=<?php echo $projectID ?>")
  .then(response => response.json())
  .then(data => {
    const tasks = data.map((item, index) => ({
      id: `task-${index}`,
      name: `${item.task}`, // Use task name
      start: item.start,
      end: item.end,
      progress: item.status === "completed" ? 100 : 
               item.status === "ongoing" ? 50 : 0,
      custom_class: `status-${item.status}`,
      details: `Assigned to: ${item.assignedTo}` // Detail for tooltip
    }));

    const startDate = tasks.length > 0 ? new Date(tasks[0].start) : new Date();
    const endDate = tasks.length > 0 ? new Date(tasks[tasks.length-1].end) : new Date();

    new Gantt("#gantt", tasks, {
      view_mode: "Month",
      date_format: 'YYYY-MM-DD',
      custom_popup_html: function(task) {
        return `
          <div class="gantt-tooltip">
            <h5>${task.name}</h5>
            <p>${task.details}</p>
            <p>Start: ${moment(task.start).format('MMM D, YYYY')}</p>
            <p>End: ${moment(task.end).format('MMM D, YYYY')}</p>
            <p>Status: ${task.custom_class.replace('status-', '')}</p>
          </div>
        `;
      }
    });
  })
  .catch(error => console.error("Fetch Error:", error));
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

 