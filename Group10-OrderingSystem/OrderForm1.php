<!DOCTYPE html>
<html lang="en">
<head>
	<title>OrderForm</title>
	
	<?php
 	/*
		Enables a session for the user on that page.
		Checks to see if they have pressed the logout button, if they have redirect
		Checks if they don't have an access level (meaning they are not logged in), if not redirect
 	*/
		session_start();

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
 /*
	Makes a connection to the database
	THESE VALUES CAN BE CHANGED AS NEEDED
 */
	$user_Id = $_SESSION['username'];
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

	<script>
		var descriptions = new Array();
		var quantities = new Array();
		var prices = new Array();
		var totalCosts = new Array();



		<?php
   /*
		If the submit button is pressed, initialise the variables below
		Send all of those values to orders table
		Go through the total number of items, send them to the database
   */

		if(isset($_POST["submit"])){

			$req = $_REQUEST['reqSite'];
			$accNo = $_REQUEST['AccountID'];
			$tScale = $_REQUEST['TimeScale'];
			$curDate = date("Y-m-d");
			$itemNo = count($_REQUEST['desc']); 
			$estCost =  $_REQUEST['estCost'];
			if($_REQUEST['recurring'] == "Yes"){
				$recurring = 1;
			} else {
				$recurring = 0;
			}

			$sql = "INSERT INTO SH_Orders (AccountID, userID, reqSite, reqDate, noItems, estCost, Authorised, Recurring, TimeScale, OrderState) VALUES ('$accNo', '$user_Id', '$req', '$curDate', $itemNo, $estCost, 0, $recurring, '$tScale',1)";

			if(mysqli_query($link,$sql)){
				$id = $link->insert_id;

				

				for($x = 0; $x<$itemNo;$x++){

					
					$tempDesc = $_REQUEST['desc'][$x];
					$tempPrice = $_REQUEST['price'][$x];
					$tempQnty = $_REQUEST['qnty'][$x];
					$tempTCost = $_REQUEST['tCost'][$x];
					$tempItemNo = $x +1;
					$sql2 = "INSERT INTO SH_Items (reqNo, itemNo, Description, Price, Quantity, totalCost) VALUES ($id, $tempItemNo, '$tempDesc', $tempPrice,  $tempQnty, $tempTCost);";

					
					if(mysqli_query($link,$sql2)){
						
					} 
					
				}

			} else {
				echo "console.log(\"ERROR: Unable to execute willy $sql. ".mysqli_error($link)."\");";
			}




		}

		?>
	</script>

	<!-- NAV BAR -->
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

      	/*
			Checks the access level of the user, determines what buttons appear in the nav bar
			1 = Budget Holder
			2 = Req Officer
			3 = Finance
			4 = Admin
      	*/

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
			<form method="POST" action="OrderForm1.php">
				<input type="submit" name="logout" class="btn btn-primary btn-rounded btn-small center-block moveButton" value="Logout">
			</form>
		</ul>
	</div>
</nav>

<br>
<!-- Form to Place Order -->
<h3 class="text-center">
	Fill in the Form Below to Place an Order
</h3>
<br>

<div>
	<form class="form-horizontal" action="OrderForm1.php" method="post" id="itemForm">
		<div class="row">
			<div class="col-md-offset-4 col-md-4">

				<div class="form-group">
					<label class="control-label">Requsite:</label>
					<input  name="reqSite" id="req" placeholder="e.g www.amazon.com" class="form-control" minlength="1" type="text" required>
					<label></label>
				</div>

				<div class="form-group">
					<label class="control-label">Account Number:</label>
					<input name="AccountID" placeholder="012345678" class="form-control" minlength="4" type="text" required>
				</div>

				<div class="form-group">
					<label>Recurring</label>
					<div class="radio">
						<label><input type="radio" name="recurring" value="Yes" id="recYes" onchange="enableText()" checked="true"/> Yes</label>
					</div>
					<div class="radio">
						<label><input type="radio" name="recurring" value="No" id="recNo" onchange="enableText()"/> No</label>
					</div>
				</div>

				<div class="form-group">
					<label class="control-label">Time Scale:</label>
					<input name="TimeScale" placeholder="" class="form-control" id="timeField" type="text" required>
				</div>

				<div class="form-group">
					<label class="control-label">Estimated Cost:</label>
					<input name="estCost" placeholder="" class="form-control" id="timeField" type="text" required>
				</div>


				<div class="form-group">
					<label class="control-label">Item Description</label>
					<input name='description'  placeholder='Description' class="form-control" id= "desc"  type="text" required/>
				</div>

				<div class="form-group">
					<label class="control-label">Item Price:</label>
					<input name='price' class="form-control" id= "price"  type="text" required/>
				</div>


				<div class="form-group">
					<label class="control-label">Item Quantity:</label>
					<input name='quantity' placeholder='Quantity' class="form-control" id= "qnty"  type="text" required/>
				</div>

				<div class="form-group">
					<label class="control-label">Total Cost:</label>
					<input name='total_cost' placeholder='Total Cost' class="form-control" id= "tCost"  type="text" required/>
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

		<br>
		<h3 id="orderForm" class="text-center">
			Item List 
		</h3>
		<br>

		<!-- Table to display times -->
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
							<tr id='addr0'>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>

	</form>
</div>


</div>
<br>

</script>

<script type="text/javascript">

	/*
		Adds a new item row to the items table 
		*/	
		var counter = 1;

		function updateForm() {
			var desc = document.getElementById("desc").value;
			var price = document.getElementById("price").value;
			var qnty = document.getElementById("qnty").value;     
			var tCost = document.getElementById("tCost").value;


			var table=document.getElementById("tab_logic1");
			var row=table.insertRow(-1);
			var cell1=row.insertCell(0);
			var cell2=row.insertCell(1);
			var cell3=row.insertCell(2);
			var cell4=row.insertCell(3);
			var cell5=row.insertCell(4);

			cell1.innerHTML=counter;

			cell2.innerHTML= "<input type = \"hidden\" name = \"desc[]\" value = \""+desc+"\">" + desc;

			cell3.innerHTML= "<input type = \"hidden\" name = \"price[]\" value = \""+price+"\">" +price;     

			cell4.innerHTML= "<input type = \"hidden\" name = \"qnty[]\" value = \""+qnty+"\">" +qnty;    

			cell5.innerHTML= "<input type = \"hidden\" name = \"tCost[]\" value = \""+tCost+"\">" +tCost;


			counter++;     
		}

  /*
	Delets the last row from the table
	*/
	function DeleteRow() {
		if(counter > 1){
			descriptions.pop();
			prices.pop();
			quantities.pop();
			totalCosts.pop();
			document.getElementById("tab_logic1").deleteRow(tab_logic1.rows.length-1);
			counter--;
		}
	}

</script>


<script type="text/javascript">
	/*
		enable or disables of the timescale text field, depedning on which radio button is pressed
		*/
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