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

    <title>[LIRS] - List Rooms</title>
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
					<h1 class="page-header">List Rooms</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <center><b>Room List</b></center>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="dataTable_wrapper">
                                <table class="table table-striped table-bordered table-hover" id="dataTables-data">
                                    <thead>
                                        <tr>
                                            <td align="center"><b>No.</b></td>
                                            <td align="center"><b>Room ID</b></td>
                                            <td align="center"><b>Room Type</b></td>
                                            <td align="center"><b>House</b></td>
                                            <td align="center"><b>Room Status</b></td>
											<td align="center"><b>Action</b></td>
                                        </tr>
                                    </thead>
                                    <tbody>
                        <?php                            
							$BIL = 1;
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
								
								// output room information
								echo "<tr>\n\t\t\t\t\t\t\t";
								echo "<td align=\"center\">$BIL</td>\n\t\t\t\t\t\t\t";
								echo "<td align=\"center\">$R_ID</td>\n\t\t\t\t\t\t\t
										<td align=\"center\">$R_TYPE</td>\n\t\t\t\t\t\t\t
										<td align=\"center\">$DHOUSE</td>\n\t\t\t\t\t\t\t
										<td align=\"center\">$R_STAT</td>\n\t\t\t\t\t\t\t";
								echo "<td align=\"center\"> <a href=\"edit_room.php?ID=$R_ID\"><button type=\"button\"  class=\"btn btn-outline btn-warning\">Edit</button></a> <button type=\"button\"  class=\"btn btn-outline btn-danger\" value=\"Delete\" onClick='confirmDel(\"$R_ID\")'>Delete</button> </td>\n\t\t\t\t\t\t\t";
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
