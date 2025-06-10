
<!-- Clarita Antoun -->
<?php
session_start();
include 'conx.php';
if($_SERVER['REQUEST_METHOD']=='POST'){
    if(empty($_POST['fullName'])||empty($_POST['email'])||empty($_POST['password'])
||empty($_POST['phoneNb'])||empty($_POST['role'])){

    $_SESSION['signUp_error']['emptyFields']="Enter required fields!";
    header('Location:signUp.php');
    exit();
}
else{



    function validateAge($age) {
        if ( $age < 18 || $age > 64) {
            return "Age must be between 18 and 64.";
        }
        return true;
    }
   
    function validateEmail($email,$conn,$fullName,$role){
      
        $fullName = trim($fullName);
        $partsSpace = array_filter(explode(" ", $fullName));  // Remove extra spaces
                if(count($partsSpace)<2){
                return "Full name must be name followed by last name seperated with a single space";
                    }
            $nameWithoutSpaces = strtolower(str_replace(" ", "", $fullName));
            $email = strtolower(trim($email));
            $parts = explode('@', $email);
            
            if (count($parts) !== 2 || strtolower($parts[1]) !== strtolower($role . ".buildEase") || 
            strpos(strtolower($parts[0]), $nameWithoutSpaces) !== 0|| !preg_match('/\d/', $parts[0])) {
                return "Email must be in the format: yourfullnamewithdigits@$role.buildEase";
            }
            $table = $conn->real_escape_string($role);
            $stmt = $conn->prepare("SELECT * FROM $table WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result(); 
            
            if ($result->num_rows > 0) { 
                return "Email already exists.";
            }
            
            $stmt->close();

        return true;
        }
        function validatePhoneNumber($phoneNb) {
           
            $phoneNb = preg_replace('/\D/', '', $phoneNb); // Remove any non-digit characters (like spaces, slashes, etc.)
            if (strlen($phoneNb) !== 8) {
                return "Invalid phone number. It must have exactly 8 digits.";
            }
            if (!is_numeric(substr($phoneNb, 0, 2))) {
                return "Invalid phone number format. The first two digits should be numeric.";
            }if (!is_numeric(substr($phoneNb, 2, 3))) {
                return "Invalid phone number format. The next three digits should be numeric.";
            }if (!is_numeric(substr($phoneNb, 5, 3))) {
                return "Invalid phone number format. The last three digits should be numeric.";
            }
            
            return true;
        }
        
        

        function passwordValidation($password){
            if(strlen($password)<8){
                return "Password must be at least 8 characters long.";
            }
            $hasDigit=false;
            $hasupper=false;
            $hasChar=false;
            $charsArray=['@','/','#','!'];
            for($i=0;$i<strlen($password);$i++){
                if(ctype_digit($password[$i])){
                    $hasDigit=true;
                }
                else if(ctype_upper($password[$i])){
                    $hasupper=true; 
                }
                 else if(in_array($password[$i],$charsArray)){
                    $hasChar=true;
                 }
            }
            if(!$hasChar){
                return "<br> Please password must have characters!";
            }
            if(!$hasDigit){
                return "<br> Please password must have digits!";
            }
        
            if(!$hasupper){
                return "<br> Please password must have  uppers!";
            }
        
        return true;
        }



        function insertCV($conn, $educations, $experiences, $skills, $languages, $certifications) {
            $stmt = $conn->prepare("INSERT INTO curriculum_vitae (educations, experiences, skills, languages, certifications) 
                                    VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sssss", $educations, $experiences, $skills, $languages, $certifications);
            
            if ($stmt->execute()) {
                return $conn->insert_id; // Return the newly inserted cvID
            } else {
                return false; // Insert failed
            }
        }
        



        function insertAddress($conn, $fullAddress) {
            // Split the address into parts
            $addressParts = explode("/", $fullAddress);
        
            if (count($addressParts) !== 4) {
                return false; // Invalid format
            }
        
            list($street, $city, $state, $postalCode) = $addressParts;
        
            // Prepare the SQL query
            $stmt = $conn->prepare("INSERT INTO address (street, city, state, postalCode) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $street, $city, $state, $postalCode);
        
            if ($stmt->execute()) {
                return $stmt->insert_id; // Return the new addressID
            } else {
               
                return false; // Error inserting address
            }
        }
        









    $fullName = $_POST['fullName'] ;
    $email = $_POST['email'];
    $password = $_POST['password'] ;
    $phoneNb = $_POST['phoneNb'] ;
    $address= $_POST['address'] ;
    $role = $_POST['role'] ;

    $emailValidation = validateEmail($email, $conn, $fullName,$role);
    $passwordValidation = passwordValidation($password);
    $phoneNbValidation=validatePhoneNumber($phoneNb);


    if($emailValidation===true&&$passwordValidation===true && $phoneNbValidation===true){
        $passwordh=password_hash($password,PASSWORD_DEFAULT);
        if($role=="homeowner"){

            $addressID = insertAddress($conn, $address);
            if ($addressID === false) {
                $_SESSION['sqlError'] = "Error inserting the address";
                header('Location: signUp.php');
                exit();
            }

        
$stmt = $conn->prepare("INSERT INTO homeowner (fullName, email, password, phoneNumber, addressID) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("ssssi", $fullName, $email, $passwordh, $phoneNb, $addressID);
$result = $stmt->execute();
            if($result===true){
                $sql="select id from homeowner order by id desc limit 1";
                $res=$conn->query($sql);
                $row=$res->fetch_assoc();
                $id=$row['id'];
                $_SESSION['homeOwner_identity']=   [
                "id"=>$id,
                "fullName"=>$fullName,
               
                ];
         header('Location:index.php');
         exit();
            }
            else{
                $_SESSION['sqlError']= "error inserting the HomeOwner";
                header('Location:signUp.php');
                exit();
            }
        }

        if ($role == "contractor") {
            if (empty($_POST['educations']) || empty($_POST['experiences']) || empty($_POST['skills'])
                || empty($_POST['languages']) || empty($_POST['certifications'])) {
                $_SESSION['signUp_error']['emptyFields'] = "Enter required fields!";
                header('Location:signUp.php');
                exit();
            }
        
            $educations = $_POST['educations'];
            $experiences = $_POST['experiences'];
            $skills = $_POST['skills'];
            $languages = implode(",", $_POST['languages']);
            $certifications = $_POST['certifications'];
        
            // Insert CV only once
            $cvID = insertCV($conn, $educations, $experiences, $skills, $languages, $certifications);
            if (!$cvID) {
                $_SESSION['sqlError'] = "Error inserting the CV info";
                header('Location:signUp.php');
                exit();
            }
            $addressID = insertAddress($conn, $address);

            if ($addressID === false) {
                $_SESSION['signUp_error']['address']="Invalid address format!";
                header('Location:signUp.php');
                exit();
            }
            // Insert contractor
            $stmt = $conn->prepare("INSERT INTO contractor (fullName, email, password, phoneNumber, cvID,addressID) 
                                    VALUES (?, ?, ?, ?, ?,?)");
            $stmt->bind_param("ssssii", $fullName, $email, $passwordh, $phoneNb, $cvID,$addressID);
            
            if ($stmt->execute()) {
                $contractor_id = $stmt->insert_id;
                $_SESSION['contractor_identity'] = [
                    "id" => $contractor_id,
                    "fullName" => $fullName,
                ];
                header('Location:index.php');
                exit();
            } else {
                $_SESSION['sqlError'] = "Error inserting the contractor";
                header('Location:signUp.php');
                exit();
            }
        }
        
    
        if ($role == "professional") {
            if (empty($_POST['age']) || empty($_POST['areaOfWork']) || empty($_POST['educations']) 
                || empty($_POST['experiences']) || empty($_POST['skills'])
                || empty($_POST['languages']) || empty($_POST['certifications'])) {
                $_SESSION['signUp_error']['emptyFields'] = "Enter required fields!";
                header('Location:signUp.php');
                exit();
            }
        
            $age = $_POST['age'];
            $areaOfWork = $_POST['areaOfWork'] === '__other__' ? trim($_POST['otherAreaOfWork']) : $_POST['areaOfWork'];

            $educations = $_POST['educations'];
            $experiences = $_POST['experiences'];
            $skills = $_POST['skills'];
            $languages = isset($_POST['languages']) ? implode(",", $_POST['languages']) : "";
            $certifications = $_POST['certifications'];
        
            $ageValidation = validateAge($age);
            if ($ageValidation !== true) {
                $_SESSION['signUp_error']['age'] = $ageValidation;
                header('Location:signUp.php');
                exit();
            }
        
            // Insert CV only once
            $cvID = insertCV($conn, $educations, $experiences, $skills, $languages, $certifications);
            if (!$cvID) {
                $_SESSION['sqlError'] = "Error inserting the CV info";
                header('Location:signUp.php');
                exit();
            }
            $addressID = insertAddress($conn, $address);

            if ($addressID === false) {
                $_SESSION['sqlError'] = "Error inserting the address";
                header('Location: signUp.php');
                exit();
            }
            
            $stmt = $conn->prepare("INSERT INTO professional (fullName, email, password, phoneNumber, age, addressID, cvID) 
            VALUES (?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("ssssiii", $fullName, $email, $passwordh, $phoneNb, $age, $addressID, $cvID);

if ($stmt->execute()) {
$professional_id = $stmt->insert_id;//It gets the auto-incremented professionalID that was just inserted into the professional table


$stmt2 = $conn->prepare("INSERT INTO professional_details (areaOfWork, startDate, availibilityStatus, price, priceDetails, professionalID)
                 VALUES (?, ?, ?, ?, ?, ?)");
$stmt2->bind_param("sssisi", $areaOfWork, $startDate, $availabilityStatus, $price, $priceDetails, $professional_id);
$startDate = null;
$availabilityStatus = null;
$price = null;
$priceDetails = null;

if ($stmt2->execute()) {
$_SESSION['professional_identity'] = [
"id" => $professional_id,
"fullName" => $fullName,
];
header('Location: index.php');
exit();
} else {
$_SESSION['sqlError'] = "Error inserting into professional_details";
header('Location: signUp.php');
exit();
}
} else {
$_SESSION['sqlError'] = "Error inserting into professional";
header('Location: signUp.php');
exit();
}

        }
        
    }
    else{
        if($emailValidation!==true){
            $_SESSION['signUp_error']['email']=$emailValidation;
            }
        if($passwordValidation!==true){
                $_SESSION['signUp_error']['password']=$passwordValidation;
            }
            if($phoneNbValidation!==true){
                $_SESSION['signUp_error']['phoneNb']=$phoneNbValidation;
            }


        
           header('Location:signUp.php');
           exit();
            $conn->close();
    }
   
   
}

}
?>