<?php
	session_start();
	if(!$_SESSION['LOGIN'])
	{
		header("Location: index.php?error=exp");
		exit;
	}
	
	include("connectDB.php");
	?>
<html>
<head>
	<title>[LIRS] - Main Page</title>
	<!-- <link rel="shortcut icon" href="favicon.ico"> -->
	
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

	</style>
	
	<link rel="apple-touch-icon" sizes="180x180" href="favicon/apple-touch-icon.png">
	<link rel="icon" type="image/png" href="favicon/favicon-32x32.png" sizes="32x32">
	<link rel="icon" type="image/png" href="favicon/favicon-16x16.png" sizes="16x16">
	<link rel="manifest" href="favicon/manifest.json">
	<link rel="mask-icon" href="favicon/safari-pinned-tab.svg" color="#ff1f00">
	<link rel="shortcut icon" href="favicon/favicon.ico">
	<meta name="msapplication-TileColor" content="#da532c">
	<meta name="msapplication-TileImage" content="favicon/mstile-144x144.png">
	<meta name="msapplication-config" content="favicon/browserconfig.xml">
	<meta name="theme-color" content="#ffffff">
</head>
<body>
	<header>
		<center><h1 class="head">LEDANG INN RESERVATION SYSTEM (LIRS)</h1></center>
	</header>
	
	<?php
	// USER ######################################################################################################################
	if($_SESSION["PRIV"] == "USER")
	{
		$ident	= $_SESSION["IDENT"];
		$sql	= "SELECT * FROM STUDENT WHERE S_ID = $ident";
		$result	= mysql_query($sql);
		while($row = mysql_fetch_array($result))
		{
			$S_ID		= $row["S_ID"];
			$S_NAME		= $row["S_NAME"];
			$S_IC		= $row["S_IC"];
			$S_PROG		= $row["S_PROG"];
			$S_PART		= $row["S_PART"];
			$S_PHONE	= $row["S_PHONE"];
			$S_MAIL		= $row["S_MAIL"];
			
			$sql2		= "SELECT PROG FROM PROGRAMME WHERE ID_PROG = \"$S_PROG\"";
			$result2	= mysql_query($sql2);
			while($row2 = mysql_fetch_array($result2))
			{	$S_PROG = $S_PROG . " - " . $row2["PROG"];	}
		}
		?>
		
		<table style="width: 100%">
			<tr>
				<td align="right">
					<br>Welcome, <?php print($_SESSION['NAME']); ?> | <a href="logout.php">Logout</a><br><br>
				</td>
			</tr>
			<tr>
			<td align="center">
				<table border="1" class="tdet">
					<tr>
						<td colspan="4" align="center"><strong><h2>Student Detail</h2></strong></td>
					</td>
					<tr>
						<td><strong>Student ID</strong></td>
						<td><?php print($S_ID); ?></td>
						<td><strong>Part/Sem</strong></td>
						<td><?php print($S_PART); ?></td>
					</tr>
					<tr>
						<td><strong>Name</strong></td>
						<td><?php print($S_NAME); ?></td>
						<td><strong>Phone</strong></td>
						<td><?php print($S_PHONE); ?></td>
					</tr>
					<tr>
						<td><strong>IC Number</strong></td>
						<td><?php print($S_IC); ?></td>
						<td><strong>E-Mail</strong></td>
						<td><?php print($S_MAIL); ?></td>
					</tr>
					<tr>
						<td><strong>Program</strong></td>
						<td colspan="3"><?php print($S_PROG); ?></td>
					</tr>
				</table><br><br>
			</td>
			</tr>
			<tr>
				<td align="center">
					<a href="#"><button>List Reservation</button></a>&nbsp;&nbsp;&nbsp;
					<a href="#"><button>Add Reservation</button></a>&nbsp;&nbsp;&nbsp;
					<a href="#"><button>Update User Info</button></a>
				</td>
			</tr>
		</table>
		<?php
	}
	
	// UPK #######################################################################################################################
	if($_SESSION["PRIV"] == "SUPK")
	{
		$ident	= $_SESSION["IDENT"];
		$sql	= "SELECT U_ID, U_NAME, U_PHONE FROM UPK WHERE U_ID = $ident";
		$result	= mysql_query($sql);
		while($row = mysql_fetch_array($result))
		{
			$U_ID		= $row["U_ID"];
			$U_NAME		= $row["U_NAME"];
			$U_PHONE	= $row["U_PHONE"];
		}
		?>
		
		<table style="width: 100%">
			<tr>
				<td align="right" colspan="6">
					<br>Welcome, <?php print($_SESSION['NAME']); ?> | <a href="logout.php">Logout</a><br><br>
				</td>
			</tr>
			<tr>
				<td align="center" colspan="6">
					<table border="1" class="tdet">
						<tr>
							<td colspan="2" align="center"><strong><h2>Staff Detail</h2></strong></td>
						</td>
						<tr>
							<td><strong>Staff ID</strong></td>
							<td><?php print($U_ID); ?></td>
						</tr>
						<tr>
							<td><strong>Name</strong></td>
							<td><?php print($U_NAME); ?></td>
						</tr>
						<tr>
							<td><strong>Phone</strong></td>
							<td><?php print($U_PHONE); ?></td>
						</tr>
					</table><br><br>
				</td>
			</tr>
			<tr>
				<td align="center">
					<table class="tdet">
						<tr align="center">
							<td>&nbsp;&nbsp;&nbsp;</td>
							<td>&nbsp;&nbsp;&nbsp;</td>
							<td>&nbsp;&nbsp;&nbsp;</td>
							<td>&nbsp;&nbsp;&nbsp;</td>
						</tr>
					</table>
				</td>
				<td align="center">
					<table class="tdet">
						<tr>
							<th>Reservation</th>
						</tr>
						<tr align="center">
							<td>
								<a href="#"><button>List All Reservations</button></a><br><br>
								<a href="#"><button>List Pending Reservations</button></a><br><br>
								<a href="#"><button>List Approved Reservations</button></a>
							</td>
						</tr>
					</table>
				</td>
				<td align="center">
					<table class="tdet">
						<tr>
							<th>House</th>
						</tr>
						<tr align="center">
							<td>
								<a href="add_house.php"><button>Add House Room</button></a><br><br>
								<a href="list_house.php"><button>List Houses</button></a><br><br>
							</td>
						</tr>
					</table>
				</td>
				<td align="center">
					<table class="tdet">
						<tr>
							<th>Room</th>
						</tr>
						<tr align="center">
							<td>
								<a href="add_room.php"><button>Add New Room</button></a><br><br>
								<a href="list_room.php"><button>List Rooms</button></a><br><br>
							</td>
						</tr>
					</table>
				</td>
				<td align="center">
					<table class="tdet">
						<tr>
							<th>Student</th>
						</tr>
						<tr align="center">
							<td>
								<a href="add_stud.php"><button>Add New Student</button></a><br><br>
								<a href="list_stud.php"><button>List Students</button></a><br><br>
							</td>
						</tr>
					</table>
				</td>
				<td align="center">
					<table class="tdet">
						<tr align="center">
							<td>&nbsp;&nbsp;&nbsp;</td>
							<td>&nbsp;&nbsp;&nbsp;</td>
							<td>&nbsp;&nbsp;&nbsp;</td>
							<td>&nbsp;&nbsp;&nbsp;</td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
		<?php
	}
?>
</body>
</html>