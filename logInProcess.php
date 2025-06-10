<!-- Clarita Antoun -->
<?php
session_start();
session_unset();
session_destroy();
session_start();

include 'conx.php';
if($_SERVER['REQUEST_METHOD']=='POST'){
    if(empty($_POST['email']) && empty($_POST['password'])){
     
            $_SESSION['logIn_user_error']['email_password']="enter required fields";
            header('Location: logInPage.php');
            exit();
        
        
    }
    else{
$email=$_POST['email'];
$pass=$_POST['password'];


$emailParts=explode('@',$email);
if (count($emailParts) != 2) {
    $_SESSION['logIn_user_error']['email'] = "Invalid email format.";
    header('Location: logInPage.php');
    exit();
}
else{
    $domain = $emailParts[1];
    
    switch ($domain) {
        case 'admin.buildEase':
            $table = 'admin';
            break;
        case 'professional.buildEase':
            $table = 'professional';
            break;
        case 'homeowner.buildEase':
            $table = 'homeowner';
            break;
        case 'contractor.buildEase':
            $table = 'contractor';
            break;
        default:
            $_SESSION['logIn_user_error']['email'] = "Unknown email domain.";
            header('Location: logInPage.php');
            exit();
    }


    if(validateEmail($email,$table,$conn) && validatePassword($email,$table,$conn,$pass)){
        $sql="select id,fullName from $table where email='$email'";
        $res=$conn->query($sql);
        $row=$res->fetch_assoc();
        $userId=$row['id'];
        $userFullName=$row['fullName'];
switch ($domain) {
    case 'admin.buildEase':
 $_SESSION['admin_identity']['id']=$userId;
$_SESSION['admin_identity']['fullName']=$userFullName;
$_SESSION['admin_identity']['type']=$domain;
        header('Location: adminPage.php');
        exit();
        break;
    case 'professional.buildEase':
        $_SESSION['professional_identity']['id']=$userId;
        $_SESSION['professional_identity']['fullName']=$userFullName;
        $_SESSION['professional_identity']['type']=$domain;
        header('Location: professionalPage.php');
        exit();
        break;
    case 'homeowner.buildEase':
        $_SESSION['homeOwner_identity']['id']=$userId;
        $_SESSION['homeOwner_identity']['fullName']=$userFullName;
        $_SESSION['homeOwner_identity']['type']=$domain;
        header('Location: homeOwnerPage.php');
        exit();
        break;
    case 'contractor.buildEase':
        $_SESSION['contractor_identity']['id']=$userId;
        $_SESSION['contractor_identity']['fullName']=$userFullName;
        $_SESSION['contractor_identity']['type']=$domain;
        header('Location: contractorPage.php');
        exit();
        break;
}
    }
    else {
        header('Location: logInPage.php');
        exit();
    }
}
}


}




function validateEmail($email, $table, $conn) {
    // Admins do not have a status column
    if ($table === 'admin') {
        $sql = "SELECT * FROM $table WHERE email = ?";
    } else {
        $sql = "SELECT status FROM $table WHERE email = ?";
    }

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows > 0) {
        if ($table === 'admin') {
            return true;
        }
        $row = $res->fetch_assoc();
        $status = $row['status'];
        if ($status === 'accepted') {
            return true;
        } elseif ($status === 'pending') {
            $_SESSION['logIn_user_error']['email'] = "Your account is pending approval.";
        } elseif ($status === 'rejected') {
            $_SESSION['logIn_user_error']['email'] = "Your account was rejected.";
        } else {
            $_SESSION['logIn_user_error']['email'] = "Your account status is unknown.";
        }
    } else {
        $_SESSION['logIn_user_error']['email'] = "Email not found.";
    }

    return false;
}


function validatePassword($email,$table,$conn,$pass){
 $sql="select password from $table where email='$email' ";
 $res=$conn->query($sql);
 if($res->num_rows>0){
     $row = $res->fetch_assoc();
     $passDB = $row['password'];
         if (password_verify($pass,$passDB)) {
             return true;
        } 
         else{
             $_SESSION['logIn_user_error']['password']="Invalid password";
             return false;
         }
    
    
    
}
 else{
     $_SESSION['logIn_user_error']['email'] = "Email not found"; 
     return false;
     }

 }



 ?>