<?php
	$db_host	= "";
	$db_uname	= "";
	$db_pword	= "";
	$db_dbname	= "";
	
	//connect to MySQL database server
	$connection = mysql_connect($db_host, $db_uname, $db_pword) or die("Failed MySQL server connection attempt.<br>" . mysql_error() . "<br>");

	//connecting to database
	$selection = mysql_select_db($db_dbname) or die("Fail to connect to database.<br>" . mysql_error() . "<br>");
?>