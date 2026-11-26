<?php
	session_start();
	
	include('connectDB.php');
	
	$enteredID	= mysqli_real_escape_string($condb, $_POST['uname']);
	$enteredPW	= mysqli_real_escape_string($condb, $_POST['pword']);
	$enteredTP	= mysqli_real_escape_string($condb, $_POST['type']);
		
	// Checking User Account ##################################################################################################
	function user($condb, $enteredID, $enteredPW)
	{
		$problem = "failed";

		//SQL query command
		$sql="SELECT S_ID, S_NAME, S_IC FROM STUDENT WHERE S_ID = $enteredID";
		
		// execute query
		$res_sql	= mysqli_query($condb, $sql);

		if($res_sql)
		{
			$row		= mysqli_fetch_array($res_sql);
			
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
		}
		
		return $problem;
	}
	
	// Checking Admin Account ##################################################################################################
	function admin($condb, $enteredID, $enteredPW)
	{
		$problem = "failed";

		//SQL query command
		$sql="SELECT U_ID, U_NAME, U_PWD FROM UPK WHERE U_ID = $enteredID";
		
		// execute query
		$res_sql = mysqli_query($condb, $sql);
		if($res_sql)
		{
			$row = mysqli_fetch_array($res_sql);
		
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
		}
	
		return $problem;
	}
	
	if($enteredTP == "1")
	{
		$problem = user($condb, $enteredID, $enteredPW);
	}
	elseif($enteredTP == "2")
	{
		$problem = admin($condb, $enteredID, $enteredPW);
	}
	else
	{
		$problem = "type";
	}
	
	if(!$condb){ $problem = "server"; }
	//elseif(!$selection){ $problem = "db"; }
	
	$url = "Location: index.php?error=$problem";
	header($url);
	exit;
?>