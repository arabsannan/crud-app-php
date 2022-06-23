<?php 
session_start(); // to remember logged-in users

require "../db.php";



$errors = array();
$generalErr = $namepassErr = $activateErr= "";

if(isset($_POST['submitBtn'])){
    // Verify if fields have been filled
    if ( !isset($_POST['username'], $_POST['password']) ) {
       $errors['generalErr'] = "Please fill all fields";
    }

    // Check if account with username and password exists
    if ($stmt = $con->prepare("SELECT id, password, is_activated FROM accounts WHERE username = ?")) {
        // Supply username entered to SELECT statement
        $stmt->bind_param('s', $_POST['username']);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($id, $password, $is_activated);
            $stmt->fetch();

			if($is_activated===1){
				if (password_verify($_POST['password'], $password)) {
					// Verification success! User has logged-in!
					session_regenerate_id(); //create session to remember user
					$_SESSION['loggedin'] = TRUE;
					$_SESSION['name'] = $_POST['username'];
					$_SESSION['id'] = $id;
					header('Location: home.php');
            	} else {
					// Incorrect password
					$errors['namepassErr'] = 'Incorrect username or password!';
            	}
			}else{
				$_SESSION['not_activated_message'] ='Kindly check your email and validate your account first!';
				// $errors['activateErr'] ='Kindly check your email and validate your account first!';
			}        
        } else {
            // Incorrect username
            $errors['namepassErr'] = 'Incorrect username or password!';
        }

        $stmt->close();
    }
}

?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Login</title>
		<link href="https://unpkg.com/tailwindcss@^1.0/dist/tailwind.min.css" rel="stylesheet">
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
	</head>
	<body>
		<section class="h-screen">
			<div class="container px-6 py-12 h-full">
			  <div class="flex justify-center items- flex-wrap h-full g-6 text-gray-800">
				<div class="md:w-8/12 lg:w-6/12 mb-12 md:mb-0">
				  <img
					src="../assets/img/login_background.svg"
					class="w-full"
					alt="Phone image"
				  />
				</div>
				<div class="md:w-8/12 lg:w-5/12 lg:ml-20">
				  <h1 class="xl:text-4xl text-3xl text-center text-blue-800 font-extrabold pb-6 sm:w-4/6 w-5/6 mx-auto">SIGN IN</h1>
				   <!-- Display activation notice -->
				   <?php if(isset($_SESSION['activated_message']))
						echo '<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 mb-4 rounded relative" role="alert">
							<span class="block sm:inline">'.$_SESSION['activated_message'].'</span>
							</div>';
						unset($_SESSION['activated_message']);
					?>
					<!-- Display a not activated account notice -->
					<?php if(isset($_SESSION['not_activated_message']))
						echo'<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 mb-4 rounded relative" role="alert">
								<span class="block sm:inline">'.$_SESSION['not_activated_message'].'</span>
							</div>';
						unset($_SESSION['not_activated_message']);
					?>
					<!-- Display form errors -->
				   <?php if(isset($errors['namepassErr'])) 
					    echo "<p class='text-red-600 text-center pb-3 font-bold'>".$errors['namepassErr']."</p>" 
				    ?>
                  <form action='<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>' method="post">
					<!-- Username input -->
					<div class="mb-6">
					  <input
						type="text" name="username" <?php if (!empty($_POST['username'])) {echo "value=\"" . $_POST["username"] . "\"";} ?>
						class="form-control block w-full px-4 py-2 text-xl font-normal text-gray-700 bg-white bg-clip-padding border border-solid border-gray-300 rounded transition ease-in-out m-0 focus:text-gray-700 focus:bg-white focus:border-blue-600 focus:outline-none"
						id="username" placeholder="Username" required
					  /> 
					</div>
		  
					<!-- Password input -->
					<div class="mb-4">
					  <input
						type="password" name="password" 
						class="form-control block w-full px-4 py-2 text-xl font-normal text-gray-700 bg-white bg-clip-padding border border-solid border-gray-300 rounded transition ease-in-out m-0 focus:text-gray-700 focus:bg-white focus:border-blue-600 focus:outline-none"
						id="password" placeholder="Password" required
					  />
					</div>
		  
					<div class="flex justify-between items-center mb-2">
					  <a
						href="#!"
						class="text-blue-600 hover:text-blue-700 focus:text-blue-700 active:text-blue-800 duration-200 transition ease-in-out"
						>Forgot password?</a>
					</div>
		  
					<!-- Submit button -->
					<button
					  type="submit"
					  class="inline-block px-7 py-3 bg-blue-800 text-white font-medium text-sm leading-snug uppercase rounded shadow-md hover:bg-blue-700 hover:shadow-lg focus:bg-blue-700 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-blue-800 active:shadow-lg transition duration-150 ease-in-out w-full"
					  data-mdb-ripple="true"
					  data-mdb-ripple-color="light" name="submitBtn"
					>
					 SIGN IN
					</button>
				  </form>
				  <div class="flex flex-row mt-2">
					 <p>Don't have an account?</p>
					<a
							href="../pages/signup.php"
							class="ml-1 text-blue-600 hover:text-blue-700 focus:text-blue-700 duration-200 transition ease-in-out"
							>Create an account!</a
						>
				  </div>
					</div>
				</div>
			  </div>
			</div>
		  </section>
	</body>
</html>