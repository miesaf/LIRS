<?php
	session_start();
	
	include('connectDB.php');
	
	$enteredID	= mysql_real_escape_string($_POST['uname']);
	$enteredPW	= mysql_real_escape_string($_POST['pword']);
	$enteredTP	= mysql_real_escape_string($_POST['type']);
		
	// Checking User Account ##################################################################################################
	function user($enteredID, $enteredPW)
	{
		//SQL query command
		$sql="SELECT S_ID, S_NAME, S_IC FROM STUDENT WHERE S_ID = $enteredID";
		
		// execute query
		$res_sql	= mysql_query($sql);
		$row		= mysql_fetch_array($res_sql);
		
		// extract specific fields
		$S_ID	= $row["S_ID"];
		$S_NAME	= $row["S_NAME"];
		$S_IC	= $row["S_IC"];
		
		if(($enteredID == $S_ID) && ($enteredPW == $S_IC))
		{
			$_SESSION['LOGIN']	= "YES";
			$_SESSION['IDENT']	= $S_ID;
			$_SESSION['PRIV']	= "USER";
			$_SESSION['NAME'] 	= $S_NAME;
			
			$url = "Location: main.php";
			header($url);
			exit;
		}
		
		if($enteredID != $S_ID || $enteredPW != $S_IC)
		{
			$problem = "failed";
		}
		
		return $problem;
	}
	
	// Checking Admin Account ##################################################################################################
	function admin($enteredID, $enteredPW)
	{
		//SQL query command
		$sql="SELECT U_ID, U_NAME, U_PWD FROM UPK WHERE U_ID = $enteredID";
		
		// execute query
		$res_sql = mysql_query($sql);
		$row = mysql_fetch_array($res_sql);
		
		// extract specific fields
		$U_ID	= $row["U_ID"];
		$U_NAME	= $row["U_NAME"];
		$U_PWD	= $row["U_PWD"];
		
		if(($enteredID == $U_ID) && ($enteredPW == $U_PWD))
		{
			$_SESSION['LOGIN']	= "YES";
			$_SESSION['IDENT']	= $U_ID;
			$_SESSION['PRIV']	= "SUPK";
			$_SESSION['NAME']	= $U_NAME;
			
			$url = "Location: main.php";
			header($url);
			exit;
		}
		
		if($enteredID != $U_ID || $enteredPW != $U_PWD)
		{
			$problem = "failed";
		}
	
		return $problem;
	}
	
	if($enteredTP == "1")
	{
		$problem = user($enteredID, $enteredPW);
	}
	elseif($enteredTP == "2")
	{
		$problem = admin($enteredID, $enteredPW);
	}
	else
	{
		$problem = "type";
	}
	
	if(!$connection){ $problem = "server"; }
	elseif(!$selection){ $problem = "db"; }
	
	$url = "Location: index.php?error=$problem";
	header($url);
	exit;
?>