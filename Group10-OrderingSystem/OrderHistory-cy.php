<!DOCTYPE html>
<html lang="cy">
<head>
  <title>Archeb Hanes</title>
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
  $usertable1="SH_Orders";
  
  $link = mysqli_connect($hostname,$username, $password, $dbname); 

  if (isset($_POST['submit']) && isset($_POST['reqDate'])&& !empty($_POST['reqDate'])) {

    $sql = "SELECT * FROM $usertable1 WHERE ";

    if(isset($_POST['reqDate'])&& !empty($_POST['reqDate'])){
      $reqDate = $_REQUEST['reqDate'];
      $reqDate = mysqli_real_escape_string($link,$reqDate);
      $sql = $sql."reqDate like \"%".$reqDate."%\" AND ";
    }     

/*
    if(isset($_POST['userId'])&& !empty($_POST['userId'])){
      $userId = $_REQUEST['userId'];
      $userId = mysqli_real_escape_string($link,$userId);
      $sql = $sql."userID like \"%".$userId."%\" AND ";
    }      
    */

  } else {

    if($link===false){ 
      die ("<script language='JavaScript'>alert('Unable to connect to dababase!
        Please try again later.'),history.go(-1)</script>");
    }
    
    //$sql = "SELECT * FROM $usertable1 WHERE orderState BETWEEN 1 AND 3;";

    $sql = "SELECT * FROM $usertable1;";
  }
  //$result = mysqli_query($link, "SELECT * FROM $table");

  ?>

    <script type="text/javascript">

    <?php

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

  <nav class="navbar navbar-inverse">
    <div class="container-fluid">
      <div class="navbar-header">
        <a href="homepage.html" class="navbar-left"><img src="images/Bangor_Logo_A2.png"></a>
        
      </div>
      <ul class="nav navbar-nav">
        <li class=""><a href="homepage.html">Cartref</a></li>
        <li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" href="#">Ordering <span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href="OrderForm-cy.php">Ffurflen Archebu</a></li>
            <li><a href="OrderHistory-cy.php">Archeb Hanes</a></li>            
          </ul>
        </li>
        <li class=""><a href="budgetHolder-cy.php">deiliad y gyllideb</a></li>
        <li class=""><a href="requisitionOfficer-cy.php">Req Swyddog</a></li>
        <li class=""><a href="finance-cy.php">Cyllid</a></li>
      </ul>
      <ul class="nav navbar-nav navbar-right">
        <li><a href="userDetails-cy.php"><span class="glyphicon glyphicon-user"></span> Manylion defnyddiwr</a></li>
        <li><a href="Login-cy.php"><span class="glyphicon glyphicon-log-in"></span> Allgofnodi</a></li>
      </ul>
    </div>
  </nav>
  
  <br>
  <h3 class="text-center">Archeb Hanes</h3>
  <br>
  <br>

<form method = "post" class="form-inline text-center" action="OrderHistory.php">
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
            <td><?php echo $row['orderState'];?></td>  

          </tr>
        <?php endwhile;?>
      </tbody>
    </table>
  </div>
  


</body>