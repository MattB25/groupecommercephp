<!DOCTYPE html>
<html lang="cy">
<head>
	<title>Archeb</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="style.css">
	<script src='http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>
	<script src='http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js'></script>
	<script src="//cdnjs.cloudflare.com/ajax/libs/jquery-form-validator/2.3.26/jquery.form-validator.min.js"></script>
</head>
<body>
 <?php
 $hostname="mysql.cs.bangor.ac.uk:3306";
 $username="eeu829";
 $password="Bangor16";
 $dbname="eeu829";
 $table="SH_Orders";
 $table2="SH_Items";

 $link = mysqli_connect($hostname,$username, $password, $dbname); 
 $result = mysqli_query($link, "SELECT * FROM $table");
 $result2 = mysqli_query($link, "SELECT * FROM $table2");

 ?>

 <?php

if(isset($_POST["submit"])){

  $req = $_REQUEST['reqSite'];
  $accNo = $_REQUEST['AccountID'];
  $tScale = $_REQUEST['TimeScale'];

  $sql = "INSERT INTO SH_Orders (AccountID, reqSite, TimeScale) VALUES ('$accNo', '$req', '$tScale')";

  if(mysqli_query($link,$sql)){
    echo "Records added successfully.";
  } else {
    echo "ERROR: Unable to execute $sql. " . mysqli_error($link);
  }

  $Description = $_REQUEST['description'];
  $Price = $_REQUEST['price'];
  $Quantity = $_REQUEST['quantity'];

  $sql2 = "INSERT INTO SH_Items (Description, Price, Quantity) VALUES ('$Description', '$Price', '$Quantity')";

  if(mysqli_query($link,$sql2)){
    echo "items added successfully.";
  } else {
    echo "ERROR: Unable to execute $sql2. " . mysqli_error($link);
  }
  

 }

?>

 <nav class="navbar navbar-inverse">
  <div class="container-fluid">
    <div class="navbar-header">
      <a href="index.html" class="navbar-left"><img src="images/Bangor_Logo_A2.png"></a>

    </div>
    <ul class="nav navbar-nav">
      <li class=""><a href="index.html">Cartref</a></li>
      <li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" href="#">Archebu <span class="caret"></span></a>
        <ul class="dropdown-menu">
          <li><a href="OrderForm-cy.php">archebu</a></li>
          <li><a href="OrderHistory-cy.php">archebu hanes</a></li>            
        </ul>
      </li>
      <li class=""><a href="budgetHolder-cy.php">deiliad y gyllideb</a></li>
      <li class=""><a href="requisitionOfficer-cy.php">swyddog req</a></li>
      <li class=""><a href="finance-cy.php">cyllid</a></li>
    </ul>
    <ul class="nav navbar-nav navbar-right">
      <li><a href="userDetails-cy.php"><span class="glyphicon glyphicon-user"></span>manylion defnyddiwr</a></li>
      <li><a href="#"><span class="glyphicon glyphicon-log-in"></span> allgofnodi</a></li>
    </ul>
  
</nav>

<br>
<h3 class="text-center">
Llenwch y ffurflen isod i osod archeb
</h3>
<br>

<div>
  <form class="form-horizontal " action="OrderForm1.php" method="post" id="itemForm">
    <div class="row">
      <div class="col-md-offset-4 col-md-4">

        <div class="form-group">
          <label class="control-label">Angenrheidiol</label>
          <input  name="reqSite" id="req" placeholder="e.g www.amazon.com" class="form-control" minlength="2" type="text" required>
          <label></label>
        </div>

        <div class="form-group">
          <label class="control-label">Rhif cyfrif:</label>
          <input name="AccountID" placeholder="012345678" class="form-control" minlength="2" type="text" required>
        </div>

        <div class="form-group">
          <label>Yn rheolaidd</label>
          <div class="radio">
            <label><input type="radio" name="recurring" value="Yes" id="recYes" onchange="enableText()" checked="true"/> Yes</label>
          </div>
          <div class="radio">
            <label><input type="radio" name="recurring" value="No" id="recNo" onchange="enableText()"/> No</label>
          </div>
        </div>

        <div class="form-group">
          <label class="control-label">Amser graddfa:</label>
          <input name="TimeScale" placeholder="" class="form-control" id="timeField" minlength="2" type="text" required>
        </div>

        <div class="form-group">
          <label class="control-label">Eitem disgrifiad</label>
          <input name='description'  placeholder='Disgrifiad' class="form-control" id= "desc" minlength="2" type="text" required/>
        </div>

        <div class="form-group">
        	<label class="control-label">Pris eitem:</label>
			<input name='price' class="form-control" id= "Pris" minlength="2" type="text" required/>
		</div>


        <div class="form-group">
          <label class="control-label">Eitem maint:</label>
          <input name='quantity' placeholder='maint' class="form-control" id= "qnty" minlength="2" type="text" required/>
        </div>

        <div class="form-group">
          <label class="control-label">cyfanswm Cost:</label>
          <input name='total_cost' placeholder='Cyfanswm Cost' class="form-control" id= "tCost" minlength="2" type="text" required/>
        </div>

        <div class="form-group">
          <input id="add_row" type="button" class="btn btn-default pull-left" onClick="updateForm();" value="Add Row" />
          <input id='delete_row' type="button" onClick="DeleteRow();" class="btn btn-default pull-left" value="Delete Row"/>
        </div>

        <div class="form-group">
          <input type="submit" name="submit" class="btn btn-success" value="Submit">
        </div>

      </div>
    </div>
  </form>
</div>

<br>
<h3 id="orderForm" class="text-center">
  Rhestr o eitemau 
</h3>
<br>

<div class="container">
  <div class="row clearfix">
    <div class="col-md-12 column">
      <table class="table table-bordered table-hover" id="tab_logic1">
        <thead>
          <tr >
            <th class="text-center">
              #
            </th>
            <th class="text-center">
              Disgrifiad
            </th>
            <th class="text-center">
              Pris
            </th>
            <th class="text-center">
              maint
            </th>
            <th class="text-center">
              Cyfanswm Cost
            </th>
          </tr>
        </thead>
        <tbody>
          <tr id='addr0'>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
  </div>
  
</div>
<br>

</script>

<script type="text/javascript">
    
    var counter = 1;

    function updateForm() {
        var desc = document.getElementById("desc").value;
        var price = document.getElementById("price").value;
        var qnty = document.getElementById("qnty").value;     
        var tCost = document.getElementById("tCost").value

        

        var table=document.getElementById("tab_logic1");
        var row=table.insertRow(-1);
        var cell1=row.insertCell(0);
        var cell2=row.insertCell(1);
        var cell3=row.insertCell(2);
        var cell4=row.insertCell(3);
        var cell5=row.insertCell(4);

        cell1.innerHTML=counter;
        cell2.innerHTML=desc;
        cell3.innerHTML=price;        
        cell4.innerHTML=qnty;      
        cell5.innerHTML=tCost;

        counter++;     
    }

    function DeleteRow() {
      if(counter > 1){
        document.getElementById("tab_logic1").deleteRow(tab_logic1.rows.length-1);
        counter--;
      }
    }
 
</script>


<script type="text/javascript">
function enableText() {

  if (document.getElementById("recYes").checked == true) {
    document.getElementById("timeField").disabled = false;

  }if(document.getElementById("recNo").checked == true){
    document.getElementById("timeField").disabled = true;
  }
  
}

</script>

</body>
</html>