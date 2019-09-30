<!DOCTYPE html>
<html lang="en">
<head>
  <title>Order History</title>
  <?php
  //begin session
  session_start();

  //check if the logout session has been pressed, if it has invalidate the users session and redirect.
  //if the user hasn't check to see if an access level is stored in the session, if there isn't an access 
  //level redirect to the login page
  if (isset($_POST['logout'])) {
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
  //variables to connect to database.
  //////////CHANGE THESE VARIABLES AS NEEDED//////////
  $hostname="mysql.cs.bangor.ac.uk:3306";
  $username="eeu829";
  $password="Bangor16";
  $dbname="eeu829";
  $usertable1="SH_Orders";
  $userName = $_SESSION["username"];

  //establish link to database
  $link = mysqli_connect($hostname,$username, $password, $dbname); 

  //concatinate search values if search button is pressed

  if (isset($_POST['submit']) && isset($_POST['reqDate'])&& !empty($_POST['reqDate'])) {

    $sql = "SELECT * FROM $usertable1 WHERE userID = '$userName' AND ";

    if(isset($_POST['reqDate'])&& !empty($_POST['reqDate'])){
      $reqDate = $_REQUEST['reqDate'];
      $reqDate = mysqli_real_escape_string($link,$reqDate);
      $sql = $sql."reqDate like \"%".$reqDate."%\" AND ";
    }     

  } else {

    if($link===false){ 
      die ("<script language='JavaScript'>alert('Unable to connect to dababase!
        Please try again later.'),history.go(-1)</script>");
    }
    
    $sql = "SELECT * FROM $usertable1 WHERE userID = '$userName';";
  }


  ?>

  <script type="text/javascript">

    <?php
    //if submit button is pressed search the database with the sql statement, else bring back all rows
    if(isset($_POST['submit'])){
      $sql = rtrim($sql," AND ").";";    

      $result = $link->query($sql);

      $sql = mysqli_real_escape_string($link,$sql);

      echo "console.log(\"SQL Stat: ".$sql."\");";
      echo "console.log(\"".$link->error."\");";


    } else {

      $result = $link->query($sql);

    }
    ?>

  </script>

  <nav class="navbar navbar-inverse">  <!-- This is the creation of the nav bar section-->
    <div class="container-fluid">
      <div class="navbar-header">
        <a class="navbar-left"><img src="images/Bangor_Logo_A2.png"></a>
        
      </div>
      <ul class="nav navbar-nav">
        <li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" href="#">Ordering <span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href="OrderForm1.php">Order Form</a></li>     <!-- drop down box section of the nav bar linking to other pages-->
            <li><a href="OrderHistory.php">Order History</a></li>            
          </ul>
        </li>
        <?php
        // this section of php uses access levels to dictate the redriection
        // access level 1 will show a button to redirect to the budget holder
        if($_SESSION['accessLevel'] == 1){
          echo "<li><a href=\"budgetHolder.php\">Budget Holder</a></li>"; 
        }
         // access level 2 will show a button to redirect to the requistion officer.php page
        if($_SESSION['accessLevel'] == 2){
          echo "<li><a href=\"requisitionOfficer.php\">Req Officer</a></li>";
        }
 		// access level 3 will show a button to redirect to the Finance.php page                                                                      
        if($_SESSION['accessLevel'] == 3){
          echo "<li><a href=\"finance.php\">Finance</a></li>";
        }
        // access level 4 will show all buttons above                                                              
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

        <form method="POST" action="OrderHistory.php">
          <input type="submit" name="logout" class="btn btn-primary btn-rounded btn-small center-block moveButton" value="Logout">
        </form>
    </div>
  </nav>
  
  <br>
  <h3 class="text-center">Order History</h3>
  <br>
  <br>

  <form method = "post" class="form-inline text-center" action="OrderHistory.php"> <!--order history form used for dispalying details of past orders made by users -->
    <div class="form-group">
      <input type="text" class="form-control" name="reqDate" value=<?php echo "\"".$reqDate."\"";?> placeholder="Requisition Date"/>       

      <br>
      <br>
      
      <input type="submit" class="btn btn-success" name="submit" value="Search">

      <br>
      <br>
    </div>

  </form>

  <div class="container">        
    <table class="table table-dark table-bordered table-hover table-responsive-md">
      <thead>
        <tr>
          <th>ReqNo</th>
          <th>AccountID</th>
          <th>userID</th>
          <th>reqSite</th>
          <th>reqDate</th>
          <th>noItems</th>
          <th>estcost</th>
          <th>actCost</th>
          <th>orderDate</th>
          <th>authorised</th>
          <th>recurring</th>
          <th>TimeScale</th>
          <th>Order State</th>
        </tr>
      </thead>
      <tbody>

          <!--This section of code was required so that the relevent information can be fetched from the database-->

        <?php while($row = mysqli_fetch_array($result)):;?>
          <tr>
            <td><?php echo $row['reqNo'];?></td>
            <td><?php echo $row['accountID'];?></td>
            <td><?php echo $row['userID'];?></td>
            <td><?php echo $row['reqSite'];?></td>
            <td><?php echo $row['reqDate'];?></td>
            <td><?php echo $row['noItems'];?></td>
            <td>&pound;<?php echo $row['estCost'];?></td>
            <td>&pound;<?php echo $row['actCost'];?></td>
            <td><?php echo $row['orderDate'];?></td>
            <td><?php echo $row['Authorised'];?></td>
            <td><?php echo $row['Recurring'];?></td>
            <td><?php echo $row['Timescale'];?></td>
            <td>
                <?php
                if($row['orderState']==1){
                  echo "Requisitioned";
                }
                else if($row['orderState']==2){
                  echo "Denied";
                }
                else if($row['orderState']==3){
                  echo "Accepted";
                }
                else if($row['orderState']==4){
                  echo "Ordered";
                }
                else if($row['orderState']==5){
                  echo "Delivered";
                }
                else if($row['orderState']==6){
                  echo "Financed";
                }
                ?>
            </td> 

          </tr>
        <?php endwhile;?>
      </tbody>
    </table>
  </div>
  


</body>