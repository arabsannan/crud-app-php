<?php
    $target_dir = "../uploads/images/";
    $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
   
    $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

    // $imageErr = "";

    // Check if image file is a actual image or fake image
    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
    
    if($check) {
        // Check if file already exists
        if (file_exists($target_file)) {
            $_SESSION['imageErr'] = "Image already exists, please choose another or rename that image.";
        }
        // Check file size
        if ($_FILES["fileToUpload"]["size"] > 500000) {
           $_SESSION['imageErr'] = "Sorry, your file is too large, please choose an image less than 500kb.";
        }

        // Allow certain file formats
        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
             $_SESSION['imageErr'] = "Sorry, only JPG, JPEG and PNG files are allowed.";
           
        }

        if(empty($imageErr)){
            move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file);
            
            // Insert image into database
            $stmt = $con->prepare('UPDATE accounts SET image_path=? WHERE id =?');
            $stmt->bind_param('ss', $target_file, $_SESSION['id']);
            $stmt->execute();
            $stmt->close();

            $_SESSION['success_message'] = "Image updated successfully";
            // $imageSuccess = "Image uploaded successfully";
        } else {
            $_SESSION['imageErr'] = "Sorry, there was an error uploading your file.";
        }
    }else{
        $_SESSION['imageErr'] = "Please upload an image";
    }
?>