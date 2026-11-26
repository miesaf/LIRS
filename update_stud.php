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
		require_login('USER');
		
		if(isset($_POST["REG"]))
		{
			// A student changes only their own record (the ID in the session).
			$T_ID		= $_SESSION['lirs_ident'];
			$S_ID		= request_id($_POST, 'S_ID');
			$S_NAME		= request_text($_POST, 'S_NAME') ?? '';
			$S_IC		= request_text($_POST, 'S_IC') ?? '';
			$S_PROG		= request_text($_POST, 'S_PROG') ?? '';
			$S_PART		= request_text($_POST, 'S_PART') ?? '';
			$S_PHONE	= request_text($_POST, 'S_PHONE') ?? '';
			$S_MAIL		= request_text($_POST, 'S_MAIL') ?? '';
			
			$exe_sql = $S_ID !== null && $S_NAME !== '' && $S_IC !== ''
				&& db_query("UPDATE STUDENT SET S_ID = ?, S_NAME = ?, S_IC = ?, S_PROG = ?, S_PART = ?, S_PHONE = ?, S_MAIL = ? WHERE S_ID = ?",
					[$S_ID, $S_NAME, $S_IC, $S_PROG, $S_PART, $S_PHONE, $S_MAIL, $T_ID]);
			
			// confirming the record is saved
			if ($exe_sql)
			{
				$_SESSION['lirs_ident']	= (string) $S_ID;
				$_SESSION['lirs_name']	= $S_NAME;
				
				js_alert("Edition successfull!\nRecord was saved into the database.", "main.php");
			}
			else
			{
				js_alert("Edition failed!\nRecord was not saved into the database.");
			}
		}
		
		if(!isset($_POST["REG"]))
		{
			$S_ID = $S_NAME = $S_IC = $S_PROG = $S_PART = $S_PHONE = $S_MAIL = '';
			$row = db_row("SELECT * FROM STUDENT WHERE S_ID = ?", [$_SESSION['lirs_ident']]);
			if ($row)
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

    <title>[LIRS] - Update Personal Detail</title>
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
				<div class="col-lg-12">
					<h1 class="page-header">Update Personal Detail</h1>
				</div>
                <!-- /.col-lg-12 -->
			</div>
			<!-- /.row -->
				<!-- mula     ##########################################################################################     mula -->
			<div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <b>Personal Detail Amendment Form</b>
                        </div>
					<?php
						if(!isset($_POST["REG"]))
						{
					?>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-6">
                                    <form role="form" id="update_stud" action ="update_stud.php" method ="POST">
										<input type="hidden" name="REG" value="YES">
										<input type="hidden" name="T_ID" value="<?php echo e($S_ID); ?>">
                                        <div class="form-group">
											<label>Student ID</label>
                                            <input class="form-control" type="text" size="12" maxlength="10" name="S_ID" value="<?php echo e($S_ID); ?>">
                                        </div>
										<div class="form-group">
											<label>Student Name</label>
                                            <input class="form-control" type="text" size="50" maxlength="100" name="S_NAME" value="<?php echo e($S_NAME); ?>">
                                        </div>
										<div class="form-group">
											<label>Student IC</label>
                                            <input class="form-control" type="text" size="12" maxlength="12" name="S_IC" value="<?php echo e($S_IC); ?>">
                                            <p class="help-block">Eg: 95xxxxxxxxxx (Without dash '-')</p>
                                        </div>
										<div class="form-group">
											<label>Programme</label>
											<select class="form-control" name="S_PROG">
												<option disabled> Please choose </option>
												<option disabled> </option>
												<?php
													$result_choose = db_query("SELECT * FROM PROGRAMME ORDER BY ID_PROG");
													while ($row = mysqli_fetch_array($result_choose))
													{
														$PROG1 = $row["ID_PROG"];
														$PROG2 = $row["PROG"];
														
														$SEL = null;
														if($PROG1 == $S_PROG)
														{	$SEL = " selected";	}
														
														echo '<option value="' . e($PROG1) . '"' . $SEL . '> ' . e($PROG1 . ' - ' . $PROG2) . '</option>';
													}
												?>
											</select>
										</div>
								</div>
								<!-- /.col-lg-6 -->
								<div class="col-lg-6">
										<div class="form-group">
											<label>Part/Semester</label>
											<input class="form-control" type="text" size="12" maxlength="2" name="S_PART" value="<?php echo e($S_PART); ?>">
										</div>
										<div class="form-group">
											<label>Phone Number</label>
											<input class="form-control" type="text" size="12" maxlength="20" name="S_PHONE" value="<?php echo e($S_PHONE); ?>">
											<p class="help-block">Eg: +6012-xxx xxxx</p>
										</div>
										<div class="form-group">
											<label>E-Mail</label>
											<input class="form-control" type="text" size="30" maxlength="50" name="S_MAIL" value="<?php echo e($S_MAIL); ?>">
											<p class="help-block">Eg: yourname@gmail.com</p>
										</div>
								</div>
								<!-- /.col-lg-6 -->
							</div>
							<!-- /.row -->
							<div class="row" align="center">
								<br />
								<button type="submit" class="btn btn-outline btn-success" form="update_stud" value="Submit">Submit</button>
								<button type="reset" class="btn btn-outline btn-warning"form="update_stud" value="Reset">Reset</button>
								<a href="main.php"><button type="button" class="btn btn-outline btn-danger" >Cancel</button></a>

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
