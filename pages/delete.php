<?php
require "../db.php";

$msg = '';
// Check that the image ID exists
if (isset($_GET['id'])) {
    // Select the record that is going to be deleted
    $stmt = $con->prepare('SELECT * FROM gallery WHERE id = ?');
    $stmt->execute([ $_GET['id'] ]);
    $stmt->bind_result($id, $username, $image_path, $uploaded_date);
    $image = $stmt->fetch();
       
    if (!$image) {
        exit('Image doesn\'t exist with that ID!');
    }
    $stmt->close();
    // Make sure the user confirms before deletion
    if (isset($_GET['confirm'])) {
        if ($_GET['confirm'] == 'yes') {
            // User clicked the "Yes" button, delete file & delete record
            $stmt = $con->prepare('DELETE FROM gallery WHERE id = ?');
            $stmt->execute([ $_GET['id'] ]);
            unlink($image_path);
            // Output msg
            $stmt->close();
            $_SESSION['delete_message'] = 'Image deleted successfully';
            header('Location: gallery.php');
            
            // $msg = 'You have deleted the image!';
        } else {
            // User clicked the "No" button, redirect them back to the home/index page
            header('Location: gallery.php');
            exit;
        }
    }
} else {
    exit('No ID specified!');
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">  
	<link href="https://unpkg.com/tailwindcss@^1.0/dist/tailwind.min.css" rel="stylesheet">
	<title>Delete</title>
</head>
<body>	
    <!-- Deletion modal -->
    <div class="w-full md:w-1/3 mx-auto mt-20">
        <div class="flex flex-col p-5 rounded-lg shadow bg-white">
            <div class="flex">
                <div>
                    <svg class="w-6 h-6 fill-current text-red-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M12 5.99L19.53 19H4.47L12 5.99M12 2L1 21h22L12 2zm1 14h-2v2h2v-2zm0-6h-2v4h2v-4z"/></svg>
                </div>

                <div class="ml-3">
                    <h2 class="font-semibold text-gray-800">Are you sure you want to delete this image?</h2>
                    <p class="mt-2 text-sm text-gray-600 leading-relaxed">This action is irreversible!</p>
                </div>
            </div>
            <div class="flex justify-center mt-3">
                <a href="delete.php?id=<?=$id?>&confirm=no">
                    <button class="flex-1 px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-800 text-sm font-medium rounded-md">
                        Cancel
                    </button>
                </a>
                <a href="delete.php?id=<?=$id?>&confirm=yes">
                    <button class="flex-1 px-4 py-2 ml-6 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-md">
                        Delete
                    </button>
                </a>
            </div>
        </div>
    </div>
</body>
</html>