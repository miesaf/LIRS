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
		$sql="DELETE FROM HOUSE WHERE H_ID = '$DEL_ID'";
		
		// execute query
		$exe_sql = mysql_query($sql);
		
		// confirming the record is added
		if ($exe_sql)
		{
			echo '<html>
					<head>
						<script>
							window.alert("House was removed from the database.");
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
							window.alert("Failed to remove house from the database.");
							window.history.go(-1);
						</script>
					</head>
				</html>';
		}
	}
?>
<html>
<head>
	<title>[LIRS] - List House</title>
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
			<tr>
				<th colspan="3"> House List </th>
			</tr>
			<tr>
				<th> House ID </th>
				<th> House Name </th>
				<th> Action </th>
			</tr>
			<?php
				$counter = 1;
				$sql_choose = "SELECT * FROM HOUSE ORDER BY H_NAME";
				$result_choose = mysql_query($sql_choose);
				while ($row = mysql_fetch_array($result_choose))
				{
					$H_ID 	= $row["H_ID"];
					$H_NAME = $row["H_NAME"];
					
					echo "<tr>
							<td align=\"center\"> $H_ID </td>
							<td> $H_NAME </td>
							<td align=\"center\"> <a href=\"edit_house.php?ID=$H_ID\"><button type=\"button\">Edit</button></a> <button type='button' value='Delete' onClick='confirmDel(\"$H_ID\")'>Delete</button> </td>
						</tr>";
					
					$counter++;
				}
			?>
		</table>
		</td>
		
		<script language="JavaScript">
		function confirmDel(nums)
		{
			var del = confirm("Do you want to delete this house?");
			if (del == true)
			{
				window.location.assign("list_house.php?DEL=" + nums);
			} else 
			{
				alert("House was not deleted.");
			}
		}
	</script>
</body>
</html>