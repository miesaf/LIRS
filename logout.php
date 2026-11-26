<?php
	session_start();

	unset($_SESSION['lirs_login'], $_SESSION['lirs_ident'], $_SESSION['lirs_priv'], $_SESSION['lirs_name']);
	session_regenerate_id(true);
?>
<html>
<head>
	<link rel="shortcut icon" href="favicon.ico">
	<title>[LIRS] - Logout</title>
	<script>
		window.alert("You have been logged out successfully!");
	</script>
</head>
<body>
		<meta http-equiv="refresh" content="0; url=index.php" />
</body>
</html>
