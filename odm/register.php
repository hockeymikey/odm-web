<?php
	
    session_start();
	
	if(!empty($_SESSION['token']))
	{
				header("Location: index.php");
				exit;
	}
	
	include 'include/config.php';
	include 'include/db.php';

	if (!$ALLOW_REGISTRATIONS) {
		header("Location: login.php");
		exit;
	}
	
	dbconnect();
		
	//var_dump(ini_set('display_errors', 1));  

	$error = "";
	if (isset($_POST["submit"])) {
		$username = "";
		$confirm_email = "";
		$password = "";
		$confirm_password = "";
		$erg_eingabe = "";
		$activation = "";
		$usercheck = "";
		$emailcheck = "";
		
		if (filter_var($_POST['e-mail'], FILTER_VALIDATE_EMAIL)) {
            $confirm_email .= $_POST["e-mail"];
        } else {
            $error .= 'Your E-Mail Address is invalid!<br>';
				$zhl1 = rand(0,10);
				$zhl2 = rand(0,10);
				$aufgabe = rand(0,2);
				
				if($aufgabe == 0) {
				  $text = $zhl1." + ".$zhl2;
				  $erg = $zhl1 + $zhl2;
				}
				if($aufgabe == 1) {
				  $text = $zhl1." - ".$zhl2;
				  $erg = $zhl1 - $zhl2;
				}
				if($aufgabe == 2) {
				  $text = $zhl1." x ".$zhl2;
				  $erg = $zhl1 * $zhl2;
				}
				
				$_SESSION['ergebnis'] = $erg;
        }
		
		if (isset($_POST["username"])) $username = $_POST["username"];
		if (isset($_POST["password"])) $password = $_POST["password"];
		if (isset($_POST["confirm_password"])) $confirm_password = $_POST["confirm_password"];
		if (isset($_POST["erg_eingabe"])) $erg_eingabe = $_POST["erg_eingabe"];
		$username = htmlspecialchars($username);
		$confirm_email = htmlspecialchars($confirm_email);
		$password = htmlspecialchars($password);
		$confirm_password = htmlspecialchars($confirm_password);
		$erg_eingabe = htmlspecialchars($erg_eingabe);
		$usercheck = getUserRecord($username);
		$emailcheck = getEmailRecord($confirm_email);
		if ($username == $emailcheck["username"]) {
			$error .= "Username already exists!<br>";
				$zhl1 = rand(0,10);
				$zhl2 = rand(0,10);
				$aufgabe = rand(0,2);
				
				if($aufgabe == 0) {
				  $text = $zhl1." + ".$zhl2;
				  $erg = $zhl1 + $zhl2;
				}
				if($aufgabe == 1) {
				  $text = $zhl1." - ".$zhl2;
				  $erg = $zhl1 - $zhl2;
				}
				if($aufgabe == 2) {
				  $text = $zhl1." x ".$zhl2;
				  $erg = $zhl1 * $zhl2;
				}
				$_SESSION['ergebnis'] = $erg;
		}
		else if ($confirm_email == $usercheck["email"]) {
			$error .= "E-Mail already exists!<br>";
				$zhl1 = rand(0,10);
				$zhl2 = rand(0,10);
				$aufgabe = rand(0,2);
				
				if($aufgabe == 0) {
				  $text = $zhl1." + ".$zhl2;
				  $erg = $zhl1 + $zhl2;
				}
				if($aufgabe == 1) {
				  $text = $zhl1." - ".$zhl2;
				  $erg = $zhl1 - $zhl2;
				}
				if($aufgabe == 2) {
				  $text = $zhl1." x ".$zhl2;
				  $erg = $zhl1 * $zhl2;
				}
				$_SESSION['ergebnis'] = $erg;
		}
		else if ($password != $confirm_password) {
			$error .= "Passwords do not match!<br>";
				$zhl1 = rand(0,10);
				$zhl2 = rand(0,10);
				$aufgabe = rand(0,2);
				
				if($aufgabe == 0) {
				  $text = $zhl1." + ".$zhl2;
				  $erg = $zhl1 + $zhl2;
				}
				if($aufgabe == 1) {
				  $text = $zhl1." - ".$zhl2;
				  $erg = $zhl1 - $zhl2;
				}
				if($aufgabe == 2) {
				  $text = $zhl1." x ".$zhl2;
				  $erg = $zhl1 * $zhl2;
				}
				$_SESSION['ergebnis'] = $erg;
			}
		else if (strlen($username) > 50) {
			$error .= "Username is too long!<br>";
				$zhl1 = rand(0,10);
				$zhl2 = rand(0,10);
				$aufgabe = rand(0,2);
				
				if($aufgabe == 0) {
				  $text = $zhl1." + ".$zhl2;
				  $erg = $zhl1 + $zhl2;
				}
				if($aufgabe == 1) {
				  $text = $zhl1." - ".$zhl2;
				  $erg = $zhl1 - $zhl2;
				}
				if($aufgabe == 2) {
				  $text = $zhl1." x ".$zhl2;
				  $erg = $zhl1 * $zhl2;
				}
				$_SESSION['ergebnis'] = $erg;
			}
		else if($erg_eingabe != $_SESSION['ergebnis']) {
			$zhl1 = rand(0,10);
			$zhl2 = rand(0,10);
			$aufgabe = rand(0,2);
			
			if($aufgabe == 0) {
			  $text = $zhl1." + ".$zhl2;
			  $erg = $zhl1 + $zhl2;
			}
			if($aufgabe == 1) {
			  $text = $zhl1." - ".$zhl2;
			  $erg = $zhl1 - $zhl2;
			}
			if($aufgabe == 2) {
			  $text = $zhl1." x ".$zhl2;
			  $erg = $zhl1 * $zhl2;
			}
			
			$_SESSION['ergebnis'] = $erg;
			$error = "Captcha does not match!<br>";
		}
		else {
			$activation = md5(uniqid(rand(), true));
			$cost = 10;
			$salt = strtr(base64_encode(mcrypt_create_iv(16, MCRYPT_DEV_URANDOM)), '+', '.');
			$salt = sprintf("$2a$%02d$", $cost) . $salt;
			$hash = crypt($password, $salt);
			$token = storeUsername($username, $confirm_email, $hash, $activation);
			
			// Send the email:
			$message = "To activate your account, please click on this link:\n\n";
			$message .= "http://odm.linewalker.de/2/activate.php?email=" . urlencode($confirm_email) . "&key=".$activation;
			mail($confirm_email, 'ODM: Registration Confirmation', $message, 'From:activation@odm.linewalker.de');
						
			header("Location: activate.php");
			exit;
		}
	}
	else {
				$zhl1 = rand(0,10);
				$zhl2 = rand(0,10);
				$aufgabe = rand(0,2);
				
				if($aufgabe == 0) {
				  $text = $zhl1." + ".$zhl2;
				  $erg = $zhl1 + $zhl2;
				}
				if($aufgabe == 1) {
				  $text = $zhl1." - ".$zhl2;
				  $erg = $zhl1 - $zhl2;
				}
				if($aufgabe == 2) {
				  $text = $zhl1." x ".$zhl2;
				  $erg = $zhl1 * $zhl2;
				}
				
				$_SESSION['ergebnis'] = $erg;
	}

	include 'include/header.php';

?>

	<div id="overlay">

		<div id="containerreg">
		
		<div id="loginimg"><img src="icons/register-128.png"></div>
			<form id="loginreg" method="POST">
				<h1><div id="txtlogin" class="txtlogin">Anmeldung</div></h1>
							<?php if (strlen($error) > 0) { ?>
							<div class="error"><p><?php echo $error; ?></p></div>
							<?php }
								else { echo "<p>&nbsp;</p>"; } ?>
				<fieldset id="inputs">
					<input id="username" type="text" name="username" placeholder="Benutzer / Username" autofocus required>
					<input id="e-mail" type="text" name="e-mail" placeholder="E-Mail" autofocus required>
					<input id="password" type="password" name="password" placeholder="Kennwort / Password" required>
					<input id="password" type="password" name="confirm_password" placeholder="Kennwort bestätigen / Confirm password" required>
					<input id="captcha" type='text' name='erg_eingabe' placeholder="Captcha: <?php echo $text; ?>" required>
				</fieldset>
				<fieldset id="actions">
					<input type="submit" id="submitreg" name="submit" value="Absenden / Register">
				</fieldset>
			</form>
		
		</div>

	</div>
<?php
	include 'include/footer.php';
	dbclose();
?>
