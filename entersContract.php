<!-- Clarita Antoun -->
<?php
session_start(); 
include 'conx.php';
if (!isset($_SESSION['admin_identity']['id'])) {
    header("Location: logInPage.php");
    exit();
}
if($_SERVER['REQUEST_METHOD']=='POST'){
    if(isset($_POST['contractorID'])){
        $contractorId=$_POST['contractorID']; 
        $sql="select * from contractor where id='$contractorId'";
        $res=$conn->query($sql);
        $row=$res->fetch_assoc();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enter Contract Details</title>
<style>
    body {
        background-color: #f5f5f5;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        color: #333;
        min-height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 20px;
    }
    
    .contract-form {
        width: 100%;
        max-width: 700px;
        background: white;
        padding: 40px;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        border: 1px solid #e0e0e0;
    }

    h2.form-title {
        color: #6B8E23; /* Olive green */
        font-size: 28px;
        text-align: center;
        margin-bottom: 30px;
        border-bottom: 2px solid #6B8E23;
        padding-bottom: 15px;
    }

    .form-group {
        margin-bottom: 20px;
    }

    label {
        display: block;
        margin-bottom: 8px;
        color: #556B2F; /* Darker olive */
        font-weight: 600;
    }

    input, textarea {
        width: 100%;
        padding: 12px;
        background: white;
        border: 1px solid #ddd;
        border-radius: 6px;
        color: #333;
        font-size: 16px;
        margin-top: 5px;
    }

    input:focus, textarea:focus {
        outline: none;
        border-color: #6B8E23;
        box-shadow: 0 0 0 2px rgba(107, 142, 35, 0.2);
    }

    input[type="date"] {
        background: white;
        color: #333;
    }

    textarea {
        resize: vertical;
        min-height: 120px;
    }

    button {
        width: 100%;
        padding: 14px;
        margin-top: 10px;
        background-color: #6B8E23; /* Olive green */
        color: white;
        font-weight: bold;
        font-size: 16px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        transition: background 0.3s;
    }

    button:hover {
        background-color: #556B2F; /* Darker olive green */
    }

    /* Go Back button */
    button[onclick="window.location.href='adminPage.php'"] {
        position: fixed;
        top: 15px;
        left: 15px;
        background-color: #6B8E23;
        color: white;
        padding: 10px 20px;
        font-size: 16px;
        font-weight: bold;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        z-index: 1000;
        width: auto;
    }

    button[onclick="window.location.href='adminPage.php'"]:hover {
        background-color: #556B2F;
    }

    /* Contractor name highlight */
    span[style="color: red;"] {
        color: #6B8E23 !important;
        font-weight: bold;
    }

    @media (max-width: 768px) {
        .contract-form {
            padding: 25px;
        }
        button[onclick="window.location.href='adminPage.php'"] {
            top: 10px;
            left: 10px;
            padding: 8px 15px;
            font-size: 14px;
        }
    }
</style>
</head>
<body>
    
<button onclick="window.location.href='adminPage.php'" style="
    position: fixed;
    top: 15px;
    left: 15px;
    background-color: grey;
    color: black;
    padding: 10px 20px;
    font-size: 16px;
    font-weight: bold;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    z-index: 1000;
    width: 300px;;
">
    ← Go Back
</button>
    <div class="contract-form">
    <h2 class="form-title">
    Enter Contract Details for 
    <span style="color: red;">
        <?php echo $row['fullName']; ?>
    </span>
</h2>


        <form action="saveContract.php" method="POST" onsubmit="return validateContractForm()">
            <input type="hidden" name="contractorID" value="<?php echo $contractorId; ?>">
            
            <div class="form-group">
                <label for="salary">Salary ($):</label>
                <input type="number" id="salary" name="salary" min="0" step="0.01" required placeholder="Enter annual salary">
            </div>

            <div class="form-group">
                <label for="startDate">Contract Start Date:</label>
                <input type="date" id="startDate" name="startDate" required>
            </div>

            <div class="form-group">
                <label for="endDate">Contract End Date:</label>
                <input type="date" id="endDate" name="endDate" required>
            </div>

            <div class="form-group">
                <label for="status">Contract Status:</label>
                <input type="text" id="status" name="status" placeholder="active or diactivated">
            </div>

            <div class="form-group">
                <label for="details">Contract Terms:</label>
                <textarea id="details" name="details" placeholder="Enter detailed contract terms and conditions..." required></textarea>
            </div>

            <button type="submit">Save Contract Details</button>
        </form>
    </div>

    <script>
        function validateContractForm() {
            const startDate = new Date(document.getElementById('startDate').value);
            const endDate = new Date(document.getElementById('endDate').value);
            
            if (startDate >= endDate) {
                alert('End date must be after start date.');
                return false;
            }
            
            if (document.getElementById('salary').value <= 0) {
                alert('Please enter a valid salary amount.');
                return false;
            }
            if (document.getElementById('status').value != "active" && document.getElementById('status').value != "deactivated" ) {
                alert('Please status should be active or deactivated');
                return false;
            }
            return true;
        }
    </script>
</body>
</html>
