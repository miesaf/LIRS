<?php
	//connect to MySQL database server
	$connection = mysql_connect("mysql.hostinger.my", "u926489498_lirs", "lirsiman") or die("Failed MySQL server connection attempt.<br>" . mysql_error() . "<br>");

	//connecting to database
	$selection = mysql_select_db("u926489498_lirs") or die("Fail to connect to database.<br>" . mysql_error() . "<br>");
?>