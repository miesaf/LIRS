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
		
		// Register account
		if(isset($_POST['REG']))
		{	
			// Variables from upk.php
			$U_ID		= mysql_real_escape_string($_POST['U_ID']);
			$U_NAME		= mysql_real_escape_string($_POST['U_NAME']);
			$U_PWD1		= mysql_real_escape_string($_POST['U_PWD1']);
			$U_PWD2		= mysql_real_escape_string($_POST['U_PWD2']);
			$U_PHONE	= mysql_real_escape_string($_POST['U_PHONE']);
			
			if($U_PWD1 == $U_PWD2)
			{
				//SQL query command
				$sql="INSERT INTO UPK (U_ID, U_NAME, U_PWD, U_PHONE) VALUES ($U_ID, '$U_NAME', '$U_PWD1', '$U_PHONE')";
				
				// execute query
				$exe_sql = mysql_query($sql);
				
				// confirming the record is added
				if ($exe_sql)
				{
					echo '<html>
							<head>
								<script>
									window.alert("UPK staff registration successfull!");
								</script>
								<meta http-equiv="refresh" content="0; url=upk.php" />
							</head>
						</html>';
				}
				else
				{
					echo "SQL insert statement failed.<br>" . mysql_error();
					echo '<html>
							<head>
								<script>
									window.alert("UPK staff registration failed!");
									window.history.go(-1);
								</script>
							</head>
						</html>';
				}
			}
			else
			{
				echo '<html>
							<head>
								<script>
									window.alert("Password didn\'t match!");
									window.history.go(-1);
								</script>
							</head>
						</html>';
			}
		}

		// Delete account
		if(isset($_GET['DEL']))
		{
			// get ID value
			$ID = mysql_real_escape_string($_GET['DEL']);
			
			// delete the entry
			$result = mysql_query("DELETE FROM UPK WHERE U_ID = \"$ID\""); 
			
			// check for deletion
			if ($result)
			{
			   echo '<html>
						<head>
							<script>
								window.alert("UPK staff deleted!");
							</script>
							<meta http-equiv="refresh" content="0; url=upk.php" />
						</head>
					</html>';
			}
			else
			{
				//echo "SQL insert statement failed.<br>" . mysql_error();
				echo '<html>
						<head>
							<script>
								window.alert("UPK staff failed to delete!");
								window.history.go(-1);
							</script>
						</head>
					</html>';
			}
		}
		
		// Edit account
		if(isset($_POST['EDIT']))
		{
			$ID2		= mysql_real_escape_string($_POST['ID2']);
			$U_ID		= mysql_real_escape_string($_POST['U_ID']);
			$U_NAME		= mysql_real_escape_string($_POST['U_NAME']);
			$U_PWD1		= mysql_real_escape_string($_POST['U_PWD1']);
			$U_PWD2		= mysql_real_escape_string($_POST['U_PWD2']);
			$U_PHONE	= mysql_real_escape_string($_POST['U_PHONE']);
			
			if($U_PWD1 == hash('sha256', "my_old_password"))
			{
				$sql="UPDATE UPK SET U_ID = $U_ID, U_NAME = '$U_NAME', U_PHONE = '$U_PHONE' WHERE U_ID = '$ID2'";
			}
			else
			{
				if($U_PWD1 == $U_PWD2)
				{
					$sql="UPDATE UPK SET U_ID = $U_ID, U_NAME = '$U_NAME', U_PWD = '$U_PWD1', U_PHONE = '$U_PHONE' WHERE U_ID = '$ID2'";
				}
				else
				{
					echo '<html>
								<head>
									<script>
										window.alert("Password didn\'t match!");
										window.history.go(-1);
									</script>
								</head>
							</html>';
				}
			}
			
			// execute query
			$exe_sql = mysql_query($sql);
			
			// confirming the record is added
			if ($exe_sql)
			{
				echo '<html>
						<head>
							<script>
								window.alert("Account amendment successfull!");
							</script>
							<meta http-equiv="refresh" content="0; url=upk.php" />
						</head>
					</html>';
			}
			else
			{
				echo "SQL insert statement failed.<br>" . mysql_error();
				echo '<html>
						<head>
							<script>
								window.alert("Account ammendment failed!");
								window.history.go(-1);
							</script>
						</head>
					</html>';
			}
		}
	?>

    <title>[LIRS] - UPK Staff</title>
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
                    <h1 class="page-header">UPK Staff Account Management</h1>
                </div>
                <!-- /.col-lg-12 -->
			</div>
            <!-- /.row -->
			<?php
				if(isset($_GET['EDIT']))
				{
					$EDIT_ID	= $_GET['EDIT'];
					
					// create the query
					$sql = "SELECT * FROM UPK WHERE U_ID = '$EDIT_ID'";
					
					// execute query
					$result = mysql_query($sql) or die("SQL select statement failed");
					
					// iterate through all rows in result set
					$row = mysql_fetch_array($result);
					
					// extract specific fields
					$U_ID		= $row["U_ID"];
					$U_NAME		= $row["U_NAME"];
					$U_PHONE	= $row["U_PHONE"];
			?>
			<div class="row">
                <div class="col-lg-12">
					<div class="panel panel-default">
						<div class="panel-heading">
							<center><b>Account Amendment</b></center>
						</div>
						<!-- /.panel-heading -->
						<div class="panel-body">
							<form id="edit_upk" action ="upk.php" method ="POST">
								<input type="hidden" name="ID2" value="<?php echo $U_ID; ?>">
								<input type="hidden" name="EDIT" value="YES">
								<div class="form-group">
									<label>Staff ID</label>
									<input class="form-control" type="text" maxlength="10" name="U_ID" value="<?php echo $U_ID; ?>">
								</div>
								<div class="form-group">
									<label>Staff Name</label>
									<input class="form-control" type="text" maxlength="100" name="U_NAME" value="<?php echo $U_NAME; ?>">
								</div>
								<div class="form-group">
									<label>Password</label>
									<input class="form-control" type="password" maxlength="50" name="U_PWD1" value="<?php echo hash('sha256', "my_old_password"); ?>">
								</div>
								<div class="form-group">
									<label>Please Re-type Password</label>
									<input class="form-control" type="password" maxlength="50" name="U_PWD2" value="<?php echo hash('sha256', "my_old_password"); ?>">
								</div>
								<div class="form-group">
									<label>Staff Phone Number</label>
									<input class="form-control" type="text" maxlength="100" name="U_PHONE" value="<?php echo $U_PHONE; ?>">
								</div>
								<div class="form-group" align="center">
									<button class="btn btn-outline btn-success" type="submit" form="edit_upk" value="Submit">Submit</button>
									<button type="reset" class="btn btn-outline btn-warning"form="edit_upk" value="Reset">Reset</button>
									<a href="upk.php"><button type="button" class="btn btn-outline btn-danger" >Cancel</button></a>
								</div>
							</form>
						</div>
						<!-- /.panel-body -->
					</div>
					<!-- /.panel-default -->
                </div>
                <!-- /.col-lg-12 -->
			</div>
            <!-- /.row -->
			<?php
				}
				elseif(isset($_GET['ADD']))
				{
			?>
			<div class="row">
                <div class="col-lg-6">
					<div class="panel panel-default">
						<div class="panel-heading">
							<center><b>Account Registration</b></center>
						</div>
						<!-- /.panel-heading -->
						<div class="panel-body">
							<form id="add_upk" action ="upk.php" method ="POST">
								<input type="hidden" name="REG" value="YES">
								<div class="form-group">
									<label>Staff ID</label>
									<input class="form-control" type="text" maxlength="10" name="U_ID">
								</div>
								<div class="form-group">
									<label>Staff Name</label>
									<input class="form-control" type="text" maxlength="100" name="U_NAME">
								</div>
								<div class="form-group">
									<label>Password</label>
									<input class="form-control" type="password" maxlength="50" name="U_PWD1">
								</div>
								<div class="form-group">
									<label>Please Re-type Password</label>
									<input class="form-control" type="password" maxlength="50" name="U_PWD2">
								</div>
								<div class="form-group">
									<label>Staff Phone Number</label>
									<input class="form-control" type="text" maxlength="20" name="U_PHONE">
								</div>
								<div class="form-group" align="center">
									<button class="btn btn-outline btn-success" type="submit" form="add_upk" value="Submit">Register</button>
									<button type="reset" class="btn btn-outline btn-warning"form="add_upk" value="Reset">Reset</button>
									<a href="upk.php"><button type="button" class="btn btn-outline btn-danger" >Cancel</button></a>
								</div>
							</form>
						</div>
						<!-- /.panel-body -->
					</div>
					<!-- /.panel-default -->
                </div>
                <!-- /.col-lg-6 -->
			</div>
            <!-- /.row -->
			<?php
				}
				else
				{
			?>
            <div class="row">
				<div class="col-lg-12">
					<div class="form-group"  align="center">
						<span class="input-group-btn">
							<a href="upk.php?ADD=1"><button type="button" class="btn btn-outline btn-success" ><i class="fa fa-plus"></i> Register New Staff</button></a>
						</span>
					</div>
				</div>
				<div class="col-lg-12">
					<div class="panel panel-default">
                        <div class="panel-heading">
                            <center><b>UPK Staffs</b></center>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
							<div class="table-responsive">
								<table class="table table-striped table-bordered table-hover">
								<thead>
									<tr>
										<td align="center"><b>Staff ID</b></td>
										<td align="center"><b>Staff Name</b></td>
										<td align="center"><b>Staff Phone</b></td>
										<td align="center"><b>Account</b></td>
									<tr>
								</thead>
								<tbody>
								<?php
									// create the listing query
									$sql = "SELECT * FROM UPK WHERE U_ID != 12345 ORDER BY U_NAME";
									
									// execute listing query
									$result = mysql_query($sql) or die("SQL select statement failed");

									// Papar table		
									$BIL = 0;
									while ($row = mysql_fetch_array($result))
									{
										$BIL++;
										
										// extract specific fields
										$U_ID		= $row["U_ID"];
										$U_NAMA		= $row["U_NAME"];
										$U_PHONE	= $row["U_PHONE"];
										
										// output student information
										echo "<tr>";
										echo "<td align=center>$U_ID</td><td>$U_NAMA</td><td align=center>$U_PHONE</td><td align=center><a href='upk.php?EDIT=$U_ID'><button class='btn btn-outline btn-warning' type='button' value='Edit'>Edit</button></a> <button class='btn btn-outline btn-danger' type='button' value='Delete' onClick='confirmDel(\"$U_ID\")'>Delete</button></td>";
										echo "</tr>";
									}
									if($BIL == 0)
									{
										echo "<tr><td align='center' colspan='4'><i> No data to display. </i></td></tr>";
									}
								?>
								</tbody>
								</table>
							</div>
							<!-- /.table-responsive -->
						</div>
						<!-- /.panel-body -->
                    </div>
					<!-- /.panel-default -->
                </div>
				<!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
			<?php
				}
			?>
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
	
	<script language="JavaScript">
		function confirmDel(nums)
		{
			var del = confirm("Do you want to delete this staff?");
			if (del == true)
			{
				window.location.assign("upk.php?DEL=" + nums);
			} else 
			{
				alert("Staff was not deleted.");
			}
		}
	</script>

</body>

</html>
