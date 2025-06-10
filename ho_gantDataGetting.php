<?php
session_start();
include 'conx.php';

$projectID = isset($_GET['projectID']) ? $_GET['projectID'] : null;

if (!$projectID) {
    echo "Project ID not provided.";
    exit;
}

// Standard steps that are used in this project only
$standardStepsSql = "SELECT 
    w.stepNumber,
    s.name AS stepName,
    s.details,
    DATE_FORMAT(w.startDate, '%Y-%m-%d') AS startDate,
    DATE_FORMAT(w.endDate, '%Y-%m-%d') AS endDate,
    w.stepStatus,
    p.fullName AS professionalName,
    pd.areaOfWork AS professionalArea,
    FALSE AS is_custom
FROM work_in w
JOIN step s ON w.stepNumber = s.stepNumber
LEFT JOIN professional p ON w.professionalID = p.id
LEFT JOIN professional_details pd ON p.id = pd.professionalID
WHERE w.projectID = ? AND w.stepName IS NULL AND w.is_active = 1
ORDER BY w.stepNumber";

$stmt = $conn->prepare($standardStepsSql);
$stmt->bind_param("i", $projectID);
$stmt->execute();
$standardSteps = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// Custom steps created for this project
$customStepsSql = "SELECT 
    w.stepNumber,
    w.stepName AS stepName,
    w.stepDetails AS details,
    DATE_FORMAT(w.startDate, '%Y-%m-%d') AS startDate,
    DATE_FORMAT(w.endDate, '%Y-%m-%d') AS endDate,
    w.stepStatus,
    p.fullName AS professionalName,
    pd.areaOfWork AS professionalArea,
    TRUE AS is_custom
FROM work_in w
LEFT JOIN professional p ON w.professionalID = p.id
LEFT JOIN professional_details pd ON p.id = pd.professionalID
WHERE w.projectID = ? AND w.stepName IS NOT NULL AND w.is_active = 1
ORDER BY w.stepNumber";

$stmt = $conn->prepare($customStepsSql);
$stmt->bind_param("i", $projectID);
$stmt->execute();
$customSteps = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// Merge all steps
$steps = array_merge($standardSteps, $customSteps);

// Format JSON output
$data = [];
foreach ($steps as $row) {
    $data[] = [
        "task" => $row['stepName'],
        "start" => $row['startDate'],
        "end" => $row['endDate'],
        "assignedTo" => $row['professionalName'],
        "status" => $row['stepStatus']
    ];
}

header('Content-Type: application/json');
echo json_encode($data);

mysqli_close($conn);
?>
