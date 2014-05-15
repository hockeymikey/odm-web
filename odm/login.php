<?php
	
    session_start();
	
	if(!empty($_SESSION['token']))
	{
				header("Location: index.php");
				exit;
	}

	include 'include/config.php';
	include 'include/db.php';
	
    $hostname = $_SERVER['HTTP_HOST'];
    $path = dirname($_SERVER['PHP_SELF']);
	
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

	dbconnect();

	$error = "";

	if (isset($_POST["submit"])) {
		$email = "";
		$password = "";
		
		if (isset($_POST["e-mail"])) $email = $_POST["e-mail"];
		if (isset($_POST["password"])) $password = $_POST["password"];
		if (isset($_POST["erg_eingabe"])) $erg_eingabe = $_POST["erg_eingabe"];
		$email = htmlspecialchars($email);
		$password = htmlspecialchars($password);
		$erg_eingabe = htmlspecialchars($erg_eingabe);
        if($erg_eingabe == $_SESSION['ergebnis']) {
		if ($LDAP) {
			$ldapuser = $email;
			if ($LDAP_DOMAIN != "")
				$ldapuser = $LDAP_DOMAIN."\\".$email;
			$ldap = ldap_connect($LDAP_SERVER);
			if ($bind = ldap_bind($ldap, $ldapuser, $password)) {
				$cost = 10;
				$salt = strtr(base64_encode(mcrypt_create_iv(16, MCRYPT_DEV_URANDOM)), '+', '.');
				$salt = sprintf("$2a$%02d$", $cost) . $salt;
				$hash = crypt($password, $salt);
				$token = storeUsername($email, $hash);
                $user = getUserRecord($email);
				$_SESSION['user_id'] = $user['user_id'];
				$_SESSION['username'] = $user['username'];
				$_SESSION['token'] = $token;
				$_SESSION['email'] = $email;
				$_SESSION['activation'] = $activation;
                header("Location: index.php");
				exit;
			} else {
				$error = "E-Mail or password do not match.";
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
		} else {		
				$checkuser = getEmailRecord($email);
				if(!empty($checkuser['activation'])) {
                header("Location: activate.php");
				exit;
				}
				else {
			$user = getEmailRecord($email);
			if (crypt($password, $user['hash']) == $user['hash']) {
				$_SESSION['user_id'] = $user['user_id'];
				$_SESSION['username'] = $user['username'];
				$_SESSION['token'] = $user['token'];
				$_SESSION['email'] = $email;
				$_SESSION['activation'] = $user['activation'];
				header("Location: index.php");
				exit;
			} else {
				$error = "E-Mail or password do not match.";
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
			}
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
		$error = "Captcha does not match.";
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
	
	}
	else {
		
		$erg = "";
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

		<div id="container" class="widget draggable hardwareSpeed">
		
		<div id="loginimg"><img src="icons/login-128.png"></div>
			<form id="login" method="POST">
				<h1><div id="txtlogin" class="txtlogin">Anmeldung</div></h1>
							<?php if (strlen($error) > 0) { ?>
							<div class="error"><p><?php echo $error; ?></p></div>
							<?php }
								else { echo "<p>&nbsp;</p>"; } ?>
				<fieldset id="inputs">
					<input id="e-mail" type="text" name="e-mail" placeholder="E-Mail" autofocus required>
					<input id="password" type="password" name="password" placeholder="Kennwort / Password" required>
					<input id="captcha" type='text' name='erg_eingabe' placeholder="Captcha: <?php echo $text; ?>" required>
				</fieldset>
				<fieldset id="actions">
					<input type="submit" id="submit" name="submit" value="Login">
					<?php if ($ALLOW_REGISTRATIONS) { ?>
					&nbsp;<div id="txtneedaccount" class="txtneedaccount">Need an account?</div><a href="register.php"><div id="txtregister" class="txtregister">Register</div></a>
					<?php } ?>
				</fieldset>
			</form>
		
		</div>

	</div>
<?php
	include 'include/footer.php';
	dbclose();
?>
