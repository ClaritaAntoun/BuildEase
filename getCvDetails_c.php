<!-- Clarita Antoun -->
<?php
session_start();
include 'conx.php';

if (isset($_POST['contractorId'])) {
    $contractorId = ($_POST['contractorId']);

    $stmt = $conn->prepare("SELECT cv.educations, cv.skills, cv.experiences, 
                                   cv.certifications, cv.languages
                            FROM curriculum_vitae cv
                            INNER JOIN contractor c ON c.cvID = cv.cvID
                            WHERE c.id = ? and c.status='accepted'");
$stmt->bind_param("i", $contractorId); 
$stmt->execute();
$result = $stmt->get_result(); 

}

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
   

?>
