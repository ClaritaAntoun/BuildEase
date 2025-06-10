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
$stmt->bind_param('i', $projectID); // 'i' for integer
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$stmt->close();
///////////////////////////////////

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
    <title>BuildEase - Premium Home Construction</title>
    <link rel="stylesheet" href="https://unpkg.com/frappe-gantt@0.6.0/dist/frappe-gantt.css">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/custom.css">
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" crossorigin="anonymous"/>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Add this to your Google Fonts link -->
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
<style>



.text-success { color: green !important; }
.text-danger { color: red !important; }


.update-btn {
  padding: 8px 16px;
  background-color: #007bff;
  color: white;
  border: none;
  border-radius: 4px;
  font-size: 14px;
  cursor: pointer;
}
.update-btn:hover {
  background-color: #0056b3;
}
/* Section Headers */
h2, h3 {
    font-family: 'Playfair Display', serif;
    color:brown; 
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
    background:brown;
}

/* Empty State */
.text-danger {
    color: #b87d7d !important; /* Dusty rose */
    font-style: italic;
}
    body {
        font-family: 'Playfair Display', serif;
        background: #f8f9fa;
        color:black;
    }

    /* Header Top Bar */
    .header-area {
        background: #1A2A3A; /* Deep navy blue */
        padding: 12px 0;
        color:brown;
        font-family: 'Poppins', sans-serif;
        border-bottom: 1px solid rgba(212, 175, 55, 0.1);
        height: 50px;
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


/* === Table Design === */
.table {
    background: #ffffff;
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    overflow: hidden;
    width:100%;
}

.table thead {
    background: linear-gradient(45deg, #0a192f, #1a3658);
}

.table th {
    color: #ffffff;
    font-weight: 600;
    letter-spacing: 0.5px;
    border-bottom: 2px solid brown !important;
}

.table td {
    vertical-align: middle;
    border-color: #f0f0f0 !important;
}

.table tr:nth-child(even) {
    background: #f9f9f9;
}

.table-hover tbody tr:hover {
    background-color: #fff5f5;
}

/* === Buttons === */
.check-status-button {
    background: linear-gradient(135deg,brown,brown);
    color: #ffffff;
    padding: 14px 36px;
    border-radius: 8px;
    font-weight: 600;
    position: fixed;
    bottom: 30px;
    right: 30px;
    transition: all 0.3s ease;
}

.check-status-button:hover {
    background: linear-gradient(135deg, #0a192f, #1a3658);
    transform: translateY(-2px);
}

.btn-warning {
    background: #0a192f;
    color: #ffffff !important;
    border-radius: 6px;
    padding: 10px 25px;
    font-weight: 500;
}

/* === Contractor Info === */
.contractor-info {
    background: #0a192f;
    color: #ffffff;
    border-left: 4px solid brown;
    border-radius: 8px;
    padding: 20px;
    margin: 30px 0;
    position: relative;
    overflow: hidden;
}

.contractor-info::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 4px;
    background: brown;
}

/* === Form Elements === */
select {
    border: 1px solid #e0e0e0 !important;
    border-radius: 6px !important;
    padding: 8px 16px !important;
    transition: all 0.3s ease;
}

select:focus {
    border-color: brown !important;
    box-shadow: 0 0 0 3px rgba(128, 0, 32, 0.1) !important;
}

button[type="submit"] {
    background: brown;
    color: #ffffff !important;
    border: none;
    padding: 8px 20px;
    border-radius: 6px;
    transition: all 0.3s ease;
}

button[type="submit"]:hover {
    background:brown;
}

/* === Typography === */
h1, h2, h3 {
    font-family: 'Playfair Display', serif;
    color: #0a192f;
    margin: 30px 0;
    position: relative;
}

h1 {
    font-size: 2.8rem;
    text-align: center;
    padding-bottom: 20px;
}

h1::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 50%;
    transform: translateX(-50%);
    width: 80px;
    height: 3px;
    background:brown;
}

h2 {
    color: brown;
    font-size: 2rem;
    margin-top: 40px;
}

h3 {
    color: #1a3658;
    font-size: 1.6rem;
    margin-bottom: 25px;
}



.table td {
    color: black !important;
}
/* Add this to your styles */
select.form-control {
    min-width: 250px;
    max-width: 100%;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

/* For the professional select */
td:nth-child(7) .form-control {
    min-width: 200px;
}
.table select {
    width: 100%;
    height: 10%;
    padding: 4px;
    box-sizing: border-box;
    color: black; /* ensures text is black inside select */
}
.table td {
    max-width: none;
    white-space: normal;
}
/* Improved Table Layout */
.table-responsive {
    width: 100%;
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
    margin-bottom: 20px;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
}

.table {
    width: 100% !important;
    max-width: 100%;
    margin-bottom: 1rem;
    background-color: transparent;
    border-collapse: collapse;
    table-layout: fixed; /* This is key for equal column widths */
}

.table th,
.table td {
    padding: 12px 8px;
    vertical-align: middle;
    text-align: left;
    word-wrap: break-word;
    max-width: 200px; /* Adjust as needed */
}

/* Make specific columns narrower */
.table th:nth-child(1), 
.table td:nth-child(1) { /* Step # */
    width: 5%;
}

.table th:nth-child(2), 
.table td:nth-child(2) { /* Step Name */
    width: 15%;
}

.table th:nth-child(3), 
.table td:nth-child(3) { /* Dates */
    width: 12%;
}

.table th:nth-child(4), 
.table td:nth-child(4) { /* Professional */
    width: 15%;
}

.table th:nth-child(5), 
.table td:nth-child(5) { /* Professional Contact */
    width: 15%;
}

.table th:nth-child(6), 
.table td:nth-child(6) { /* update professional */
    width: 18%;
}

.table th:nth-child(7), 
.table td:nth-child(7) { /* Update Materials Used */
    width: 20%;
}

/* Responsive adjustments */
@media (max-width: 1200px) {
    .table th, 
    .table td {
        padding: 8px 5px;
        font-size: 14px;
    }
}

@media (max-width: 992px) {
    .table-responsive {
        display: block;
        width: 100%;
        overflow-x: auto;
    }
    
    .table {
        display: block;
        width: 100%;
    }
}
/* Materials list styles */
.table ul.list-unstyled {
    max-height: 200px;
    overflow-y: auto;
    padding: 0;
    margin: 0;
}

.table ul.list-unstyled li {
    margin-bottom: 8px;
    padding: 8px;
    background: #f8f9fa;
    border-radius: 4px;
}

.table ul.list-unstyled li .form-control {
    width: 100% !important;
    max-width: 100%;
}

/* Make select elements more compact */
.table select.form-control {
    min-width: 100px !important;
    padding: 4px 8px !important;
    height: auto !important;
}

/* Quantity input */
.table input[type="number"] {
    width: 60px !important;
    display: inline-block !important;
}
.container {
    max-width: 100%;
    padding: 0 15px;
    margin: 0 auto;
}

@media (min-width: 1200px) {
    .container {
        max-width: 95%;
    }
}
/* Table Layout Adjustments */
.table-responsive {
    width: 100%;
    margin-bottom: 20px;
}

.table {
    width: 100%;
    table-layout: auto; /* Changed from fixed to auto */
}

/* Minimize other columns */
.table th:nth-child(1), 
.table td:nth-child(1) { /* Step # */
    width: 1%;
    min-width: 40px;
}

.table th:nth-child(2), 
.table td:nth-child(2) { /* Step Name */
    width: 8%;
    min-width: 100px;
}

.table th:nth-child(3), 
.table td:nth-child(3) { /* Dates */
    width: 1%;
    min-width: 120px;
}

.table th:nth-child(4), 
.table td:nth-child(4) { /* Professional */
    width: 5%;
    min-width: 120px;
}

.table th:nth-child(5), 
.table td:nth-child(5) { /* Professional Contact */
    width: 18%;
    min-width: 150px;
}

.table th:nth-child(6), 
.table td:nth-child(6) { /* update professional */
    width: 6%;
    min-width: 150px;
}

/* Maximize materials column */
.table th:nth-child(7), 
.table td:nth-child(7) { /* Update Materials Used */
    width: 35%; /* Takes the remaining space */
    min-width: 300px;
}

/* Materials list styling */
.table ul.list-unstyled {
    max-height: 200px;
    overflow-y: auto;
    padding: 0;
    margin: 0;
}

.table ul.list-unstyled li {
    margin-bottom: 8px;
    padding: 8px;
    background: #f8f9fa;
    border-radius: 4px;
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    align-items: center;
}

/* Compact form elements */
.table ul.list-unstyled li select.form-control {
    flex: 1;
    min-width: 150px;
    max-width: 200px;
}

.table ul.list-unstyled li input[type="number"] {
    width: 60px;
}


@media (max-width: 1200px) {
    .table th, 
    .table td {
        padding: 8px 5px;
        font-size: 14px;
    }
    
    .table th:nth-child(7), 
    .table td:nth-child(7) {
        min-width: 250px;
    }
}

@media (max-width: 992px) {
    .table-responsive {
        overflow-x: auto;
    }
    
    .table th:nth-child(7), 
    .table td:nth-child(7) {
        min-width: 350px;
    }
}
/* Base Font */
body {
    font-family: 'Playfair Display', serif;
    background: #f8f9fa;
    color: black;
    font-size: 17px; /* Increased from default */
}

/* Header Top Bar */
.header-left a, .header-right ul li a {
    font-size: 17px; /* Increased */
}

/* Navigation Bar */
.navbar-nav .nav-link {
    font-size: 18px; /* Increased */
}

/* Headings */
h1 {
    font-size: 3.2rem; /* Increased */
}
h2 {
    font-size: 2.4rem; /* Increased */
}
h3 {
    font-size: 1.8rem; /* Increased */
}

/* Table Text */
.table th, .table td {
    font-size: 16px; /* Increased for better readability */
}

/* Buttons */
.update-btn,
.check-status-button,
.btn-warning,
button[type="submit"] {
    font-size: 16px; /* Increased */
}

/* Form Elements */
select,
.table select.form-control,
.table ul.list-unstyled li select.form-control,
.table input[type="number"] {
    font-size: 16px !important; /* Ensures consistent font size */
}

/* Dropdown items */
.dropdown-menu .dropdown-item {
    font-size: 16px; /* Increased */
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
              <li class="nav-item active"><a class="nav-link" href="homeOwnerPage.php">Go To Dashboard</a></li>
              
                

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
   

<?php

$sql = "
    -- Standard Steps
    SELECT 
        s.stepNumber AS stepNumber,
        s.name AS stepName,
        w.startDate,
        w.endDate,
        w.stepStatus,
        p.id AS professionalID,
        p.fullName AS professionalName,
        p.email AS professionalEmail,
        p.phoneNumber AS professionalPhone,
        FALSE AS is_custom
    FROM work_in w
    JOIN step s ON w.stepNumber = s.stepNumber
    LEFT JOIN professional p ON w.professionalID = p.id
    WHERE w.projectID = ? AND w.stepName IS NULL AND w.is_active = 1

    UNION ALL

    -- Custom Steps
    SELECT 
        w.stepNumber AS stepNumber,
        w.stepName AS stepName,
        w.startDate,
        w.endDate,
        w.stepStatus,
        p.id AS professionalID,
        p.fullName AS professionalName,
        p.email AS professionalEmail,
        p.phoneNumber AS professionalPhone,
        TRUE AS is_custom
    FROM work_in w
    LEFT JOIN professional p ON w.professionalID = p.id
    WHERE w.projectID = ? AND w.stepName IS NOT NULL AND w.is_active = 1

    ORDER BY stepNumber ASC
";

$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "ii", $projectID, $projectID);
mysqli_stmt_execute($stmt);
$result1 = mysqli_stmt_get_result($stmt);


?>


    <div class="container mt-4">
  <div class="mb-4">
     <a href="ho_checks_projectFlow.php?projectID=<?php echo $projectID; ?>"
       class="btn"
       style="background-color: black; color: brown; border: 1px solid brown; padding: 8px 16px; font-size: 16px; margin-top: 10px;">
        <i class="fas fa-arrow-left me-1" style="color: brown;"></i>
        Go back
    </a>
    <h2 style="color: #8B0000; font-size: 2rem; font-weight: bold;">
        <?php echo "<span style='color:black;'>Project name: </span>" . htmlspecialchars($row['name']) ; ?>

    </h2>
   
</div>

 <h1> Professionals & Materials</h1>
    <div class="table-responsive">
       

   


    <table class="table table-striped table-bordered">
        <thead class="thead-dark">
            <tr>
                <th>#</th>
                <th>Step Name</th>
                <th>Dates</th>
                <th>Professional</th>
                <th>Professional Contact</th>
                 <th>update professional</th>
                <th>Update Materials Used</th>
              
            
            </tr>
        </thead>
        <tbody>
            <?php 
            // Reset the result pointer to loop through steps again
            mysqli_data_seek($result1, 0);
            while ($row1 = mysqli_fetch_assoc($result1)): 
                
                $sql_materials = "SELECT ml.id, ml.title, ml.category, ml.price, wm.quantity,wm.id as work_materials_id
                                 FROM work_materials wm
                                 JOIN material_library ml ON wm.materialID = ml.id
                                 WHERE wm.projectID = ? AND wm.stepNumber = ? 
                                ";
                $stmt_materials = mysqli_prepare($conn, $sql_materials);
                mysqli_stmt_bind_param($stmt_materials, "ii", $projectID, $row1['stepNumber']);
                mysqli_stmt_execute($stmt_materials);
                $result_materials = mysqli_stmt_get_result($stmt_materials);
            ?>
                <tr>
                    <td><?php echo htmlspecialchars($row1['stepNumber']); ?></td>
                    <td><?php echo htmlspecialchars($row1['stepName']); ?></td>
                    <td>
                        <?php echo htmlspecialchars($row1['startDate']); ?><br>
                        to<br>
                        <?php echo htmlspecialchars($row1['endDate']); ?>
                    </td>
                    <td><?php echo htmlspecialchars($row1['professionalName']); ?></td>
                    <td>
                        <?php echo "Email: " . htmlspecialchars($row1['professionalEmail']) . "<br>"; ?>
                        <?php echo "Phone: " . htmlspecialchars($row1['professionalPhone']); ?>
                    </td>
               <td>
                        <!-- Professional Update Form -->
                        <form action="ho_updates_professional.php" method="POST" class="mb-3">
                            <input type="hidden" name="projectID" value="<?php echo $projectID; ?>">
                            <input type="hidden" name="stepName" value="<?php echo $row1['stepName']; ?>">
                            
                            <div class="form-group">
                                <select name="professionalID" class="form-control form-control-sm" required>
                                    <option value="">Select Professional</option>
                                   <?php
$stepName = $row1['stepName'];
$professionalQuery = "SELECT p.id, p.fullName, pd.price, pd.priceDetails
                      FROM professional p 
                      JOIN professional_details pd ON p.id = pd.professionalID 
                      WHERE pd.areaOfWork = ? AND p.status='accepted'";
$professionalStmt = mysqli_prepare($conn, $professionalQuery);
mysqli_stmt_bind_param($professionalStmt, "s", $stepName);
mysqli_stmt_execute($professionalStmt);
$professionalResult = mysqli_stmt_get_result($professionalStmt);

while ($professional = mysqli_fetch_assoc($professionalResult)): ?>
    <option value="<?php echo $professional['id']; ?>"
        <?php echo ($professional['id'] == $row1['professionalID']) ? 'selected' : ''; ?>>
        <?php
        $fullName = htmlspecialchars($professional['fullName']);
        $price = $professional['price'];
        $priceDetails = htmlspecialchars($professional['priceDetails']);

        if (empty($price) || $price == 0) {
            echo "$fullName (Price not set yet)";
        } else {
            echo "$fullName ($$price)";
            if (!empty($priceDetails)) {
                echo " - $priceDetails";
            }
        }
        ?>




                                        </option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary btn-sm btn-block">Update Professional</button>
                        </form>
                        
                      
                    </td>  
      
                    <td>
    <?php if (mysqli_num_rows($result_materials) > 0): ?>
        <ul class="list-unstyled">
            <?php 
            $counter = 0;
            mysqli_data_seek($result_materials, 0);
            while ($material = mysqli_fetch_assoc($result_materials)): 
                $counter++;
            ?>
                <li class="mb-3 p-2 bg-light rounded">
                    <form action="update_material_selection.php" method="POST">
                        <input type="hidden" name="projectID" value="<?php echo htmlspecialchars($projectID); ?>">
                        <input type="hidden" name="stepNumber" value="<?php echo htmlspecialchars($row1['stepNumber']); ?>">
                        <input type="hidden" name="work_materials_id" value="<?php echo htmlspecialchars($material['work_materials_id']); ?>">
                        
                        <div class="d-flex align-items-center">
                            <span class="font-weight-bold mr-2"><?php echo $counter; ?>.</span>
                            
                            <select name="materialID" class="form-control form-control-sm mr-3" style="width: 250px;">
                                <?php
                            $sql_all_materials = "SELECT id, title, category, price FROM material_library";

                                $result_all_materials = $conn->query($sql_all_materials);
                                if ($result_all_materials):
                                    while ($mat = $result_all_materials->fetch_assoc()): 
                                ?>
                                    <option value="<?php echo htmlspecialchars($mat['id']); ?>"
                                        <?php echo ($mat['id'] == $material['id']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($mat['title'] . ' (' . $mat['category'] . ') - $' . $mat['price']); ?>
                                    </option>
                                <?php 
                                    endwhile;
                                endif;
                                ?>
                            </select>
                              <span class="mr-2">Quantity:</span>
                          <input readonly type="number" name="quantity" class="mr-3" 
       min="1" value="<?php echo htmlspecialchars($material['quantity']); ?>" style="width: 20px;">

                            <span class="mr-2">Total:</span>
                            <span class="font-weight-bold text-success mr-3">
                                $<?php echo number_format($material['price'] * $material['quantity'], 2); ?>
                            </span>
                            
                            <button type="submit" class="btn btn-primary btn-sm">
                                <i class="fas fa-save"></i> Update
                            </button>
                        </div>
                    </form>
                </li>
            <?php endwhile; ?>
        </ul>
    <?php else: ?>
        <div class="alert alert-info">No materials assigned to this step</div>
    <?php endif; ?>
</td>
         
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>
</div>


</div>

   

<script src="https://unpkg.com/frappe-gantt@0.6.0/dist/frappe-gantt.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

  </body>
</html>

 