
<!-- Clarita Antoun -->
<?php
session_start();
include "conx.php";

if (!isset($_SESSION['professional_identity']['id'])) {
    header("Location: logInPage.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $profId = $_SESSION['professional_identity']['id'];

    if (
       
        isset($_POST['price']) &&
        isset($_POST['priceDetails'])
    ) {
      
      
        $price = mysqli_real_escape_string($conn, $_POST['price']);
        $priceDetails = mysqli_real_escape_string($conn, $_POST['priceDetails']);

       
        $sql = "UPDATE professional_details 
                SET  price = ?, priceDetails = ? 
                WHERE professionalID = ?";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssi",  $price, $priceDetails, $profId);
        
        if ($stmt->execute()) {
            header('Location: professionalPage.php');
            exit();
        } else {
            echo "Error updating details: " . $stmt->error;
        }

        $stmt->close();
    }
}
?>
