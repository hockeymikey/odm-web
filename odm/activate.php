<?php
	
    session_start();
	
	if(!empty($_SESSION['token']))
	{
				header("Location: index.php");
				exit;
	}

	include 'include/config.php';
	include 'include/db.php';

	dbconnect();
	
	$report = "";
	
	if (filter_var($_GET['email'], FILTER_VALIDATE_EMAIL)) {
            $confirm_email = $_GET["email"];
        }
	if (isset($_GET['key']) && (strlen($_GET['key']) == 32))
		{
		$key = $_GET['key'];
		}
		
		$user = getEmailRecord($confirm_email);
		$_SESSION['activation'] = $user['activation'];
	
	if (isset($confirm_email) && isset($key) && ($key == $_SESSION['activation'])) {
	
	$do = updateActivation($confirm_email, $key);
	if($do == "success") {
		$report = 'Your account is now active. You may now <a href="login.php">Log in</a>';
		} else {
		$report = 'Your account could not be activated. Please recheck the link or contact the system administrator.';
		}
	}
	else {
		$report = 'Your account is not activated. Please check the link in your activation email.';
		}

	include 'include/header.php';
?>

	<div id="overlay">

		<div id="container">
		
		<div id="loginimg"><img src="icons/login-128.png"></div>
			<form id="login" method="POST">
				<h1><div id="txtlogin" class="txtlogin">Activation</div></h1>
							<div class="error"><p><?php echo $report; ?></p></div>
			</form>
		
		</div>

	</div>
<?php
	include 'include/footer.php';
	dbclose();
?>
