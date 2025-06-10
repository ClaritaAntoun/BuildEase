

<!-- Clarita Antoun -->
<?php
 session_start();
 include 'conx.php';
 if (!isset($_SESSION['homeOwner_identity']['id'])) {
    header("Location: logInPage.php");
    exit();
  }
  $projectId=$_GET['projectID'];

  $sql1 = "SELECT c.id AS id, c.fullName, c.email, c.cvID 
  FROM Contractor c 
  WHERE c.status = 'accepted'";
  $result1= $conn->query($sql1);
  
$sql2="select * from project,address where project.addressId=address.addressId and projectId='$projectId'";
$result2 = $conn->query($sql2);
$project = $result2->fetch_assoc();

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
    <!-- Add this to your Google Fonts link -->
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
 .form-label{
    color: white;
    font-weight: bold;
 }
    .card {
      background-color: #1a1a1a;
      border: 1px solid #333;
      box-shadow: 0 4px 12px rgba(245, 166, 35, 0.1);
    }
    .form-control, .form-select {
      background-color: #1a1a1a;
      border: 1px solid #333;
      color: black;
    }
    .form-control:focus, .form-select:focus {
      background-color: transparent;
      border-color: brown;
      box-shadow: 0 0 0 0.25rem rgba(245, 166, 35, 0.25);
      color: #fff;
    }
    .btn-primary {
      background-color: brown;
      border-color: brown;
      color: #000;
      font-weight: bold;
    }
    .btn-primary:hover {
      background-color:brown;
      border-color: brown;
    }
    .btn-success {
      background-color: brown;
      border-color: brown;
      color: #000;
    }
    .btn-success:hover {
      background-color: brown;
      border-color:hsl(33, 100.00%, 50.00%);
    }
    .modal-content {
      background-color: white;
      border: 1px solid #333;
    }
    pre {
      background-color: #000 !important;
      color: brown !important;
      border: 1px solid #333;
    }
    .contractor-card {
      transition: transform 0.2s, border-color 0.2s;
      cursor: pointer;
    }
    .contractor-card:hover {
      transform: translateY(-5px);
      border-color: brown !important;
    }
    .selected-contractor {
      border-color: brown !important;
      box-shadow: 0 0 15px rgba(245, 166, 35, 0.3);
    }
    h2 {
      color: brown !important;
      border-bottom: 2px solid brown;
      padding-bottom: 0.5rem;
    }
    body {
        font-family: 'Playfair Display', serif;
        background:rgba(255, 255, 255, 0.8)1a;
        color: #495057;
    }

    /* Header Top Bar */
    .header-area {
        background: #495057; /* Deep navy blue */
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
        color: #1a1a1a;
        letter-spacing: 0.5px;
    }
    .logo span {
        color:brown;
        font-weight: 400;
    }

    .navbar-nav .nav-link {
        color: #1a1a1a !important;
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
        color: #1a1a1a !important;
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
        color: #1a1a1a !important;
        padding: 14px 30px;
        border-radius: 30px;
        font-weight: 600;
        transition: 0.3s;
        letter-spacing: 0.5px;
        border: 2px solid transparent;
    }
    .appoint-btn a:hover {
        background: #1a1a1a;
        color: #FFFFFF !important;
        border-color:yellow;
        transform: translateY(-2px);
    }
    h5{
      text-decoration: underline;
            color: brown;
            font-weight: bolder;
        font-family: 'Lucida Sans', 'Lucida Sans Regular', 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif;
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
 
/* Add this to your existing CSS */
#cvModal .modal-content {
  background-color: #1a1a1a;
  color: #333;
  border: 1px solid #ddd;
}

#cvModal .modal-header {
  border-bottom: 1px solid brown;
}

#cvModal .modal-title {
  color: white;
}

#cvModal .btn-close {
  filter: none; /* Remove any existing inversion */
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
      </nav>
    </div>

<br>
    

<div class="container mt-5">
    <h2 class="mb-4">🔁 Reassign Contractor</h2>

    <div class="card p-4">
        <form id="reassignForm" action="reassign_contractor_process.php" method="POST">
            <input type="hidden" name="projectID" value="<?= htmlspecialchars($project['projectID']) ?>">

            <div class="mb-3">
                <label class="form-label">Project Name</label>
                <input type="text" class="form-control" value="<?= htmlspecialchars($project['name']) ?>" readonly>
            </div>

            <div class="mb-3">
                <label class="form-label">Budget ($)</label>
                <input type="number" class="form-control" value="<?= htmlspecialchars($project['budget']) ?>" readonly>
            </div>

            <div class="mb-3">
                <label class="form-label">Start Date</label>
                <input type="date" class="form-control" value="<?= htmlspecialchars($project['startDate']) ?>" readonly>
            </div>

            <div class="mb-3">
                <label class="form-label">Address</label>
                <input type="text" class="form-control" value="<?= htmlspecialchars($project['street'])."/".htmlspecialchars($project['city'])."/".htmlspecialchars($project['state'])."/".htmlspecialchars($project['postalCode'])  ?>" readonly>
            </div>

            <!-- Contractor Dropdown -->
            <div class="mb-4">
                <label class="form-label" style="color: brown;">Select New Contractor</label>

                <div class="input-group">
                    <select class="form-select" id="contractorSelect" name="contractorID" style="background-color: #1a1a1a; border: 1px solid #333; color: #fff;" required>
                        <option value="" selected disabled>-- Select a Contractor --</option>
                        <?php while($row1 = $result1->fetch_assoc()) { ?>
                            <option value="<?= $row1['id'] ?>" 
                                    data-name="<?= htmlspecialchars($row1['fullName']) ?>"
                                    data-email="<?= htmlspecialchars($row1['email']) ?>">
                                <?= htmlspecialchars($row1['fullName']) ?> (<?= htmlspecialchars($row1['email']) ?>)
                            </option>
                        <?php } ?>
                    </select>

                    <button type="button" class="btn btn-primary" id="viewCvBtn"
                            style="background-color: brown; border-color: brown; color: #000;"
                            data-bs-toggle="modal" data-bs-target="#cvModal">
                        View CV
                    </button>
                </div>
            </div>

            <button type="submit" class="btn btn-warning w-100 py-2">Reassign Contractor</button>
        </form>
    </div>
</div>

<!-- CV Modal -->
<div class="modal fade" id="cvModal" tabindex="-1" aria-labelledby="cvModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content" style="background-color: #1a1a1a; color: white;">
      <div class="modal-header" style="border-bottom: 1px solid #ddd;">
        <h1 class="modal-title fs-4" style="color: brown;">Curriculum Vitae</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="cvDetails" style="white-space: pre-wrap; padding: 20px;">
        <!-- CV content will appear here -->
      </div>
    </div>
  </div>
</div>
<!-- Bootstrap JS (Bootstrap 5.x version) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

<script>
document.getElementById('contractorSelect').onchange = function () {
    const selectedOption = this.options[this.selectedIndex];
    const contractorId = selectedOption.value;

    const viewBtn = document.getElementById('viewCvBtn');
    viewBtn.disabled = false;
    viewBtn.setAttribute('data-contractor-id', contractorId);
};

document.getElementById('viewCvBtn').onclick = function () {
    const contractorId = this.getAttribute('data-contractor-id');
    const cvDetails = document.getElementById('cvDetails');
    cvDetails.textContent = 'Loading...';

    fetch('getCvDetails_c.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'contractorId=' + encodeURIComponent(contractorId)
    })
    .then(response => response.text())
    .then(data => {
        cvDetails.innerHTML = data;
        const modal = new bootstrap.Modal(document.getElementById('cvModal'));
        modal.show();
    })
    .catch(error => {
        cvDetails.textContent = 'Error loading CV.';
        console.error('Fetch error:', error);
    });
};
</script>

 