<?php
session_start();
include 'conx.php';

// Check if contractor is logged in
if (!isset($_SESSION['contractor_identity'])) {
    header("Location: logInPage.php");
    exit();
}

// Check if form was submitted
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: add_material.php");
    exit();
}

// Get form data
$title = $_POST['material_name'] ?? '';
$category = $_POST['category'] ?? '';
$price = $_POST['unit_price'] ?? 0;
$supplier = $_POST['supplier'] ?? '';
$unit_measure = $_POST['unit_measure'] ?? '';
$description = $_POST['description'] ?? '';
$contractor_id = $_POST['contractor_id'] ?? 0;
$project_id = $_POST['project_id'] ?? 0;

// Validate required fields
if (empty($title) || empty($category) || empty($price)) {
    echo json_encode([
        'success' => false,
        'message' => 'Material name, category, and price are required fields.'
    ]);
    exit();
}

// Sanitize and validate data
$title = htmlspecialchars(trim($title));
$category = htmlspecialchars(trim($category));
$price = (float)$price;
$supplier = htmlspecialchars(trim($supplier));
$unit_measure = htmlspecialchars(trim($unit_measure));
$description = htmlspecialchars(trim($description));
$contractor_id = (int)$contractor_id;
$project_id = (int)$project_id;

try {
    // Prepare SQL statement
    $stmt = $conn->prepare("
        INSERT INTO material_library (
            title, 
            category, 
            price, 
            supplier, 
            unit_measure, 
            description, 
            contractorID
        ) VALUES (?, ?, ?, ?, ?, ?, ?)
    ");
    
    // Bind parameters
    $stmt->bind_param(
        "ssdsssi", 
        $title, 
        $category, 
        $price, 
        $supplier, 
        $unit_measure, 
        $description, 
        $contractor_id
    );
    
    // Execute query
    if ($stmt->execute()) {
        // Success - return JSON response
        echo json_encode([
            'success' => true,
            'message' => 'Material added to library successfully!'
        ]);
    } else {
        // Error in execution
        echo json_encode([
            'success' => false,
            'message' => 'Failed to add material to library. Please try again.'
        ]);
    }
    
    $stmt->close();
} catch (Exception $e) {
    // Handle database errors
    echo json_encode([
        'success' => false,
        'message' => 'Database error: ' . $e->getMessage()
    ]);
}

$conn->close();
?>