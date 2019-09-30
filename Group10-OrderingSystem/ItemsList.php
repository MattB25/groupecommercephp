<!DOCTYPE html>
<html lang="en">
<head>
  <title>Item List</title>
  <?php
  //Start session
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
  <script type="text/javascript">
    <?php
    //variables to connect to database.
    //////////CHANGE THESE VARIABLES AS NEEDED//////////
    $hostname="mysql.cs.bangor.ac.uk:3306";
    $username="eeu829";
    $password="Bangor16";
    $dbname="eeu829";
    $table="SH_Items";

    $reqNoVal = $_POST['valueBox'];
    
    //establish link to database
    $link = mysqli_connect($hostname,$username, $password, $dbname); 
    //return all items in the order with the reqNo
    $result = mysqli_query($link, "SELECT * FROM $table WHERE reqNo =".$reqNoVal."; ");
    ?>
  </script>

  <nav class="navbar navbar-inverse">
    <div class="container-fluid">
      <div class="navbar-header">
        <aclass="navbar-left"><img src="images/Bangor_Logo_A2.png"></a>
        
      </div>
      <ul class="nav navbar-nav">
        <li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" href="#">Ordering <span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href="OrderForm1.php">Order Form</a></li>
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
          <input type="submit" name="userDetails" class="btn btn-primary btn-rounded btn-small center-block" value="User Details">
        </form>
        <!--<li><a name="logout" href="finance.php"><span class="glyphicon glyphicon-log-in"></span> Logout</a></li>-->
        <form method="POST" action="ItemsList.php">
          <input type="submit" name="logout" class="btn btn-primary btn-rounded btn-small center-block" value="Logout">
        </form>
      </ul>
    </div>
  </nav>
  
  <div class="container">
    <div class="row clearfix">
      <div class="col-md-12 column">
        <table class="table table-bordered table-hover table-responsive-md btn-table" id="tab_logic1">
          <thead>
            <tr >
              <th class="text-center">
                Requisition No.
              </th>
              <th class="text-center">
                Item No.
              </th>
              <th class="text-center">
                Description
              </th>
              <th class="text-center">
                Price
              </th>
              <th class="text-center">
                Quantity
              </th>
              <th class="text-center">
                Total Cost
              </th>
            </tr>
          </thead>
          <tbody>
            <?php 
            //This code adds a row to the table for each item and inputs relevant data
            while($row = mysqli_fetch_array($result)):;
              ?>
              <tr>
                <td><?php echo $row['reqNo'];?></td>
                <td><?php echo $row['itemNo'];?></td>
                <td><?php echo $row['Description'];?></td>
                <td><?php echo $row['Price'];?></td>
                <td><?php echo $row['Quantity'];?></td>
                <td><?php echo $row['totalCost'];?></td>
              </tr>
            <?php endwhile;?>
          </tbody>
        </table>
      </div>
    </div>
    

  </body>
  </html>



















  <!--DAB-->