<!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<script type="text/javascript" src="js/jquery.min.js"></script>
		<script type="text/javascript" src="js/jquery-ui.js"></script>
		<script type="text/javascript" src="js/tech.js"></script>
		<script type="text/javascript" src="js/drag.js"></script>
		<link rel="stylesheet" href="css/styles.css" type="text/css" media="all" />
		
		<title>Open Device Manager [RX]</title>
		
	</head>
	<body link="#FFFFFF">
	
	<!--header-->
		<div style="width:100%; height:80px; overflow:hidden; margin: 0 auto; background-color:#333; position:fixed; z-index:1003; top:0; left:0;">

		  <div style="width:1200px; height:80px; margin: 0 auto; overflow:hidden; position:relative;">
				
				<div id="caption" style="height:44px; margin-top:20px;float:left; margin-left:12px; position:absolute"> Open Device Manager <sup>[RX]</sup> </div>
				
				<div id="languagechanger" style="height:20px; margin-top:35px;float:right; margin-left:12px; position:absolute; right: 20px;">
						<?php if (isset($_SESSION['user_id'])) { ?>
						<a href="logout.php"><div id="txtlgout" class="txtlgout" style="float:left; margin-right:5px;">logout | </div></a>
						<div id="txtchpwd" class="txtchpwd" style="float:left; margin-right:5px;">Password | </div>
						<?php }
							else { echo ""; }
							?>
						<div id="lng" class="lng" style="float:left; margin-right:5px;">Language</div>
							<select name="language" id="language" style="float:left, margin-right:5px;">
								<option value="english" onclick="loadUrl('?lang=en'); return false;">English</option>
								<option value="german" onclick="loadUrl('?lang=de'); return false;">Deutsch</option>
							</select>
					</div>
				</div>

		</div>