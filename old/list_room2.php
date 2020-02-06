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
		$sql="DELETE FROM ROOM WHERE R_ID = '$DEL_ID'";
		
		// execute query
		$exe_sql = mysql_query($sql);
		
		// confirming the record is added
		if ($exe_sql)
		{
			echo '<html>
					<head>
						<script>
							window.alert("Room was removed from the database.");
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
							window.alert("Failed to remove room from the database.");
							window.history.go(-1);
						</script>
					</head>
				</html>';
		}
	}
?>
<html>
<head>
	<title>[LIRS] - List Room</title>
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
				<th colspan="6"> Room List </th>
			</tr>
			<tr>
				<th> No. </th>
				<th> Room ID </th>
				<th> Room Type </th>
				<th> House </th>
				<th> Room Status </th>
				<th> Action </th>
			</tr>
			<?php
				$counter = 1;
				$sql_choose = "SELECT * FROM ROOM ORDER BY R_HOUSE, R_ID";
				$result_choose = mysql_query($sql_choose);
				while ($row = mysql_fetch_array($result_choose))
				{
					$R_ID 		= $row["R_ID"];
					$R_TYPE 	= $row["R_TYPE"];
					$R_HOUSE	= $row["R_HOUSE"];
					$R_STAT 	= $row["R_STAT"];
					
					// Decode codes into string
					if($R_TYPE == "TS")
					{	$R_TYPE = "Two Single Bed";	}
					elseif($R_TYPE == "QB")
					{	$R_TYPE = "One Queen Bed";	}
					elseif($R_TYPE == "KB")
					{	$R_TYPE = "One King Bed";	}
					
					$sql_display = "SELECT H_NAME FROM HOUSE WHERE H_ID = '$R_HOUSE'";
					$result_dispay = mysql_query($sql_display);
					while($row = mysql_fetch_array($result_dispay)) { $DHOUSE = $row["H_NAME"]; }
					
					if($R_STAT == true)
					{	$R_STAT = "Available";	}
					else
					{	$R_STAT = "Not Available";	}
					
					echo "<tr>
							<td align=\"center\"> $counter </td>
							<td align=\"center\"> $R_ID </td>
							<td align=\"center\"> $R_TYPE </td>
							<td> $DHOUSE </td>
							<td align=\"center\"> $R_STAT </td>
							<td align=\"center\"> <a href=\"edit_room.php?ID=$R_ID\"><button type=\"button\">Edit</button></a> <button type='button' value='Delete' onClick='confirmDel(\"$R_ID\")'>Delete</button> </td>
						</tr>";
					
					$counter++;
				}
			?>
		</table>
		</td>
		
		<script language="JavaScript">
		function confirmDel(nums)
		{
			var del = confirm("Do you want to delete this room?");
			if (del == true)
			{
				window.location.assign("list_room.php?DEL=" + nums);
			} else 
			{
				alert("Room was not deleted.");
			}
		}
	</script>
</body>
</html>