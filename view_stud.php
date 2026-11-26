<?php ob_start(); ?>
<html lang="en">
<head>
	<link rel="shortcut icon" href="favicon.ico">
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">	
	<meta name="keywords" content="LIRS" />
	<meta name="description" content="Ledang Inn Reservation System" />
	<?php
		session_start();
		require_once __DIR__ . '/connectDB.php';
		require_login('SUPK');
		
		if(isset($_GET["DEL"]))
		{
			$DEL_ID	= request_id($_GET, "DEL");
			
			// A student who has reservations is not deleted (foreign key).
			if ($DEL_ID !== null && db_query("DELETE FROM STUDENT WHERE S_ID = ?", [$DEL_ID]) && db_affected() === 1)
			{
				js_alert("Record was removed from the database.", "list_stud.php");
			}
			else
			{
				js_alert("Failed to remove record from the database.");
			}
		}
	?>

    <title>[LIRS] - View Student</title>

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
					<h1 class="page-header">Student Personal Detail</h1>
				<div>
				<!-- /.col-lg-10 -->
			</div>
			<!-- /.row -->
			<div class="row">
				<div class="col-lg-8">					
				<?php
					$S_ID	= null;
					$ID		= request_id($_GET, "ID");
					$row	= $ID === null ? null : db_row("SELECT * FROM STUDENT WHERE S_ID = ?", [$ID]);
					if($row)
					{
						// extract specific fields
						$S_ID		= $row["S_ID"];
						$S_NAME		= $row["S_NAME"];
						$S_IC		= $row["S_IC"];
						$S_PROG		= $row["S_PROG"];
						$S_PART		= $row["S_PART"];
						$S_PHONE	= $row["S_PHONE"];
						$S_MAIL		= $row["S_MAIL"];
						
						$DPROG		= db_row("SELECT PROG FROM PROGRAMME WHERE ID_PROG = ?", [$S_PROG])["PROG"] ?? '';
					}
				?>
					<div class="panel panel-default">
						<div class="panel-heading">
							<center><b> Student Personal Detail </b></center>
						</div>
						<div class="panel-body">
							<div class="table-responsive">
								<table class="table table-striped table-bordered table-hover">
									<tbody>
									<?php
										if($S_ID !== null)
										{
											?>
										<tr>
										  <td valign="center" ><div align="center"><b>Student ID</b></div></td>
										  <td valign="center" ><?php echo e($S_ID); ?></td>
										</tr>
										<tr>
										  <td valign="center" ><div align="center"><b>Student Name</b></div></td>
										  <td valign="center" ><div align="left"><?php echo e($S_NAME); ?></div></td>
										</tr>
										<tr>
										  <td valign="center"><div align="center"><b>Student IC</b></div></td>
										  <td valign="center"><div align="left"><?php echo e($S_IC); ?></div></td>
										</tr>
										<tr>
										  <td valign="center"><div align="center"><b>Programme</b></div></td>
										  <td valign="center"><div align="left"><?php echo e($S_PROG . " - " . $DPROG); ?></div></td>
										</tr>
										<tr>
										  <td valign="center"><div align="center"><b>Part/Semester</b></div></td>
										  <td valign="center"><div align="left"><?php echo e($S_PART); ?></div></td>
										</tr>
										<tr>
										  <td valign="center"><div align="center"><b>Phone Number</b></div></td>
										  <td valign="center"><div align="left"><?php echo e($S_PHONE); ?></div></td>
										</tr>
										<tr>
										  <td valign="center"><div align="center"><b>E-Mail</b></div></td>
										  <td valign="center"><div align="left"><?php echo e($S_MAIL); ?></div></td>
										</tr>
										<tr>
											<td colspan="2" align="center">
												<a href="edit_stud.php?ID=<?php echo e($S_ID); ?>"><button class="btn btn-outline btn-warning"> Edit </button></a>&nbsp;&nbsp;&nbsp;
												<button type="button" class="btn btn-outline btn-danger" onClick='confirmDel()'> Delete </button>
											</td>
										</tr>
											<?php
										}
										else
										{
											?>
										<tr>
										  <td valign="center"><div align="center"><i>No data to display</i></div></td>
										</tr>
											<?php
										}
									?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
				<!-- /.col-lg-8 -->
			</div>
			<!-- /.row -->
        </div>
        <!-- /#page-wrapper -->

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
		function confirmDel()
		{
			var del = confirm("Do you want to delete this record?");
			if (del == true)
			{
				window.location.assign("view_stud.php?DEL=" + encodeURIComponent(<?php echo js((string) $S_ID); ?>));

			} else 
			{
				alert("Record was not deleted.");
			}
		}
	</script>
</body>

</html>
