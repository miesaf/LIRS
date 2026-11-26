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
		
		// Register account
		if(isset($_POST['REG']))
		{	
			$U_ID		= request_id($_POST, 'U_ID');
			$U_NAME		= request_text($_POST, 'U_NAME') ?? '';
			$U_PWD1		= request_password('U_PWD1');
			$U_PWD2		= request_password('U_PWD2');
			$U_PHONE	= request_text($_POST, 'U_PHONE') ?? '';
			
			if($U_PWD1 !== $U_PWD2)
			{
				js_alert("Password didn't match!");
			}
			elseif($U_ID !== null && $U_NAME !== '' && $U_PWD1 !== ''
				&& db_query("INSERT INTO UPK (U_ID, U_NAME, U_PWD, U_PHONE) VALUES (?, ?, ?, ?)",
					[$U_ID, $U_NAME, password_hash($U_PWD1, PASSWORD_DEFAULT), $U_PHONE]))
			{
				js_alert("UPK staff registration successfull!", "upk.php");
			}
			else
			{
				js_alert("UPK staff registration failed!");
			}
		}
		
		// Delete account
		if(isset($_GET['DEL']))
		{
			$ID = request_id($_GET, 'DEL');
			
			// Staff cannot delete their own account (they would be locked out at once).
			if ($ID !== null && (string) $ID !== (string) $_SESSION['lirs_ident']
				&& db_query("DELETE FROM UPK WHERE U_ID = ?", [$ID]) && db_affected() === 1)
			{
				js_alert("UPK staff deleted!", "upk.php");
			}
			else
			{
				js_alert("UPK staff failed to delete!", "upk.php");
			}
		}
		
		// Edit account: leave both password fields empty to keep the current password.
		if(isset($_POST['EDIT']))
		{
			$ID2		= request_id($_POST, 'ID2');
			$U_ID		= request_id($_POST, 'U_ID');
			$U_NAME		= request_text($_POST, 'U_NAME') ?? '';
			$U_PWD1		= request_password('U_PWD1');
			$U_PWD2		= request_password('U_PWD2');
			$U_PHONE	= request_text($_POST, 'U_PHONE') ?? '';
			
			if($U_PWD1 !== $U_PWD2)
			{
				js_alert("Password didn't match!");
			}
			else
			{
				$exe_sql = false;
				if ($ID2 !== null && $U_ID !== null && $U_NAME !== '')
				{
					if ($U_PWD1 === '')
					{
						$exe_sql = db_query("UPDATE UPK SET U_ID = ?, U_NAME = ?, U_PHONE = ? WHERE U_ID = ?", [$U_ID, $U_NAME, $U_PHONE, $ID2]);
					}
					else
					{
						$exe_sql = db_query("UPDATE UPK SET U_ID = ?, U_NAME = ?, U_PWD = ?, U_PHONE = ? WHERE U_ID = ?",
							[$U_ID, $U_NAME, password_hash($U_PWD1, PASSWORD_DEFAULT), $U_PHONE, $ID2]);
					}
				}
				
				// confirming the record is saved
				if ($exe_sql)
				{
					// Staff who change their own ID or name stay logged in under the new ones.
					if ((string) $ID2 === (string) $_SESSION['lirs_ident'])
					{
						$_SESSION['lirs_ident']	= (string) $U_ID;
						$_SESSION['lirs_name']	= $U_NAME;
					}
					js_alert("Account amendment successfull!", "upk.php");
				}
				else
				{
					js_alert("Account ammendment failed!");
				}
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
                    <h1 class="page-header">UPK Staff Account Management</h1>
                </div>
                <!-- /.col-lg-12 -->
			</div>
            <!-- /.row -->
			<?php
				$EDIT_ROW = isset($_GET['EDIT']) && request_id($_GET, 'EDIT') !== null
					? db_row("SELECT U_ID, U_NAME, U_PHONE FROM UPK WHERE U_ID = ?", [request_id($_GET, 'EDIT')]) : null;
				if($EDIT_ROW)
				{
					// extract specific fields
					$U_ID		= $EDIT_ROW["U_ID"];
					$U_NAME		= $EDIT_ROW["U_NAME"];
					$U_PHONE	= $EDIT_ROW["U_PHONE"];
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
								<input type="hidden" name="ID2" value="<?php echo e($U_ID); ?>">
								<input type="hidden" name="EDIT" value="YES">
								<div class="form-group">
									<label>Staff ID</label>
									<input class="form-control" type="text" maxlength="10" name="U_ID" value="<?php echo e($U_ID); ?>">
								</div>
								<div class="form-group">
									<label>Staff Name</label>
									<input class="form-control" type="text" maxlength="100" name="U_NAME" value="<?php echo e($U_NAME); ?>">
								</div>
								<div class="form-group">
									<label>Password</label>
									<input class="form-control" type="password" name="U_PWD1" autocomplete="new-password">
									<p class="help-block">Leave both password fields empty to keep the current password.</p>
								</div>
								<div class="form-group">
									<label>Please Re-type Password</label>
									<input class="form-control" type="password" name="U_PWD2" autocomplete="new-password">
								</div>
								<div class="form-group">
									<label>Staff Phone Number</label>
									<input class="form-control" type="text" maxlength="100" name="U_PHONE" value="<?php echo e($U_PHONE); ?>">
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
									<input class="form-control" type="password" name="U_PWD1" autocomplete="new-password">
								</div>
								<div class="form-group">
									<label>Please Re-type Password</label>
									<input class="form-control" type="password" name="U_PWD2" autocomplete="new-password">
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
									// execute listing query (12345 is the built-in account, not listed)
									$result = db_query("SELECT U_ID, U_NAME, U_PHONE FROM UPK WHERE U_ID != 12345 ORDER BY U_NAME") or die("SQL select statement failed");

									// Papar table		
									$BIL = 0;
									while ($row = mysqli_fetch_array($result))
									{
										$BIL++;
										
										// extract specific fields
										$U_ID		= e($row["U_ID"]);
										$U_NAMA		= e($row["U_NAME"]);
										$U_PHONE	= e($row["U_PHONE"]);
										$U_JS		= e(js((string) $row["U_ID"]));
										
										// output student information
										echo "<tr>";
										echo "<td align=center>$U_ID</td><td>$U_NAMA</td><td align=center>$U_PHONE</td><td align=center><a href='upk.php?EDIT=$U_ID'><button class='btn btn-outline btn-warning' type='button' value='Edit'>Edit</button></a> <button class='btn btn-outline btn-danger' type='button' value='Delete' onClick='confirmDel($U_JS)'>Delete</button></td>";
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
				window.location.assign("upk.php?DEL=" + encodeURIComponent(nums));

			} else 
			{
				alert("Staff was not deleted.");
			}
		}
	</script>

</body>

</html>
