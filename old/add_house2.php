<?php
	session_start();
	if(!$_SESSION['LOGIN'] || $_SESSION['PRIV'] != "SUPK")
	{
		header("Location: index.php?error=exp");
		exit;
	}
	
	include("connectDB.php");
	
	if(isset($_POST["REG"]))
	{
		// Variables from pendaftaran.php
		$H_ID		= mysql_real_escape_string($_POST['H_ID']);
		$H_NAME		= mysql_real_escape_string($_POST['H_NAME']);
		
		//SQL query command
		$sql="INSERT INTO HOUSE (H_ID, H_NAME) VALUES ('$H_ID', '$H_NAME')";
		
		// execute query
		$exe_sql = mysql_query($sql);
		
		// confirming the record is added
		if ($exe_sql)
		{
			echo '<html>
					<head>
						<script>
							window.alert("Registration successfull!\nRecord was saved into the database.");
						</script>
						<meta http-equiv="refresh" content="0; url=list_house.php" />
					</head>
				</html>';
		}
		else
		{
			echo "SQL insert statement failed.<br>" . mysql_error();
			echo '<html>
					<head>
						<script>
							window.alert("Registration failed!\nRecord was not saved into the database.");
							window.history.go(-1);
						</script>
					</head>
				</html>';
		}
	}
?>
<html>
<head>
	<title>[LIRS] - Add House</title>
	<link rel="shortcut icon" href="favicon.ico">
	
	<style>
	header{
		width:100%;
		height:100;
		background-image:url(img/sky.jpg);
	}
	
	.head{
		padding-top:30px;
		font-family:Courier; // this is font;
	}
	
	.tdet td{
		padding: 10px;
		border: 0px solid #000;
	}
	
	.tdet2 td{
		padding: 10px;
		border: 1px solid #000;
	}

	</style>
</head>
<body>
	<header>
		<center><h1 class="head">LEDANG INN RESERVATION SYSTEM (LIRS)</h1></center>
	</header>
	
	<table style="width: 100%">
		<tr>
			<td align="right">
				<br>Welcome, <?php print($_SESSION['NAME']); ?> | <a href="main.php">Back to Main Page</a> | <a href="logout.php">Logout</a><br><br>
			</td>
		</tr>
		<tr>
		<td align="center">
		<form id="add_house" action="add_house.php" method="POST">
			<input type="hidden" name="REG" value="YES">
			<table border="1" class="tdet2">
				<tr>
					<th colspan="2"> House Registration Form </th>
				</tr>
				<tr>
					<th> House ID </th>
					<td> <input type="text" size="5" maxlength="3" name="H_ID"> </td>
				</tr>
				<tr>
					<th> House Name </th>
					<td> <input type="text" size="50" maxlength="100" name="H_NAME"> </td>
				</tr>
				<tr align="center">
					<td colspan="2">
						<button type="submit" form="add_house" value="Submit">Submit</button>
						<button type="reset" form="add_house" value="Reset">Reset</button>
						<a href="main.php"><button type="button">Cancel</button></a>
					</td>
				</tr>
			</table>
		</form>
		</td>
</body>
</html>