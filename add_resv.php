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
		require_login();
		
		if(isset($_POST["REG2"]))
		{
			// A student always books for themselves.
			$RV_STUD	= $_SESSION['lirs_priv'] == "USER" ? (int) $_SESSION['lirs_ident'] : request_id($_POST, 'RV_STUD');
			$RV_IN		= request_date($_POST, 'RV_IN');
			$RV_OUT		= request_date($_POST, 'RV_OUT');
			$RV_ROOM	= request_text($_POST, 'RV_ROOM') ?? '';
			
			// The room is checked again: it may have been taken since the list was shown.
			$exe_sql = $RV_STUD !== null && $RV_IN !== null && $RV_OUT !== null && $RV_IN < $RV_OUT
				&& lirs_room_free($RV_ROOM, $RV_IN, $RV_OUT)
				&& db_query("INSERT INTO RESERVE (RV_STUD, RV_IN, RV_OUT, RV_ROOM) VALUES (?, ?, ?, ?)", [$RV_STUD, $RV_IN, $RV_OUT, $RV_ROOM]);
			
			// confirming the record is added
			if ($exe_sql)
			{
				$last_id = mysqli_insert_id($condb);
				js_alert("Registration successfull!\nRecord was saved into the database.\nYour reservation ID is: " . $last_id, "view_resv.php?ID=" . $last_id);
			}
			else
			{
				js_alert("Registration failed!\nRecord was not saved into the database.");
			}
		}
		
		// Step 1 -> step 2: the dates must be real and the check-out after the check-in.
		if(isset($_POST["REG"]) && !isset($_POST["REG2"]))
		{
			$RV_STUD	= $_SESSION['lirs_priv'] == "USER" ? (int) $_SESSION['lirs_ident'] : request_id($_POST, 'RV_STUD');
			$RV_IN		= request_date($_POST, 'RV_IN');
			$RV_OUT		= request_date($_POST, 'RV_OUT');
			
			if ($RV_STUD === null || $RV_IN === null || $RV_OUT === null || $RV_IN >= $RV_OUT)
			{
				js_alert("Please choose the student, and a check-out date after the check-in date.");
				exit;
			}
		}
		
		$CANCEL_URL = $_SESSION['lirs_priv'] == "USER" ? "list_resv.php" : "list_all.php";
	?>

    <title>[LIRS] - Add Reservation</title>
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
					<h1 class="page-header">Add Reservation</h1>
				</div>
                <!-- /.col-lg-6 -->
			</div>
			<!-- /.row -->
			<div class="row">
                <div class="col-lg-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <b>Reservation Aplication Form</b>
                        </div>
					<?php
						if(!isset($_POST['REG']) && !isset($_POST['REG2']))
						{
					?>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-12">
                                    <form role="form" id="add_resv" action ="add_resv.php" method ="POST">
										<input type="hidden" name="REG" value="YES">
                                        <div class="form-group">
											<label>Student ID</label>
											<?php
												if($_SESSION['lirs_priv'] == "SUPK")
												{
													?>
                                            <select class="form-control" name="RV_STUD">
												<option disabled selected> Please choose </option>
												<option disabled> </option>
													<?php
														$T_PROG		= null;
														$T2_PROG	= null;
														
														$result_choose = db_query("SELECT S_ID, S_NAME, S_PROG FROM STUDENT ORDER BY S_PROG, S_NAME");
														while ($row = mysqli_fetch_array($result_choose))
														{
															
															$S_ID 	= $row["S_ID"];
															$S_NAME = $row["S_NAME"];
															$S_PROG = $row["S_PROG"];
															
															if($T_PROG != $S_PROG)
															{
																if($T_PROG	!= null)
																{
																	echo "</optgroup>";
																}
																echo "<optgroup label=\"" . e($S_PROG) . "\">";
																$T_PROG 	= $S_PROG;
																$T2_PROG	= $S_PROG;
															}
															
															echo '<option value="' . e($S_ID) . '"> ' . e($S_ID . ' - ' . $S_NAME) . '</option>';
														}
														if($T_PROG !== null)
														{
															echo "</optgroup>";
														}
													?>
												</select>
											<?php
												}
												elseif($_SESSION['lirs_priv'] == "USER")
												{
													$T_ID		= e($_SESSION['lirs_ident']);
													$T_NAME		= e($_SESSION['lirs_name']);
													
													echo "<input class=\"form-control\" type=\"hidden\" name=\"RV_STUD\" value=\"$T_ID\"><input class=\"form-control\" type=\"text\" name=\"D_ID\" value=\"$T_ID - $T_NAME\" disabled>";
												}
												?>
											</select>
                                        </div>
										<div class="form-group">
											<label>Check-In Date</label>
                                            <input class="form-control" type="date" min="2000-01-02" name="RV_IN">
                                        </div>
										<div class="form-group">
											<label>Check-Out Date</label>
                                            <input class="form-control" type="date" min="2000-01-02" name="RV_OUT">
                                        </div>
								</div>
								<!-- /.col-lg-12 -->
							</div>
							<!-- /.row -->
							<div class="row" align="center">
								<br />
								<button type="submit" class="btn btn-outline btn-success" form="add_resv" value="Submit">Proceed</button>
								<button type="reset" class="btn btn-outline btn-warning" form="add_resv" value="Reset">Reset</button>
								<a href="<?php echo e($CANCEL_URL); ?>"><button type="button" class="btn btn-outline btn-danger" >Cancel</button></a>
								<br /><br />
							</div>
							<!-- /.row -->
									</form>
						</div>
						<!-- /.panel-body -->
					<?php
						}
						
						if(isset($_POST["REG"]))
						{
							// rooms taken for these dates
							$BUSY = lirs_busy_rooms($RV_IN, $RV_OUT);
							?>
						<div class="panel-body">
							<div class="row">
								<div class="col-lg-12">
									<form role="form" id="add_resv2" action ="add_resv.php" method ="POST">
										<input type="hidden" name="REG2" value="YES">
										<input type="hidden" name="RV_STUD" value="<?php echo e($RV_STUD); ?>">
										<input type="hidden" name="RV_IN" value="<?php echo e($RV_IN); ?>">
										<input type="hidden" name="RV_OUT" value="<?php echo e($RV_OUT); ?>">
										<div class="form-group">
											<label>Student ID</label>
                                            <input class="form-control" type="text" value="<?php echo e($RV_STUD); ?>" name="a" disabled>
                                        </div>
										<div class="form-group">
											<label>Check-In Date</label>
                                            <input class="form-control" type="date" value="<?php echo e($RV_IN); ?>" name="b" disabled>
                                        </div>
										<div class="form-group">
											<label>Check-Out Date</label>
                                            <input class="form-control" type="date" value="<?php echo e($RV_OUT); ?>" name="c" disabled>
                                        </div>
										<div class="form-group">
											<label>Room</label>
											<select class="form-control" name="RV_ROOM">
												<option disabled selected> Please choose </option>
												<option disabled> </option>
												<?php
													$result_h	= db_query("SELECT * FROM HOUSE ORDER BY H_NAME");
													
													$ind	= 0;
													while ($row_h = mysqli_fetch_array($result_h))
													{
														
														$H_ID 	= $row_h["H_ID"];
														$H_NAME = $row_h["H_NAME"];
														
														echo "<optgroup label=\"" . e($H_NAME) . "\">";
														
														$result_r	= db_query("SELECT R_ID, R_TYPE FROM ROOM WHERE R_STAT = TRUE AND R_HOUSE = ? ORDER BY R_TYPE", [$H_ID]);
														while ($row_r = mysqli_fetch_array($result_r))
														{
															$R_ID		= $row_r["R_ID"];
															$R_TYPE		= $row_r["R_TYPE"];
															
															if(!isset($BUSY[$R_ID]))
															{
																echo '<option value="' . e($R_ID) . '"> ' . e($R_ID . ' - ' . (LIRS_ROOM_TYPES[$R_TYPE] ?? $R_TYPE)) . '</option>';
																$ind++;
															}
														}
														
														echo "</optgroup><option disabled> </option>";
													}
													
													if($ind == 0)
													{
														echo "<option disabled> No room available </option>";
													}
												?>
											</select>
										</div>
								</div>
								<!-- /.col-lg-12 -->
							</div>
							<!-- /.row -->
							<div class="row" align="center">
								<br />
								<button type="submit" class="btn btn-outline btn-success" form="add_resv2" value="Submit">Submit</button>
								<button type="button" class="btn btn-outline btn-warning" onclick="goBack()">Go Back</button>
								<a href="<?php echo e($CANCEL_URL); ?>"><button type="button" class="btn btn-outline btn-danger" >Cancel</button></a>

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
	
	<script>
		function goBack() {
			window.history.back();
		}
	</script>

</body>

</html>
