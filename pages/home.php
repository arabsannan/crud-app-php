<?php
	session_start();
	// Redirect user to login page if not logged in
	if (!isset($_SESSION['loggedin'])) {
		header('Location: login.php');
		exit;
	}


$errors=array();
$emailErr = $passwordErr1 =$passwordErr2 ="";
$success=array();

require "../update.php";

$stmt = $con->prepare('SELECT image_path FROM accounts WHERE id = ? ');
$stmt->bind_param('s', $_SESSION['id']);	
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
	$stmt->bind_result($image_path);
	$stmt->fetch();
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">  
	<link href="https://unpkg.com/tailwindcss@^1.0/dist/tailwind.min.css" rel="stylesheet">
	<title>Home</title>
</head>
<body>	
	<div class="container flex items-center justify-center mx-auto">
		<div class="max-w-md py-4 px-8 bg-white shadow-lg rounded-lg my-20 mx-6">
			<h2 class="text-gray-800 text-center text-3xl font-semibold">Welcome back, <p class="capitalize inline"><?=$_SESSION['name']?></p>!</h2>
			<div>				
				<h1 class="text-xl text-center text-blue-800 font-bold py-2">UPDATE YOUR CREDENTIALS</h1>
				<!-- Success Message -->
				<?php  if (isset($_SESSION['success_message']) && !empty($_SESSION['success_message'])) 
					echo "<p class='text-green-600 text-center pb-3 font-bold'>".$_SESSION['success_message']."</p>";
					unset($_SESSION['success_message']);
				 ?>
				 <!-- Error Message -->
				 <?php  if (isset($_SESSION['imageErr']) && !empty($_SESSION['imageErr'])) 
					echo "<p class='text-red-600 text-center pb-3 font-bold'>".$_SESSION['imageErr']."</p>";
					unset($_SESSION['imageErr']);
				 ?>
				<!-- Update Form begins-->
				<form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" method="post" enctype="multipart/form-data">
					<!-- Profile Image -->	
					<?php if($image_path===NULL){
						echo '<div class="flex justify-center mb-6 mt-4">
							<input accept="image/*" type=file name="fileToUpload" id="fileToUpload">
							<img class="w-20 h-20 object-cover rounded-full border-2  border-blue-800" src="../assets/img/unknown_profile_img.png">
							</div>';
						}else{
							echo '<div class="flex justify-center mb-6 mt-4">
								<input accept="image/*" type=file name="fileToUpload" id="fileToUpload">
								<img class="w-20 h-20 object-cover rounded-full border-2  border-blue-800" src='.$image_path.'>
								</div>';
						}		
					?>
					<!-- Username input -->
					<div class="mb-6">
						<input
						type="text" name="username" value="<?=$_SESSION['name']?>"
						class="form-control block w-full px-4 py-2 text-xl font-normal text-gray-700 bg-white bg-clip-padding border border-solid border-gray-300 rounded transition ease-in-out m-0 focus:text-gray-700 focus:bg-white focus:border-blue-600 focus:outline-none"
						id="username" placeholder="Username" disabled
						/>
					</div>

					<!-- Email input -->
					<div class="mb-6">
						<input
						type="email" name="email" 
						class="form-control block w-full px-4 py-2 text-xl font-normal text-gray-700 bg-white bg-clip-padding border border-solid border-gray-300 rounded transition ease-in-out m-0 focus:text-gray-700 focus:bg-white focus:border-blue-600 focus:outline-none"
						id="email" placeholder="Email" value=<?php echo $email ?>
						/>
						<?php if(isset($errors['emailErr'])) 
						echo "<p class='text-red-600 font-bold'>".$errors['emailErr']."</p>" 
						?>
					</div>

					<h1 class="text-xl text-blue-800 font-bold py-2">Change Password</h1>			
					<!-- Password input -->
					<div class="mb-4">
						<input
						type="password" name="old_password" 
						class="form-control block w-full px-4 py-2 text-xl font-normal text-gray-700 bg-white bg-clip-padding border border-solid border-gray-300 rounded transition ease-in-out m-0 focus:text-gray-700 focus:bg-white focus:border-blue-600 focus:outline-none"
						id="password" placeholder="Enter old password" 
						/>
						<?php if(isset($errors['passwordErr1'])) 
						echo "<p class='text-red-600 font-bold'>".$errors['passwordErr1']."</p>" 
						?>
					</div>
					<div class="mb-4">
						<input
						type="password" name="new_password" 
						class="form-control block w-full px-4 py-2 text-xl font-normal text-gray-700 bg-white bg-clip-padding border border-solid border-gray-300 rounded transition ease-in-out m-0 focus:text-gray-700 focus:bg-white focus:border-blue-600 focus:outline-none"
						id="password" placeholder="Enter new password" 
						/>
						<?php if(isset($errors['passwordErr2'])) 
						echo "<p class='text-red-600 font-bold'>".$errors['passwordErr2']."</p>" 
						?>
					</div>
			
					<!-- Update button -->
					<button
						type="submit"
						class="inline-block px-7 py-3 bg-blue-800 text-white font-medium text-sm leading-snug uppercase rounded shadow-md hover:bg-blue-700 hover:shadow-lg focus:bg-blue-700 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-blue-800 active:shadow-lg transition duration-150 ease-in-out w-full"
						data-mdb-ripple="true"
						data-mdb-ripple-color="light" name="updateBtn"
					>
						Update
					</button>
				</form>		
			</div>
			<!-- Update Form Ends -->
			<div class="flex justify-between mt-4">
				<a href="gallery.php" class="text-blue-500 hover:underline font-bold">
					View gallery
				</a>
				<a href="logout.php">
					<button class="bg-blue-500 hover:bg-blue-800 text-white font-bold py-2 px-4 rounded-full">
					Log out
					</button>
				</a>
			</div>
		</div>
	</div>
</body>
</html>

