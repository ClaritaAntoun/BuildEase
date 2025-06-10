<!-- Clarita Antoun -->
<?php
 session_start();
 include 'conx.php';
 if (!isset($_SESSION['homeOwner_identity']['id'])) {
    header("Location: logInPage.php");
    exit();
  }
  $homeOwnerId = $_SESSION['homeOwner_identity']['id'];
  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
 
    $projectID = isset($_POST['projectID']) ? intval($_POST['projectID']) : 0;
    $stepName = isset($_POST['stepName']) ? trim($_POST['stepName']) : '';
    $newProfessionalID = isset($_POST['professionalID']) ? intval($_POST['professionalID']) : 0;
   
 

       
        $stepQuery = "SELECT stepNumber FROM step WHERE name = ?";
        $stepStmt = mysqli_prepare($conn, $stepQuery);
        mysqli_stmt_bind_param($stepStmt, "s", $stepName);
        mysqli_stmt_execute($stepStmt);
        $stepResult = mysqli_stmt_get_result($stepStmt);
        $stepRow = mysqli_fetch_assoc($stepResult);

        if ($stepRow) {
            $stepNumber = $stepRow['stepNumber'];

          
            $updateQuery = "UPDATE work_in SET professionalID = ? WHERE projectID = ? AND stepNumber = ?";
            $updateStmt = mysqli_prepare($conn, $updateQuery);
            mysqli_stmt_bind_param($updateStmt, "iii", $newProfessionalID, $projectID, $stepNumber);

         mysqli_stmt_execute($updateStmt);
         header("Location: HO_updateProjectDetails.php?projectID=$projectID");
         exit;
         
        } else {
            echo "Invalid step name.";
        }
    } else {
        echo "Invalid input data.";
    }

 ?>