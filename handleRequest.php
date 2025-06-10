<!-- Clarita Antoun -->
<?php
session_start(); 
include 'conx.php';
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $requestId = $_POST['requestId'];
    $action = $_POST['action'];

    // Fetch the request details
    $stmt = $conn->prepare("SELECT * FROM change_requests WHERE id = ?");
    $stmt->bind_param("i", $requestId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($request = $result->fetch_assoc()) {
        $userId = $request['user_id'];
        $requestType = $request['request_type'];
        $newValue = $request['new_value'];
        $userRole = $request['user_role']; 

        if ($action === "approve") {
            $table = "";
            if ($userRole === "homeowner") $table = "homeowner";
            elseif ($userRole === "contractor") $table = "contractor";
            elseif ($userRole === "professional") $table = "professional";

            if ($requestType === "email") {
                $update = $conn->prepare("UPDATE $table SET email = ? WHERE id = ?");
                $update->bind_param("si", $newValue, $userId);
                $update->execute();
            } elseif ($requestType === "password") {
                $password =$newValue;
                $update = $conn->prepare("UPDATE $table SET password = ? WHERE id = ?");
                $update->bind_param("si", $password, $userId);
                $update->execute();
            }

      

            $updateStatus = $conn->prepare("UPDATE change_requests SET status = 'approved' WHERE id = ?");
            $updateStatus->bind_param("i", $requestId);
            $updateStatus->execute();

        } elseif ($action === "reject") {
           // $_SESSION['message'] = "Your request to change your $requestType was rejected.";
            $updateStatus = $conn->prepare("UPDATE change_requests SET status = 'rejected' WHERE id = ?");
            $updateStatus->bind_param("i", $requestId);
            $updateStatus->execute();
        }
    }
}
header("Location: adminPage.php");
exit;
?>
