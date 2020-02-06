<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">	
	<meta name="keywords" content="LIRS" />
	<meta name="description" content="Ledang Inn Reservation System" />
	<?php
		session_start();
		if(!$_SESSION['LOGIN'] || $_SESSION['PRIV'] != "SUPK")
		{
			header("Location: index.php?error=exp");
			exit;
		}
		
		include("connectDB.php");
	?>

    <title>[LIRS] - List Student</title>
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
					<h1 class="page-header">List Students</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <center><b>Student List</b></center>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="dataTable_wrapper">
                                <table class="table table-striped table-bordered table-hover" id="dataTables-data">
                                    <thead>
                                        <tr>
                                            <td align="center"><b>No.</b></td>
                                            <td align="center"><b>Student ID</b></td>
                                            <td align="center"><b>Name</b></td>
                                            <td align="center"><b>Part</b></td>
                                            <td align="center"><b>Programme</b></td>
                                        </tr>
                                    </thead>
                                    <tbody>
                        <?php                            
							// create the listing query
							$sql = "SELECT S_ID, S_NAME, S_PART, S_PROG FROM STUDENT ORDER BY S_PART, S_PROG, S_NAME";
								
							// execute listing query
							$result	= mysql_query($sql) or die("SQL select statement failed");
							
							// Initialise index number
							$BIL = 0;
							
							// Display programme name array
							$sql_dprog			= "SELECT * FROM PROGRAMME ORDER BY ID_PROG";
							$result_dprog		= mysql_query($sql_dprog);
							while($row_dprog	= mysql_fetch_array($result_dprog))
							{
								$DID_PROG			= $row_dprog["ID_PROG"];
								$DPROG2				= $row_dprog["PROG"];
								$DPROG[$DID_PROG]	= $DPROG2;
							}
							
							// iterate through all rows in result set
							while ($row = mysql_fetch_array($result))
							{
								$BIL++;
								
								// extract specific fields
								$S_ID 	= $row["S_ID"];
								$S_NAME = $row["S_NAME"];
								$S_PART	= $row["S_PART"];
								$S_PROG	= $row["S_PROG"];
								
								// output student information
								echo "<tr>\n\t\t\t\t\t\t\t";
								echo "<td align=center>$BIL</td>\n\t\t\t\t\t\t\t";
								echo "<td align=center><a target='_blank' href='view_stud.php?ID=$S_ID'>$S_ID</a></td>\n\t\t\t\t\t\t\t<td>$S_NAME</td>\n\t\t\t\t\t\t\t<td align=center>$S_PART</td>\n\t\t\t\t\t\t\t<td align=center>$S_PROG - $DPROG[$S_PROG]</td>\n\t\t\t\t\t\t\t";
								echo "</tr>\n\t\t\t\t\t\t"; 
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

</body>

</html>
