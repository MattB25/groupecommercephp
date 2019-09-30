<!DOCTYPE html>

<?php

session_start(); // This section of the code makes a connection to the database and is protected from mysql injections

$redirect = "";
$phase = 0;
if (isset($_POST['loginSubmit'])){
	$hostname="mysql.cs.bangor.ac.uk:3306";
	$username="eeu829";
	$password="Bangor16";
	$dbname="eeu829";
	$usertable1="SH_UserLog";

	$link = mysqli_connect($hostname,$username, $password, $dbname); 	

	$username = $_POST['username'];
	$username = mysqli_real_escape_string($link,$username);

	$password = $_POST['password'];
	$password = mysqli_real_escape_string($link,$password);



	$sql = "SELECT * FROM $usertable1 WHERE userID = '$username' AND password ='$password';"; // this retreives the account information from the database
	
	$phase = 1;
	$result = $link->query($sql);
	if(mysqli_num_rows($result) > 0){
		$phase = 2;
		$row = mysqli_fetch_array($result);

		$_SESSION["username"] = $username;
		$_SESSION["accessLevel"] = $row['accessLevel'];
		$redirect = "<meta http-equiv=\"Refresh\" content=\"0;url=OrderForm1.php\">"; // this section of the code checks the user name and its acsess levels and redirects the page accordingly
	}
	else // this part of the code below checks that the information enteredd into the login form is correct
	{
		 echo "<script> alert('Incorrect details. Please try again.'); 
		 history.back();
		 </script>";

	}

	

}
?>

<html lang="en">
<head>
	<title>Login Landing Page</title>
	<?php echo $redirect;?>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="style.css">
	<script src='http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>
	<script src='http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js'></script>
	<script src='http://cdnjs.cloudflare.com/ajax/libs/bootstrap-validator/0.4.5/js/bootstrapvalidator.min.js'></script>
</head>
<body> <!--The section of html code below is a form that contains the log in inputs and onces submitted calles login.php-->
	<script>
		console.log("Phase = " + <?php echo $phase ?>); 
		console.log("sql = " + "<?php echo $sql ?>");
	</script>
	<div class="wrapper">
		<form class="form-signin" method="post" action="Login.php">
			<img id="profile-img" class="profile-img-card" src="images/Bangor_Logo_B1.png" />
			<div class="input-group">
				<span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
				<input id="email" type="text" class="form-control" name="username" placeholder="Username" >
			</div>
			<div class="input-group">
				<span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
				<input id="password" type="password" class="form-control" name="password" placeholder="Password">
			</div>
			<br>
			<button class="btn btn-lg btn-primary btn-block" type="submit" name="loginSubmit">Login</button>
			<a href="Login-cy.php">
				<img class="flag" src="images/welshFlag.png" alt="welsh flag" id="welshFlag"> 
			</a>
		</form>
	</div>
</body>
</html>