<?php

     session_start();
	 
	 $hostname = $_SERVER['HTTP_HOST'];
     $path = dirname($_SERVER['PHP_SELF']);

if(count(get_included_files()) ==1) exit("Direkter Zugriff ist nicht erlaubt.<br>No direct access!");

	if (isset($_SESSION['user_id']) && isset($_SESSION['token']) && isset($_SESSION['username'])) {
		$user = getUserRecord($_SESSION['username']);
		if ($_SESSION['user_id'] != $user['user_id'] || $_SESSION['token'] != $user['token']) {
			header('Location: http://'.$hostname.($path == '/' ? '' : $path).'/login.php');
			exit;
		}
	} else {
		header('Location: http://'.$hostname.($path == '/' ? '' : $path).'/login.php');
		exit;
	}
?>
