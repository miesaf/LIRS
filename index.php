<html>
<head>
	<?php
		session_start();
	?>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Johor's Commander Intergrated System" />
    <meta name="author" content="PRISKOR">

    <title>[LIRS] - Ledang Inn Reservation System</title>
	<link rel="shortcut icon" href="favicon.ico">
	
	<?php			
		if(isset($_SESSION['LOGIN']))
		{
			header("Location: main.php");
			exit;
		}
		
		// Clear reset
		include('connectDB.php');
		
		$problem = ""; // 2) Declare
		// $problem = $_GET["problem"];	// 1) Undefined index error
		if(isset($_GET["error"]))	// 3) The magic function
		{
			$problem = $_GET["error"];
		}
		$errormsg = "<font color='red'> ERROR: ";
		if($problem == "failed")
		{
			$errormsg = $errormsg . " Wrong ID and Password combination!!";
		}
		if($problem == "type")
		{
			$errormsg = $errormsg . " Please select login type!!";
		}
		if($problem == "exp")
		{
			$errormsg = $errormsg . " Haven't logged in!!";
		}
		if($problem == "server")
		{
			$errormsg = $errormsg . " Server is currently unreachable!!";
		}
		if($problem == "db")
		{
			$errormsg = $errormsg . " Failed to connect to database!!";
		}
		$errormsg = $errormsg . "</font>";
	?>

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
	<link rel="stylesheet" href="dist/css/login.css" type="text/css">
	
    <div class="container">
        <div class="row">
            <div class="col-md-4 col-md-offset-4">
                <div class="login-panel panel panel-default">
                    <div class="panel-heading">
						<!-- JCIS<sup><font size="3">[Beta]</font></sup> -->
                        <h1><strong><center>LIRS</center></strong></h1>
						<h5><strong><center>LEDANG INN<br>RESERVATION SYSTEM</center></strong></h5>
						<h6><center>"Servicing Hospitality"</center></h6>
                    </div>
                    <div class="panel-body">
                        <form id="masuk" name="masuk" role="form" action="checkLogin.php" method="post">
                            <fieldset>
								<?php
									if($problem != "")
									{
										echo '<div class="form-group">
												<center><t class="ralat">
													' . $errormsg . '<br><br>
												</t></center>';
									}
								?>
                                <div class="form-group">
                                    <input class="form-control" placeholder="Student/Staff ID" name="uname" type="text" autofocus required>
                                </div>
                                <div class="form-group">
                                    <input class="form-control" placeholder="Password" name="pword" type="password" required>
                                </div>
								<div class="form-group">
									<select class="form-control" name="type">
										<option disabled selected> Please select login type </option>
										<option disabled> </option>
										<option value="1"> Student </option>
										<option value="2"> Staff </option>
									</select>
								</div>
                                <!-- Change this to a button or input when using this as a form -->
								<input type="submit" class="btn btn-lg btn-success btn-block" form="masuk" value="Login" />
                            </fieldset>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

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
