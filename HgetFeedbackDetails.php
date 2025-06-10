<!-- Clarita Antoun -->
<style>
  hr.custom-hr {
    border: none; 
    height: 2px; 
    background-color: black;
    width: 100%; 
    margin: 20px 0; 
  }
</style>
<?php
include 'conx.php';

if (isset($_POST['professional_id'])) {
    $professionalId = ($_POST['professional_id']);

    $stmt = $conn->prepare("SELECT hp.*, ho.fullName AS homeownerName, pro.fullName AS professionalName
    FROM ho_pro_feedback hp
    JOIN professional pro ON hp.professionalID = pro.id
    JOIN homeowner ho ON hp.homeownerID = ho.id
    WHERE pro.id = ? and pro.status='accepted' and ho.status='accepted'");
    $stmt->bind_param("i", $professionalId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
      $counter=1;
        while ($row = $result->fetch_assoc()) {
          echo "<b><i><u>Feedback".$counter++."</u></i></b>";
            echo "<div class='feedback'>";
            echo "<h5>Inserted by:</h5><p>" . htmlspecialchars($row['homeownerName']) . "</p>";
            echo "<h5>For:</h5><p>" . htmlspecialchars($row['professionalName']) . "</p>";
            echo "<h5>Rating:</h5><p>" . htmlspecialchars($row['rating']) . "</p>";
            echo "<h5>Comment:</h5><p>" . htmlspecialchars($row['comment']) . "</p>";
            echo "<h5>Date:</h5><p>" . htmlspecialchars($row['date']) . "</p>";
            echo "<hr class='custom-hr'>";
            echo "</div>";
        }
    } else {
        echo "No feedback found.";
    }

    $stmt->close();
}
?>
