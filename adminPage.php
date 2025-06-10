<!-- CLARITA ANTOUN -->
<?php
session_start(); 
include 'conx.php';
if (!isset($_SESSION['admin_identity']['id'])) {
    header("Location: logInPage.php");
    exit();
}
$sql1 = "SELECT * FROM homeOwner  ";

$sql2 = "SELECT  p.*,  pd.areaOfWork,  AVG(f.rating) AS average_rating
FROM  professional p
LEFT JOIN professional_details pd ON p.id = pd.professionalID
LEFT JOIN cont_pro_feedback f ON p.id = f.professionalID
GROUP BY p.id
ORDER BY  average_rating DESC";

$sql3 = "SELECT c.*, AVG(f.rating) AS average_rating
FROM  contractor c
LEFT JOIN ho_cont_feedback f ON c.id = f.contractorID
GROUP BY  c.id
ORDER BY average_rating DESC";

$res1 = $conn->query($sql1);

$res2 = $conn->query($sql2);

$res3 = $conn->query($sql3);
?>


<!DOCTYPE html>
<html lang="en">
<head>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        
        :root {
            --primary-color: #2C3E50;
            --secondary-color: #1ABC9C;
            --accent-color: #E74C3C;
            --light-color: #ECF0F1;
            --dark-color: #2C3E50;
            --success-color: #27AE60;
            --warning-color: #F39C12;
            --danger-color: #E74C3C;
            --info-color: #3498DB;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
            overflow-x: hidden;
        }

       
        

        .navbar { padding: 15px 10px; background: #fff; border: none; margin-bottom: 20px; box-shadow: 1px 1px 3px rgba(0, 0, 0, 0.1); }
        .navbar-btn { box-shadow: none; outline: none !important; border: none; }

        .card { border: none; border-radius: 10px; box-shadow: 0 0.15rem 1.75rem 0 rgba(33, 40, 50, 0.15); margin-bottom: 2rem; }
        .card-header { background-color: #fff; border-bottom: 1px solid #e3e6f0; padding: 1rem 1.35rem; border-radius: 10px 10px 0 0 !important; }
        .card-body { padding: 1.5rem; }

        table { width: 100%; border-collapse: collapse; margin: 30px 0; font-size: 16px; font-family: Arial, sans-serif; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        thead tr { background-color: #009879; color: #ffffff; text-align: left; }
        th, td { padding: 12px 20px; border-bottom: 1px solid #dddddd; }
        tbody tr:nth-child(even) { background-color: #f3f3f3; }
        tbody tr:hover { background-color: #f1f1f1; }

        button { background-color: #28a745; border: none; color: white; padding: 6px 12px; margin: 2px; border-radius: 4px; cursor: pointer; }
        button:hover { opacity: 0.9; }
        h1 { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; font-size: 28px; color: #333; margin-top: 30px; margin-bottom: 20px; text-align: center; font-weight: bold; border-bottom: 2px solid #009879; padding-bottom: 10px; }
       
        /* Add these styles */
        .wrapper {
            display: flex;
            min-height: 100vh;
        }

        #sidebar {
            min-width: 250px;
            background: var(--primary-color);
            color: #fff;
            transition: all 0.3s;
        }

        #sidebar .sidebar-header {
            padding: 20px;
            background: var(--dark-color);
            text-align: center;
        }

        #sidebar ul.components {
            padding: 20px 0;
        }

        #sidebar ul li a {
            padding: 10px 15px;
            color: #fff;
            text-decoration: none;
            display: block;
            transition: all 0.3s;
        }

        #sidebar ul li a:hover {
            background: var(--secondary-color);
        }

        .content-section {
            display: none;
        }

        .content-section.active {
            display: block;
        }
        /* Wrapper for sidebar and content */
.wrapper {
    display: flex;
    min-height: 100vh;
    transition: all 0.3s;
}

/* Sidebar */
#sidebar {
    min-width: 250px;
    background: var(--primary-color);
    color: #fff;
    transition: all 0.3s;
    flex-shrink: 0;
}

/* When sidebar is collapsed */
#sidebar.active {
    width: 80px; /* Adjust for collapsed sidebar */
}

/* Content section */
#content {
    flex-grow: 1;
    padding: 20px;
    transition: all 0.3s;
}

/* Ensure that active content section has proper display */
.content-section {
    display: none;
}

.content-section.active {
    display: block;
}
/* Add this to your existing styles */
table {
    table-layout: fixed;
    width: 100%;
    font-size: 0.85rem; /* Slightly smaller font */
}

th, td {
    padding: 8px 10px; /* Reduced padding */
    word-break: break-word; /* Allow text wrapping */
}

/* Specific column adjustments */
th:nth-child(1), td:nth-child(1) { width: 40px; } /* # column */
th:nth-child(2), td:nth-child(2) { width: 60px; } /* User ID */
th:nth-child(3), td:nth-child(3) { width: 120px; } /* Full Name */
th:nth-child(4), td:nth-child(4) { width: 100px; } /* User Role */
th:nth-child(5), td:nth-child(5) { width: 100px; } /* Request Type */
th:nth-child(6), td:nth-child(6) { width: 180px; } /* New Value */
th:nth-child(7), td:nth-child(7) { width: 120px; } /* Requested At */
th:nth-child(8), td:nth-child(8) { 
    width: 150px; 
    white-space: nowrap; /* Keep buttons on one line */
} /* Actions */

/* Make buttons more compact */
table button {
    padding: 4px 8px;
    font-size: 0.8rem;
    margin: 2px;
}
td:nth-child(6) {
    overflow: hidden;
    text-overflow: ellipsis;
    max-width: 180px;
}

    </style>
</head>


<body>
    <div class="wrapper">
        <!-- Sidebar -->
        <nav id="sidebar">
            <div class="sidebar-header">
                <h3>Admin Dashboard</h3>
            </div>
            <ul class="list-unstyled">
                <li>
                    <a href="#homeowners" class="active" data-target="homeOwnerSection">
                        <i class="fas fa-home"></i> Homeowners
                    </a>
                </li>
                <li>
                    <a href="#professionals" data-target="professionalSection">
                        <i class="fas fa-user-tie"></i> Professionals
                    </a>
                </li>
                <li>
                    <a href="#contractors" data-target="contractorSection">
                        <i class="fas fa-tools"></i> Contractors
                    </a>
                </li>
            </ul>
        </nav>

        <div id="content">
        <div id="content">
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container-fluid">
                <button type="button" id="sidebarCollapse" class="btn btn-info">
                    <i class="fas fa-align-left"></i>
                </button>
                <div class="d-flex align-items-center">
                    <div class="dropdown ms-3">
                        <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-user-circle me-1"></i> Admin User
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <li><a class="dropdown-item" href="#">
                                <?php echo isset($_SESSION['admin_identity']['fullName']) ? ucwords(strtolower($_SESSION['admin_identity']['fullName'])) : 'Profile'; ?>
                            </a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="logOut.php">SignOut</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </nav>


    
        <?php
$stmt = $conn->prepare("SELECT * FROM change_requests WHERE status = ? ORDER BY created_at DESC");
$status = 'pending';
$stmt->bind_param("s", $status);
$stmt->execute();
$result = $stmt->get_result();

$counter = 1;
?>

<h1>Request for changing email/password:</h1>

<table>
    <thead>
        <tr>
            <th>#</th>
            <th>User ID</th>
            <th>Full Name</th>
           
            <th>User Role</th>
            <th>Request Type</th>
            <th>New Value</th>
            <th>Requested At</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php
        while ($row = $result->fetch_assoc()) { ?>
          <?php
            $full_name = "Unknown";
            
          
            if ($row['user_role'] == 'homeowner') {
                $stmt = $conn->prepare("SELECT fullName FROM homeowner WHERE id = ?");
                $stmt->bind_param("i", $row['user_id']);
                $stmt->execute();
                $user = $stmt->get_result()->fetch_assoc();
                $full_name = $user['fullName'] ?? $full_name;
            }
          
            elseif ($row['user_role'] == 'contractor') {
                $stmt = $conn->prepare("SELECT fullName FROM contractor WHERE id = ?");
                $stmt->bind_param("i", $row['user_id']);
                $stmt->execute();
                $user = $stmt->get_result()->fetch_assoc();
                $full_name = $user['fullName'] ?? $full_name;
            }
       
            else {
                $stmt = $conn->prepare("SELECT fullName FROM professional WHERE id = ?");
                $stmt->bind_param("i", $row['user_id']);
                $stmt->execute();
                $user = $stmt->get_result()->fetch_assoc();
                $full_name = $user['fullName'] ?? $full_name;
            }
            ?>
        <tr>
            <td><?php echo    $counter++.')'; ?></td>
            <td><?php echo htmlspecialchars($row['user_id']); ?></td>
            <td><?php echo htmlspecialchars($full_name); ?></td>
            <td><?php echo htmlspecialchars($row['user_role']); ?></td>
            
            <td><?php echo htmlspecialchars($row['request_type']); ?></td>
            <td><?php echo htmlspecialchars($row['new_value']); ?></td>
            <td><?php echo htmlspecialchars($row['created_at']); ?></td>
            <td>
                <form method="POST" action="handleRequest.php" style="display:inline;">
                    <input type="hidden" name="requestId" value="<?php echo $row['id']; ?>">
                    <input type="hidden" name="action" value="approve">
                    <button type="submit">Accept</button>
                </form>
                <form method="POST" action="handleRequest.php" style="display:inline;">
                    <input type="hidden" name="requestId" value="<?php echo $row['id']; ?>">
                    <input type="hidden" name="action" value="reject">
                    <button type="submit">Reject</button>
                </form>
            </td>
        </tr>
        <?php } ?>
    </tbody>
</table>

<?php
$stmt->close();
?>

            <!-- Content Sections -->
            <div id="homeOwnerSection" class="content-section">
            <h1>Manage Homeowners:</h1>
            <table>
               

<table>
<thead>
    <tr>
    <th>#</th> <th>ID</th><th>Full Name</th><th>Email</th><th>Phone</th><th>Status</th><th>Actions</th>
    </tr>
</thead>
<tbody>
<?php 
$counter=1;
while($row1 = $res1->fetch_assoc()) { ?>
    <tr>
    <td><?php echo    $counter++.')'; ?></td>
        <td><?php echo $row1['id']; ?></td>
        <td><?php echo $row1['fullName']; ?></td>
        <td><?php echo $row1['email']; ?></td>
        <td><?php echo $row1['phoneNumber']; ?></td>
        <td><span class="badge bg-success"><?php echo $row1['status']; ?></span></td>
        <td>
            <?php
            if ($row1['status'] === 'pending') {
                echo "
                <form method='POST' action='updateStatus.php' style='display:inline;'>
                    <input type='hidden' name='homeOwnerID' value='{$row1['id']}'>
                    <input type='hidden' name='status' value='accepted'>
                    <button type='submit'>Accept</button>
                </form>
                <form method='POST' action='updateStatus.php' style='display:inline;'>
                    <input type='hidden' name='homeOwnerID' value='{$row1['id']}'>
                    <input type='hidden' name='status' value='rejected'>
                    <button type='submit'>Reject</button>
                </form>";
            }
            if ($row1['status'] === 'accepted') {
                echo "<form method='POST' action='updateStatus.php' style='display:inline;'>
                        <input type='hidden' name='homeOwnerID' value='{$row1['id']}'>
                        <input type='hidden' name='status' value='removed'>
                        <button type='submit'>Remove</button>
                      </form>
                      ";
            }
          
            ?>
        </td>
    </tr>
<?php } ?>
</tbody>
</table>
        

            </div>

            <div id="professionalSection" class="content-section">
            <h1>Manage Professionals:</h1>
            <table>
               

<table>
<thead>
    <tr>
    <th>#</th>  <th>ID</th><th>Full Name</th><th>Email</th><th>Phone</th><th>Area of work</th><th>Average rating</th><th>CV</th><th>Status</th><th>Actions</th>
    </tr>
</thead>
<tbody>
<?php
$counter=1;
while($row2 = $res2->fetch_assoc()) { ?>
    <tr>
    <td><?php echo    $counter++.')'; ?></td>
        <td><?php echo $row2['id']; ?></td>
        <td><?php echo $row2['fullName']; ?></td>
        <td><?php echo $row2['email']; ?></td>
        <td><?php echo $row2['phoneNumber']; ?></td>
        <td><?php echo $row2['areaOfWork']; ?></td>
        <td><?php echo $row2['average_rating'] ? "⭐" . round($row2['average_rating'], 1) . "⭐" : "<span class='text-danger'>No feedback yet</span>"; ?></td>
        <td><a href="<?php echo 'viewCv.php?role=professional&professionalID=' . $row2['id']; ?>">View CV</a></td>
        <td><span class="badge bg-success"><?php echo $row2['status']; ?></span></td>
        <td>
            <?php
            if ($row2['status'] === 'pending') {
                echo "<form method='POST' action='updateStatus.php' style='display:inline;'>
                        <input type='hidden' name='professionalID' value='{$row2['id']}'>
                        <input type='hidden' name='status' value='accepted'>
                        <button type='submit'>Accept</button>
                      </form>
                      <form method='POST' action='updateStatus.php' style='display:inline;'>
                        <input type='hidden' name='professionalID' value='{$row2['id']}'>
                        <input type='hidden' name='status' value='rejected'>
                        <button type='submit'>Reject</button>
                      </form>";
            }
            if ($row2['status'] === 'accepted') {
                echo "<form method='POST' action='updateStatus.php' style='display:inline;'>
                        <input type='hidden' name='professionalID' value='{$row2['id']}'>
                        <input type='hidden' name='status' value='removed'>
                        <button type='submit'>Remove</button>
                      </form>";
            }
           
            ?>
        </td>
    </tr>
<?php } ?>
</tbody>
</table>
            </table>
            </div>

            <div id="contractorSection" class="content-section">
            <h1>Manage Contractors:</h1>
            <table>
               

<table>
<thead>
    <tr>
       <th>#</th> <th>ID</th><th>Full Name</th><th>Email</th><th>Phone</th><th>Average Rating</th><th> CV</th><th>Status</th><th>Actions</th>
    </tr>
</thead>
<tbody>
<?php 
$counter=1;
while($row3 = $res3->fetch_assoc()) { ?>
    <tr>
    <td><?php echo    $counter++.')'; ?></td>
        <td><?php echo $row3['id']; ?></td>
        <td><?php echo $row3['fullName']; ?></td>
        <td><?php echo $row3['email']; ?></td>
        <td><?php echo $row3['phoneNumber']; ?></td>
        <td><?php echo $row3['average_rating'] ? "⭐" . round($row3['average_rating'], 1) . "⭐" : "<span class='text-danger'>No feedback yet</span>"; ?></td>
        <td><a href="<?php echo 'viewCv.php?role=contractor&contractorID=' . $row3['id']; ?>">View CV</a></td>
        <td><span class="badge bg-success"><?php echo $row3['status']; ?></span></td>
        <td>
            <?php
            if ($row3['status'] === 'pending') {
                echo "<form method='POST' action='updateStatus.php' style='display:inline;'>
                        <input type='hidden' name='contractorID' value='{$row3['id']}'>
                        <input type='hidden' name='status' value='accepted'>
                        <button type='submit'>Accept</button>
                      </form>
                      <form method='POST' action='updateStatus.php' style='display:inline;'>
                        <input type='hidden' name='contractorID' value='{$row3['id']}'>
                        <input type='hidden' name='status' value='rejected'>
                        <button type='submit'>Reject</button>
                      </form>";
            }
            if ($row3['status'] === 'accepted') {
                echo "<form method='POST' action='updateStatus.php' style='display:inline;'>
                        <input type='hidden' name='contractorID' value='{$row3['id']}'>
                        <input type='hidden' name='status' value='removed'>
                        <button type='submit'>Remove</button>
                      </form>";
              
                    echo "<form method='POST' action='entersContract.php' style='display:inline;'>
                            <input type='hidden' name='contractorID' value='{$row3['id']}'>
                            <button type='submit'>Contract Details</button>
                          </form>";
                
            }
            
           
            ?>
        </td>
    </tr>
<?php } ?>
</tbody>
</table>
            </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>


document.querySelectorAll('#sidebar a').forEach(link => {
    link.addEventListener('click', function(e) {
        e.preventDefault();

        document.querySelectorAll('.content-section').forEach(section => {
            section.style.display = 'none';
        });

     
        const targetId = this.getAttribute('data-target');
        document.getElementById(targetId).style.display = 'block';
    });
});



</script>
</body>
</html>
