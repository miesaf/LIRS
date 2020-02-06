<?php
	session_start();
	if(!$_SESSION['LOGIN'] || $_SESSION['PRIV'] != "SUPK")
	{
		header("Location: index.php?error=exp");
		exit;
	}
	
	include("connectDB.php");
	
	if(isset($_GET["DEL"]))
	{
		$DEL_ID	= $_GET["DEL"];
		
		//SQL query command
		$sql="DELETE FROM STUDENT WHERE S_ID = $DEL_ID";
		
		// execute query
		$exe_sql = mysql_query($sql);
		
		// confirming the record is added
		if ($exe_sql)
		{
			echo '<html>
					<head>
						<script>
							window.alert("Record was removed from the database.");
						</script>
						<meta http-equiv="refresh" content="0; url=view_stud.php?ID=' . $DEL_ID . '" />
					</head>
				</html>';
		}
		else
		{
			echo "SQL insert statement failed.<br>" . mysql_error();
			echo '<html>
					<head>
						<script>
							window.alert("Failed to remove record from the database.");
							window.history.go(-1);
						</script>
					</head>
				</html>';
		}
	}
?>
<html>
<head>
	<title>[LIRS] - View Student</title>
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
			<table border="1" class="tdet2">
			<?php
				if($_GET["ID"] != null)
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
					
					if($S_ID != null)
					{
						// Decode codes into string
						$sql_display = "SELECT PROG FROM PROGRAMME WHERE ID_PROG = '$S_PROG'";
						$result_dispay = mysql_query($sql_display);
						while($row = mysql_fetch_array($result_dispay)) { $DPROG = $row["PROG"]; }
			?>
				<tr>
					<th colspan="2"> Student Personal Detail </th>
				</tr>
				<tr>
					<th> Student ID </th>
					<td> <?php print($S_ID); ?> </td>
				</tr>
				<tr>
					<th> Name </th>
					<td> <?php print($S_NAME); ?> </td>
				</tr>
				<tr>
					<th> Student IC </th>
					<td> <?php print($S_IC); ?> </td>
				</tr>
				<tr>
					<th> Programme </th>
					<td> <?php print($S_PROG . " - " . $DPROG); ?> </td>
				</tr>
				<tr>
					<th> Part/Semester </th>
					<td> <?php print($S_PART); ?> </td>
				</tr>
				<tr>
					<th> Phone No. </th>
					<td> <?php print($S_PHONE); ?> </td>
				</tr>
				<tr>
					<th> E-Mail </th>
					<td> <?php print($S_MAIL); ?> </td>
				</tr>
				<tr>
					<td colspan="2" align="center">
						<a href="edit_stud.php?ID=<?php print($S_ID); ?>"><button> Edit </button></a>&nbsp;&nbsp;&nbsp;
						<button onClick='confirmDel()'> Delete </button>
					</td>
				</tr>
			<?php 
					}
					else
					{
						?>
						<tr>
							<th> Student Personal Detail </th>
						</tr>
						<tr>
							<td align="center"> No data to display </td>
						</tr>
						<?php
					}
				}
				else
				{
					?>
					<tr>
						<th> Student Personal Detail </th>
					</tr>
					<tr>
						<td align="center"> No data to display </td>
					</tr>
					<?php
				}
			?>
			</table>
			</td>
		</tr>
	</table>
	
	<script language="JavaScript">
		function confirmDel()
		{
			var del = confirm("Do you want to delete this record?");
			if (del == true)
			{
				window.location.assign("view_stud.php?DEL=<?php print($S_ID); ?>");
			} else 
			{
				alert("Record was not deleted.");
			}
		}
	</script>
	
</body>
</html>