<!-- Clarita Antoun -->
<?php
session_start();
include 'conx.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    

    // Validate required fields
    $requiredFields = ['userId', 'userRole', 'requestType', 'newValue'];
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
   

   
   if ($requestType === 'password') {
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