<?php
session_start();
// Include the database configuration file 
require_once '../db.php'; 

// File upload configuration  
$allowTypes = array('jpg','png','jpeg', 'jfif');  
$target_dir = "../uploads/images/";
$imageErr = $imageSuccess = "";

if(isset($_POST['addPhotoBtn'])){ 
    $fileNames = array_filter($_FILES['images']['name']); 
    if(!empty($fileNames)){ 
        foreach($_FILES['images']['name'] as $key=>$val){ 
            // File upload path 
            $check = getimagesize($_FILES["images"]["tmp_name"][$key]);
            $fileName = basename($_FILES['images']['name'][$key]); 
            $targetFilePath = $target_dir . $fileName;
            $imageFileType = strtolower(pathinfo($targetFilePath,PATHINFO_EXTENSION));


            if($check) {
                // Check if file already exists
                if (file_exists($targetFilePath)) {
                    $imageErr = "Image already exists, please choose another or rename that image.";
                }
                // Check file size
                if ($_FILES["images"]["size"][$key] > 1000000) {
                    $imageErr = "Sorry, your file is too large, please choose an image less than 1mb.";
                }              
                // Check whether file type is valid           
                if(!(in_array($imageFileType, $allowTypes))){               
                    $imageErr = "Sorry, only JPG, JPEG and PNG files are allowed.";          
                }
                // If there are no errors:        
                if(empty($imageErr)){
                    // Move image into folder 
                    if(move_uploaded_file($_FILES["images"]["tmp_name"][$key], $targetFilePath)){ 
                        // Insert image into database
                        $stmt = $con->prepare('INSERT INTO gallery (username, image_path, uploaded_date) VALUES (?,  ?, CURRENT_TIMESTAMP)');
                        $stmt->bind_param('ss', $_SESSION['name'], $targetFilePath);
                        $stmt->execute();
                        $stmt->close();
                        $imageSuccess = "Images uploaded successfully";
                    }else{
                        // $imageErr = "Sorry, there was an error uploading your file.";
                        $imageErr = "Sorry, couldn't place image in folder during file upload.";
                    }                
                } 
            }else{
                $imageErr = "Please upload an image";
            }
        }
    }else{ 
        $imageErr = 'Please select an image to upload.'; 
    } 
} 
 

//Display images
$stmt = $con->prepare('SELECT id, image_path FROM gallery WHERE username = ? ORDER BY uploaded_date DESC'); 
$stmt->bind_param('s', $_SESSION['name']);
$stmt->execute();
$stmt->store_result();


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://unpkg.com/tailwindcss@^1.0/dist/tailwind.min.css" rel="stylesheet">
	<title>Gallery</title>
</head>
<body>
        
    <!-- Gallery images -->
    <section class="overflow-hidden text-gray-700 ">
        <div class="container px-5 py-2 mx-auto lg:pt-6 lg:px-32">
            <div class="grid grid-cols-3 ">
                <a href="home.php" class="text-blue-500 hover:underline font-medium">
					Back to home 
				</a>
                <h1 class="text-xl text-center text-blue-800 font-bold py-6">YOUR GALLERY</h1>
            </div>
            <?php if(isset($_SESSION['delete_message']))
                echo '<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 mb-4 rounded relative" role="alert">
                    <span class="block sm:inline">'.$_SESSION["delete_message"].'</span>
                    </div>';
                // unset($_SESSION['activated_message']);
            ?>
            <!-- Display successful upload message -->
            <?php if(!empty($imageSuccess))
                echo '<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 mb-4 rounded relative" role="alert">
                    <span class="block sm:inline">'.$imageSuccess.'</span>
                    </div>';
                // unset($_SESSION['activated_message']);
            ?>
            <!-- Display unsuccessful upload message -->
            <?php if(!empty($imageErr))
                echo'<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 mb-4 rounded relative" role="alert">
                        <span class="block sm:inline">'.$imageErr.'</span>
                    </div>';
                // unset($_SESSION['not_activated_message']);
            ?>
            <div class="flex flex-wrap -m-1 md:-m-2 overflow-auto">
                <?php if ($stmt->num_rows > 0) {
                        $stmt->bind_result($id, $image_path);
        
                        while($stmt->fetch()){                          
                            echo '<div class="flex flex-wrap w-1/4" >
                                    <div class="w-full p-1 md:p-2" >
                                        <a href="delete.php?id='.$id.'" >
                                            <img alt="gallery" " class="block object-cover object-center w-full h-full rounded-lg"
                                                src="'.$image_path.'">
                                        </a>
                                    </div>
                                </div>';
                        }
                    }else{
                        $noImageInGallery = "No image in Gallery";
					    echo "<p class='text-red-600 text-center pb-3 font-bold'>".$noImageInGallery."</p>"; 

                    }
                     
                ?>               
            </div>
            <!-- Add a photo button -->
            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" method="post" enctype="multipart/form-data">
                <input type="file" name="images[]" multiple >
                <button
                    type="submit"
                    class="inline-block mt-7 py-3 px-3 my-4 bg-blue-800 text-white font-medium text-sm leading-snug uppercase rounded shadow-md hover:bg-blue-700 hover:shadow-lg focus:bg-blue-700 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-blue-800 active:shadow-lg transition duration-150 ease-in-out"
                    data-mdb-ripple="true"
                    data-mdb-ripple-color="light" name="addPhotoBtn"
                >
                    Add a new photo
                </button>
            </form>
        </div>        
    </section>
  

    
    <!-- Javascript -->
    <script>

    </script>

</body>
</html>