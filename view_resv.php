<html lang="en">
<head>
	<link rel="shortcut icon" href="favicon.ico">
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">	
	<meta name="keywords" content="LIRS" />
	<meta name="description" content="Ledang Inn Reservation System" />
	<?php
		session_start();
		if(!$_SESSION['LOGIN'])
		{
			header("Location: index.php?error=exp");
			exit;
		}
		
		include("connectDB.php");
		
		if($_SESSION["PRIV"] == "SUPK")
		{
			// Approve Process
			if(isset($_GET["APP"]))
			{
				$APP_ID		= $_GET["APP"];
				$APP_ROOM	=	$_GET["ROOM"];
				$APP_STAT	= true;
				
				$sql2		= "SELECT RV_ROOM FROM RESERVE WHERE RV_OUT > \"$RV_IN\" AND RV_STAT = \"AP\" UNION SELECT RV_ROOM FROM RESERVE WHERE RV_IN > \"$RV_OUT\" AND RV_STAT = \"AP\" GROUP BY RV_ROOM";
				$result2	= mysql_query($sql2);
				while($row2 = mysql_fetch_array($result2))
				{
					$RV_ROOM		= $row2["RV_ROOM"];
					$OVLP[$RV_ROOM]	= true;
					
					if($APP_ROOM == $RV_ROOM)
					{
						$APP_STAT = false;
					}
				}
				
				if($APP_STAT == true)
				{
					//SQL query command
					$sql="UPDATE RESERVE SET RV_STAT = \"AP\" WHERE RV_ID = $APP_ID";
					
					// execute query
					$exe_sql = mysql_query($sql);
					
					// confirming the record is added
					if ($exe_sql)
					{
						echo '<html>
								<head>
									<script>
										window.alert("Application was approved.");
									</script>
									<meta http-equiv="refresh" content="0; url=view_resv.php?ID=' . $APP_ID . '" />
								</head>
							</html>';
					}
					else
					{
						echo "SQL insert statement failed.<br>" . mysql_error();
						echo '<html>
								<head>
									<script>
										window.alert("Failed to approve the application.");
										window.history.go(-1);
									</script>
								</head>
							</html>';
					}
				}
				else
				{
					echo '<html>
							<head>
								<script>
									window.alert("Failed to approve the application due to overlapped with another approved aplication.");
									window.history.go(-1);
								</script>
							</head>
						</html>';
				}
				
			}
			
			// Deny Process
			if(isset($_GET["DEN"]))
			{
				$DEN_ID	= $_GET["DEN"];
				
				//SQL query command
				$sql="UPDATE RESERVE SET RV_STAT = \"DY\" WHERE RV_ID = $DEN_ID";
				
				// execute query
				$exe_sql = mysql_query($sql);
				
				// confirming the record is added
				if ($exe_sql)
				{
					echo '<html>
							<head>
								<script>
									window.alert("Application was denied.");
								</script>
								<meta http-equiv="refresh" content="0; url=view_resv.php?ID=' . $DEN_ID . '" />
							</head>
						</html>';
				}
				else
				{
					echo "SQL insert statement failed.<br>" . mysql_error();
					echo '<html>
							<head>
								<script>
									window.alert("Failed to deniy application.");
									window.history.go(-1);
								</script>
							</head>
						</html>';
				}
			}
		}
		
		// Delete Process
		if(isset($_GET["DEL"]))
		{
			if($_SESSION["PRIV"] == "USER")
			{
				$DEL_ID		= $_GET["DEL"];
				$STUD_ID	= $_SESSION["IDENT"];
				
				//SQL query command
				$sql="DELETE FROM RESERVE WHERE RV_ID = $DEL_ID AND RV_STUD = $STUD_ID";
				
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
								<meta http-equiv="refresh" content="0; url=view_resv.php?ID=' . $DEL_ID . '" />
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
			elseif($_SESSION["PRIV"] == "SUPK")
			{
				$DEL_ID	= $_GET["DEL"];
				
				//SQL query command
				$sql="DELETE FROM RESERVE WHERE RV_ID = $DEL_ID";
				
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
								<meta http-equiv="refresh" content="0; url=view_resv.php?ID=' . $DEL_ID . '" />
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
		}
	?>

    <title>[LIRS] - View Reservation Detail</title>

    <!-- Bootstrap Core CSS -->
    <link href="bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- MetisMenu CSS -->
    <link href="bower_components/metisMenu/dist/metisMenu.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="dist/css/sb-admin-2.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="bower_components/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->	
</head>

<body>

    <div id="wrapper">

        <!-- Navigation -->
        <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="index.php">Ledang Inn Reservation System (LIRS)</a>
            </div>
            <!-- /.navbar-header -->

            <ul class="nav navbar-top-links navbar-right">
				<li class="dropdown">
					Welcome, <strong><?php print($_SESSION["NAME"]); ?></strong>
				</li>				
                <li class="dropdown">
					<a href="logout.php">
                        <i class="fa fa-sign-out fa-fw"></i> Logout
                    </a>
                </li>
                <!-- /.dropdown -->
            </ul>
            <!-- /.navbar-top-links -->

            <div class="navbar-default sidebar" role="navigation">
                <div class="sidebar-nav navbar-collapse">
                    <ul class="nav" id="side-menu">
						<?php
							include('nav.php');
						?>
                    </ul>
                </div>
                <!-- /.sidebar-collapse -->
            </div>
            <!-- /.navbar-static-side -->
        </nav>

        <!-- Page Content -->
        <div id="page-wrapper">
			<div class="row">
				<div class="col-lg-12">
					<h1 class="page-header">View Reservation Detail</h1>
				<div>
				<!-- /.col-lg-10 -->
			</div>
			<!-- /.row -->
			<div class="row">
				<div class="col-lg-8">					
				<?php
					if(isset($_GET["ID"]) && ($_GET["ID"] != null))
					{
						if($_SESSION["PRIV"] == "USER")
						{
							// get the appropriate IDs
							$ID1	= $_GET["ID"];
							$ID2	= $_SESSION["IDENT"];
							
							// create the query
							$sql	= "SELECT * FROM RESERVE WHERE RV_STUD = $ID2 AND RV_ID = \"$ID1\"";
						}
						elseif($_SESSION["PRIV"] == "SUPK")
						{
							// get the appropriate IDs
							$ID1	= $_GET["ID"];
							
							// create the query
							$sql	= "SELECT * FROM RESERVE WHERE RV_ID = \"$ID1\"";
						}
						
						// execute query
						$result = mysql_query($sql) or die("SQL select statement failed");
						
						// iterate through all rows in result set
						$row = mysql_fetch_array($result);
					
						// extract specific fields
						$RV_ID		= $row["RV_ID"];
						$RV_STUD	= $row["RV_STUD"];
						$RV_DATE	= $row["RV_DATE"];
						$RV_IN		= $row["RV_IN"];
						$RV_OUT		= $row["RV_OUT"];
						$RV_ROOM	= $row["RV_ROOM"];
						$RV_STAT	= $row["RV_STAT"];
						
						if(isset($RV_ID))
						{
							// student info
							$sql2		= "SELECT * FROM STUDENT WHERE S_ID = $RV_STUD";
							$result2	= mysql_query($sql2) or die("SQL select statement failed");
							$row2		= mysql_fetch_array($result2);
							
							$S_ID		= $row2["S_ID"];
							$S_NAME		= $row2["S_NAME"];
							$S_IC		= $row2["S_IC"];
							$S_PROG		= $row2["S_PROG"];
							$S_PART		= $row2["S_PART"];
							$S_PHONE	= $row2["S_PHONE"];
							$S_MAIL		= $row2["S_MAIL"];
							
							// Room name
							$sql3		= "SELECT R_HOUSE, R_TYPE FROM ROOM WHERE R_ID = \"$RV_ROOM\"";
							$result3	= mysql_query($sql3);
							$row3		= mysql_fetch_array($result3);
							
							$R_HOUSE	= $row3["R_HOUSE"];
							$R_TYPE		= $row3["R_TYPE"];
							
							// Display programme name
							$sql4		= "SELECT PROG FROM PROGRAMME WHERE ID_PROG = \"$S_PROG\"";
							$result4	= mysql_query($sql4);
							$row4		= mysql_fetch_array($result4);
							
							$DPROG		= $row4["PROG"];
							
							// Display house name
							$sql5		= "SELECT H_NAME FROM HOUSE WHERE H_ID = \"$R_HOUSE\"";
							$result5	= mysql_query($sql5);
							$row5		= mysql_fetch_array($result5);
							
							$DHOUSE = $row5["H_NAME"];
						
							// Display month name array
							$MONTH["01"]	= "Jan";
							$MONTH["02"]	= "Feb";
							$MONTH["03"]	= "Mar";
							$MONTH["04"]	= "Apr";
							$MONTH["05"]	= "May";
							$MONTH["06"]	= "Jun";
							$MONTH["07"]	= "Jul";
							$MONTH["08"]	= "Aug";
							$MONTH["09"]	= "Sep";
							$MONTH["10"]	= "Oct";
							$MONTH["11"]	= "Nov";
							$MONTH["12"]	= "Dec";
							
							// Display status name array
							$R_STAT["AP"]	= "Approved";
							$R_STAT["DY"]	= "Denied";
							$R_STAT["PG"]	= "Pending";
							
							// Display room type
							if($R_TYPE == "TS")
							{	$R_TYPE = "Two Single Bed";	}
							elseif($R_TYPE == "QB")
							{	$R_TYPE = "One Queen Bed";	}
							elseif($R_TYPE == "KB")
							{	$R_TYPE = "One King Bed";	}
							
							// Reserve date formater
							$R_YY		= substr($RV_DATE, 0, 4);
							$R_MM		= substr($RV_DATE, 5, 2);
							$R_DD		= substr($RV_DATE, 8, 2);
							
							// Reserve time formater
							$R_TT		= substr($RV_DATE, 11, 9);
							
							// Check-In date formater
							$I_YY		= substr($RV_IN, 0, 4);
							$I_MM		= substr($RV_IN, 5, 2);
							$I_DD		= substr($RV_IN, 8, 2);
							
							// Check-Out date formater
							$O_YY		= substr($RV_OUT, 0, 4);
							$O_MM		= substr($RV_OUT, 5, 2);
							$O_DD		= substr($RV_OUT, 8, 2);
							
							// Status color
							$S_CLR	= null;
							if($RV_STAT == "AP")
							{	$S_CLR = "#2eb82e";	}
							elseif($RV_STAT == "DY")
							{	$S_CLR = "red";	}
							elseif($RV_STAT == "PG")
							{	$S_CLR = "#ff9900";	}
						}
					}
				?>
					<div class="panel panel-default">
						<div class="panel-heading">
							<center><b> Reservation Detail </b></center>
						</div>
						<div class="panel-body">
							<div class="table-responsive">
								<table class="table table-striped table-bordered table-hover">
									<tbody>
									<?php
										if(isset($_GET["ID"]) && isset($RV_ID))
										{
											?>
										<tr>
										  <td valign="center"colspan="2"><div align="center"><b>Accomodation Details</b></div></td>
										</tr>
										<tr>
										  <td valign="center" ><div align="center"><b>Reservation ID</b></div></td>
										  <td valign="center" ><div align="left"><?php print($RV_ID); ?></div></td>
										</tr>
										<tr>
										  <td valign="center" ><div align="center"><b>Room Requested</b></div></td>
										  <td valign="center" ><div align="left"><?php print($RV_ROOM); ?></div></td>
										</tr>
										<tr>
										  <td valign="center" ><div align="center"><b>Room Type</b></div></td>
										  <td valign="center" ><div align="left"><?php print($R_TYPE); ?></div></td>
										</tr>
										<tr>
										  <td valign="center" ><div align="center"><b>House Requested</b></div></td>
										  <td valign="center" ><div align="left"><?php print($DHOUSE); ?></div></td>
										</tr>
										<tr>
										  <td valign="center" ><div align="center"><b>Check-In Date</b></div></td>
										  <td valign="center" ><div align="left"><?php echo "$I_DD $MONTH[$I_MM] $I_YY"; ?></div></td>
										</tr>
										<tr>
										  <td valign="center" ><div align="center"><b>Check-Out Date</b></div></td>
										  <td valign="center" ><div align="left"><?php echo "$O_DD $MONTH[$O_MM] $O_YY"; ?></div></td>
										</tr>
										<tr>
										  <td valign="center" ><div align="center"><b>Status of Application</b></div></td>
										  <td valign="center" ><div align="left"><?php print("<font color=\"$S_CLR\"><b>$R_STAT[$RV_STAT]</b>"); ?></div></td>
										</tr>
										<tr>
										  <td valign="center" ><div align="center"><b>Time of Application</b></div></td>
										  <td valign="center" ><div align="left"><?php echo "$R_TT, $R_DD $MONTH[$R_MM] $R_YY"; ?></div></td>
										</tr>
										<tr>
										  <td valign="center"colspan="2"><div align="center">&nbsp;</div></td>
										</tr>
										<tr>
										  <td valign="center"colspan="2"><div align="center"><b>Student Details</b></div></td>
										</tr>
										<tr>
										  <td valign="center" ><div align="center"><b>Student ID</b></div></td>
										  <td valign="center" ><?php print($S_ID); ?></td>
										</tr>
										<tr>
										  <td valign="center" ><div align="center"><b>Student Name</b></div></td>
										  <td valign="center" ><div align="left"><?php print($S_NAME); ?></div></td>
										</tr>
										<tr>
										  <td valign="center"><div align="center"><b>Student IC</b></div></td>
										  <td valign="center"><div align="left"><?php print($S_IC); ?></div></td>
										</tr>
										<tr>
										  <td valign="center"><div align="center"><b>Programme</b></div></td>
										  <td valign="center"><div align="left"><?php print($S_PROG . " - " . $DPROG); ?></div></td>
										</tr>
										<tr>
										  <td valign="center"><div align="center"><b>Part/Semester</b></div></td>
										  <td valign="center"><div align="left"><?php print($S_PART); ?></div></td>
										</tr>
										<tr>
										  <td valign="center"><div align="center"><b>Phone Number</b></div></td>
										  <td valign="center"><div align="left"><?php print($S_PHONE); ?></div></td>
										</tr>
										<tr>
										  <td valign="center"><div align="center"><b>E-Mail</b></div></td>
										  <td valign="center"><div align="left"><?php print($S_MAIL); ?></div></td>
										</tr>
										<tr>
										  <td valign="center"colspan="2"><div align="center">&nbsp;</div></td>
										</tr>
										<tr>
											<td colspan="2" align="center">
												<?php
													$DBTN = "Cancel";
													if($_SESSION["PRIV"] == "SUPK")
													{
														if($RV_STAT == "PG")
														{
															?>
												<button type="button" class="btn btn-outline btn-success" onClick='confirmApp()'> Approve </button>&nbsp;&nbsp;&nbsp
												<button type="button" class="btn btn-outline btn-danger" onClick='confirmDen()'> Deny </button>&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp
															<?php
														}
														elseif($RV_STAT == "AP")
														{
															?>
												<button type="button" class="btn btn-outline btn-danger" onClick='confirmDen()'> Deny </button>&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp
															<?php
														}
														elseif($RV_STAT == "DY")
														{
															?>
												<button type="button" class="btn btn-outline btn-success" onClick='confirmApp()'> Approve </button>&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp
															<?php
														}
														
														$DBTN = "Delete";
													}
												?>
												<a href="edit_resv.php?ID=<?php print($RV_ID); ?>" target='_blank'><button class="btn btn-outline btn-warning"> Edit </button></a>&nbsp;&nbsp;&nbsp;
												<button type="button" class="btn btn-outline btn-danger" onClick='confirmDel()'> <?php print($DBTN); ?> </button>
											</td>
										</tr>
											<?php
										}
										else
										{
											?>
										<tr>
										  <td valign="center"><div align="center"><i>No data to display</i></div></td>
										</tr>
											<?php
										}
									?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
				<!-- /.col-lg-8 -->
			</div>
			<!-- /.row -->
        </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->

    <!-- jQuery -->
    <script src="bower_components/jquery/dist/jquery.min.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>

    <!-- Metis Menu Plugin JavaScript -->
    <script src="bower_components/metisMenu/dist/metisMenu.min.js"></script>

    <!-- Custom Theme JavaScript -->
    <script src="dist/js/sb-admin-2.js"></script>
	
	<script language="JavaScript">
		function confirmApp()
		{
			var del = confirm("Do you want to approve this application?");
			if (del == true)
			{
				window.location.assign("view_resv.php?APP=<?php print($RV_ID); ?>&ROOM=<?php print($RV_ROOM); ?>");
			} else 
			{
				alert("Application was not approved.");
			}
		}
		
		function confirmDen()
		{
			var del = confirm("Do you want to deny this application?");
			if (del == true)
			{
				window.location.assign("view_resv.php?DEN=<?php print($RV_ID); ?>");
			} else 
			{
				alert("Application was not denied.");
			}
		}
		
		function confirmDel()
		{
			var del = confirm("Do you want to delete this record?");
			if (del == true)
			{
				window.location.assign("view_resv.php?DEL=<?php print($RV_ID); ?>");
			} else 
			{
				alert("Record was not deleted.");
			}
		}
	</script>
</body>

</html>
