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
		
		if(isset($_POST["REG"]))
		{
			$T_ID		= request_text($_POST, 'T_ID') ?? '';
			$R_ID		= request_text($_POST, 'R_ID') ?? '';
			$R_TYPE		= request_text($_POST, 'R_TYPE') ?? '';
			$R_HOUSE	= request_text($_POST, 'R_HOUSE') ?? '';
			$R_STAT		= request_text($_POST, 'R_STAT');
			
			$exe_sql = $T_ID !== '' && $R_ID !== '' && isset(LIRS_ROOM_TYPES[$R_TYPE]) && in_array($R_STAT, ["0", "1"], true)
				&& db_query("UPDATE ROOM SET R_ID = ?, R_TYPE = ?, R_HOUSE = ?, R_STAT = ? WHERE R_ID = ?", [$R_ID, $R_TYPE, $R_HOUSE, (int) $R_STAT, $T_ID]);
			
			// confirming the record is saved
			if ($exe_sql)
			{
				js_alert("Edition successfull!\nRecord was saved into the database.", "list_room.php");
			}
			else
			{
				js_alert("Edition failed!\nRecord was not saved into the database.");
			}
		}
		
		$R_ID		= null;
		$R_TYPE		= null;
		$R_HOUSE	= null;
		$R_STAT		= null;
		if(request_text($_GET, "ID"))
		{
			$row = db_row("SELECT * FROM ROOM WHERE R_ID = ?", [request_text($_GET, "ID")]);
			if ($row)
			{
				$R_ID 		= $row["R_ID"];
				$R_TYPE 	= $row["R_TYPE"];
				$R_HOUSE 	= $row["R_HOUSE"];
				$R_STAT 	= $row["R_STAT"];
			}
		}
	?>

    <title>[LIRS] - Edit Room</title>
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
				<div class="col-lg-6">
					<h1 class="page-header">Edit Room</h1>
				</div>
                <!-- /.col-lg-6 -->
			</div>
			<!-- /.row -->
				<!-- mula     ##########################################################################################     mula -->
			<div class="row">
                <div class="col-lg-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <b>Room Amendment Form</b>
                        </div>
					<?php
						if($R_ID !== null && !isset($_POST["REG"]))
						{
					?>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-12">
                                    <form role="form" id="edit_room" action ="edit_room.php" method ="POST">
										<input type="hidden" name="REG" value="YES">
										<input type="hidden" name="T_ID" value="<?php echo e($R_ID); ?>">
                                        <div class="form-group">
											<label>Room ID</label>
                                            <input class="form-control" type="text" size="5" maxlength="5" name="R_ID" value="<?php echo e($R_ID); ?>">
											<p class="help-block">Eg: LI001</p>
                                        </div>
										<div class="form-group">
											<label>Room Type</label>
                                            <select class="form-control" name="R_TYPE">
												<option disabled selected> Please choose </option>
												<option disabled> </option>
												<option value="TS" <?php if($R_TYPE == "TS"){ echo "selected"; } ?>> Two Single Bed</option>
												<option value="QB" <?php if($R_TYPE == "QB"){ echo "selected"; } ?>> One Queen Bed</option>
												<option value="KB" <?php if($R_TYPE == "KB"){ echo "selected"; } ?>> One King Bed</option>
											</select>
                                        </div>
										<div class="form-group">
											<label>Room House</label>
                                            <select class="form-control" name="R_HOUSE">
												<option disabled selected> Please choose </option>
												<option disabled> </option>
												<?php
													$result_choose = db_query("SELECT * FROM HOUSE ORDER BY H_NAME");
													while ($row = mysqli_fetch_array($result_choose))
													{
														$H1 = $row["H_ID"];
														$H2 = $row["H_NAME"];
														
														$sel = null;
														if($H1 == $R_HOUSE)
														{ $sel = "selected"; }
														
														echo '<option value="' . e($H1) . '" ' . $sel . '> ' . e($H2) . '</option>';
													}
												?>
											</select>
                                        </div>
										<div class="form-group">
											<label>Room Status</label>
											<select class="form-control" name="R_STAT">
												<option disabled selected> Please choose </option>
												<option disabled> </option>
												<option value="1" <?php if($R_STAT == 1){ echo "selected"; } ?>> Available</option>
												<option value="0" <?php if($R_STAT == 0){ echo "selected"; } ?>
> Not Available</option>
											</select>
										</div>
								</div>
								<!-- /.col-lg-12 -->
							</div>
							<!-- /.row -->
							<div class="row" align="center">
								<br />
								<button type="submit" class="btn btn-outline btn-success" form="edit_room" value="Submit">Submit</button>
								<button type="reset" class="btn btn-outline btn-warning"form="edit_room" value="Reset">Reset</button>
								<a href="list_room.php"><button type="button" class="btn btn-outline btn-danger" >Cancel</button></a>
								<br /><br />
							</div>
							<!-- /.row -->
									</form>
						</div>
						<!-- /.panel-body -->
					</div>
					<!-- /.panel-default -->
					<?php
						}
					?>
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
