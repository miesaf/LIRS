<?php
	session_start();
	
	unset($_SESSION['LOGIN']);
	unset($_SESSION['IDENT']);
	unset($_SESSION['PRIV']);
	unset($_SESSION['NAME']);
	// session_destroy();
?>
<html>
<head>
	<link rel="shortcut icon" href="favicon.ico">
	<title>[LIRS] - Logout</title>
	<script>
		window.alert("You have been logged out successfully!");
	</script>
	<?php
		//$name = $_GET['name'];
	?>
</head>
<body>
		<!--<h1> Goodbye</h1>-->
		<meta http-equiv="refresh" content="0; url=index.php" />
</body>
</html>