<!DOCTYPE html>

<html lang="en">
<head>
	<title>User Details</title>
	<?php
	session_start();

	/*Checks whether the user has access to the page and directs them to the login page if not*/

	if (isset($_POST['logout'])) {
    //echo $_SESSION["accessLevel"];
		$_SESSION = array();
		session_destroy();
		echo "<meta http-equiv=\"refresh\" content=\"0; URL=Login.php\">";
	}
	else if(!isset($_SESSION['accessLevel'])){
		echo "<meta http-equiv=\"refresh\" content=\"0; URL=Login.php\">";
	}


	?>
	<script src='http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>
	<script src='http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js'></script>
	<script src='http://cdnjs.cloudflare.com/ajax/libs/bootstrap-validator/0.4.5/js/bootstrapvalidator.min.js'></script>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>	

	<?php

	/* Connects to the database*/
	$hostname="mysql.cs.bangor.ac.uk:3306";
	$username="eeu829";
	$password="Bangor16";
	$dbname="eeu829";
	$usertable="SH_UserLog";
	
	$link = mysqli_connect($hostname,$username, $password, $dbname); 

	if($link===false){ 
		die ("<html><script language='JavaScript'>alert('Unable to connect to dababase!
			Please try again later.'),history.go(-1)</script></html>");
	}
	/*This gets the information for the specific user from the databse and displays it*/
	$email = $_REQUEST['email'];
	$telNo = $_REQUEST['telNo'];
	$roomNo = $_REQUEST['roomNo'];
	$userName = $_SESSION["username"];

	
	/* Updates the database with whatever the user has entered into the text boxes*/
	if(isset($_POST["update"])){

		$sql2 = "UPDATE SH_UserLog SET email='$email', roomNo='$roomNo', telNo='$telNo' WHERE userID = '$userName'";
		mysqli_query($link,$sql2);

	}

	$sql = "SELECT * FROM SH_UserLog WHERE userID = '$userName'";
	$result = $link->query($sql);
	$row=mysqli_fetch_array($result);

	

	

	mysqli_close($link);

	?>



	<!-- 
		This is the nav bar
	-->
	<nav class="navbar navbar-inverse">
		<div class="container-fluid">
			<div class="navbar-header">
				<a class="navbar-left"><img src="images/Bangor_Logo_A2.png"></a>

			</div>
			<ul class="nav navbar-nav">
				
				<li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" href="#">Ordering <span class="caret"></span></a>
					<ul class="dropdown-menu">
						<li><a href="OrderForm1.php">Order Form</a></li>
						<li><a href="OrderHistory.php">Order History</a></li>            
					</ul>
				</li>
				<?php

				/* The users that can access the different pages page */

				if($_SESSION['accessLevel'] == 1){
					echo "<li><a href=\"budgetHolder.php\">Budget Holder</a></li>";
				}

				if($_SESSION['accessLevel'] == 2){
					echo "<li><a href=\"requisitionOfficer.php\">Req Officer</a></li>";
				}

				if($_SESSION['accessLevel'] == 3){
					echo "<li><a href=\"finance.php\">Finance</a></li>";
				}

				if($_SESSION['accessLevel'] == 4){
					echo "<li><a href=\"budgetHolder.php\">Budget Holder</a></li>";
					echo "<li><a href=\"requisitionOfficer.php\">Req Officer</a></li>";
					echo "<li><a href=\"finance.php\">Finance</a></li>";
				}

				?>
			</ul>
			<ul class="nav navbar-nav navbar-right">
				<form method="POST" action="userDetails.php">
					<input type="submit" name="userDetails" class="btn btn-primary btn-rounded btn-small center-block moveButton" value="User Details">
				</form>
				<!--<li><a name="logout" href="finance.php"><span class="glyphicon glyphicon-log-in"></span> Logout</a></li>-->
				<form method="POST" action="userDetails.php">
					<input type="submit" name="logout" class="btn btn-primary btn-rounded btn-small center-block moveButton" value="Logout">
				</form>
			</ul>
		</div>
	</nav>


<!-- 
	This code displays the text boxes with placeholders
-->
	<div class="container">
		<h3>User Details</h3> 
	</div>
	<div>
		<form method="POST" class="form-horizontal" id="validateForm" action="userDetails.php">
			<div class="row">
				<div class="col-md-offset-1 col-md-4">

					<div class="form-group">
						<label class="control-label">Email:</label>
						<input  name="email" placeholder="e.g. example@bangor.ac.uk" class="form-control" <?php echo  "value=\"".$row['email']."\"";?>  type="text">
					</div>

					<div class="form-group">
						<label class="control-label">Room:</label>
						<input name="roomNo" placeholder="e.g. Room 319" class="form-control" <?php echo  "value=\"".$row['roomNo']."\"";?> type="text">
					</div>

					<div class="form-group">
						<label class="control-label">Phone Number:</label>
						<input name="telNo"  placeholder=" e.g. 012345678" class="form-control" <?php echo  "value=\"".$row['telNo']."\"";?> type="text">
					</div>

					<div class="form-group">
						<input type="submit" name="update" class="btn btn-success" value="Save">
					</div>

				</div>
			</div>
		</form>

	</div>

</body>
</html>