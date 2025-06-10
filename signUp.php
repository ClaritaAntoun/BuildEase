
<!-- Clarita Antoun -->
<?php
session_start();
include 'conx.php';
include("header.php");
$sql = "SELECT name FROM Step";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet"> <!-- Add Font Awesome -->
    <style>
        body {
            background: url('images/b5.jpeg') no-repeat center center fixed;
            background-size: cover;
        }
        .signup-container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background: rgba(255, 255, 255, 0.3);
            border-radius: 8px;
            box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.2);
            backdrop-filter: blur(10px);
        }
        .hidden { display: none; }
        .textColorH { color: black; font-size: 38px; }
        .textColor { color: black; font-size: 16px; font-style: italic; }
        .buttonDesign {
            width: 100%;
            padding: 12px;
            background: grey;
            color: white;
            font-size: 18px;
            font-weight: bold;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: background 0.3s ease-in-out, transform 0.2s ease-in-out;
        }
        .buttonDesign:hover { background: orangered; transform: scale(1.05); }
        .buttonDesign:active { background: orange; transform: scale(0.98); }
        input.form-control, textarea.form-control, select.form-control {
            background-color: transparent !important;
            color: black;
            border: 1px solid white;
        }
        input::placeholder, textarea::placeholder { color: rgba(0, 0, 0, 0.6); }
        .eye-icon {
            cursor: pointer;
            position: absolute;
            right: 10px;
            top: 70%;
            transform: translateY(-50%);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="signup-container">
            <h3 class="text-center textColorH"><u><b>Signup</b></u></h3>

            <?php
            if(isset($_SESSION['signUp_error'])){
                if(isset( $_SESSION['signUp_error']['email'])){
                    echo "<p class='text-danger text-center'>".$_SESSION['signUp_error']['email']."</p>" ;
                    unset($_SESSION['signUp_error']['email']);
                }
                if(isset( $_SESSION['signUp_error']['address'])){
                    echo "<p class='text-danger text-center'>".$_SESSION['signUp_error']['address']."</p>" ;
                    unset($_SESSION['signUp_error']['address']);
                }
                if(isset( $_SESSION['signUp_error']['emptyFields'])){
                    echo "<p class='text-danger text-center'>".$_SESSION['signUp_error']['emptyFields']."</p>" ;
                    unset($_SESSION['signUp_error']['emptyFields']);
                }
                if(isset($_SESSION['signUp_error']['password'])){
                    echo "<p class='text-danger text-center'>".$_SESSION['signUp_error']['password']."</p>" ;
                    unset($_SESSION['signUp_error']['password']);
                }
                if(isset( $_SESSION['signUp_error']['phoneNb'])){
                    echo "<p class='text-danger text-center'>".$_SESSION['signUp_error']['phoneNb']."</p>" ;
                    unset($_SESSION['signUp_error']['phoneNb']);
                }
                if(isset($_SESSION['signUp_error']['age'])){
                    echo "<p class='text-danger text-center'>".$_SESSION['signUp_error']['age']."</p>" ;
                    unset($_SESSION['signUp_error']['age']);
                }
            }
            ?>

            <form id="signupForm" method="POST" action="signUpProcess.php">
                <div class="mb-3">
                    <label for="fullName" class="form-label textColor"><b>Full Name</b></label>
                    <input type="text" class="form-control" id="fullName" name="fullName" placeholder="Enter your full name" >
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label textColor"><b>Email address</b></label>
                    <input type="text" class="form-control" id="email" name="email" placeholder="Enter your email address" >
                </div>
                <div class="mb-3 position-relative">
                    <label for="password" class="form-label textColor"><b>Password</b></label>
                    <input type="text" class="form-control" id="password" name="password" placeholder="Enter your password">
                    <i id="togglePassword" class="fas fa-eye eye-icon"></i> <!-- Eye Icon -->
                </div>
                <div class="mb-3">
                    <label for="address" class="form-label textColor"><b>Address</b></label>
                    <input type="text" class="form-control" id="address" name="address" 
                           placeholder="Street/City/State/PostalCode (separated by slashes)">
                    <small class="text-muted">Example: 123 Main St/Beirut/Beirut/12345</small>
                </div>
                <div class="mb-3">
                    <label for="phoneNumber" class="form-label textColor"><b>Phone Number</b></label>
                    <input type="text" class="form-control" id="phoneNumber" placeholder="+961 00/000 000" name="phoneNb" >
                </div>
                <div class="mb-3">
                    <label for="role" class="form-label textColor"><b>Select Role</b></label>
                    <select class="form-control" id="role" name="role" onchange="showRoleSpecificFields()">
                        <option value="" disabled selected>Select your role</option>
                        <option value="homeowner">HomeOwner</option>
                        <option value="professional">Professional</option>
                        <option value="contractor">Contractor</option>
                    </select>
                </div>

                <!-- Professional Fields -->
                <div id="professionalFields" class="hidden">
                    <div class="mb-3">
                        <label for="age" class="form-label textColor"><b>Age</b></label>
                        <input type="number" class="form-control" id="age" name="age" placeholder="Enter your age">
                    </div>
                   <div class="mb-3">
    <label for="areaOfWork" class="form-label textColor"><b>Area of Work</b></label>
    
    <!-- Dropdown -->
    <select class="form-control" id="areaOfWork" name="areaOfWork" onchange="toggleOtherInput(this)">
        <option value="" disabled selected>Select an Area of Work</option>
        <?php
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo '<option value="' . htmlspecialchars($row['name']) . '">' . htmlspecialchars($row['name']) . '</option>';
            }
        }
        ?>
        <option value="__other__">Other (please specify)</option>
    </select>
    <input type="text" class="form-control mt-2" id="otherAreaOfWork" name="otherAreaOfWork" placeholder="Enter new area of work" style="display: none;">
</div>

                </div>
                 
                <!-- Shared Fields for Professionals and Contractors -->
                <div id="sharedFields" class="hidden">
                    <div class="mb-3">
                        <label for="education" class="form-label textColor"><b>Educations</b></label>
                        <textarea class="form-control" id="education" rows="3" name="educations" placeholder="List your education background"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="experience" class="form-label textColor"><b>Experiences</b></label>
                        <textarea class="form-control" id="experience" rows="3" name="experiences" placeholder="Describe your work experience"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="skills" class="form-label textColor"><b>Skills</b></label>
                        <textarea class="form-control" id="skills" rows="3" name="skills" placeholder="List your skills"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="languages" class="form-label textColor"><b>Languages</b></label>
                        <select class="form-control" id="languages" multiple size="5" name="languages[]">
                            <option value="English">English</option>
                            <option value="Arabic">Arabic</option>
                            <option value="French">French</option>
                            <option value="Spanish">Spanish</option>
                            <option value="German">German</option>
                            <option value="Italian">Italian</option>
                            <option value="Chinese">Chinese</option>
                            <option value="Japanese">Japanese</option>
                            <option value="Russian">Russian</option>
                            <option value="Portuguese">Portuguese</option>
                        </select>
                        <small class="text-muted">Hold Ctrl (Windows) or Command (Mac) to select multiple.</small>
                    </div>
                    <div class="mb-3">
                        <label for="Certifications" class="form-label textColor"><b>Certifications</b></label>
                        <textarea class="form-control" id="certifications" rows="3" name="certifications"  placeholder="Mention your certifications"></textarea>
                    </div>
                </div>

                <button type="submit" class="buttonDesign"><b>Sign Up</b></button>
            </form>
        </div>
    </div>

    <script>
        // Toggle password visibility
        const togglePassword = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');

        togglePassword.addEventListener('click', function () {
            // Toggle the type attribute
            const type = passwordInput.type === 'password' ? 'text' : 'password';
            passwordInput.type = type;

            // Toggle the eye icon
            togglePassword.classList.toggle('fa-eye-slash');
        });

        function showRoleSpecificFields() {
            const role = document.getElementById('role').value;
            document.getElementById('professionalFields').classList.add('hidden');
            document.getElementById('sharedFields').classList.add('hidden');

            if (role === 'professional' || role === 'contractor') {
                document.getElementById('sharedFields').classList.remove('hidden');
            }
            if (role === 'professional') {
                document.getElementById('professionalFields').classList.remove('hidden');
            }
        }

        // Ensure the correct fields are shown when the page loads
        document.addEventListener("DOMContentLoaded", function () {
            showRoleSpecificFields(); // Call this to ensure fields are displayed correctly
        });
    </script>
    <script>
function toggleOtherInput(select) {
    const otherInput = document.getElementById('otherAreaOfWork');
    if (select.value === '__other__') {
        otherInput.style.display = 'block';
        otherInput.required = true;
    } else {
        otherInput.style.display = 'none';
        otherInput.required = false;
    }
}
</script>

</body>
</html>
<?php include("footer.php"); ?>
