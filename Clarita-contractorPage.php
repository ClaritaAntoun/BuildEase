<?php
session_start(); 
include 'conx.php';

if (!isset($_SESSION['contractor_identity']['id'])) {
    header("Location: logInPage.php");
    exit();
}

$contId = $_SESSION['contractor_identity']['id'];

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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">


<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
      /* Custom Orange & Black Theme */
.table-custom {
    background-color: #B71C1C;
    border: 2px solid #B71C1C;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.3);
}

/* Modern Muted Red/Brown & White Theme */
.table-custom {
    background-color: #ffffff;
    border: 1px solid blanchedalmond; /* Muted brown */
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 4px 12px rgba(109, 76, 65, 0.1);
    font-family: 'Segoe UI', sans-serif;
}

.table-custom thead {
    background: linear-gradient(145deg, #6D4C41, #8D6E63); /* Muted brown gradient */
    color: white;
    border-bottom: 2px solid #5D4037;
}

.table-custom thead th {
    color: #ffffff;
    font-weight: 600;
    letter-spacing: 0.03em;
    padding: 16px 24px;
    border-right: 1px solid rgba(255, 255, 255, 0.1);
    text-transform: uppercase;
    font-size: 0.9em;
}

.table-custom tbody td {
    color: #4E342E; /* Dark brown text */
    background-color: #ffffff;
    border-color: #F5F5F5;
    padding: 14px 24px;
    vertical-align: middle;
    transition: background-color 0.2s ease;
}

.table-custom tbody tr:nth-child(even) td {
    background-color: #FFF3E0; /* Very light brown */
}

.table-custom tbody tr:hover td {
    background-color: #FFE0B2; /* Light brown hover */
}

.btn-custom-accept {
    background: linear-gradient(135deg, #6D4C41, #8D6E63);
    border: none;
    color: white;
    font-weight: 500;
    min-width: 90px;
    padding: 8px 16px;
    border-radius: 6px;
    transition: all 0.2s ease;
}

.btn-custom-accept:hover {
    background: linear-gradient(135deg, #8D6E63, #6D4C41);
    transform: translateY(-1px);
    box-shadow: 0 3px 8px rgba(109, 76, 65, 0.2);
}

.btn-custom-reject {
    background: transparent;
    border: 1px solid #6D4C41;
    color: #6D4C41;
    font-weight: 500;
    min-width: 90px;
    padding: 8px 16px;
    border-radius: 6px;
    transition: all 0.2s ease;
}

.btn-custom-reject:hover {
    background: #6D4C41;
    color: white;
    border-color: #6D4C41;
}

.no-projects {
    background-color: #FFF8F0;
    color: #6D4C41 !important;
    border-top: 2px solid #6D4C41;
    font-style: italic;
    padding: 24px;
}

.table-custom thead {
    background-color: #1a1a1a;
    color: white;
    border-bottom: 3px solid #B71C1C;
}

.table-custom thead th {
    color: #1a1a1a;
    font-weight: 600;
    letter-spacing: 0.05em;
    border-right: 1px solid #333;
}

.table-custom tbody td {
    color: #1a1a1a;
    background-color:grey;
    border-color: #333;
    vertical-align: middle;
}

.table-custom tbody tr:nth-child(even) td {
    background-color: wheat;
}

.table-custom tbody tr:hover td {
    background-color: #FFCDD2;
    transition: background-color 0.3s ease;
}

.btn-custom-accept {
    background-color: brown;
    border-color: brown;
    color: #000;
    font-weight: 500;
    min-width: 80px;
}

.btn-custom-accept:hover {
    background-color: wheat;
    border-color: black;
    color: #000;
}

.btn-custom-reject {
    background-color: transparent;
    border-color: brown;
    color:brown;
    font-weight: 500;
    min-width: 80px;
}

.btn-custom-reject:hover {
    background-color: wheat;
    color: #000;
}

.no-projects {
    background-color: #1a1a1a;
    color:#f5a623 !important;
    border-top: 2px solid brown;
}
body {
    font-family: 'Playfair Display', serif;
    background: white; /* Light red background */
    color: #3e0f0f;
}

/* Header Top Bar */
.header-area {
    background: #B71C1C; /* Dark red */
    padding: 12px 0;
    color: #FFCDD2;
    font-family: 'Poppins', sans-serif;
    border-bottom: 1px solid rgba(255, 0, 0, 0.1);
}

.header-left a, .header-right ul li a {
    color: #FFCDD2;
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
    border-bottom: 2px solid #f8bbd0;
}

.logo {
    font-family: 'Playfair Display', serif;
    font-size: 36px;
    font-weight: 700;
    color: #B71C1C;
    letter-spacing: 0.5px;
}
.logo span {
    color: #FF5252;
    font-weight: 400;
}

.navbar-nav .nav-link {
    color: #B71C1C !important;
    font-weight: 500;
    padding: 15px 20px !important;
    transition: 0.3s;
    font-size: 16px;
    position: relative;
}

.navbar-nav .nav-link:hover, 
.navbar-nav .nav-item.active .nav-link {
    color: #FF5252;
}

.navbar-nav .nav-link:hover::after {
    content: '';
    position: absolute;
    bottom: -2px;
    left: 0;
    right: 0;
    height: 2px;
    background: #FF5252;
}

/* Dropdown Menu */
.dropdown-menu {
    background: #FFFFFF;
    border: 1px solid rgba(183, 28, 28, 0.1);
    border-radius: 4px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    margin-top: 10px !important;
}
.dropdown-menu .dropdown-item {
    color: #B71C1C !important;
    padding: 12px 25px;
    font-size: 15px;
    transition: 0.3s;
}
.dropdown-menu .dropdown-item:hover {
    background: #FFEBEE;
    color: #D32F2F !important;
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

/* Build Project Button */
.build-project-btn {
    background: linear-gradient(135deg, #e53935, #d32f2f);
    color: white;
    font-size: 22px;
    font-weight: bold;
    padding: 15px 35px;
    border-radius: 8px;
    box-shadow: 0px 5px 15px rgba(211, 47, 47, 0.4);
    transition: all 0.3s ease;
    text-transform: uppercase;
    display: inline-block;
}

.build-project-btn:hover {
    background: linear-gradient(135deg, #d32f2f, #e53935);
    box-shadow: 0px 8px 20px rgba(211, 47, 47, 0.5);
    transform: scale(1.05);
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
                <li class="nav-item active"><a class="nav-link" href="index.php">Home</a></li>
                

                <li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
        <?php echo isset($_SESSION['contractor_identity']['fullName']) ? $_SESSION['contractor_identity']['fullName'] : 'Profile'; ?>
    </a>
    <ul class="dropdown-menu" aria-labelledby="userDropdown">
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
     
            </div>
          </nav>
        </div>
      </nav>
    </div>



 













<br><br><br><br><br>

<?php $counter=0;?>
<div class="container mt-5">
  <h2 class="text-orange mb-4" style="color: balck;">📋 Assigned Projects</h2>
  <table class="table table-custom">
    <thead class="table-custom thead">
      <tr>
      <th>#</th>
        <th>Name</th>
        <th>Home Owner</th>
        <th>Budget</th>
        <th>Start Date</th>
        <th>Address</th>
        <th>Status</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php
      
     $sql=" SELECT project.*, address.*, HomeOwner.fullName AS homeOwnerName
FROM project
JOIN address ON project.addressID = address.addressID
JOIN Creates ON project.projectID = Creates.projectID
JOIN HomeOwner ON Creates.homeOwnerID = HomeOwner.ID
WHERE project.contractorID = ? AND project.status = 'pending' AND HomeOwner.status='accepted' ";


      $stmt = $conn->prepare($sql);
      $stmt->bind_param("i", $contId);
      $stmt->execute();
      $result = $stmt->get_result();

      if ($result->num_rows > 0):
        while ($row = $result->fetch_assoc()): ?>
          <tr>
            <td><?php  echo $counter+=1;?></td>
            <td><?= htmlspecialchars($row['name']) ?></td>
            <td><?= htmlspecialchars($row['homeOwnerName']) ?></td>
            <td>$<?= htmlspecialchars(number_format($row['budget'], 2)) ?></td>
            <td><?= htmlspecialchars(date('M d, Y', strtotime($row['startDate']))) ?></td>
            <td><?= htmlspecialchars($row['street'])."/".htmlspecialchars($row['city'])."/".htmlspecialchars($row['state'])."/".htmlspecialchars($row['postalCode']) ?></td>
            <td><span class="badge bg-orange"><?= htmlspecialchars(ucfirst($row['status'])) ?></span></td>
            <td>
              <form action="handle_project_response.php" method="POST" >
                <input type="hidden" name="projectID" value="<?= $row['projectID'] ?>">
                <button type="submit" name="action" value="accept" class="btn btn-custom-accept me-2">Accept</button>
                <button type="submit" name="action" value="reject" class="btn btn-custom-reject">Reject</button>
              </form>
            </td>
          </tr>
        <?php endwhile;
      else: ?>
        <tr>
          <td colspan="5" class="no-projects py-4 text-center">
            <i class="fas fa-exclamation-circle me-2"></i>
            There are no projects assigned to this contractor by Homeowners
          </td>
        </tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  </body>
</html>

 