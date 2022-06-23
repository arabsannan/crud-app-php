<?php
session_start();
require "../db.php";

$errors= array();
$generalErr=$usernameErr=$passwordErr=$emailErr=$existsErr="";


if(isset($_POST['submitBtn'])){	
	if (!isset($_POST['username'], $_POST['password'], $_POST['email'])) { // Check if form fields are filled
		$errors['general'] = "Please complete the registration form!";
	}
	if (empty($_POST['username']) || empty($_POST['password']) || empty($_POST['email'])) {
		$errors['general'] = "Please complete the registration form!";		
	}
	if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) { // Email Validation
		$errors['emailErr'] = "Email is not valid";	
	}
	if (strlen($_POST['password']) > 20 || strlen($_POST['password']) < 5) { // Password Validation
		$errors['passwordErr'] = "Password must be between 5 and 20 characters long!";	
	}
	if (preg_match('/^[a-zA-Z0-9]+$/', $_POST['username']) == 0) { // Username Validation
		$errors['usernameErr'] = "Please enter a valid username";
	}else{
		$stmt = $con->prepare('SELECT id, password FROM accounts WHERE username = ?');  // Check if account with username already exists
		$stmt->bind_param('s', $_POST['username']);
		$stmt->execute();
		$stmt->store_result();
		
		if ($stmt->num_rows > 0) {
			// Username already exists
			$errors['existsErr'] = 'Username already exists!';	
		}else{
			// Add new account if username doesn't exists
			if ($stmt = $con->prepare('INSERT INTO accounts (username, password, email, activation_code) VALUES (?, ?, ?, ?)')) {
				$uniquid = uniqid();
				$password = password_hash($_POST['password'], PASSWORD_DEFAULT);
				$stmt->bind_param('ssss', $_POST['username'], $password, $_POST['email'], $uniquid);
				$stmt->execute();

				require '../mail_activate.php';

				$notice ="Kindly check your email and activate your account";
				$_SESSION['activated_message'] = $notice ;
				header('Location: login.php');			
			} else {
				exit ('Could not prepare statement! ');
			}
		}
		$stmt->close();
	}	
}

?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Sign Up</title>
		<link href="https://unpkg.com/tailwindcss@^1.0/dist/tailwind.min.css" rel="stylesheet">
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
	</head>
	<body>
		<section class="h-screen overflow-hidden">
			<div class="container px-6 py-12 h-full">
				<div class="flex justify-center items-center flex-wrap h-full g-6 text-gray-800">
					<div class="md:w-8/12 lg:w-6/12 mb-12 md:mb-0">
						<img
						src="../assets/img/signup_background.svg"
						class="w-full"
						alt="Phone image"
						/>
					</div>
					<div class="md:w-8/12 lg:w-5/12 lg:ml-20">
						<h1 class="xl:text-4xl text-3xl text-center text-blue-800 font-extrabold pb-6 sm:w-4/6 w-5/6 mx-auto">SIGN UP</h1>
						<?php if(isset($errors['generalErr'])) 
						echo "<p class='text-red-600 text-center pb-3 font-bold'>".$errors['generalErr']."</p>" 
						?>
						<?php if(isset($errors['existsErr'])) 
						echo "<p class='text-red-600 text-center pb-3 font-bold'>".$errors['existsErr']."</p>" 
						?>
						<form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" method="post">
						<!-- Username input -->
						<div class="mb-6">
							<input
							type="text" name="username" <?php if (!empty($_POST['username'])) {echo "value=\"" . $_POST["username"] . "\"";} ?>
							class="form-control block w-full px-4 py-2 text-xl font-normal text-gray-700 bg-white bg-clip-padding border border-solid border-gray-300 rounded transition ease-in-out m-0 focus:text-gray-700 focus:bg-white focus:border-blue-600 focus:outline-none"
							id="username" placeholder="Username" required
							/>
							<?php if(isset($errors['usernameErr'])) 
							echo "<p class='text-red-600 font-bold'>".$errors['usernameErr']."</p>" 
							?>
						</div>

						<!-- Email input -->
						<div class="mb-6">
							<input
							type="email" name="email" <?php if (!empty($_POST['email'])) {echo "value=\"" . $_POST["email"] . "\"";} ?>
							class="form-control block w-full px-4 py-2 text-xl font-normal text-gray-700 bg-white bg-clip-padding border border-solid border-gray-300 rounded transition ease-in-out m-0 focus:text-gray-700 focus:bg-white focus:border-blue-600 focus:outline-none"
							id="email" placeholder="Email" required
							/>
							<?php if(isset($errors['emailErr'])) 
							echo "<p class='text-red-600 font-bold'>".$errors['emailErr']."</p>" 
							?>
						</div>
				
						<!-- Password input -->
						<div class="mb-4">
							<input
							type="password" name="password"
							class="form-control block w-full px-4 py-2 text-xl font-normal text-gray-700 bg-white bg-clip-padding border border-solid border-gray-300 rounded transition ease-in-out m-0 focus:text-gray-700 focus:bg-white focus:border-blue-600 focus:outline-none"
							id="password" placeholder="Password" required
							/>
							<?php if(isset($errors['passwordErr'])) 
							echo "<p class='text-red-600 font-bold'>".$errors['passwordErr']."</p>" 
							?>
						</div>
				
						<!-- Submit button -->
						<button
							type="submit"
							class="inline-block px-7 py-3 bg-blue-800 text-white font-medium text-sm leading-snug uppercase rounded shadow-md hover:bg-blue-700 hover:shadow-lg focus:bg-blue-700 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-blue-800 active:shadow-lg transition duration-150 ease-in-out w-full"
							data-mdb-ripple="true"
							data-mdb-ripple-color="light" name="submitBtn"
						>
							SIGN UP
						</button>
						</form>
						<div class="flex flex-row mt-2">
							<p>Already got an account?</p>
						<a
								href="../pages/login.php"
								class="ml-1 text-blue-600 hover:text-blue-700 focus:text-blue-700 duration-200 transition ease-in-out"
								>Log in</a
							>
						</div>
						</div>
					</div>
				</div>
			</div>
		</section>
	</body>
</html>