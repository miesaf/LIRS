<?php
	//connect to MySQL database server
	$connection = mysql_connect("", "", "") or die("Failed MySQL server connection attempt.<br>" . mysql_error() . "<br>");

	//connecting to database
	$selection = mysql_select_db("") or die("Fail to connect to database.<br>" . mysql_error() . "<br>");
?>