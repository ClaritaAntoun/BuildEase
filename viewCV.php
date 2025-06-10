
<!-- Clarita Antoun -->
<?php 
session_start();
include("conx.php");

if (!isset($_SESSION['admin_identity']['id'])) {
    header("Location: logInPage.php");
    exit();
}

$role = $_GET['role'];
$result = null;

if ($role == "professional" && isset($_GET['professionalID'])) {
    $professionalID = $_GET['professionalID'];

    $stmt = $conn->prepare("SELECT cv.educations, cv.skills, cv.experiences, 
                                   cv.certifications, cv.languages
                            FROM curriculum_vitae cv
                            INNER JOIN professional p ON p.cvID = cv.cvID
                            WHERE p.id = ? and p.status='accepted'");
    $stmt->bind_param("i", $professionalID); 
    $stmt->execute();
    $result = $stmt->get_result(); 
    $row=$result->fetch_assoc();
} 
else if ($role == "contractor" && isset($_GET['contractorID'])) {
    $contractorID = $_GET['contractorID'];

    $stmt = $conn->prepare("SELECT cv.educations, cv.skills, cv.experiences, 
                                   cv.certifications, cv.languages
                            FROM curriculum_vitae cv
                            INNER JOIN contractor c ON c.cvID = cv.cvID
                            WHERE c.id = ? and c.status='accepted'");
    $stmt->bind_param("i", $contractorID); 
    $stmt->execute();
    $result = $stmt->get_result(); 
    $row=$result->fetch_assoc();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CV Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"> <!-- Font Awesome -->
 <style>
    body {
        background-color: #f8f8f8;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        color: #333;
        min-height: 100vh;
        margin: 0;
        padding: 20px;
    }

    .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 20px;
    }

    .login-container {
        max-width: 100%;
        margin: 30px auto;
        padding: 30px;
        background: white;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        border: 1px solid #e0e0e0;
    }

    h3.text-center {
        color: #6B8E23; /* Olive green */
        font-size: 28px;
        text-align: center;
        margin-bottom: 30px;
        border-bottom: 2px solid #6B8E23;
        padding-bottom: 15px;
    }

    .form-label {
        color: #556B2F; /* Darker olive */
        font-weight: 600;
        margin-bottom: 8px;
        display: block;
    }

    textarea.form-control, 
    input[type="text"].form-control, 
    select.form-control {
        width: 100%;
        font-size: 1rem;
        padding: 12px;
        background-color: white;
        border: 1px solid #ddd;
        border-radius: 6px;
        color: #333;
        margin-bottom: 20px;
    }

    textarea.form-control {
        min-height: 120px;
        resize: vertical;
    }

    textarea.form-control:focus, 
    input[type="text"].form-control:focus, 
    select.form-control:focus {
        outline: none;
        border-color: #6B8E23;
        box-shadow: 0 0 0 2px rgba(107, 142, 35, 0.2);
    }

    /* Go Back button */
    button[onclick="window.location.href='adminPage.php'"] {
        position: fixed;
        top: 15px;
        left: 15px;
        background-color: #6B8E23;
        color: white;
        padding: 10px 20px;
        font-size: 16px;
        font-weight: bold;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        z-index: 1000;
        transition: background 0.3s;
    }

    button[onclick="window.location.href='adminPage.php'"]:hover {
        background-color: #556B2F;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .container {
            padding: 10px;
        }
        .login-container {
            padding: 20px;
        }
        button[onclick="window.location.href='adminPage.php'"] {
            top: 10px;
            left: 10px;
            padding: 8px 15px;
            font-size: 14px;
        }
    }
       body {
        background-color: whitesmoke;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        font-weight: bold; /* Added */
        color: #333;
        min-height: 100vh;
        margin: 0;
        padding: 20px;
    }

    .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 20px;
        font-weight: bold; /* Added */
    }

    .login-container {
        max-width: 100%;
        margin: 30px auto;
        padding: 30px;
        background: white;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        border: 1px solid #e0e0e0;
        font-weight: bold; /* Added */
    }

    h3.text-center {
        color: #6B8E23;
        font-size: 28px;
        text-align: center;
        margin-bottom: 30px;
        border-bottom: 2px solid #6B8E23;
        padding-bottom: 15px;
        font-weight: bold; /* Added */
    }

    .form-label {
        color: #556B2F;
        font-weight: bold;
        margin-bottom: 8px;
        display: block;
    }

    textarea.form-control, 
    input[type="text"].form-control, 
    select.form-control {
        width: 100%;
        font-size: 1rem;
        padding: 12px;
        background-color: white;
        border: 1px solid #ddd;
        border-radius: 6px;
        color: #333;
        margin-bottom: 20px;
        font-weight: bold; /* Added */
    }

    textarea.form-control {
        min-height: 120px;
        resize: vertical;
    }

    textarea.form-control:focus, 
    input[type="text"].form-control:focus, 
    select.form-control:focus {
        outline: none;
        border-color: #6B8E23;
        box-shadow: 0 0 0 2px rgba(107, 142, 35, 0.2);
    }

    /* Go Back button */
    button[onclick="window.location.href='adminPage.php'"] {
        position: fixed;
        top: 15px;
        left: 15px;
        background-color: #6B8E23;
        color: white;
        padding: 10px 20px;
        font-size: 16px;
        font-weight: bold;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        z-index: 1000;
        transition: background 0.3s;
    }

    button[onclick="window.location.href='adminPage.php'"]:hover {
        background-color: #556B2F;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .container {
            padding: 10px;
        }
        .login-container {
            padding: 20px;
        }
        button[onclick="window.location.href='adminPage.php'"] {
            top: 10px;
            left: 10px;
            padding: 8px 15px;
            font-size: 14px;
        }
    }
</style>
</head>
<body>
<button onclick="window.location.href='adminPage.php'" style="
    position: fixed;
    top: 15px;
    left: 15px;
    background-color: grey;
    color: black;
    padding: 10px 20px;
    font-size: 16px;
    font-weight: bold;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    z-index: 1000;
">
    ← Go Back
</button>
    <div class="container">
        <div class="login-container">
            <h3 class="text-center">Curriculum vitae</h3>
          
         
          <?php if ($row): ?>
<div class="mb-3">
    <label for="educations" class="form-label"><b><i>Educations</i></b></label>
    <textarea class="form-control" id="educations" name="educations" readonly><?php echo $row['educations']; ?></textarea>
</div>

<div class="mb-3">
    <label for="experiences" class="form-label"><b><i>Experiences</i></b></label>
    <textarea class="form-control" id="experiences" name="experiences" readonly><?php echo $row['experiences']; ?></textarea>
</div>

<div class="mb-3">
    <label for="skills" class="form-label"><b><i>Skills</i></b></label>
    <textarea class="form-control" id="skills" name="skills" readonly><?php echo $row['skills']; ?></textarea>
</div>

<div class="mb-3">
    <label for="languages" class="form-label"><b><i>Languages</i></b></label>
    <textarea class="form-control" id="languages" name="languages" readonly><?php echo $row['languages']; ?></textarea>
</div>

<div class="mb-3">
    <label for="certifications" class="form-label"><b><i>Certifications</i></b></label>
    <textarea class="form-control" id="certifications" name="certifications" readonly><?php echo $row['certifications']; ?></textarea>
</div>
<?php else: ?>
    <div class="alert alert-warning">CV details not found or the user is not accepted.</div>
<?php endif; ?>


   
   
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>


