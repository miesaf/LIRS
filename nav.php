<li>
	<a href="main.php"><i class="fa fa-dashboard fa-fw"></i> Main Page</a>
</li>
<?php
if($_SESSION["PRIV"] == "USER")
{
?>
<li>
	<a href="update_stud.php"><i class="fa fa-user fa-fw"></i> Update Personal Detail</a>
</li>
<li>
	<a href="add_resv.php"><i class="fa fa-pencil fa-fw"></i> Add Reservation</a>
</li>
<li>
	<a href="list_resv.php"><i class="fa fa-list fa-fw"></i> List Reservations</a>
</li>
<?php
}
if($_SESSION["PRIV"] == "SUPK")
{
?>
<li>
	<a href="#"><i class="fa fa-calendar fa-fw"></i> Reservations<span class="fa arrow"></span></a>
	<ul class="nav nav-second-level">
		<li>
			<a href="add_resv.php"><i class="fa fa-pencil fa-fw"></i> Add Reservation</a>
		</li>
		<li>
			<a href="list_all.php"><i class="fa fa-list fa-fw"></i> All</a>
		</li>
		<li>
			<a href="list_pending.php"><i class="fa fa-share-square-o fa-fw"></i> Pending</a>
		</li>
		<li>
			<a href="list_approved.php"><i class="fa fa-check-square-o fa-fw"></i> Approved</a>
		</li>
	</ul>
</li>
<li>
	<a href="#"><i class="fa fa-home fa-fw"></i> Houses<span class="fa arrow"></span></a>
	<ul class="nav nav-second-level">
		<li>
			<a href="add_house.php"><i class="fa fa-pencil fa-fw"></i> Add House</a>
		</li>
		<li>
			<a href="list_house.php"><i class="fa fa-list fa-fw"></i> List Houses</a>
		</li>
	</ul>
</li>
<li>
	<a href="#"><i class="fa fa-building-o fa-fw"></i> Rooms<span class="fa arrow"></span></a>
	<ul class="nav nav-second-level">
		<li>
			<a href="add_room.php"><i class="fa fa-pencil fa-fw"></i> Add Room</a>
		</li>
		<li>
			<a href="list_room.php"><i class="fa fa-list fa-fw"></i> List Rooms</a>
		</li>
	</ul>
</li>
<li>
	<a href="#"><i class="fa fa-user fa-fw"></i> Students<span class="fa arrow"></span></a>
	<ul class="nav nav-second-level">
		<li>
			<a href="add_stud.php"><i class="fa fa-pencil fa-fw"></i> Add Student</a>
		</li>
		<li>
			<a href="list_stud.php"><i class="fa fa-list fa-fw"></i> List Students</a>
		</li>
	</ul>
</li>
<li>
	<a href="upk.php"><i class="fa fa-users fa-fw"></i> UPK Staff</span></a>
</li>
<?php
}
?>