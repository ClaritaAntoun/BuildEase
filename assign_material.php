<?php
session_start();
include 'conx.php';

header('Content-Type: application/json');
ob_start();

try {
    // Verify contractor session
    if (!isset($_SESSION['contractor_identity'])) {
        throw new Exception("Not authorized", 401);
    }

    // Only accept POST requests
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception("Method not allowed", 405);
    }

    // Get and validate input
    $projectId = (int)($_POST['project_id'] ?? 0);
    $materialId = (int)($_POST['material_id'] ?? 0);
    $quantity = (int)($_POST['quantity'] ?? 1);
    $isCustom = isset($_POST['is_custom']) && $_POST['is_custom'] === '1';
    $stepNumber = (int)($_POST['step_number'] ?? 0);
    $stepName = $_POST['custom_step_name'] ?? '';

    // Validate inputs
    if ($projectId <= 0 || $materialId <= 0 || $quantity <= 0) {
        throw new Exception("Invalid input parameters", 400);
    }

    // Verify material ownership
    $stmt = $conn->prepare("SELECT id FROM material_library WHERE id = ? AND contractorID = ?");
    $stmt->bind_param("ii", $materialId, $_SESSION['contractor_identity']['id']);
    $stmt->execute();
    
    if ($stmt->get_result()->num_rows === 0) {
        throw new Exception("Material not found or not owned by you");
    }
    $stmt->close();

    // Handle step assignment
    $professionalId = null;
    $actualStepNumber = null;
    
    if ($isCustom) {
        if (empty($stepName)) {
            throw new Exception("Custom step name is required");
        }

        // Check if custom step exists and has professional assigned
        $stmt = $conn->prepare("
            SELECT stepNumber, professionalID 
            FROM work_in 
            WHERE projectID = ? 
            AND stepType = 'custom' 
            AND stepName = ?
            LIMIT 1
        ");
        $stmt->bind_param("is", $projectId, $stepName);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 0) {
            throw new Exception("Custom step '$stepName' not found in this project");
        }
        
        $row = $result->fetch_assoc();
        $actualStepNumber = (int)$row['stepNumber'];
        $professionalId = (int)$row['professionalID'];
        
        if ($professionalId <= 0) {
            throw new Exception("No professional assigned to custom step '$stepName'");
        }
    } else {
        // Handle both standard and custom steps that might be misclassified
        if ($stepNumber <= 0) {
            // Check if this is actually a custom step
            $stmt = $conn->prepare("
                SELECT professionalID 
                FROM work_in 
                WHERE projectID = ? 
                AND stepNumber = ?
                AND stepType = 'custom'
                LIMIT 1
            ");
            $stmt->bind_param("ii", $projectId, $stepNumber);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $professionalId = (int)$row['professionalID'];
                $actualStepNumber = $stepNumber;
                
                if ($professionalId <= 0) {
                    throw new Exception("No professional assigned to custom step #$stepNumber");
                }
            } else {
                throw new Exception("Step #$stepNumber is not a valid standard step (must be positive)");
            }
        } else {
            // Original standard step check
            $stmt = $conn->prepare("
                SELECT professionalID 
                FROM work_in 
                WHERE projectID = ? 
                AND stepNumber = ?
                AND stepType = 'standard'
                LIMIT 1
            ");
            $stmt->bind_param("ii", $projectId, $stepNumber);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows === 0) {
                throw new Exception("Standard step #$stepNumber not assigned in this project");
            }
            
            $row = $result->fetch_assoc();
            $professionalId = (int)$row['professionalID'];
            $actualStepNumber = $stepNumber;
            
            if ($professionalId <= 0) {
                throw new Exception("No professional assigned to step #$stepNumber");
            }
        }
    }
    $stmt->close();

    // Assign material to step
    $stmt = $conn->prepare("
        INSERT INTO work_materials (projectID, materialID, stepNumber, quantity, assigned_date, professionalID)
        VALUES (?, ?, ?, ?, CURRENT_DATE(), ?)
        ON DUPLICATE KEY UPDATE 
        quantity = quantity + VALUES(quantity),
        assigned_date = CURRENT_DATE()
    ");
    $stmt->bind_param("iiiii", $projectId, $materialId, $actualStepNumber, $quantity, $professionalId);
    
    if (!$stmt->execute()) {
        throw new Exception("Failed to assign material: " . $stmt->error);
    }

    ob_end_clean();
    echo json_encode([
        'success' => true,
        'message' => 'Material assigned successfully',
        'data' => [
            'materialId' => $materialId,
            'stepNumber' => $actualStepNumber,
            'stepName' => $isCustom ? $stepName : 'Step '.$actualStepNumber,
            'quantity' => $quantity,
            'professionalId' => $professionalId,
            'stepType' => $isCustom ? 'custom' : 'standard'
        ]
    ]);

} catch (Exception $e) {
    ob_end_clean();
    http_response_code($e->getCode() ?: 500);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage(),
        'error' => [
            'projectId' => $projectId ?? null,
            'materialId' => $materialId ?? null,
            'stepNumber' => $actualStepNumber ?? $stepNumber ?? null,
            'stepName' => $stepName ?? null,
            'isCustom' => $isCustom
        ]
    ]);
} finally {
    if (isset($stmt)) $stmt->close();
    $conn->close();
}
?>