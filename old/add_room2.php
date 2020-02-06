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
		$R_ID		= mysql_real_escape_string($_POST['R_ID']);
		$R_TYPE		= mysql_real_escape_string($_POST['R_TYPE']);
		$R_HOUSE	= mysql_real_escape_string($_POST['R_HOUSE']);
		$R_STAT		= mysql_real_escape_string($_POST['R_STAT']);
		
		//SQL query command
		$sql="INSERT INTO ROOM (R_ID, R_TYPE, R_HOUSE, R_STAT) VALUES ('$R_ID', '$R_TYPE', '$R_HOUSE', $R_STAT)";
		
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
						<meta http-equiv="refresh" content="0; url=list_room.php" />
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
	<title>[LIRS] - Add Room</title>
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
		<form id="add_room" action="add_room.php" method="POST">
			<input type="hidden" name="REG" value="YES">
			<table border="1" class="tdet2">
				<tr>
					<th colspan="2"> Room Registration Form </th>
				</tr>
				<tr>
					<th> Room ID </th>
					<td> <input type="text" size="5" maxlength="5" name="R_ID"> </td>
				</tr>
				<tr>
					<th> Room Type </th>
					<td>
						<select name="R_TYPE">
							<option disabled selected> Please choose </option>
							<option disabled> </option>
							<option value="TS"> Two Single Bed</option>
							<option value="QB"> One Queen Bed</option>
							<option value="KB"> One King Bed</option>
						</select>
					</td>
				</tr>
				<tr>
					<th> Room House </th>
					<td>
						<select name="R_HOUSE">
							<option disabled selected> Please choose </option>
							<option disabled> </option>
							<?php
								$sql_choose = "SELECT * FROM HOUSE ORDER BY H_NAME";
								$result_choose = mysql_query($sql_choose);
								while ($row = mysql_fetch_array($result_choose))
								{
									$H1 = $row["H_ID"];
									$H2 = $row["H_NAME"];
									echo '<option value="' . $H1 . '"> ' . $H2 . '</option>';
								}
							?>
						</select>
					</td>
				</tr>
				<tr>
					<th> Room Status </th>
					<td>
						<select name="R_STAT">
							<option disabled selected> Please choose </option>
							<option disabled> </option>
							<option value="1"> Available</option>
							<option value="0"> Not Available</option>
						</select>
					</td>
				</tr>
				<tr align="center">
					<td colspan="2">
						<button type="submit" form="add_room" value="Submit">Submit</button>
						<button type="reset" form="add_room" value="Reset">Reset</button>
						<a href="main.php"><button type="button">Cancel</button></a>
					</td>
				</tr>
			</table>
		</form>
		</td>
</body>
</html>