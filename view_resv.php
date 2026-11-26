<?php ob_start(); ?>
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
		require_once __DIR__ . '/connectDB.php';
		require_login();
		
		$LIST_URL = $_SESSION['lirs_priv'] == "USER" ? "list_resv.php" : "list_all.php";
		
		if($_SESSION['lirs_priv'] == "SUPK")
		{
			// Approve Process: refused when the room is approved for someone else on any of the same nights.
			if(isset($_GET["APP"]))
			{
				$APP_ID		= request_id($_GET, "APP");
				$APP		= $APP_ID === null ? null : db_row("SELECT RV_ID, RV_IN, RV_OUT, RV_ROOM FROM RESERVE WHERE RV_ID = ?", [$APP_ID]);
				
				if(!$APP)
				{
					js_alert("Failed to approve the application.", $LIST_URL);
				}
				elseif(isset(lirs_busy_rooms($APP["RV_IN"], $APP["RV_OUT"], $APP_ID, true)[(string) $APP["RV_ROOM"]]))
				{
					js_alert("Failed to approve the application due to overlapped with another approved aplication.");
				}
				elseif(db_query("UPDATE RESERVE SET RV_STAT = 'AP' WHERE RV_ID = ?", [$APP_ID]))
				{
					js_alert("Application was approved.", "view_resv.php?ID=" . $APP_ID);
				}
				else
				{
					js_alert("Failed to approve the application.");
				}
			}
			
			// Deny Process
			if(isset($_GET["DEN"]))
			{
				$DEN_ID	= request_id($_GET, "DEN");
				
				if ($DEN_ID !== null && db_query("UPDATE RESERVE SET RV_STAT = 'DY' WHERE RV_ID = ?", [$DEN_ID]) && db_affected() === 1)
				{
					js_alert("Application was denied.", "view_resv.php?ID=" . $DEN_ID);
				}
				else
				{
					js_alert("Failed to deny application.");
				}
			}
		}
		
		// Delete Process: a student deletes (cancels) only their own reservation.
		if(isset($_GET["DEL"]))
		{
			$DEL_ID	= request_id($_GET, "DEL");
			
			if($_SESSION['lirs_priv'] == "USER")
			{
				$exe_sql = $DEL_ID !== null && db_query("DELETE FROM RESERVE WHERE RV_ID = ? AND RV_STUD = ?", [$DEL_ID, $_SESSION['lirs_ident']]);
			}
			else
			{
				$exe_sql = $DEL_ID !== null && db_query("DELETE FROM RESERVE WHERE RV_ID = ?", [$DEL_ID]);
			}
			
			if ($exe_sql && db_affected() === 1)
			{
				js_alert("Record was removed from the database.", $LIST_URL);
			}
			else
			{
				js_alert("Failed to remove record from the database.");
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
					Welcome, <strong><?php echo e($_SESSION['lirs_name']); ?></strong>
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
					$RV_ID	= null;
					$ID1	= request_id($_GET, "ID");
					if($ID1 !== null)
					{
						if($_SESSION['lirs_priv'] == "USER")
						{
							$row = db_row("SELECT * FROM RESERVE WHERE RV_STUD = ? AND RV_ID = ?", [$_SESSION['lirs_ident'], $ID1]);
						}
						else
						{
							$row = db_row("SELECT * FROM RESERVE WHERE RV_ID = ?", [$ID1]);
						}
						
						if($row)
						{
							// extract specific fields
							$RV_ID		= $row["RV_ID"];
							$RV_STUD	= $row["RV_STUD"];
							$RV_DATE	= $row["RV_DATE"];
							$RV_IN		= $row["RV_IN"];
							$RV_OUT		= $row["RV_OUT"];
							$RV_ROOM	= $row["RV_ROOM"];
							$RV_STAT	= $row["RV_STAT"];
							
							// student info
							$row2		= db_row("SELECT * FROM STUDENT WHERE S_ID = ?", [$RV_STUD]) ?? [];
							$S_ID		= $row2["S_ID"] ?? '';
							$S_NAME		= $row2["S_NAME"] ?? '';
							$S_IC		= $row2["S_IC"] ?? '';
							$S_PROG		= $row2["S_PROG"] ?? '';
							$S_PART		= $row2["S_PART"] ?? '';
							$S_PHONE	= $row2["S_PHONE"] ?? '';
							$S_MAIL		= $row2["S_MAIL"] ?? '';
							
							// Room, house and programme names
							$row3		= db_row("SELECT ROOM.R_TYPE, HOUSE.H_NAME FROM ROOM LEFT JOIN HOUSE ON HOUSE.H_ID = ROOM.R_HOUSE WHERE ROOM.R_ID = ?", [$RV_ROOM]) ?? [];
							$R_TYPE		= LIRS_ROOM_TYPES[$row3["R_TYPE"] ?? ''] ?? ($row3["R_TYPE"] ?? '');
							$DHOUSE		= $row3["H_NAME"] ?? '';
							$DPROG		= db_row("SELECT PROG FROM PROGRAMME WHERE ID_PROG = ?", [$S_PROG])["PROG"] ?? '';
							
							// Status name and colour
							$D_STAT		= LIRS_STATUS[$RV_STAT] ?? $RV_STAT;
							$S_CLR		= LIRS_STATUS_COLOUR[$RV_STAT] ?? '';
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
										if($RV_ID !== null)
										{
											?>
										<tr>
										  <td valign="center"colspan="2"><div align="center"><b>Accomodation Details</b></div></td>
										</tr>
										<tr>
										  <td valign="center" ><div align="center"><b>Reservation ID</b></div></td>
										  <td valign="center" ><div align="left"><?php echo e($RV_ID); ?></div></td>
										</tr>
										<tr>
										  <td valign="center" ><div align="center"><b>Room Requested</b></div></td>
										  <td valign="center" ><div align="left"><?php echo e($RV_ROOM); ?></div></td>
										</tr>
										<tr>
										  <td valign="center" ><div align="center"><b>Room Type</b></div></td>
										  <td valign="center" ><div align="left"><?php echo e($R_TYPE); ?></div></td>
										</tr>
										<tr>
										  <td valign="center" ><div align="center"><b>House Requested</b></div></td>
										  <td valign="center" ><div align="left"><?php echo e($DHOUSE); ?></div></td>
										</tr>
										<tr>
										  <td valign="center" ><div align="center"><b>Check-In Date</b></div></td>
										  <td valign="center" ><div align="left"><?php echo e(lirs_date($RV_IN)); ?></div></td>
										</tr>
										<tr>
										  <td valign="center" ><div align="center"><b>Check-Out Date</b></div></td>
										  <td valign="center" ><div align="left"><?php echo e(lirs_date($RV_OUT)); ?></div></td>
										</tr>
										<tr>
										  <td valign="center" ><div align="center"><b>Status of Application</b></div></td>
										  <td valign="center" ><div align="left"><?php echo "<font color=\"" . e($S_CLR) . "\"><b>" . e($D_STAT) . "</b></font>"; ?></div></td>
										</tr>
										<tr>
										  <td valign="center" ><div align="center"><b>Time of Application</b></div></td>
										  <td valign="center" ><div align="left"><?php echo e(lirs_date($RV_DATE, true)); ?></div></td>
										</tr>
										<tr>
										  <td valign="center"colspan="2"><div align="center">&nbsp;</div></td>
										</tr>
										<tr>
										  <td valign="center"colspan="2"><div align="center"><b>Student Details</b></div></td>
										</tr>
										<tr>
										  <td valign="center" ><div align="center"><b>Student ID</b></div></td>
										  <td valign="center" ><?php echo e($S_ID); ?></td>
										</tr>
										<tr>
										  <td valign="center" ><div align="center"><b>Student Name</b></div></td>
										  <td valign="center" ><div align="left"><?php echo e($S_NAME); ?></div></td>
										</tr>
										<tr>
										  <td valign="center"><div align="center"><b>Student IC</b></div></td>
										  <td valign="center"><div align="left"><?php echo e($S_IC); ?></div></td>
										</tr>
										<tr>
										  <td valign="center"><div align="center"><b>Programme</b></div></td>
										  <td valign="center"><div align="left"><?php echo e($S_PROG . " - " . $DPROG); ?></div></td>
										</tr>
										<tr>
										  <td valign="center"><div align="center"><b>Part/Semester</b></div></td>
										  <td valign="center"><div align="left"><?php echo e($S_PART); ?></div></td>
										</tr>
										<tr>
										  <td valign="center"><div align="center"><b>Phone Number</b></div></td>
										  <td valign="center"><div align="left"><?php echo e($S_PHONE); ?></div></td>
										</tr>
										<tr>
										  <td valign="center"><div align="center"><b>E-Mail</b></div></td>
										  <td valign="center"><div align="left"><?php echo e($S_MAIL); ?></div></td>
										</tr>
										<tr>
										  <td valign="center"colspan="2"><div align="center">&nbsp;</div></td>
										</tr>
										<tr>
											<td colspan="2" align="center">
												<?php
													$DBTN = "Cancel";
													if($_SESSION['lirs_priv'] == "SUPK")
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
												<a href="edit_resv.php?ID=<?php echo e($RV_ID); ?>" target='_blank'><button class="btn btn-outline btn-warning"> Edit </button></a>&nbsp;&nbsp;&nbsp;
												<button type="button" class="btn btn-outline btn-danger" onClick='confirmDel()'> <?php echo e($DBTN); ?> </button>
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
				window.location.assign("view_resv.php?APP=" + <?php echo js((string) $RV_ID); ?>);
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
				window.location.assign("view_resv.php?DEN=" + <?php echo js((string) $RV_ID); ?>);
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
				window.location.assign("view_resv.php?DEL=" + <?php echo js((string) $RV_ID); ?>);

			} else 
			{
				alert("Record was not deleted.");
			}
		}
	</script>
</body>

</html>
