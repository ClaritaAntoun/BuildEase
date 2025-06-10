<!-- Clarita Antoun -->
<?php
session_start();
include 'conx.php';

if (isset($_POST['professional_id'])) {
    $professionalId = ($_POST['professional_id']);

    $stmt = $conn->prepare("SELECT cv.educations, cv.skills, cv.experiences, 
                                   cv.certifications, cv.languages
                            FROM curriculum_vitae cv
                            INNER JOIN professional p ON p.cvID = cv.cvID
                            WHERE p.id = ? and p.status='accepted'");
$stmt->bind_param("i", $professionalId); 
$stmt->execute();
$result = $stmt->get_result(); 



    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
       
        echo "<h5>Education</h5><p>" . htmlspecialchars($row['educations']) . "</p>";
        echo "<h5>Skills</h5><p>" . htmlspecialchars($row['skills']) . "</p>";
        echo "<h5>Experiences</h5><p>" . htmlspecialchars($row['experiences']) . "</p>";
        echo "<h5>Certifications</h5><p>" . htmlspecialchars($row['certifications']) . "</p>";
        echo "<h5>Languages</h5><p>" . htmlspecialchars($row['languages']) . "</p>";
    } else {
        echo "CV details not found.";
    }
   
$stmt->close();
}
?>
