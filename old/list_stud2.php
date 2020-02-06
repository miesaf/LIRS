<?php
	session_start();
	if(!$_SESSION['LOGIN'] || $_SESSION['PRIV'] != "SUPK")
	{
		header("Location: index.php?error=exp");
		exit;
	}
	
	include("connectDB.php");
?>
<html>
<head>
	<title>[LIRS] - List Student</title>
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
				<th colspan="5"> Student List </th>
			</tr>
			<tr>
				<th> No. </th>
				<th> Stud. ID </th>
				<th> Name </th>
				<th> Part </th>
				<th> Programme </th>
			</tr>
			<?php
				$counter = 1;
				$sql_choose = "SELECT * FROM STUDENT ORDER BY S_NAME";
				$result_choose = mysql_query($sql_choose);
				while ($row = mysql_fetch_array($result_choose))
				{
					$S_ID 	= $row["S_ID"];
					$S_NAME = $row["S_NAME"];
					$S_PART	= $row["S_PART"];
					$S_PROG	= $row["S_PROG"];
					
					echo "<tr>
							<td> $counter </td>
							<td> <a href=\"view_stud.php?ID=$S_ID\"> $S_ID </a> </td>
							<td> $S_NAME </td>
							<td> $S_PART </td>
							<td> $S_PROG </td>
						</tr>";
					
					$counter++;
				}
			?>
		</table>
		</td>
</body>
</html>