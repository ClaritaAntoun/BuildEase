<!-- Clarita Antoun -->
<?php
session_start();
include 'conx.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    function validateEmail($email, $fullName, $role) {
        $fullName = trim($fullName);
        $partsSpace = array_filter(explode(" ", $fullName));
        if (count($partsSpace) < 2) {
            return "Full name must include first and last name separated by space";
        }

        $nameWithoutSpaces = strtolower(str_replace(" ", "", $fullName));
        $email = strtolower(trim($email));
        $parts = explode('@', $email);

        if (count($parts) !== 2 || 
            $parts[1] !== $role . ".buildease" ||
            strpos($parts[0], $nameWithoutSpaces) !== 0 || 
            !preg_match('/\d/', $parts[0])) {
            return "Email must follow format: namewithdigits@$role.buildease";
        }

        return true;
    }

    // Validate required fields
    $requiredFields = ['userId', 'userRole', 'requestType', 'newValue', 'userFullName'];
    foreach ($requiredFields as $field) {
        if (empty($_POST[$field])) {
            $_SESSION['error'] = "Missing required field: $field";
            header('Location: profile.php');
            exit;
        }
    }

    // Extract and sanitize input
    $userId = $conn->real_escape_string($_POST['userId']);
    $userRole = $conn->real_escape_string($_POST['userRole']);
    $requestType = $conn->real_escape_string($_POST['requestType']);
    $newValue = $conn->real_escape_string($_POST['newValue']);
    $userFullName = $conn->real_escape_string($_POST['userFullName']);

    // Process based on request type
    if ($requestType === 'email') {
        $validation = validateEmail($newValue, $userFullName, $userRole);
        if ($validation !== true) {
            $_SESSION['error'] = $validation;
            header('Location: profile.php');
            exit;
        }
    } elseif ($requestType === 'password') {
        if (strlen($newValue) < 8) {
            $_SESSION['error'] = "Password must be at least 8 characters";
            header('Location: profile.php');
            exit;
        }
        $newValue = password_hash($newValue, PASSWORD_DEFAULT);
    }

    // Insert change request
    $stmt = $conn->prepare("INSERT INTO change_requests 
                          (user_id, user_role, request_type, new_value) 
                          VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $userId, $userRole, $requestType, $newValue);

    if ($stmt->execute()) {
        $_SESSION['message'] = "Change request submitted for admin approval";
    } else {
        $_SESSION['error'] = "Error submitting request: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
  if($userRole=="professional"){
    header('Location:professionalProfile.php');exit;
  }
  else  if($userRole=="contractor"){
    header('Location:contractorProfile.php');exit;
  }
  else{
    header('Location:homeOwnerProfile.php');exit;
  }
  
}