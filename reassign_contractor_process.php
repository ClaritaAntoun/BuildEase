<!-- Clarita Antoun -->
<?php
 session_start();
 include 'conx.php';
 if (!isset($_SESSION['homeOwner_identity']['id'])) {
    header("Location: logInPage.php");
    exit();
  }
  
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['projectID'], $_POST['contractorID'])) {
    
    $projectID = $_POST['projectID'];
    $contractorID = $_POST['contractorID'];

    

   
    $sql = "UPDATE project SET contractorID = ?, status = 'pending' WHERE projectID = ?";
    $stmt = $conn->prepare($sql);


    if ($stmt) {
      
        $stmt->bind_param("ii", $contractorID, $projectID);

        if ($stmt->execute()) {
           
            header("Location: homeOwnerPage.php?projectID=" . $projectID);
        } else {
           
          
            header("Location: reassign_contractor.php?projectID=" . $projectID);
        }
    } else {
        
        
        header("Location: reassign_contractor.php?projectID=" . $projectID);
    }
} else {
  
   
    header("Location: reassign_contractor.php?projectID=" . $projectID);
}
exit;
?>