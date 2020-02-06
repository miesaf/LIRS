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
		$T_ID		= mysql_real_escape_string($_POST['T_ID']);
		$S_ID		= mysql_real_escape_string($_POST['S_ID']);
		$S_NAME		= mysql_real_escape_string($_POST['S_NAME']);
		$S_IC		= mysql_real_escape_string($_POST['S_IC']);
		$S_PROG		= mysql_real_escape_string($_POST['S_PROG']);
		$S_PART		= mysql_real_escape_string($_POST['S_PART']);
		$S_PHONE	= mysql_real_escape_string($_POST['S_PHONE']);
		$S_MAIL		= mysql_real_escape_string($_POST['S_MAIL']);
		
		//SQL query command
		$sql="UPDATE STUDENT SET S_ID = $S_ID, S_NAME = \"$S_NAME\", S_IC = \"$S_IC\", S_PROG = \"$S_PROG\", S_PART = \"$S_PART\", S_PHONE = \"$S_PHONE\", S_MAIL = \"$S_MAIL\" WHERE S_ID = $T_ID";
		
		// execute query
		$exe_sql = mysql_query($sql);
		
		// confirming the record is added
		if ($exe_sql)
		{
			echo '<html>
					<head>
						<script>
							window.alert("Edition successfull!\nRecord was saved into the database.");
						</script>
						<meta http-equiv="refresh" content="0; url=view_stud.php?ID=' . $S_ID . '" />
					</head>
				</html>';
		}
		else
		{
			echo "SQL insert statement failed.<br>" . mysql_error();
			echo '<html>
					<head>
						<script>
							window.alert("Edition failed!\nRecord was not saved into the database.");
							window.history.go(-1);
						</script>
					</head>
				</html>';
		}
	}
	
	if(isset($_GET["ID"]) && ($_GET["ID"] != null))
	{
		$TEMP_ID	= $_GET["ID"];
		$S_ID		= null;
		$sql_choose = "SELECT * FROM STUDENT WHERE S_ID = $TEMP_ID";
		$result_choose = mysql_query($sql_choose);
		while ($row = mysql_fetch_array($result_choose))
		{
			$S_ID 		= $row["S_ID"];
			$S_NAME 	= $row["S_NAME"];
			$S_IC		= $row["S_IC"];
			$S_PROG		= $row["S_PROG"];
			$S_PART		= $row["S_PART"];
			$S_PHONE	= $row["S_PHONE"];
			$S_MAIL		= $row["S_MAIL"];
		}
	}
?>
<html>
<head>
	<title>[LIRS] - Edit Student</title>
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
		<form id="edit_stud" action="edit_stud.php" method="POST">
			<input type="hidden" name="REG" value="YES">
			<input type="hidden" name="T_ID" value="<?php print($S_ID); ?>">
			<table border="1" class="tdet2">
			<?php
			if(isset($_GET["ID"]) && ($_GET["ID"] != null) && ($S_ID != null))
			{
				?>
				<tr>
					<th colspan="2"> Student Info Edit Form </th>
				</tr>
				<tr>
					<th> Student ID </th>
					<td> <input type="text" size="12" maxlength="10" name="S_ID" value="<?php print($S_ID); ?>"> </td>
				</tr>
				<tr>
					<th> Student Name </th>
					<td> <input type="text" size="50" maxlength="100" name="S_NAME" value="<?php print($S_NAME); ?>"> </td>
				</tr>
				<tr>
					<th> Student IC </th>
					<td> <input type="text" size="12" maxlength="12" name="S_IC" value="<?php print($S_IC); ?>"> </td>
				</tr>
				<tr>
					<th> Programme </th>
					<td>
						<select name="S_PROG">
							<option disabled selected> Please choose </option>
							<option disabled> </option>
							<?php
								$sql_choose = "SELECT * FROM PROGRAMME ORDER BY ID_PROG";
								$result_choose = mysql_query($sql_choose);
								while ($row = mysql_fetch_array($result_choose))
								{
									$PROG1 = $row["ID_PROG"];
									$PROG2 = $row["PROG"];
									
									$SEL = null;
									if($PROG1 == $S_PROG)
									{	$SEL = "selected";	}
								
									echo '<option value="' . $PROG1 . '"' . $SEL . '> ' . $PROG1 . ' - ' . $PROG2 . '</option>';
								}
							?>
						</select>
					</td>
				</tr>
				<tr>
					<th> Part/Semester </th>
					<td> <input type="text" size="12" maxlength="2" name="S_PART" value="<?php print($S_PART); ?>"> </td>
				</tr>
				<tr>
					<th> Phone Number </th>
					<td> <input type="text" size="12" maxlength="20" name="S_PHONE" value="<?php print($S_PHONE); ?>"> </td>
				</tr>
				<tr>
					<th> E-Mail </th>
					<td> <input type="text" size="30" maxlength="50" name="S_MAIL" value="<?php print($S_MAIL); ?>"> </td>
				</tr>
				<tr align="center">
					<td colspan="2">
						<button type="submit" form="edit_stud" value="Submit">Submit</button>
						<button type="reset" form="edit_stud" value="Reset">Reset</button>
						<a href="view_stud.php?ID=<?php print($S_ID); ?>"><button type="button">Cancel</button></a>
					</td>
				</tr>
				<?php
			}
			?>
			</table>
		</form>
		</td>
</body>
</html>