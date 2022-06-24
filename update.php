<?php

require "../db.php";

    $stmt = $con->prepare('SELECT email, password FROM accounts WHERE id = ? ');
    $stmt->bind_param('s', $_SESSION['id']);	
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($email, $password);
        $stmt->fetch();
    }

    if(isset($_POST['updateBtn'])){
        //Check if profile image has been updated
        // var_dump($_FILES);
        if(isset($_FILES['fileToUpload']) && isset($_FILES['fileToUpload']['tmp_name']) && !empty($_FILES['fileToUpload']['tmp_name'])){
            require 'upload.php';
        }

        // Check if email has been updated
        if($email!==$_POST['email']){
            if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) { // Email Validation
                $errors['emailErr'] = "Email is not valid";	
            }

            if (empty($errors)){
                $stmt = $con->prepare('UPDATE accounts SET email=? WHERE id =?');
                $stmt->bind_param('ss', $_POST['email'], $_SESSION['id']);
                $stmt->execute();
                $stmt->close();
                $_SESSION['success_message'] = "Email updated successfully";
                header('Location: home.php');
                exit();
            }
        }

        // Check if password has been updated
        if(!empty($_POST['new_password'])){
            if (!password_verify($_POST['old_password'], $password)){ // Old password Validation
                $errors['passwordErr1'] = "Password entered is in correct";	
            }
            if (strlen($_POST['new_password']) > 20 || strlen($_POST['new_password']) < 5) { // New Password Validation
                $errors['passwordErr2'] = "Password must be between 5 and 20 characters long!";	
            }

            if (empty($errors)){
                echo "looolll";
                $stmt = $con->prepare('UPDATE accounts SET password=? WHERE id =?');
                $password = password_hash($_POST['new_password'], PASSWORD_DEFAULT);
                $stmt->bind_param('ss', $password, $_SESSION['id']);
                $stmt->execute();
                echo "pooooll";
                $stmt->close();
                $_SESSION['success_message'] = "Password updated successfully";
                header('Location: home.php');
                exit();
            }
        }		
    }

?>