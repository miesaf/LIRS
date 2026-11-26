<?php ob_start(); ?>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">	
	<meta name="keywords" content="LIRS" />
	<meta name="description" content="Ledang Inn Reservation System" />
	<?php
		session_start();
		require_once __DIR__ . '/connectDB.php';
		require_login('SUPK');
	?>

    <title>[LIRS] - List Pendings</title>
	<link rel="shortcut icon" href="favicon.ico">

    <!-- Bootstrap Core CSS -->
    <link href="bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- MetisMenu CSS -->
    <link href="bower_components/metisMenu/dist/metisMenu.min.css" rel="stylesheet">
	
	<!-- DataTables CSS -->
    <link href="bower_components/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.css" rel="stylesheet">

    <!-- DataTables Responsive CSS -->
    <link href="bower_components/datatables-responsive/css/dataTables.responsive.css" rel="stylesheet">

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
					<h1 class="page-header">List Pending Reservation</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <center><b>Pending Reservation List</b></center>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="dataTable_wrapper">
                                <table class="table table-striped table-bordered table-hover" id="dataTables-data">
                                    <thead>
                                        <tr>
                                            <td align="center"><b>No.</b></td>
                                            <td align="center"><b>Reserve ID</b></td>
                                            <td align="center"><b>Reserved By</b></td>
                                            <td align="center"><b>Reservation Detail</b></td>
                                            <td align="center"><b>Accommodation</b></td>
                                        </tr>
                                    </thead>
                                    <tbody>
                        <?php
							// student names
							$DSN = [];
							$result_dsn	= db_query("SELECT S_ID, S_NAME FROM STUDENT ORDER BY S_ID");
							while($row_dsn = mysqli_fetch_array($result_dsn))
							{
								$DSN[$row_dsn["S_ID"]] = $row_dsn["S_NAME"];
							}
							
							// Initialise index number
							$BIL = 1;
							
							// iterate through all rows in result set
							$result	= db_query("SELECT RESERVE.*, HOUSE.H_NAME FROM RESERVE LEFT JOIN ROOM ON ROOM.R_ID = RESERVE.RV_ROOM LEFT JOIN HOUSE ON HOUSE.H_ID = ROOM.R_HOUSE WHERE RV_STAT = 'PG' ORDER BY RV_DATE") or die("SQL select statement failed");
							while ($row = mysqli_fetch_array($result))
							{
								// extract specific fields
								$RV_ID		= e($row["RV_ID"]);
								$RV_STUD	= e($row["RV_STUD"]);
								$D_NAME		= e($DSN[$row["RV_STUD"]] ?? '');
								$D_DATE		= e(lirs_date($row["RV_DATE"]));
								$D_IN		= e(lirs_date($row["RV_IN"]));
								$D_OUT		= e(lirs_date($row["RV_OUT"]));
								$RV_ROOM	= e($row["RV_ROOM"]);
								$DHOUSE		= e($row["H_NAME"]);

								// output reservation information
								echo "<tr>\n\t\t\t\t\t\t\t";
								echo "<td align=\"center\">$BIL</td>\n\t\t\t\t\t\t\t";
								echo "<td align=\"center\" title=\"Click here to view full detail\"><a href=\"view_resv.php?ID=$RV_ID\" target='_blank'><b>$RV_ID</b></a><br>($D_DATE)</td>\n\t\t\t\t\t\t\t
										<td align=\"center\">$D_NAME<br>(Student ID: $RV_STUD)</td>\n\t\t\t\t\t\t\t
										<td align=\"center\">Check-In: <font color=\"#2eb82e\"><b>$D_IN</b></font><br>Check-Out: <font color=\"red\"><b>$D_OUT</b></font></td>\n\t\t\t\t\t\t\t
										<td align=\"center\">Room: <b>$RV_ROOM</b><br>House: <b>$DHOUSE</b></td>\n\t\t\t\t\t\t\t";
								echo "</tr>\n\t\t\t\t\t\t";
								
								$BIL++;
							}
                        ?>
									</tbody>
                                </table>
                            </div>
                            <!-- /.dataTable_wrapper -->
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel-default -->
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
		</div>
		<!-- /.page-wrapper -->

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
	
	<!-- DataTables JavaScript -->
    <script src="bower_components/datatables/media/js/jquery.dataTables.min.js"></script>
    <script src="bower_components/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.min.js"></script>
    
    <!-- Page-Level Demo Scripts - Tables - Use for reference -->
    <script>
    $(document).ready(function() {
        $('#dataTables-data').DataTable({
                responsive: true
        });
    });
    </script>
	
	<script language="JavaScript">
		function confirmDel(nums)
		{
			var del = confirm("Do you want to deny this application?");
			if (del == true)
			{
				window.location.assign("list_pending.php?DENY=" + nums);
			} else 
			{
				alert("Application was not denied.");
			}
		}
	</script>
</body>

</html>
