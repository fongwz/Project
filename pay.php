
<?php
	session_start();
	$UID = $_SESSION['UID'];		//retrieve UID
	$UNAME = $_SESSION['UNAME'];	//retrieve USERNAME
	$PID = $_SESSION['PID'];
	$PNAME = $_SESSION['PNAME'];

  	// Connect to the database. 
    include 'db.php';
	$db = init_db();	

	//Display selected project based on $PID and $PNAME
	$result = pg_query($db, "SELECT * FROM projectsOwnership WHERE ownername = '$PNAME' AND projectid = '$PID'");
	$rows = pg_fetch_assoc($result);

	if (!$result) {
		echo "error getting proj from db";
	}
	/* debugging
	echo "<br><br><br>HELLO"; //testing
	echo "<br>";
	echo "$PID";
	echo "<br>";
	echo "$PNAME";
	echo "<br>debugging----ignore above this line";

	echo "<br>";
	*/
	$arr = pg_fetch_all($result);

	foreach ($arr as $value){
		$arr2 = array_values($value);
		$projname = $arr2[0];
		$projdesc = $arr2[1];
		$projSDate = $arr2[2];
		$projEDate = $arr2[3];
		$projOName = $arr2[5];
		$projamount = $arr2[6];
		$projprogress = $arr2[7];
		$projcat = $arr2[8];
	}

	//execute payment query
	if(isset($_POST['pay'])){
		//query here and show confirmation/bring to payment confirm page
		
		//get invest amount, invest type, current date
		$payvalue = $_POST['payvalue'];
		$payfield = $_POST['payfield'];
		$dateinvested = date("Y-m-d");

		//get next investmentID
		$sql = "SELECT MAX(CAST(investmentID AS INT)) + 1 AS maxID FROM investments";
		$nextIDResult = pg_query($db, $sql);
		$row = pg_fetch_assoc($nextIDResult);
		$nextId = $row[maxid];		

		//update investment table in db
		$result = pg_query($db, "INSERT INTO investments(amount, dateInvested, investmentID, investorName, projectID, ownerName)
								values ('$payvalue', '$dateinvested', '$nextId', '$UNAME', '$PID', '$PNAME')");

		//update projectsownership in db
		$sql = "SELECT targetAmount AS targetamount,progress AS progress FROM projectsOwnership WHERE projectID = '$PID' AND ownerName = '$PNAME'";
		$result = pg_query($db, $sql);
		$row = pg_fetch_assoc($result);
		$targetamount = $row[targetamount];
		$progress = $row[progress];
		$progress = (($progress * $targetamount) + $payvalue) / $targetamount;

		//debug
		// "<br><h2>$progress</h2>";
		$result = pg_query($db, "UPDATE projectsOwnership SET progress = '$progress' WHERE projectID = '$PID' AND ownerName = '$PNAME'");
	}
	
	//logging out
	if(isset($_GET['logout'])){
		$link=$_GET['logout'];
		if ($link == 'true'){
			header("Location: logout.php");
			exit;
		}
	}	
	
?> 

<!DOCTYPE html>  
<html>
<head>
  <title>UPDATE PostgreSQL data with PHP</title>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

  <!-- Import CSS Files -->
  <link rel="stylesheet" href="css/w3.css">
  
</head>

<body>
<!-- Nagivation Bar -->
<?php
if($UNAME == NULL){
	$menu = file_get_contents('menu.html');
	echo $menu;
}
else{
	$menu = file_get_contents('menu-loggedin.html');
	echo $menu;
}
?>

<!-- Slide Show
<div class="w3-content w3-section" style="max-height:500px">
  <img class="mySlides" src="img/water.jpg" style="width:100%">
  <img class="mySlides" src="img/castle.jpg" style="width:100%">
  <img class="mySlides" src="img/road.jpg" style="width:100%">
</div>
-->

<!-- Main Body -->
<?php //need to update database with payment types
echo "
	<form class='w3-container' method='POST'>
    <p>      
    <label class='w3-text-brown'><b>Project Name</b></label></p>
	<p>
    <label class='w3-text-black w3-border w3-sand'>$projname</label></p>

    <p>      
    <label class='w3-text-brown'><b>Project Description</b></label></p>
	<p>
    <label class='w3-text-black w3-border w3-sand'>$projdesc</label></p>

	<p>      
    <label class='w3-text-brown'><b>Start Date</b></label></p>
	<p>
    <label class='w3-text-black w3-border w3-sand'>$projSDate</label></p>

	<p>      
    <label class='w3-text-brown'><b>End Date</b></label></p>
	<p>
    <label class='w3-text-black w3-border w3-sand'>$projEDate</label></p>

	<p>      
    <label class='w3-text-brown'><b>Owner Name</b></label></p>
	<p>
    <label class='w3-text-black w3-border w3-sand'>$projOName</label></p>

	<p>      
    <label class='w3-text-brown'><b>Target Amount</b></label></p>
	<p>
    <label class='w3-text-black w3-border w3-sand'>$projamount</label></p>

	<p>      
    <label class='w3-text-brown'><b>Progress</b></label></p>
	<p>
    <label class='w3-text-black w3-border w3-sand'>$projprogress</label></p>

	<p>      
    <label class='w3-text-brown'><b>Category</b></label></p>
	<p>
    <label class='w3-text-black w3-border w3-sand'>$projcat</label></p>

	<hr>

    <p>
	<label class='w3-text-brown'><b>Contribution Amount:</b></label></p>
	<p>
	<input class='w3-input w3-border w3-sand' name='payvalue' type='number'></p>
	<label class='w3-text-brown'><b>Make Contribution With:</b></label>
    <select name='payfield'>
		<option value='paypal'>Paypal</option>
		<option value='enets'>eNETS</option>
		<option value='creditcard'>Credit Card</option>
    </select>
    <input class='w3-btn w3-brown' type='submit' name='pay' value='Contribute!'></button>
	</form>";
?>

<!-- Import Javascript Files -->
<script src="js/scripts.js"></script>
</body>
</html>