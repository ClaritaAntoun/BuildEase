<?php
session_start();
include 'conx.php';
if (!isset($_SESSION['homeOwner_identity']['id'])) {
    header("Location: logInPage.php");
    exit();
  }
  $homeOwnerId = $_SESSION['homeOwner_identity']['id'];
  
  error_log("POST data: " . print_r($_POST, true));
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and validate inputs
   $materialID=$_POST['materialID'];
   $projectID=$_POST['projectID'];
   $stepNumber=$_POST['stepNumber'];
   $work_materials_id=$_POST['work_materials_id']; 


    if ($work_materials_id && $projectID && $stepNumber&&$materialID) {
        
        $sql = "UPDATE work_materials 
                SET materialID = ? 
                WHERE projectID = ? AND stepNumber = ? AND work_materials.ID= ?"  ;

        if ($stmt = $conn->prepare($sql)) {
            // Bind parameters
            $stmt->bind_param("iiii", $materialID, $projectID, $stepNumber,$work_materials_id);

            // Execute the query
            if ($stmt->execute()) {
              
                header("Location: HO_updateProjectDetails.php?projectID=" . $projectID);
                exit();
            } else {
                ECHO "ERROR";
            }

            // Close the statement
            $stmt->close();
        } else {
            echo "Error preparing the update query.";
        }
    } else {
        echo "Invalid input data.";
    }
}

// Close the database connection
$conn->close();
?>
