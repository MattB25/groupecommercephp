<!DOCTYPE html>
<html lang="en">
<head>
  <title>User Details</title>
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

  $userID = $_REQUEST['userID'];
  $email = $_REQUEST['email'];
  $telNo = $_REQUEST['telNo'];
  $roomNo = $_REQUEST['roomNo'];

  
  //do validating of data types (test if ints or doubles or strings)
  //$userId = mysqli_real_escape_string($link,$userId);
 // $accountId = mysqli_real_escape_string($link,$accountId);	
  //$userName = mysql_real_escape_string($userName);

//echo "Values: ".$userId." ".$accountId." ".$userName." ".$email." ".$telNo." ".$roomNo;

                   
 $sql = "UPDATE SH_UserLog SET email='$email', telNo=$telNo, roomNo='$roomNo' WHERE userID = '$_SESSION['userID']'";




	
  /*if(mysqli_query($link,$sql)){
  echo "Records added successfully.";
  } else {
    echo "ERROR: Unable to execute $sql. " . mysqli_error($link);
  }
  
	mysqli_close($link);*/
 
  
?>




  <nav class="navbar navbar-inverse">
    <div class="container-fluid">
      <div class="navbar-header">
        <a href="homepage.html" class="navbar-left"><img src="images/Bangor_Logo_A2.png"></a>
        
      </div>
      <ul class="nav navbar-nav">
        <li class=""><a href="homepage.html">Home</a></li>
        <li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" href="#">Ordering <span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href="OrderForm1.php">Order Form</a></li>
            <li><a href="OrderHistory.php">Order History</a></li>            
          </ul>
        </li>
        <li class=""><a href="budgetHolder.php">Budget Holder</a></li>
        <li class=""><a href="requisitionOfficer.php">Req Officer</a></li>
        <li class=""><a href="finance.php">Finance</a></li>
      </ul>
      <ul class="nav navbar-nav navbar-right">
        <li><a href="userDetails.php"><span class="glyphicon glyphicon-user"></span> User Details</a></li>
        <li><a href="Login.php"><span class="glyphicon glyphicon-log-in"></span> Logout</a></li>
      </ul>
    </div>
  </nav>
  
  <div class="container">
    <h3>User Details</h3> 
  </div>
 

  <div>
  <form class="form-horizontal" id="validateForm">
    <div class="row">
      <div class="col-md-offset-1 col-md-4">

        <div class="form-group">
          <label class="control-label">Email:</label>
          <input  name="email" placeholder="e.g. example@bangor.ac.uk" class="form-control"  type="text">
        </div>

        <div class="form-group">
          <label class="control-label">Room:</label>
          <input name="room" placeholder="e.g. Room 319" class="form-control" type="text">
        </div>

        <div class="form-group">
          <label class="control-label">Phone Number:</label>
          <input name="phone_no"  placeholder=" e.g. 012345678" class="form-control" type="text">
        </div>

	      <div class="form-group">
          <button type="submit" class="btn btn-success" >Save</button>
        </div>

  </div>




</body>
</html>