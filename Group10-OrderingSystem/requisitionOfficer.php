<!DOCTYPE html>
<html lang="en">
<head>
  <title>Requisition Officer</title>
  <?php
  session_start();

  if (isset($_POST['logout'])) {
    //echo $_SESSION["accessLevel"];
    $_SESSION = array();
    session_destroy();
    echo "<meta http-equiv=\"refresh\" content=\"0; URL=Login.php\">";
  }                                                                 // this section of code checks that the logout button has been pressed and then checks if they have no session and redirects.
  else if(!isset($_SESSION['accessLevel'])){
    echo "<meta http-equiv=\"refresh\" content=\"0; URL=Login.php\">";
  }
  else if($_SESSION["accessLevel"] == 2 OR $_SESSION["accessLevel"] == 4){
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
  $hostname="mysql.cs.bangor.ac.uk:3306";    // this section of the code makes the connection to th database
  $username="eeu829";
  $password="Bangor16";
  $dbname="eeu829";
  $usertable1="SH_Orders";
  $curDate = date("Y-m-d");



  $link = mysqli_connect($hostname,$username, $password, $dbname); // if order button is pressed the order state is changed and then updated in the database

  if(isset($_POST['order'])){

    $orderSql = "UPDATE SH_Orders set orderState = 4, orderDate = '$curDate' where reqNo = ".$_POST['orderBox'].";";
    mysqli_query($link,$orderSql);
  }

  if(isset($_POST['delivered'])){ // this section chnages order state based on if delievered is pressed.

    $orderSql = "UPDATE SH_Orders set orderState = 5 where reqNo = ".$_POST['orderBox'].";";
    mysqli_query($link,$orderSql);
  }


  if (isset($_POST['submit'])) { // this section is used for searching purposes and checks for req date and user ID  

    $sql = "SELECT * FROM $usertable1 WHERE ";

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

    if($link===false){  // if cannot connect to data base the message below is displayed 
      die ("<script language='JavaScript'>alert('Unable to connect to dababase!
        Please try again later.'),history.go(-1)</script>");
    }

    $sql = "SELECT * FROM $usertable1 WHERE orderState BETWEEN 3 AND 5;";
  }



  ?>

  <script type="text/javascript">

  // this checks that submit has been pressed returns orders with a state of 3 and 5 

    <?php

    if(isset($_POST['submit'])){
      $sql = $sql."orderState BETWEEN 3 and 5;";    

      $result = $link->query($sql);

      $sql = mysqli_real_escape_string($link,$sql);

      echo "console.log(\"SQL Stat: ".$sql."\");";
      echo "console.log(\"".$link->error."\");";


    } else {

      $result = $link->query($sql);

    }
    ?>

  </script>

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
        
        <!--the php section below checks the users acsess level and redirects the user based on this-->
        <?php

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
        <form method="POST" action="requisitionOfficer.php">
          <input type="submit" name="logout" class="btn btn-primary btn-rounded btn-small center-block moveButton" value="Logout">
        </form>
      </ul>
    </div>
  </nav>

  <br>
  <h3 class="text-center">
    Orders Pending
  </h3>
  <br>
  <br>

  <!--Search Form-->
  <form method = "post" class="form-inline text-center" action="requisitionOfficer.php">

    <div class="form-group">

      <input type="text"  class="form-control" name="reqDate" value=<?php echo "\"".$reqDate."\"";?> placeholder="Requisition Date"/>       

      <input type="text"  class="form-control" name="userId" value=<?php echo "\"".$userId."\"";?> placeholder="User Id"/>

      <br>
      <br>

      <input type="submit" class="btn btn-success" name="submit" value="Search">

      <br>
      <br>
    </div>

  </form>
 <!--This section below is the table that is used for showing the req officers pending orders -->
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
                Confirm Order and Delivery
              </th>
            </tr>
          </thead>
          <tbody>
            <?php while($row = mysqli_fetch_array($result)):;?>
              <tr>
                <td><?php echo $row['userID'];?></td>
                <td><?php echo $row['reqSite'];?></td>
                <td><?php echo $row['reqDate'];?></td>
                <td><?php echo $row['noItems'];?></td>
                <td><?php echo $row['estCost'];?></td>
                <td> <?php
                if($row['orderState']==3){
                  echo "Accepted";
                }
                else if($row['orderState']==4){
                  echo "Ordered";
                }
                else if($row['orderState']==5){
                  echo "Delivered";
                }

                ?>

              </td>
              <td align="center">
                <form method="post" action="ItemsList.php" target="_blank">
                  <input type="text" name="valueBox" hidden="true" value = <?php echo "\"".$row['reqNo']."\"" ?>></input>
                  <input type="submit" name="viewItems" class="btn btn-primary btn-rounded btn-small" value="View Items"/>
                </form>
              </td>
              <td align="center">
                <form method="post" action="requisitionOfficer.php">
                  <input type="text" name="orderBox" hidden="true" value = <?php echo "\"".$row['reqNo']."\"" ?>></input>
                  <input type="submit" name="order" class="btn btn-success btn-rounded btn-small" 
                  <?php
                  if($row['orderState']>=4){
                    echo "disabled =\"true\" ";
                    echo "value=\"Ordered\"";
                  } else {
                    echo "value=\"Order\"";
                  }
                  ?>
                  />

                  <input type="submit" name="delivered" class="btn btn-danger btn-rounded btn-small" 

                  <?php
                  if($row['orderState']==5){
                    echo "disabled =\"true\" ";
                    echo "value=\"Delivered\"";
                  } else {
                    echo "value=\"Delivered\"";
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