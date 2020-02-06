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
			$T_ID		= mysql_real_escape_string($_POST['T_ID']);
			$S_ID		= mysql_real_escape_string($_POST['S_ID']);
			$S_NAME		= mysql_real_escape_string($_POST['S_NAME']);
			$S_IC		= mysql_real_escape_string($_POST['S_IC']);
			$S_PROG		= mysql_real_escape_string($_POST['S_PROG']);
			$S_PART		= mysql_real_escape_string($_POST['S_PART']);
			$S_PHONE	= mysql_real_escape_string($_POST['S_PHONE']);
			$S_MAIL		= mysql_real_escape_string($_POST['S_MAIL']);
			
			//SQL query command
			$sql="UPDATE STUDENT SET S_ID = $S_ID, S_NAME = \"$S_NAME\", S_IC = \"$S_IC\", S_PROG = \"$S_PROG\", S_PART = \"$S_PART\", S_PHONE = \"$S_PHONE\", S_MAIL = \"$S_MAIL\" WHERE S_ID = $T_ID";
			
			// execute query
			$exe_sql = mysql_query($sql);
			
			// confirming the record is added
			if ($exe_sql)
			{
				echo '<html>
						<head>
							<script>
								window.alert("Edition successfull!\nRecord was saved into the database.");
							</script>
							<meta http-equiv="refresh" content="0; url=view_stud.php?ID=' . $S_ID . '" />
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
		
		if(isset($_GET["ID"]) && ($_GET["ID"] != null))
		{
			$TEMP_ID	= $_GET["ID"];
			$S_ID		= null;
			$sql_choose = "SELECT * FROM STUDENT WHERE S_ID = $TEMP_ID";
			$result_choose = mysql_query($sql_choose);
			while ($row = mysql_fetch_array($result_choose))
			{
				$S_ID 		= $row["S_ID"];
				$S_NAME 	= $row["S_NAME"];
				$S_IC		= $row["S_IC"];
				$S_PROG		= $row["S_PROG"];
				$S_PART		= $row["S_PART"];
				$S_PHONE	= $row["S_PHONE"];
				$S_MAIL		= $row["S_MAIL"];
			}
		}
	?>

    <title>[LIRS] - Edit Student</title>
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
				<div class="col-lg-12">
					<h1 class="page-header">Edit Student Detail</h1>
				</div>
                <!-- /.col-lg-12 -->
			</div>
			<!-- /.row -->
				<!-- mula     ##########################################################################################     mula -->
			<div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <b>Student Detail Amendment Form</b>
                        </div>
					<?php
						if(isset($_GET["ID"]) && ($_GET["ID"] != null) && ($S_ID != null))
						{
					?>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-6">
                                    <form role="form" id="edit_stud" action ="edit_stud.php" method ="POST">
										<input type="hidden" name="REG" value="YES">
										<input type="hidden" name="T_ID" value="<?php print($S_ID); ?>">
                                        <div class="form-group">
											<label>Student ID</label>
                                            <input class="form-control" type="text" size="12" maxlength="10" name="S_ID" value="<?php print($S_ID); ?>">
                                        </div>
										<div class="form-group">
											<label>Student Name</label>
                                            <input class="form-control" type="text" size="50" maxlength="100" name="S_NAME" value="<?php print($S_NAME); ?>">
                                        </div>
										<div class="form-group">
											<label>Student IC</label>
                                            <input class="form-control" type="text" size="12" maxlength="12" name="S_IC" value="<?php print($S_IC); ?>">
                                            <p class="help-block">Eg: 95xxxxxxxxxx (Without dash '-')</p>
                                        </div>
										<div class="form-group">
											<label>Programme</label>
											<select class="form-control" name="S_PROG">
												<option disabled> Please choose </option>
												<option disabled> </option>
												<?php
													$sql_choose = "SELECT * FROM PROGRAMME ORDER BY ID_PROG";
													$result_choose = mysql_query($sql_choose);
													while ($row = mysql_fetch_array($result_choose))
													{
														$PROG1 = $row["ID_PROG"];
														$PROG2 = $row["PROG"];
														
														$SEL = null;
														if($PROG1 == $S_PROG)
														{	$SEL = " selected";	}
														
														echo '<option value="' . $PROG1 . '"' . $SEL . '> ' . $PROG1 . ' - ' . $PROG2 . '</option>';
													}
												?>
											</select>
										</div>
								</div>
								<!-- /.col-lg-6 -->
								<div class="col-lg-6">
										<div class="form-group">
											<label>Part/Semester</label>
											<input class="form-control" type="text" size="12" maxlength="2" name="S_PART" value="<?php print($S_PART); ?>">
										</div>
										<div class="form-group">
											<label>Phone Number</label>
											<input class="form-control" type="text" size="12" maxlength="20" name="S_PHONE" value="<?php print($S_PHONE); ?>">
											<p class="help-block">Eg: +6012-xxx xxxx</p>
										</div>
										<div class="form-group">
											<label>E-Mail</label>
											<input class="form-control" type="text" size="30" maxlength="50" name="S_MAIL" value="<?php print($S_MAIL); ?>">
											<p class="help-block">Eg: yourname@gmail.com</p>
										</div>
								</div>
								<!-- /.col-lg-6 -->
							</div>
							<!-- /.row -->
							<div class="row" align="center">
								<br />
								<button type="submit" class="btn btn-outline btn-success" form="edit_stud" value="Submit">Submit</button>
								<button type="reset" class="btn btn-outline btn-warning"form="edit_stud" value="Reset">Reset</button>
								<a href="view_stud.php?ID=<?php print($S_ID); ?>"><button type="button" class="btn btn-outline btn-danger" >Cancel</button></a>
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

</body>

</html>
