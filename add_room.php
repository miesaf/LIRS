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
		
		if(isset($_POST["REG"]))
		{
			// Variables from pendaftaran.php
			$R_ID		= mysql_real_escape_string($_POST['R_ID']);
			$R_TYPE		= mysql_real_escape_string($_POST['R_TYPE']);
			$R_HOUSE	= mysql_real_escape_string($_POST['R_HOUSE']);
			$R_STAT		= mysql_real_escape_string($_POST['R_STAT']);
			
			//SQL query command
			$sql="INSERT INTO ROOM (R_ID, R_TYPE, R_HOUSE, R_STAT) VALUES ('$R_ID', '$R_TYPE', '$R_HOUSE', $R_STAT)";
			
			// execute query
			$exe_sql = mysql_query($sql);
			
			// confirming the record is added
			if ($exe_sql)
			{
				echo '<html>
						<head>
							<script>
								window.alert("Registration successfull!\nRecord was saved into the database.");
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
								window.alert("Registration failed!\nRecord was not saved into the database.");
								window.history.go(-1);
							</script>
						</head>
					</html>';
			}
		}
	?>

    <title>[LIRS] - Add Room</title>
	<link rel="shortcut icon" href="favicon.ico">

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
				<div class="col-lg-6">
					<h1 class="page-header">Add Room</h1>
				</div>
                <!-- /.col-lg-6 -->
			</div>
			<!-- /.row -->
			<div class="row">
                <div class="col-lg-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <b>Room Registration Form</b>
                        </div>
					<?php
						if(!isset($_POST['REG']))
						{
					?>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-12">
                                    <form role="form" id="add_room" action ="add_room.php" method ="POST">
										<input type="hidden" name="REG" value="YES">
                                        <div class="form-group">
											<label>Room ID</label>
                                            <input class="form-control" type="text" size="5" maxlength="5" name="R_ID">
											<p class="help-block">Eg: LI001</p>
                                        </div>
										<div class="form-group">
											<label>Room Type</label>
                                            <select class="form-control" name="R_TYPE">
												<option disabled selected> Please choose </option>
												<option disabled> </option>
												<option value="TS"> Two Single Bed</option>
												<option value="QB"> One Queen Bed</option>
												<option value="KB"> One King Bed</option>
											</select>
                                        </div>
										<div class="form-group">
											<label>Room House</label>
                                            <select class="form-control" name="R_HOUSE">
												<option disabled selected> Please choose </option>
												<option disabled> </option>
												<?php
													$sql_choose = "SELECT * FROM HOUSE ORDER BY H_NAME";
													$result_choose = mysql_query($sql_choose);
													while ($row = mysql_fetch_array($result_choose))
													{
														$H1 = $row["H_ID"];
														$H2 = $row["H_NAME"];
														echo '<option value="' . $H1 . '"> ' . $H2 . '</option>';
													}
												?>
											</select>
                                        </div>
										<div class="form-group">
											<label>Room Status</label>
											<select class="form-control" name="R_STAT">
												<option disabled selected> Please choose </option>
												<option disabled> </option>
												<option value="1"> Available</option>
												<option value="0"> Not Available</option>
											</select>
										</div>
										<div class="form-group" align="center">
											<br>
											<button type="submit" class="btn btn-outline btn-success" form="add_room" value="Submit">Submit</button>
											<button type="reset" class="btn btn-outline btn-warning" form="add_room" value="Reset">Reset</button>
											<a href="list_room.php"><button type="button" class="btn btn-outline btn-danger" >Cancel</button></a>
										</div>
									</form>
								</div>
								<!-- /.col-lg-12 -->
							</div>
							<!-- /.row -->
						</div>
						<!-- /.panel-body -->
						<?php
							}
						?>
					</div>
					<!-- /.panel-default -->
				</div>
				<!-- /.col-lg-6 -->
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

</body>

</html>
