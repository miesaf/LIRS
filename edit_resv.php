<html lang="en">

<head>

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
		
		if(isset($_POST["REG2"]))
		{
			// Variables from pendaftaran.php
			$TRV_ID		= mysql_real_escape_string($_POST['TRV_ID']);
			$RV_STUD	= mysql_real_escape_string($_POST['RV_STUD']);
			$RV_IN		= mysql_real_escape_string($_POST['RV_IN']);
			$RV_OUT		= mysql_real_escape_string($_POST['RV_OUT']);
			$RV_ROOM	= mysql_real_escape_string($_POST['RV_ROOM']);
			
			$h			= "8";	// to rematch time with Malaysian GMT +8 time (please truncate the +/- sign)
			$hm			= $h * 60; 
			$ms			= $hm * 60;
			$NEW_DATE	= gmdate("Y-m-d H:i:s", time()+($ms));	// use (-) for -ve GMT and (+) for +ve GMT
						/* 	-----------------------
							Timestamp Configuration
							-----------------------
							Y - Full year
							m - month with leading zero
							d - date with leading zero
							H - 24-hour format hour
							i - minute with leading zero
							s - second with leading zero
							
							# Please reconfigure server time to:
							timezone - Asia/Kuala_Lumour
							latitude = 3.133333
							longitude = 101.683333
							
							#date() - will fetch server date
							#gmdate() - will fetch GMT 0:00 date
						*/
			
			//SQL query command
			$sql="UPDATE RESERVE SET RV_STUD = $RV_STUD, RV_IN = \"$RV_IN\", RV_OUT = \"$RV_OUT\", RV_ROOM = \"$RV_ROOM\", RV_DATE = \"$NEW_DATE\", RV_STAT = \"PG\" WHERE RV_ID = $TRV_ID";
			
			// execute query
			$exe_sql = mysql_query($sql);
			
			// confirming the record was edited
			if ($exe_sql)
			{
				if($_SESSION["PRIV"] == "USER")
				{	$ALT = "window.alert(\"Edition successfull!\nYour application must be re-verified if it was approved before editing.\");";	}
				elseif($_SESSION["PRIV"] == "SUPK")
				{	$ALT = "window.alert(\"Edition successfull!\Application must be re-verified if it was approved before editing.\");";	}
				
				echo '<html>
						<head>
							<script>
								window.alert("Edition successfull!\nRecord was saved into the database.");
							</script>
							<meta http-equiv="refresh" content="0; url=view_resv.php?ID=' . $TRV_ID . '" />
						</head>
					</html>';
			}
			else
			{
				echo "SQL insert statement failed.<br>" . mysql_error();
				echo '<html>
						<head>
							<script>
								window.alert("Edition failed!\nRecord was not saved into the database.");
								window.history.go(-1);
							</script>
						</head>
					</html>';
			}
		}
	?>

    <title>[LIRS] - Edit Reservation</title>
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
					<h1 class="page-header">Edit Reservation</h1>
				</div>
                <!-- /.col-lg-6 -->
			</div>
			<!-- /.row -->
			<div class="row">
                <div class="col-lg-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <b>Reservation Amendment Form</b>
                        </div>
					<?php
						if(!isset($_POST['REG']) && !isset($_POST['REG2']))
						{
							$E_ID	=	$_GET["ID"];
							?>
						<html>
							<head>
								<script>
									window.alert("If you edit reservation detail, the status of your aplication will change or remain as PENDING!");
								</script>
							</head>
						</html>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-12">
                                    <form role="form" id="edit_resv" action ="edit_resv.php" method ="POST">
										<input type="hidden" name="REG" value="YES">
										<input type="hidden" name="TRV_ID" value="<?php print($E_ID); ?>">
										<div class="form-group">
											<label>Reservation ID</label>
                                            <input class="form-control" type="text" value="<?php print($E_ID); ?>" name="ga" disabled>
                                        </div>
                                        <div class="form-group">
											<label>Student ID</label>
											<?php
												if($_SESSION["PRIV"] == "SUPK")
												{
													// create the query
													$sql	= "SELECT * FROM RESERVE WHERE RV_ID = \"$E_ID\"";
													
													// execute query
													$result = mysql_query($sql) or die("SQL select statement failed");
													
													// iterate through all rows in result set
													$row = mysql_fetch_array($result);
													
													$TRV_ID		= $row["RV_ID"];
													$RV_STUD	= $row["RV_STUD"];
													$RV_IN		= $row["RV_IN"];
													$RV_OUT		= $row["RV_OUT"];
													$RV_ROOM	= $row["RV_ROOM"];
													
													?>
                                            <select class="form-control" name="RV_STUD">
												<option disabled> Please choose </option>
												<option disabled> </option>
													<?php
														$T_PROG		= null;
														$T2_PROG	= null;
														
														$sql_choose = "SELECT S_ID, S_NAME, S_PROG FROM STUDENT ORDER BY S_PROG, S_NAME";
														$result_choose = mysql_query($sql_choose);
														while ($row = mysql_fetch_array($result_choose))
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
																echo "<optgroup label=\"$S_PROG\">";
																$T_PROG 	= $S_PROG;
																$T2_PROG	= $S_PROG;
															}
															
															$SEL_ID	= null;
															if($RV_STUD == $S_ID)
															{
																$SEL_ID	= "selected";
															}
															
															echo '<option value="' . $S_ID . '" ' . $SEL_ID . '> ' . $S_ID . ' - ' . $S_NAME . '</option>';
														}
													?>
											</select>
											<?php
												}
												elseif($_SESSION["PRIV"] == "USER")
												{
													$T_ID		= $_SESSION["IDENT"];
													$T_NAME		= $_SESSION["NAME"];
													
													// create the query
													$sql	= "SELECT RV_ID, RV_IN, RV_OUT, RV_ROOM FROM RESERVE WHERE RV_ID = \"$E_ID\" AND RV_STUD = $T_ID";
													
													// execute query
													$result = mysql_query($sql) or die("SQL select statement failed");
													
													// iterate through all rows in result set
													$row = mysql_fetch_array($result);
													
													$TRV_ID		= $row["RV_ID"];
													$RV_IN		= $row["RV_IN"];
													$RV_OUT		= $row["RV_OUT"];
													$RV_ROOM	= $row["RV_ROOM"];
													
													echo "<input class=\"form-control\" type=\"hidden\" name=\"RV_STUD\" value=\"$T_ID\"><input class=\"form-control\" type=\"text\" name=\"D_ID\" value=\"$T_ID - $T_NAME\" disabled>";
												}
												?>
											<input class="form-control" type="hidden" name="RV_ROOM" value="<?php print($RV_ROOM); ?>">
                                        </div>
										<div class="form-group">
											<label>Check-In Date</label>
                                            <input class="form-control" type="date" min="2000-01-02" name="RV_IN" value="<?php print($RV_IN); ?>">
                                        </div>
										<div class="form-group">
											<label>Check-Out Date</label>
                                            <input class="form-control" type="date" min="2000-01-02" name="RV_OUT" value="<?php print($RV_OUT); ?>">
                                        </div>
								</div>
								<!-- /.col-lg-12 -->
							</div>
							<!-- /.row -->
							<div class="row" align="center">
								<br />
								<button type="submit" class="btn btn-outline btn-success" form="edit_resv" value="Submit">Proceed</button>
								<button type="reset" class="btn btn-outline btn-warning" form="edit_resv" value="Reset">Reset</button>
								<button type="button" class="btn btn-outline btn-danger" onclick="javascript:window.close()">Cancel</button>
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
							$TRV_ID		= $_POST["TRV_ID"];
							$RV_STUD	= $_POST["RV_STUD"];
							$RV_IN		= $_POST["RV_IN"];
							$RV_OUT		= $_POST["RV_OUT"];
							$RV_ROOM2	= $_POST["RV_ROOM"];
							
							$sql1		= "SELECT R_ID FROM ROOM";
							$result1	= mysql_query($sql1);
							while($row1 = mysql_fetch_array($result1))
							{
								$R_ID		= $row1["R_ID"];
								$AR[$R_ID]	= true;
							}
							
							$sql2		= "SELECT RV_ROOM FROM RESERVE WHERE RV_IN < \"$RV_OUT\" AND RV_OUT > \"$RV_IN\" AND RV_STAT != \"DY\" GROUP BY RV_ROOM";
							$result2	= mysql_query($sql2);
							while($row2 = mysql_fetch_array($result2))
							{
								$RV_ROOM		= $row2["RV_ROOM"];
								$AR[$RV_ROOM]	= false;
							}
							
							
							?>
						<div class="panel-body">
							<div class="row">
								<div class="col-lg-12">
									<form role="form" id="edit_resv2" action ="edit_resv.php" method ="POST">
										<input type="hidden" name="REG2" value="YES">
										<input type="hidden" name="TRV_ID" value="<?php print($TRV_ID); ?>">
										<input type="hidden" name="RV_STUD" value="<?php print($RV_STUD); ?>">
										<input type="hidden" name="RV_IN" value="<?php print($RV_IN); ?>">
										<input type="hidden" name="RV_OUT" value="<?php print($RV_OUT); ?>">
										<div class="form-group">
											<label>Reservation ID</label>
                                            <input class="form-control" type="text" value="<?php print($TRV_ID); ?>" name="a" disabled>
                                        </div>
										<div class="form-group">
											<label>Student ID</label>
                                            <input class="form-control" type="text" value="<?php print($RV_STUD); ?>" name="a" disabled>
                                        </div>
										<div class="form-group">
											<label>Check-In Date</label>
                                            <input class="form-control" type="date" value="<?php print($RV_IN); ?>" name="b" disabled>
                                        </div>
										<div class="form-group">
											<label>Check-Out Date</label>
                                            <input class="form-control" type="date" value="<?php print($RV_OUT); ?>" name="c" disabled>
                                        </div>
										<div class="form-group">
											<label>Room</label>
											<select class="form-control" name="RV_ROOM">
												<option disabled> Please choose </option>
												<option disabled> </option>
												<?php
													// Display room type
													$DTYPE["TS"]	= "Two Single Bed";
													$DTYPE["QB"]	= "One Queen Bed";
													$DTYPE["KB"]	= "One King Bed";
													
													$sql_h		= "SELECT * FROM HOUSE ORDER BY H_NAME";
													$result_h	= mysql_query($sql_h);
													
													$ind	= 0;
													while ($row_h = mysql_fetch_array($result_h))
													{
														
														$H_ID 	= $row_h["H_ID"];
														$H_NAME = $row_h["H_NAME"];
														
														echo "<optgroup label=\"$H_NAME\">";
														
														$sql_r		= "SELECT R_ID, R_TYPE FROM ROOM WHERE R_STAT = TRUE AND R_HOUSE = \"$H_ID\" ORDER BY R_TYPE";
														$result_r	= mysql_query($sql_r);
														while ($row_r = mysql_fetch_array($result_r))
														{
															$R_ID		= $row_r["R_ID"];
															$R_TYPE		= $row_r["R_TYPE"];
															
															$SEL_R		= null;
															if($R_ID == $RV_ROOM2)
															{	$SEL_R = "selected";	}
															
															if(($AR[$R_ID] == true) || ($R_ID == $RV_ROOM2))
															{
																echo '<option value="' . $R_ID . '" ' . $SEL_R . '> ' . $R_ID . ' - ' . $DTYPE[$R_TYPE] . '</option>';
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
								<button type="submit" class="btn btn-outline btn-success" form="edit_resv2" value="Submit">Submit</button>
								<button class="btn btn-outline btn-warning" onclick="goBack()">Go Back</button>
								<button type="button" class="btn btn-outline btn-danger" onclick="javascript:window.close()">Cancel</button>
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
