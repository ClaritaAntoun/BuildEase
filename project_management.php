<?php
session_start();
if (!isset($_SESSION['professional_identity'])) {
    header("Location: logInPage.php");
    exit();
}

include 'conx.php';

// Get parameters from URL
$project_id = isset($_GET['project']) ? intval($_GET['project']) : 0;
$step_number = isset($_GET['step']) ? intval($_GET['step']) : 0;

if ($project_id <= 0) {
    die("Invalid project ID");
}

// Make step number optional
if ($step_number === 0) {
        die("Invalid project ID");

}


$professional_id = $_SESSION['professional_identity']['id'];

// Function to update cost in work_in table
function updateWorkInCost($conn, $professional_id, $project_id, $step_number, $total_cost) {
    $update_sql = "UPDATE work_in SET cost = ? 
                  WHERE professionalID = ? AND projectID = ? AND stepNumber = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("diii", $total_cost, $professional_id, $project_id, $step_number);
    $update_stmt->execute();
    $update_stmt->close();
}

// Verify the professional is assigned to this project step
// Verify the professional is assigned to this project step
$verify_sql = "SELECT wi.stepStatus, wi.startDate, wi.endDate, 
              COALESCE(s.name, wi.stepName) as stepName, 
              wi.worked_details, wi.exactPrice, wi.paymentStatus, pd.price as professionalRate,
              wi.cost as current_cost
              FROM work_in wi
              LEFT JOIN step s ON wi.stepNumber = s.stepNumber
              JOIN professional_details pd ON wi.professionalID = pd.professionalID
              WHERE wi.professionalID = ? 
              AND wi.projectID = ? 
              AND wi.stepNumber = ?";
$stmt = $conn->prepare($verify_sql);
$stmt->bind_param("iii", $professional_id, $project_id, $step_number);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    $stmt->close();
    die("You are not authorized to manage this project step.");
}

$step_data = $result->fetch_assoc();
$stmt->close();

// Calculate materials cost
$materials_sql = "SELECT 
                    SUM(ml.price * (wm.quantity - wm.unused_quantity)) as actual_cost,
                    SUM(ml.price * wm.quantity) as estimated_cost,
                    SUM(ml.price * wm.unused_quantity) as unused_cost
                 FROM work_materials wm
                 JOIN material_library ml ON wm.materialID = ml.id
                 WHERE wm.professionalID = ? 
                 AND wm.projectID = ? 
                 AND wm.stepNumber = ?";

$stmt = $conn->prepare($materials_sql);
$stmt->bind_param("iii", $professional_id, $project_id, $step_number);
$stmt->execute();
$materials_result = $stmt->get_result();
$material_data = $materials_result->fetch_assoc();

$actual_materials_cost = $material_data['actual_cost'] ?? 0;
$estimated_materials_cost = $material_data['estimated_cost'] ?? 0;
$unused_materials_cost = $material_data['unused_cost'] ?? 0;
$stmt->close();

// Calculate labor cost (exact price * worked details as integer)
$labor_cost = 0;
$labor_description = "Not calculated";
$worked_details_as_int = 0;

if (!empty($step_data['worked_details']) && !empty($step_data['exactPrice'])) {
    preg_match_all('/\d+/', $step_data['worked_details'], $matches);
    $worked_details_as_int = !empty($matches[0]) ? (int)implode('', $matches[0]) : 1;
    
    $labor_cost = $step_data['exactPrice'] * $worked_details_as_int;
    $labor_description = "Exact price × Work details value";
}

// Calculate total cost (actual materials + labor)
$total_cost = $actual_materials_cost + $labor_cost;

// Update cost in database if it's different from current value
if (!isset($step_data['current_cost']) || $step_data['current_cost'] != $total_cost) {
    updateWorkInCost($conn, $professional_id, $project_id, $step_number, $total_cost);
}

// Handle status update if form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['step_status'])) {
    $status = $_POST['step_status'];
    $update_sql = "UPDATE work_in SET stepStatus = ? WHERE professionalID = ? AND projectID = ? AND stepNumber = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("siii", $status, $professional_id, $project_id, $step_number);
    $update_stmt->execute();
    $update_stmt->close();
    
    // Refresh step data after update
    $refresh_sql = "SELECT wi.stepStatus, wi.startDate, wi.endDate, s.name as stepName, 
                   wi.worked_details, wi.exactPrice, wi.paymentStatus, pd.price as professionalRate,
                   wi.cost as current_cost
                   FROM work_in wi
                   JOIN step s ON wi.stepNumber = s.stepNumber
                   JOIN professional_details pd ON wi.professionalID = pd.professionalID
                   WHERE wi.professionalID = ? 
                   AND wi.projectID = ? 
                   AND wi.stepNumber = ?";
    $refresh_stmt = $conn->prepare($refresh_sql);
    $refresh_stmt->bind_param("iii", $professional_id, $project_id, $step_number);
    $refresh_stmt->execute();
    $refresh_result = $refresh_stmt->get_result();
    $step_data = $refresh_result->fetch_assoc();
    $refresh_stmt->close();
    
    $_SESSION['status_message'] = "Status updated successfully!";
    header("Location: project_management.php?project=$project_id&step=$step_number");
    exit();
}

// Handle worked details and exact price update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_details'])) {
    $worked_details = $_POST['worked_details'];
    $exact_price = $_POST['exact_price'];
    
    $update_sql = "UPDATE work_in SET worked_details = ?, exactPrice = ? 
                  WHERE professionalID = ? AND projectID = ? AND stepNumber = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("ssiii", $worked_details, $exact_price, 
                            $professional_id, $project_id, $step_number);
    $update_stmt->execute();
    $update_stmt->close();
    
    // Refresh step data after update
    $refresh_sql = "SELECT wi.stepStatus, wi.startDate, wi.endDate, s.name as stepName, 
                   wi.worked_details, wi.exactPrice, wi.paymentStatus, pd.price as professionalRate,
                   wi.cost as current_cost
                   FROM work_in wi
                   JOIN step s ON wi.stepNumber = s.stepNumber
                   JOIN professional_details pd ON wi.professionalID = pd.professionalID
                   WHERE wi.professionalID = ? 
                   AND wi.projectID = ? 
                   AND wi.stepNumber = ?";
    $refresh_stmt = $conn->prepare($refresh_sql);
    $refresh_stmt->bind_param("iii", $professional_id, $project_id, $step_number);
    $refresh_stmt->execute();
    $refresh_result = $refresh_stmt->get_result();
    $step_data = $refresh_result->fetch_assoc();
    $refresh_stmt->close();
    
    // Recalculate labor cost
    $labor_cost = 0;
    if (!empty($step_data['worked_details']) && !empty($step_data['exactPrice'])) {
        preg_match_all('/\d+/', $step_data['worked_details'], $matches);
        $worked_details_as_int = !empty($matches[0]) ? (int)implode('', $matches[0]) : 1;
        $labor_cost = $step_data['exactPrice'] * $worked_details_as_int;
    }
    
    // Recalculate total cost
    $total_cost = $actual_materials_cost + $labor_cost;
    updateWorkInCost($conn, $professional_id, $project_id, $step_number, $total_cost);
    
    $_SESSION['success_message'] = "Work details and price updated successfully!";
    header("Location: project_management.php?project=$project_id&step=$step_number");
    exit();
}

// Handle file upload if form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['step_image'])) {
    $upload_dir = "uploads/steps/";
    
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }
    
    $file_name = basename($_FILES['step_image']['name']);
    $target_file = $upload_dir . uniqid() . '_' . $file_name;
    $imageFileType = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
    
    // Validate image
    $check = getimagesize($_FILES["step_image"]["tmp_name"]);
    if ($check === false) {
        $_SESSION['error_message'] = "File is not an image.";
        header("Location: project_management.php?project=$project_id&step=$step_number");
        exit();
    }
    
    // Check file size (5MB max)
    if ($_FILES["step_image"]["size"] > 5000000) {
        $_SESSION['error_message'] = "Sorry, your file is too large (max 5MB).";
        header("Location: project_management.php?project=$project_id&step=$step_number");
        exit();
    }
    
    // Allow certain file formats
    if (!in_array($imageFileType, ['jpg', 'jpeg', 'png', 'gif'])) {
        $_SESSION['error_message'] = "Only JPG, JPEG, PNG & GIF files are allowed.";
        header("Location: project_management.php?project=$project_id&step=$step_number");
        exit();
    }
    
    if (move_uploaded_file($_FILES["step_image"]["tmp_name"], $target_file)) {
        // Temporarily disable foreign key checks
        $conn->query("SET FOREIGN_KEY_CHECKS=0");
        
        try {
            $insert_sql = "INSERT INTO step_picture (path, details, stepNumber, projectID) VALUES (?, ?, ?, ?)";
            $insert_stmt = $conn->prepare($insert_sql);
            
            $details = "Step update uploaded on " . date('Y-m-d');
            $insert_stmt->bind_param("ssii", $target_file, $details, $step_number, $project_id);
            $insert_stmt->execute();
            $insert_stmt->close();
            
            $_SESSION['success_message'] = "Image uploaded successfully!";
        } catch (Exception $e) {
            $_SESSION['error_message'] = "Error uploading image: " . $e->getMessage();
            // Delete the uploaded file if the DB insert failed
            if (file_exists($target_file)) {
                unlink($target_file);
            }
        } finally {
            // Re-enable foreign key checks
            $conn->query("SET FOREIGN_KEY_CHECKS=1");
        }
        
        header("Location: project_management.php?project=$project_id&step=$step_number");
        exit();
    } else {
        $_SESSION['error_message'] = "Error uploading file.";
        header("Location: project_management.php?project=$project_id&step=$step_number");
        exit();
    }
}
// Handle image activation/deactivation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && isset($_POST['picture_id'])) {
    $picture_id = intval($_POST['picture_id']);
    $action = $_POST['action'];
    
    if ($action === 'deactivate' || $action === 'activate') {
        $new_status = $action === 'activate' ? 1 : 0;
        
        $update_sql = "UPDATE step_picture SET is_active = ? WHERE stepPictureID = ? AND stepNumber = ? AND projectID = ?";
        $stmt = $conn->prepare($update_sql);
        $stmt->bind_param("iiii", $new_status, $picture_id, $step_number, $project_id);
        $stmt->execute();
        
        if ($stmt->affected_rows > 0) {
            $_SESSION['success_message'] = "Image " . ($action === 'activate' ? 'activated' : 'deactivated') . " successfully!";
        } else {
            $_SESSION['error_message'] = "Failed to update image status.";
        }
        
        $stmt->close();
        header("Location: project_management.php?project=$project_id&step=$step_number");
        exit();
    }
}

// Handle material update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_material'])) {
    $material_id = intval($_POST['material_id']);
    $quantity = intval($_POST['quantity']);
    $unused_quantity = intval($_POST['unused_quantity']);
    $feedback = $_POST['feedback'];
    
    if ($quantity < 0 || $unused_quantity < 0 || $unused_quantity > $quantity) {
        $_SESSION['error_message'] = "Invalid quantity values. Unused quantity cannot exceed total quantity.";
        header("Location: project_management.php?project=$project_id&step=$step_number");
        exit();
    }
    
    $update_sql = "UPDATE work_materials SET quantity = ?, unused_quantity = ?, feedback = ? 
                  WHERE id = ? AND professionalID = ? AND projectID = ? AND stepNumber = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("iissiii", $quantity, $unused_quantity, $feedback, $material_id, 
                            $professional_id, $project_id, $step_number);
    $update_stmt->execute();
    
    if ($update_stmt->affected_rows > 0) {
        // Recalculate materials cost
        $materials_result = $conn->query("SELECT 
            SUM(ml.price * (wm.quantity - wm.unused_quantity)) as actual_cost
            FROM work_materials wm
            JOIN material_library ml ON wm.materialID = ml.id
            WHERE wm.professionalID = $professional_id 
            AND wm.projectID = $project_id 
            AND wm.stepNumber = $step_number");
        $material_data = $materials_result->fetch_assoc();
        $actual_materials_cost = $material_data['actual_cost'] ?? 0;
        
        // Recalculate total cost
        $total_cost = $actual_materials_cost + $labor_cost;
        updateWorkInCost($conn, $professional_id, $project_id, $step_number, $total_cost);
        
        $_SESSION['success_message'] = "Material updated successfully!";
    } else {
        $_SESSION['error_message'] = "Failed to update material.";
    }
    
    $update_stmt->close();
    header("Location: project_management.php?project=$project_id&step=$step_number");
    exit();
}

// Get current step pictures
$pictures_sql = "SELECT * FROM step_picture WHERE stepNumber = ? AND projectID = ? AND is_active = 1 ORDER BY stepPictureID DESC";
$stmt = $conn->prepare($pictures_sql);
$stmt->bind_param("ii", $step_number, $project_id);
$stmt->execute();
$pictures_result = $stmt->get_result();
$step_pictures = $pictures_result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// Get materials for this step (detailed list)
$materials_sql = "SELECT wm.id, wm.quantity, wm.unused_quantity, wm.feedback, 
                  ml.title, ml.category, ml.price, ml.unit_measure
                  FROM work_materials wm
                  JOIN material_library ml ON wm.materialID = ml.id
                  WHERE wm.professionalID = ? 
                  AND wm.projectID = ? 
                  AND wm.stepNumber = ?";
$stmt = $conn->prepare($materials_sql);
$stmt->bind_param("iii", $professional_id, $project_id, $step_number);
$stmt->execute();
$materials_result = $stmt->get_result();
$step_materials = $materials_result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// Get project details
$project_sql = "SELECT p.name as projectName, c.fullName as contractorName, h.fullName as homeownerName
               FROM project p
               JOIN contractor c ON p.contractorID = c.id
               JOIN creates cr ON p.projectID = cr.projectID
               JOIN homeowner h ON cr.homeOwnerID = h.id
               WHERE p.projectID = ?";
$stmt = $conn->prepare($project_sql);
$stmt->bind_param("i", $project_id);
$stmt->execute();
$project_result = $stmt->get_result();
$project_data = $project_result->fetch_assoc();
$stmt->close();

// Get professional details for sidebar
$professional_sql = "SELECT p.*, a.street, a.city, a.state, a.postalCode, pd.areaOfWork, pd.price as rate
                     FROM professional p 
                     LEFT JOIN address a ON p.addressID = a.addressID
                     LEFT JOIN professional_details pd ON p.id = pd.professionalID
                     WHERE p.id = ?";
$stmt = $conn->prepare($professional_sql);
$stmt->bind_param("i", $professional_id);
$stmt->execute();
$professional = $stmt->get_result()->fetch_assoc();
$stmt->close();
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Project Management - BuildEase</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
    :root {
        --primary: #1A2A3A;
        --accent: #6a5acd;
        --light: #f8f9fa;
        --dark: #212529;
    }

    body {
        font-family: 'Poppins', sans-serif;
        background-color: #f8f9fa;
        color: #495057;
        padding-left: 250px; /* Add padding to prevent sidebar overlap */
    }

    /* Header Top Bar */
    .header-area {
        background: var(--primary);
        padding: 12px 0;
        color: var(--accent);
        font-family: 'Poppins', sans-serif;
        border-bottom: 1px solid rgba(106, 90, 205, 0.1);
        position: fixed;
        width: 100%;
        top: 0;
        z-index: 1000;
    }

    .header-left a, .header-right ul li a {
        color: var(--accent);
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
        border-bottom: 2px solid #9370db;
        position: fixed;
        width: 100%;
        top: 56px; /* Adjust based on header height */
        z-index: 1000;
    }

    .logo {
        font-family: 'Playfair Display', serif;
        font-size: 36px;
        font-weight: 700;
        color: var(--primary);
        letter-spacing: 0.5px;
    }
    .logo span {
        color: var(--accent);
        font-weight: 400;
    }

    .navbar-nav {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        width: 100%;
    }

    .navbar-nav .nav-link {
        color: var(--primary) !important;
        font-weight: 500;
        padding: 15px 20px !important;
        transition: 0.3s;
        font-size: 16px;
        position: relative;
        margin: 0 5px;
    }

    .navbar-nav .nav-link:hover, 
    .navbar-nav .nav-item.active .nav-link {
        color: var(--accent) !important;
    }

    .navbar-nav .nav-link:hover::after {
        content: '';
        position: absolute;
        bottom: -2px;
        left: 0;
        right: 0;
        height: 2px;
        background: var(--accent);
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
        color: var(--primary) !important;
        padding: 12px 25px;
        font-size: 15px;
        transition: 0.3s;
    }
    .dropdown-menu .dropdown-item:hover {
        background: #F8F9FA;
        color: var(--accent) !important;
    }

    /* Sidebar Styles */
    .sidebar {
        background-color: var(--primary);
        color: white;
        height: 100vh;
        position: fixed;
        left: 0;
        top: 0;
        width: 250px;
        overflow-y: auto;
        padding-top: 120px; /* Space for header */
        z-index: 900; /* Lower than header */
    }

    .profile-card {
        text-align: center;
        padding: 1.5rem;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    .profile-img {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid var(--accent);
        margin-bottom: 1rem;
    }

    .profile-name {
        font-size: 1.2rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
    }

    .profile-role {
        background-color: var(--accent);
        color: white;
        padding: 0.25rem 0.75rem;
        border-radius: 50px;
        font-size: 0.85rem;
        display: inline-block;
        margin-bottom: 1rem;
    }

    .sidebar .nav-link {
        color: white !important;
        border-radius: 5px;
        margin: 0.25rem 1rem;
        padding: 0.75rem 1rem;
        transition: all 0.3s ease;
    }

    .sidebar .nav-link:hover,
    .sidebar .nav-link.active {
        background-color: rgba(106, 90, 205, 0.3);
        color: white !important;
    }

    .sidebar .nav-link i {
        width: 20px;
        text-align: center;
        margin-right: 0.5rem;
        color: var(--accent);
    }

    .sidebar .nav-link.active {
        border-left: 3px solid var(--accent);
        padding-left: calc(1rem - 3px);
    }

    /* Main Content Styles */
    .main-content {
        margin-top: 120px; /* Space for fixed header and navigation */
        padding: 20px;
        width: calc(100% - 250px); /* Account for sidebar width */
        margin-left: auto; /* Push content to the right of sidebar */
    }

    /* Card styles */
    .step-card {
        background: white;
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 20px;
        box-shadow: 0 2px 15px rgba(0,0,0,0.05);
    }

    .step-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
        padding-bottom: 10px;
        border-bottom: 1px solid #eee;
    }

    .step-title {
        font-weight: 600;
        font-size: 1.2rem;
        color: var(--primary);
    }

    .step-status {
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 500;
    }

    .step-status.pending {
        background-color: #fff3cd;
        color: #856404;
    }

    .step-status.active {
        background-color: #cce5ff;
        color: #004085;
    }

    .step-status.completed {
        background-color: #d4edda;
        color: #155724;
    }

    /* Image gallery */
    .image-gallery {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: 15px;
        margin-top: 15px;
    }

    .image-card {
        position: relative;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 3px 10px rgba(0,0,0,0.1);
    }

    .image-card img {
        width: 100%;
        height: 200px;
        object-fit: cover;
    }

    .image-status-badge {
        position: absolute;
        top: 10px;
        right: 10px;
        padding: 3px 8px;
        border-radius: 4px;
        font-size: 0.7rem;
        font-weight: 500;
    }

    .status-active {
        background-color: #28a745;
        color: white;
    }

    .status-inactive {
        background-color: #6c757d;
        color: white;
    }

    .image-actions {
        display: flex;
        justify-content: space-between;
        padding: 10px;
        background: #f8f9fa;
    }

    /* Button styles */
    .btn-accent {
        background-color: var(--accent);
        color: white;
        border: none;
    }

    .btn-accent:hover {
        background-color: #5f4bb6;
        color: white;
    }

    /* Work Details Section */
    .work-details-card {
        background: white;
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 20px;
        box-shadow: 0 2px 15px rgba(0,0,0,0.05);
    }

    .work-details-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
        padding-bottom: 10px;
        border-bottom: 1px solid #eee;
    }

    .work-details-title {
        font-weight: 600;
        font-size: 1.2rem;
        color: var(--primary);
    }

    .payment-status {
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 500;
    }

    .payment-status.paid {
        background-color: #d4edda;
        color: #155724;
    }

    .payment-status.unpaid {
        background-color: #fff3cd;
        color: #856404;
    }

    /* Cost Calculation Section */
    .cost-calculation {
        background: white;
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 20px;
        box-shadow: 0 2px 15px rgba(0,0,0,0.05);
    }

    .cost-breakdown {
        margin-top: 15px;
    }

    .cost-breakdown table {
        width: 100%;
    }

    .cost-breakdown th {
        text-align: left;
        padding: 8px;
        background-color: #f8f9fa;
    }

    .cost-breakdown td {
        padding: 8px;
        border-bottom: 1px solid #eee;
    }

    .cost-breakdown .total-row {
        font-weight: bold;
        background-color: #f8f9fa;
    }

    /* Section title */
    .section-title {
        font-family: 'Playfair Display', serif;
        font-weight: 700;
        color: var(--primary);
        margin-bottom: 30px;
        position: relative;
    }

    .section-title:after {
        content: '';
        position: absolute;
        left: 0;
        bottom: -10px;
        width: 50px;
        height: 3px;
        background-color: var(--accent);
    }

    /* Responsive adjustments */
    @media (max-width: 991.98px) {
        body {
            padding-left: 0;
        }
        
        .sidebar {
            width: 100%;
            position: relative;
            height: auto;
            padding-top: 0;
            margin-top: 120px;
        }
        
        .main-content {
            width: 100%;
            margin-left: 0;
            margin-top: 20px;
        }
        
        .header-area, .navigation {
            position: relative;
            top: auto;
        }
    }
</style>
</head>
<body>
    <!-- Header -->
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
                            <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                            
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

                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="projectsDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    Projects
                                </a>
                                <ul class="dropdown-menu" aria-labelledby="projectsDropdown">
                                    <li><a class="dropdown-item" href="prof-completedProjects.php">Completed projects</a></li>
                                    <li><a class="dropdown-item" href="prof-activeProjects.php">Active projects</a></li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </nav>
            </div>
        </nav>
    </div>

    <!-- Sidebar -->
    <div class="sidebar">
        <div class="profile-card">
            <img src="https://ui-avatars.com/api/?name=<?= urlencode($professional['fullName']) ?>&background=random&color=fff" 
                 class="profile-img" alt="Profile Image">
            <h5 class="profile-name"><?= htmlspecialchars($professional['fullName']) ?></h5>
            <span class="profile-role"><?= htmlspecialchars($professional['areaOfWork'] ?? 'Professional') ?></span>
        </div>

        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link" href="professionalPage.php">
                    <i class="fas fa-tachometer-alt"></i> Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="prof-activeProjects.php">
                    <i class="fas fa-hammer"></i> Active Projects
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="prof-completedProjects.php">
                    <i class="fas fa-check-circle"></i> Completed Projects
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="prof-PendingProjects.php">
                    <i class="fas fa-tachometer-alt"></i> Pending Projects
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="professionalProfile.php">
                    <i class="fas fa-user"></i> My Profile
                </a>
            </li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="container" style="
    margin-top: 54px;">
            <?php if (isset($_SESSION['success_message'])): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?= $_SESSION['success_message'] ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php unset($_SESSION['success_message']); ?>
            <?php endif; ?>
            
            <?php if (isset($_SESSION['error_message'])): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?= $_SESSION['error_message'] ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php unset($_SESSION['error_message']); ?>
            <?php endif; ?>
            
            <?php if (isset($_SESSION['status_message'])): ?>
                <div class="alert alert-info alert-dismissible fade show" role="alert">
                    <?= $_SESSION['status_message'] ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php unset($_SESSION['status_message']); ?>
            <?php endif; ?>
            
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1>Project Management</h1>
                <a href="professionalPage.php" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i> Back to Dashboard
                </a>
            </div>

            <div class="card mb-4">
                <div class="card-header">
                    <h2><?= htmlspecialchars($project_data['projectName']) ?></h2>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h4>Project Details</h4>
                            <p><strong>Status:</strong> <?= htmlspecialchars($step_data['stepStatus']) ?></p>
                            <p><strong>Step:</strong> <?= htmlspecialchars($step_data['stepName']) ?></p>
                            <p class="step-dates">
                                <strong>Date Range:</strong><br>
                                Start: <?= htmlspecialchars($step_data['startDate']) ?><br>
                                End: <?= htmlspecialchars($step_data['endDate']) ?>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <h4>Team Information</h4>
                            <p><strong>Contractor:</strong> <?= htmlspecialchars($project_data['contractorName']) ?></p>
                            <p><strong>Homeowner:</strong> <?= htmlspecialchars($project_data['homeownerName']) ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Cost Calculation Card -->
            <div class="cost-calculation">
                <div class="step-header">
                    <span class="step-title">Step Cost Calculation</span>
                </div>
                <div class="cost-breakdown">
                    <table>
                        <tr>
                            <th>Estimated Materials Cost</th>
                            <td>$<?= number_format($estimated_materials_cost, 2) ?></td>
                        </tr>
                        <tr class="text-danger">
                            <th>Unused Materials Cost</th>
                            <td>-$<?= number_format($unused_materials_cost, 2) ?></td>
                        </tr>
                        <tr class="table-success">
                            <th>Actual Materials Cost</th>
                            <td>$<?= number_format($actual_materials_cost, 2) ?></td>
                        </tr>
                        <tr>
                            <th>Labor Cost</th>
                            <td>$<?= number_format($labor_cost, 2) ?></td>
                        </tr>
                        <tr class="total-row">
                            <th>Total Cost</th>
                            <td>$<?= number_format($total_cost, 2) ?></td>
                        </tr>
                    </table>
                </div>
                <div class="alert alert-info mt-3">
                    <i class="fas fa-info-circle"></i> 
                    Labor cost is calculated as: <strong>Exact Price × Work Details Value</strong><br>
                    Work details value is extracted as integer from the description text.
                    <?php if (!empty($step_data['worked_details'])): ?>
                    <br>Extracted value: <?= $worked_details_as_int ?>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Work Details and Price Card -->
            <div class="work-details-card">
                <div class="work-details-header">
                    <span class="work-details-title">Work Details and Pricing</span>
                    <span class="payment-status <?= $step_data['paymentStatus'] === 'paid' ? 'paid' : 'unpaid' ?>">
                        <?= ucfirst(htmlspecialchars($step_data['paymentStatus'])) ?>
                    </span>
                </div>
                
                <form method="post" action="project_management.php?project=<?= $project_id ?>&step=<?= $step_number ?>">
                    <input type="hidden" name="update_details" value="1">
                    
                    <div class="mb-3">
                        <label for="worked_details" class="form-label">Work Details</label>
                        <textarea class="form-control" id="worked_details" name="worked_details" rows="4"><?= htmlspecialchars($step_data['worked_details']) ?></textarea>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="exact_price" class="form-label">Exact Price ($)</label>
                                <input type="number" class="form-control" id="exact_price" name="exact_price" 
                                       value="<?= htmlspecialchars($step_data['exactPrice']) ?>" step="0.01" min="0">
                                <div class="form-text">Based on your calculation: $<?= number_format($total_cost, 2) ?></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="alert alert-warning mt-4">
                                <i class="fas fa-exclamation-triangle"></i> 
                                Please ensure your price reflects the actual work and materials used.
                            </div>
                        </div>
                    </div>
                    
                    <button type="submit" class="btn btn-accent">
                        <i class="fas fa-save me-2"></i> Update Details
                    </button>
                </form>
            </div>

            <!-- Status Update Card -->
            <div class="step-card">
                <div class="step-header">
                    <span class="step-title">Update Step Status</span>
                    <span class="step-status <?= $step_data['stepStatus'] === 'completed' ? 'completed' : ($step_data['stepStatus'] === 'in_progress' ? 'active' : 'pending') ?>">
                        <?= ucfirst(htmlspecialchars($step_data['stepStatus'])) ?>
                    </span>
                </div>
                
                <form method="post" action="project_management.php?project=<?= $project_id ?>&step=<?= $step_number ?>">
                    <div class="mb-3">
                        <label for="step_status" class="form-label">New Status</label>
                        <select class="form-control" id="step_status" name="step_status" required>
                            <option value="pending" <?= $step_data['stepStatus'] === 'pending' ? 'selected' : '' ?>>Pending</option>
                            <option value="in_progress" <?= $step_data['stepStatus'] === 'in_progress' ? 'selected' : '' ?>>In Progress</option>
                            <option value="completed" <?= $step_data['stepStatus'] === 'completed' ? 'selected' : '' ?>>Completed</option>
                        </select>
                    </div>
                    
                    <button type="submit" class="btn btn-accent">
                        <i class="fas fa-save me-2"></i> Update Status
                    </button>
                </form>
            </div>

            <!-- Image Upload Card -->
            <div class="step-card">
                <div class="step-header">
                    <span class="step-title">Upload Step Images</span>
                </div>
                
                <form method="post" action="project_management.php?project=<?= $project_id ?>&step=<?= $step_number ?>" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="step_image" class="form-label">Select Image</label>
                        <input class="form-control" type="file" id="step_image" name="step_image" accept="image/*">
                        <div class="form-text">Max file size: 5MB. Supported formats: JPG, PNG, GIF.</div>
                    </div>
                    
                    <button type="submit" class="btn btn-accent">
                        <i class="fas fa-upload me-2"></i> Upload Image
                    </button>
                </form>
            </div>

            <!-- Image Gallery -->
            <div class="mt-4">
                <h3>Step Images</h3>
                <?php if (count($step_pictures) > 0): ?>
                    <div class="image-gallery">
                        <?php foreach ($step_pictures as $picture): ?>
                            <div class="image-card <?= $picture['is_active'] ? '' : 'inactive-image' ?>">
                                <span class="image-status-badge <?= $picture['is_active'] ? 'status-active' : 'status-inactive' ?>">
                                    <?= $picture['is_active'] ? 'Active' : 'Inactive' ?>
                                </span>
                                <img src="<?= htmlspecialchars($picture['path']) ?>" alt="Step Picture">
                                <p><?= htmlspecialchars($picture['details']) ?></p>
                                <div class="image-actions">
                                    <a href="<?= htmlspecialchars($picture['path']) ?>" target="_blank" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-expand me-1"></i> View
                                    </a>
                                    <?php if ($picture['is_active']): ?>
                                        <form method="post" action="project_management.php?project=<?= $project_id ?>&step=<?= $step_number ?>">
                                            <input type="hidden" name="picture_id" value="<?= $picture['stepPictureID'] ?>">
                                            <input type="hidden" name="action" value="deactivate">
                                            <button type="submit" class="btn btn-sm btn-outline-warning">
                                                <i class="fas fa-eye-slash me-1"></i> Deactivate
                                            </button>
                                        </form>
                                    <?php else: ?>
                                        <form method="post" action="project_management.php?project=<?= $project_id ?>&step=<?= $step_number ?>">
                                            <input type="hidden" name="picture_id" value="<?= $picture['stepPictureID'] ?>">
                                            <input type="hidden" name="action" value="activate">
                                            <button type="submit" class="btn btn-sm btn-outline-success">
                                                <i class="fas fa-eye me-1"></i> Activate
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="alert alert-info">No images have been uploaded for this step yet.</div>
                <?php endif; ?>
            </div>

            <!-- Materials Section -->
            <div class="step-card mt-4">
                <div class="step-header">
                    <span class="step-title">Materials Used in This Step</span>
                </div>
                
                <?php if (count($step_materials) > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-bordered materials-table">
                            <thead>
                                <tr>
                                    <th>Material</th>
                                    <th>Category</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th>Unused Quantity</th>
                                    <th>Used Quantity</th>
                                    <th>Feedback</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($step_materials as $material): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($material['title']) ?></td>
                                        <td><?= htmlspecialchars($material['category']) ?></td>
                                        <td>$<?= number_format($material['price'], 2) ?> <?= htmlspecialchars($material['unit_measure']) ?></td>
                                        <td><?= htmlspecialchars($material['quantity']) ?></td>
                                        <td><?= htmlspecialchars($material['unused_quantity']) ?></td>
                                        <td class="material-used"><?= htmlspecialchars($material['quantity'] - $material['unused_quantity']) ?></td>
                                        <td class="material-feedback"><?= htmlspecialchars($material['feedback']) ?></td>
                                        <td>
                                            <button class="btn btn-sm btn-primary" data-bs-toggle="modal" 
                                                    data-bs-target="#editMaterialModal" 
                                                    data-material-id="<?= $material['id'] ?>"
                                                    data-material-name="<?= htmlspecialchars($material['title']) ?>"
                                                    data-quantity="<?= $material['quantity'] ?>"
                                                    data-unused="<?= $material['unused_quantity'] ?>"
                                                    data-feedback="<?= htmlspecialchars($material['feedback']) ?>">
                                                <i class="fas fa-edit me-1"></i> Edit
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="alert alert-info">No materials have been assigned to this step yet.</div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Edit Material Modal -->
    <div class="modal fade" id="editMaterialModal" tabindex="-1" aria-labelledby="editMaterialModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editMaterialModalLabel">Edit Material</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="post" action="project_management.php?project=<?= $project_id ?>&step=<?= $step_number ?>">
                    <div class="modal-body">
                        <input type="hidden" name="update_material" value="1">
                        <input type="hidden" name="material_id" id="modalMaterialId">
                        
                        <div class="mb-3">
                            <label class="form-label">Material</label>
                            <input type="text" class="form-control" id="modalMaterialName" readonly>
                        </div>
                        
                        <div class="mb-3">
                            <label for="modalQuantity" class="form-label">Total Quantity</label>
                            <input type="number" class="form-control" id="modalQuantity" name="quantity" min="0" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="modalUnused" class="form-label">Unused Quantity</label>
                            <input type="number" class="form-control" id="modalUnused" name="unused_quantity" min="0" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="modalFeedback" class="form-label">Feedback</label>
                            <textarea class="form-control" id="modalFeedback" name="feedback" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Handle modal data population
        document.addEventListener('DOMContentLoaded', function() {
            var editMaterialModal = document.getElementById('editMaterialModal');
            if (editMaterialModal) {
                editMaterialModal.addEventListener('show.bs.modal', function(event) {
                    var button = event.relatedTarget;
                    var materialId = button.getAttribute('data-material-id');
                    var materialName = button.getAttribute('data-material-name');
                    var quantity = button.getAttribute('data-quantity');
                    var unused = button.getAttribute('data-unused');
                    var feedback = button.getAttribute('data-feedback');
                    
                    document.getElementById('modalMaterialId').value = materialId;
                    document.getElementById('modalMaterialName').value = materialName;
                    document.getElementById('modalQuantity').value = quantity;
                    document.getElementById('modalUnused').value = unused;
                    document.getElementById('modalFeedback').value = feedback;
                });
            }
            
            // Validate quantity inputs in the modal
            var quantityInput = document.getElementById('modalQuantity');
            var unusedInput = document.getElementById('modalUnused');
            
            if (quantityInput && unusedInput) {
                quantityInput.addEventListener('change', validateQuantities);
                unusedInput.addEventListener('change', validateQuantities);
                
                function validateQuantities() {
                    var quantity = parseInt(quantityInput.value) || 0;
                    var unused = parseInt(unusedInput.value) || 0;
                    
                    if (unused > quantity) {
                        alert('Unused quantity cannot exceed total quantity');
                        unusedInput.value = quantity;
                    }
                }
            }
        });
    </script>
</body>
</html>