 <!-- Clarita Antoun -->
<?php
session_start(); 
include 'conx.php';

if (!isset($_SESSION['contractor_identity']['id'])) {
    header("Location: logInPage.php");
    exit();
}

$contractorId = $_SESSION['contractor_identity']['id'];
 
$sql1 = "
    SELECT contract.* 
    FROM contract 
    INNER JOIN contractor 
        ON contract.contractorID = contractor.id
    WHERE contractor.id = ? 
      AND contractor.status = 'accepted'
";

$stmt1 = $conn->prepare($sql1); // Prepare FIRST
if (!$stmt1) {
    die("SQL Error: " . $conn->error);
}

$stmt1->bind_param("i", $contractorId); // Only once
$stmt1->execute();
$res1 = $stmt1->get_result();


     




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

/* Base Page Styling */
body {
    font-family: Georgia, 'Times New Roman', serif;
    background-color: #fefefe;
    color: #333;
    margin: 0;
    padding: 2rem;
    display: flex;
    justify-content: center;
    flex-wrap: wrap;
}

/* Contract Container */
.contract-container {
    background-color: #fff;
    border: 1px solid #ccc;
    padding: 2rem;
    border-radius: 8px;
    max-width: 700px;
    width: 100%;
    margin: 1rem;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.06);
}

.contract-container h2 {
    font-size: 1.6rem;
    margin-top: 0;
    margin-bottom: 1rem;
    border-bottom: 1px solid #bbb;
    padding-bottom: 0.5rem;
    color: #2c3e50;
}

/* Paragraph Styling */
.contract-container p {
    font-size: 1rem;
    line-height: 1.7;
    margin: 0.6rem 0;
}

.contract-container p strong {
    display: inline-block;
    width: 130px;
    font-weight: 600;
    color: #000;
}

/* Signature Styling */
.signature {
    margin-top: 1.5rem;
}

.signature img {
    border: 1px solid #aaa;
    border-radius: 5px;
    margin-top: 0.75rem;
    max-width: 100%;
    height: auto;
}

/* Sign Button */
button.sign-btn {
    background-color: #4a5568;
    color: #fff;
    border: none;
    padding: 0.6rem 1.4rem;
    border-radius: 4px;
    font-size: 1rem;
    margin-top: 1rem;
    cursor: pointer;
    font-family: 'Georgia', serif;
    transition: background-color 0.3s ease;
}

button.sign-btn:hover {
    background-color: #2d3748;
}

/* Responsive Enhancements */
@media (max-width: 768px) {
    body {
        padding: 1rem;
    }

    .contract-container {
        padding: 1.5rem;
    }

    .contract-container p strong {
        display: block;
        width: auto;
        margin-bottom: 0.2rem;
    }
}


:root {
        --primary: #1A2A3A;
        --secondary: #f8f9fa;
        --yellow-main: #FFC107;
        --header-height: 120px;
        --sidebar-width: 250px;
    }
    
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: var(--secondary);
        padding-top: var(--header-height);
        margin: 0;
    }
    
    /* Header Top Styles */
    .header-top {
        background: var(--primary);
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
        color: var(--yellow-main);
        font-family: 'Poppins', sans-serif;
        transition: color 0.3s ease;
    }
    
    .header-top a:hover {
        color: #fff;
    }
    
    /* Main Navigation Styles */
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
        color: var(--primary);
    }
    
    .logo span {
        color: var(--yellow-main);
        font-weight: 400;
    }
    
    .nav-link {
        color: var(--primary) !important;
        font-weight: 500;
        padding: 0.5rem 1.5rem !important;
        transition: all 0.3s ease;
    }
    
    .nav-link:hover,
    .nav-link.active {
        color: var(--yellow-main) !important;
    }
    
  /* Responsive Adjustments */
  @media (max-width: 991.98px) {
        :root {
            --sidebar-width: 220px;
        }
    }
    
    @media (max-width: 767.98px) {
        :root {
            --header-height: 140px;
        }
        
        .sidebar {
            width: 100%;
            position: static;
            min-height: auto;
            top: auto;
        }
        
        .main-navbar {
            top: 80px;
        }
        
        main {
            margin-left: 0;
            margin-top: 20px;
        }
    }  /* Responsive Adjustments */
    @media (max-width: 991.98px) {
        :root {
            --sidebar-width: 220px;
        }
    }
    
    @media (max-width: 767.98px) {
        :root {
            --header-height: 140px;
        }
        
        .sidebar {
            width: 100%;
            position: static;
            min-height: auto;
            top: auto;
        }
        
        .main-navbar {
            top: 80px;
        }
        
        main {
            margin-left: 0;
            margin-top: 20px;
        }
    }
.table-custom thead th {
    font-size: 1.2em; /* Increased from 0.9em */
}

.table-custom tbody td {
    font-size: 1.1em; /* Added to increase readability */
}

button.btn-custom-accept,
button.btn-custom-reject {
    font-size: 1.1em; /* Increased button text */
}

.no-projects {
    font-size: 1.2em; /* Bigger empty message text */
}

.contract-container h2 {
    font-size: 2rem; /* Was 1.6rem */
}

.contract-container p {
    font-size: 1.2rem; /* Was 1rem */
}

.contract-container p strong {
    font-size: 1.2rem;
}

button.sign-btn {
    font-size: 1.2rem; /* Was 1rem */
}

.navbar-brand .logo {
    font-size: 2.5rem; /* Was 2rem */
}

.nav-link {
    font-size: 1.2rem; /* Increased nav link size */
}

body {
    font-size: 1.1rem; /* Global base size for anything not overridden */
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
            
            <div class="collapse navbar-collapse" id="mainNav">
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

            </div>
        </div>
    </nav>

</div>
<!-- Signature Modal (put this in your HTML body) -->
<div id="signatureModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:9999;">
  <div style="background:transparent; width:90%; max-width:450px; margin:100px auto; padding:30px 20px; border-radius:12px; text-align:center;">
    <h2 style="margin-bottom:20px;">Sign Here</h2>
    <canvas id="signature-pad" style="border:2px dashed #ccc; width:100%; height:200px;"></canvas>
    <div style="margin-top:15px;">
      <button id="clear" style="padding:10px 20px; margin:5px; background:grey; color:white; border:none;">Clear</button>
      <button id="save" style="padding:10px 20px; margin:5px; background:#f4a261; color:white; border:none;">Save</button>
      <button id="skip" style="padding:10px 20px; margin:5px; background:grey; color:white; border:none;">Skip</button>
    </div>
    <form id="sigForm" style="display:none;">
      <input type="hidden" name="contractID" id="contractIDInput" value="">
      <input type="hidden" name="signature" id="signatureInput">
    </form>
  </div>
</div>

<?php if ($res1->num_rows > 0): ?>
    <?php while ($contract = $res1->fetch_assoc()): ?>
        <div class="contract-container">
            <h2>Contract Details</h2>
            <p><strong>Contract ID:</strong> <?php echo htmlspecialchars($contract['contractID']); ?></p>
            <p><strong>Salary:</strong> $<?php echo htmlspecialchars($contract['salary']); ?></p>
            <p><strong>Start Date:</strong> <?php echo htmlspecialchars($contract['startDate']); ?></p>
            <p><strong>End Date:</strong> <?php echo htmlspecialchars($contract['endDate']); ?></p>
            <p><strong>Status:</strong> <?php echo htmlspecialchars($contract['status']); ?></p>
            <p><strong>Details:</strong> <?php echo nl2br(htmlspecialchars($contract['details'])); ?></p>

            <div class="signature">
            <p><strong>Signature:</strong>
            <?php if (!empty($contract['signature'])): ?>
                <img src="<?php echo htmlspecialchars($contract['signature']); ?>" alt="Contract Signature" width="200">
            <?php else: ?>
                <span style="color: red;">Not signed yet</span>
              
                <button class="btn btn-warning sign-btn" data-contractid="<?php echo $contract['contractID']; ?>">Sign Contract</button></p>
            <?php endif; ?>
        </div>
        </div>
    <?php endwhile; ?>
<?php endif; ?>


<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>

  const canvas = document.getElementById('signature-pad');
  const pad = new SignaturePad(canvas);

  document.getElementById('clear').addEventListener('click', () => pad.clear());

  document.getElementById('skip').addEventListener('click', () => {
    document.getElementById('signatureModal').style.display = 'none';
    pad.clear();
  });

  document.getElementById('save').addEventListener('click', () => {
    if (pad.isEmpty()) return alert("Please sign first!");

    // Convert to image
    const image = pad.toDataURL('image/png');
    document.getElementById('signatureInput').value = image;

    // Send to server
    fetch('saveSignature.php', {
      method: 'POST',
      body: new FormData(document.getElementById('sigForm'))
    })
    .then(response => response.text())
    .then(result => {
      alert(result);
      document.getElementById('signatureModal').style.display = 'none';
      pad.clear();
      location.reload();
    })
    .catch(error => alert('Error: ' + error));
  });


  document.querySelectorAll('.sign-btn').forEach(button => {
    button.addEventListener('click', () => {
      document.getElementById('contractIDInput').value = button.getAttribute('data-contractid');
      // Clear previous signature
      pad.clear();
      // Show modal
      document.getElementById('signatureModal').style.display = 'block';
    });
  });
</script>
