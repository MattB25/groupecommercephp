<!DOCTYPE html>
<html lang="en">
<head>
  <title>Budget Holder</title>
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
  else if($_SESSION["accessLevel"] == 1 OR $_SESSION["accessLevel"] == 4){
    $redirect = false;
  }else{
    $redirect = true;
  }

  if($redirect == true){
    echo "<meta http-equiv=\"refresh\" content=\"0; URL=OrderForm1.php\">";
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
  $usertable1="SH_Orders";
  $userName = $_SESSION["username"];

  $link = mysqli_connect($hostname,$username, $password, $dbname); 

  $sqlAccounts = "SELECT * FROM SH_UserLog WHERE userID = '$userName'";
  $accountResults = $link->query($sqlAccounts);
  $tempRow = mysqli_fetch_array($accountResults);
  $accountResult = $tempRow['accountID'];
  
  /* Updates orderstate in database*/

  if(isset($_POST['accept'])){

    $processSql = "UPDATE SH_Orders set orderState = 3 where reqNo = ".$_POST['processBox'].";";
    mysqli_query($link,$processSql);
  }

  if(isset($_POST['deny'])){

    $processSql = "UPDATE SH_Orders set orderState = 2 where reqNo = ".$_POST['processBox'].";";
    mysqli_query($link,$processSql);
  }

/* The two search fields to filter the results on the page*/
  if (isset($_POST['submit'])) {

    $sql = "SELECT * FROM $usertable1 WHERE accountID = '$accountResult' AND ";

    if(isset($_POST['reqDate'])&& !empty($_POST['reqDate'])){
      $reqDate = $_REQUEST['reqDate'];
      $reqDate = mysqli_real_escape_string($link,$reqDate);
      $sql = $sql."reqDate like \"%".$reqDate."%\" AND ";
    }     

    if(isset($_POST['userId'])&& !empty($_POST['userId'])){
      $userId = $_REQUEST['userId'];
      $userId = mysqli_real_escape_string($link,$userId);
      $sql = $sql."userID like \"%".$userId."%\" AND ";
    }       

  } else {

    if($link===false){ 
      die ("<script language='JavaScript'>alert('Unable to connect to dababase!
        Please try again later.'),history.go(-1)</script>");
    }
/*Shows all orders with a state of 5 or 6*/
    $sql = "SELECT * FROM $usertable1 WHERE orderState BETWEEN 1 AND 3  AND accountID = '$accountResult';";
  }

  ?>

  <script type="text/javascript">

    <?php
/* if submit is pressed, queries the database*/
    if(isset($_POST['submit'])){
      $sql = $sql."orderState BETWEEN 1 and 3;";    

      $result = $link->query($sql);

      $sql = mysqli_real_escape_string($link,$sql);

      echo "console.log(\"SQL Stat: ".$sql."\");";
      echo "console.log(\"".$link->error."\");";


    } else {

      $result = $link->query($sql);

    }
    ?>

  </script>
<!-- navbar -->
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
        <form method="POST" action="budgetHolder.php">
          <input type="submit" name="logout" class="btn btn-primary btn-rounded btn-small center-block moveButton" value="Logout">
        </form>
      </ul>
    </div>
  </nav>

  <br>
  <h3 class="text-center">
    Orders Awaiting Approval
  </h3>
  <br>
  <br>

  <!--Search Form-->
  <form method = "post" class="form-inline text-center" action="budgetHolder.php">

    <div class="form-group">

      <input type="text" class="form-control" name="reqDate" value=<?php echo "\"".$reqDate."\"";?> placeholder="Requisition Date"/>       

      <input type="text" class="form-control" name="userId" value=<?php echo "\"".$userId."\"";?> placeholder="User Id"/>

      <br>
      <br>

      <input type="submit" class="btn btn-success" name="submit" value="Search">

      <br>
      <br>
    </div>

  </form>
  <!-- Table Headers -->
  
  <div class="container">
    <div class="row clearfix">
      <div class="col-md-12 column">
        <table class="table table-bordered table-hover table-responsive-md btn-table" id="tab_logic1">
          <thead>
            <tr >

              <th class="text-center">
                User ID
              </th>
              <th class="text-center">
                Requisition Site
              </th>
              <th class="text-center">
                Requisition Date
              </th>
              <th class="text-center">
                No. Items
              </th>
              <th class="text-center">
                Estimated Cost
              </th>
              <th class="text-center">
                State
              </th>
              <th class="text-center">
                View Items
              </th>
              <th class="text-center">
                Process
              </th>
            </tr>
          </thead>
          <tbody>
            <?php while($row = mysqli_fetch_array($result)):;?>

            <!-- This shows what the order state numbers mean -->
              <tr>
                <td><?php echo $row['userID'];?></td>
                <td><?php echo $row['reqSite'];?></td>
                <td><?php echo $row['reqDate'];?></td>
                <td><?php echo $row['noItems'];?></td>
                <td><?php echo $row['estCost'];?></td>
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
                  ?>
                </td>
                <!-- Code for the buttons -->
                <td align="center">
                  <form method="post" action="ItemsList.php" target="_blank">
                    <input type="text" name="valueBox" hidden="true" value = <?php echo "\"".$row['reqNo']."\"" ?>></input>
                    <input type="submit" name="viewItems" class="btn btn-primary btn-rounded btn-small" value="View Items"/>
                  </form>
                </td>
                <td align="center">
                  <form method="post" action="budgetHolder.php">
                    <input type="text" name="processBox" hidden="true" value = <?php echo "\"".$row['reqNo']."\"" ?>></input>
                    <input type="submit" name="accept" class="btn btn-success btn-rounded btn-small" 
                    <?php
                    /* Changes orderstate to 3 if accepted*/
                    if($row['orderState']==3){
                      echo "disabled =\"true\" ";
                      echo "value=\"Accept\"";
                    } else {
                      echo "value=\"Accept\"";
                    }
                    ?>
                    />
                    <input type="submit" name="deny" class="btn btn-danger btn-rounded btn-small" 
                    <?php
                    /* Changes orderstate to 2 if denied*/
                    if($row['orderState']>=2){
                      echo "disabled =\"true\" ";
                      echo "value=\"Deny\"";
                    } else {
                      echo "value=\"Deny\"";
                    }
                    ?>
                    />
                  </form>
                </td>
              </tr>
            <?php endwhile;?>
          </tbody>
        </table>
      </div>
    </div>


  </body>
  </html>