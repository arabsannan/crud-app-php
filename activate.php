<?php
session_start();

require "db.php";

// First we check if the email and code exists...
if (isset($_GET['email'], $_GET['code'])) {
	if ($stmt = $con->prepare('SELECT * FROM accounts WHERE email = ? AND activation_code = ? AND is_activated = ?')) {
		$val = 0;
		$stmt->bind_param('ssi', $_GET['email'], $_GET['code'], $val);
		$stmt->execute();
		$stmt->store_result();

		if ($stmt->num_rows > 0) {
			// Account exists with the requested email and code.
			if ($stmt = $con->prepare('UPDATE accounts SET is_activated = ? WHERE email = ? AND activation_code = ?')) {
				// Set is_activated to true
				$newcode = true;
				$stmt->bind_param('sss', $newcode, $_GET['email'], $_GET['code']);
				$stmt->execute();
				$stmt->close();
				
				$notice = "Your account is now activated! You can login now.";
				$_SESSION['activated_message'] = $notice;
				header('Location: pages/login.php');			

				
			}
		} else {
			// session_regenerate_id();
			$notice = 'Your account is already activated. Kindly login.';
			$_SESSION['activated_message'] = $notice; 
			header('Location: pages/login.php');			
			// echo "The account is already activated or doesn't exist!";
		}
	}
}

?>