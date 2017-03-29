<?php

	/* 	5- create a log page */
		
	
	/* This page can be smaller since there is only inputs */
	$GLOBALS['tempPageDimension'] = "col-md-6 col-md-offset-3";

	/* import head.php file. It contains config.php file as well */
	require_once("head.php");
?>

<!-- Datepicker -->
<link rel='stylesheet' href='//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css'>
<script src='https://code.jquery.com/jquery-1.12.4.js'></script>
<script src='https://code.jquery.com/ui/1.12.1/jquery-ui.js'></script>

<script type='text/javascript'>
	$(function(){
		$('#datepickerPurchase').datepicker({
			todayHighlight: true,
			showButtonPanel: true,
			changeYear: true,
			changeMonth: true,
			dateFormat: "yy-mm-dd",
			toggleActive: true
		});
	});
	$(function(){
		$('#datepickerExpiration').datepicker({
			todayHighlight: true,
			showButtonPanel: true,
			changeYear: true,
			changeMonth: true,
			dateFormat: "yy-mm-dd",
			toggleActive: true
		});
	});
</script>

<script>
	function disableCheckbox(ID, checkbox) {
		document.getElementById(ID).value = "1969-12-31";
		document.getElementById(ID).disabled = checkbox.checked;
	}
</script>

<?php

	if(ISSET($_POST['submit'])) {
		/* Grab all the data from the form and insert it into variables */
		$software_name = $_POST['inputSoftwareName'];
		$version = $_POST['inputVersion'];
		$category = $_POST['inputCategory'];
		$type = $_POST['inputType'];
		$license_key = $_POST['inputLicenseKey'];
		$purchase_date = $_POST['inputPurchaseDate'];
		$expiration_date = $_POST['inputExpirationDate'];
		$purchase_vendor = $_POST['inputPurchaseVendor'];
		$quote_number = $_POST['inputQuoteNumber'];
		$price_per_unit = $_POST['inputPricePerUnit'];
		$quantity = $_POST['inputQuantity'];
		$quantity_in_use = $_POST['inputQuantityInUse'];
		
		/* create a variable that will be used as a flag for when input are incorrect */
		$passedInputCheck = true;
		
		/*******************************************************************************
		*******************************************************************************/
		
		/* checking all variables to make sure nothing is wrong */
		if(strlen($software_name) < 3 ||  strlen($software_name) > 50 ) {
			alertDanger('Invalid Software Name! Must have more than 3 characters');
			$passedInputCheck = false;
		}
		
		if(strlen($version) == 0) {
			$version = "N/A";
		}
		
		if(strlen($license_key) < 5 || strlen($license_key) > 50) {
			alertDanger("Invalid License Key! Must have more than 5 characters");
			$passedInputCheck = false;
		}
		
		if(strlen($purchase_date) == 0) {
			$purchase_date = "1969-12-31";
		}
		
		if(strlen($expiration_date) == 0) {
			$expiration_date = "1969-12-31";
		}
		
		if(strlen($quote_number) == 0) {
			$quote_number = "N/A";
		}
		
		if(strlen($price_per_unit) == 0) {
			$price_per_unit = "N/A";
		}
		
		if(strlen($quantity) == 0) {
			$quantity = "N/A";
		}
		
		if(strlen($quantity_in_use) == 0) {
			$quantity_in_use = "N/A";
		}
		
		/* If all inputs pass the validation on top we insert the query */	
		if($passedInputCheck) {
			/* INSERT query that will enter the data into the database. ? are used as PDO requirement */
			$query = "INSERT INTO `licenses` (`software_name`, 
			`version`, `category`, `type`, `license_key`, `purchase_date`, `expiration_date`, `purchase_vendor`, 
			`quote_number`, `price_per_unit`, `quantity`, `quantity_in_use`) VALUES(?,?,?,?,?,?,?,?,?,?,?,?)";

			/* Equivalent to $result = $mysqlConn->query($query) */
			$statement = $mysqlConn->prepare($query);
			
			/* Add all data into an array and use bindParam to access and insert them later */
			$values = array($software_name, $version, $category, $type, $license_key, $purchase_date, $expiration_date,
							$purchase_vendor, $quote_number, $price_per_unit, $quantity, $quantity_in_use);
			
			for($i = 0; $i < count($values); $i++){
				//echo "Index: " . ($i+1) . " " . " Value: " . $values[$i] . "<br />";
				$statement->bindParam(($i + 1), $values[$i]);
				
			}
			
			if($statement->execute()) {
				alertSuccess("New License Added Successfully!");
			}
			else {
				alertDanger("ERROR!");
			}
		} else {
			alertDanger("Values Not Inserted! Please check your inputs!");
		}
	}
?>

<!-- Header -->
<?php boxHeader("Add License");?>

<!-- Main form of the page -->

<form action="addLicense.php" method="post" class="form-horizontal">
	
	<div class="form-group">
		<label for="inputSoftwareName" class="col-sm-4 control-label text-left">Software Name:</label>
		<div class="col-sm-6">
			<input type="text" class="form-control" name="inputSoftwareName" id="inputSoftwareName" placeholder="Enter Software Name" maxlength="50" required  />
		</div>
	</div>
	
	<div class="form-group">
		<label for="inputVersion" class="col-sm-4 control-label text-left">Version:</label>
		<div class="col-sm-6">
			<input type="text" class="form-control" name="inputVersion" id="inputVersion"  placeholder="Enter Version" maxlength="50" required />
		</div>

	</div>
	
	<?php
		$query = "SELECT category_name FROM category ORDER BY `category_name`";
		$statement = $mysqlConn->prepare($query);
		$statement->execute();
	?>
	
	<div class="form-group">
		<label for="inputCategory" class="col-sm-4 control-label text-left">Category:</label>
		<div class="col-sm-6">
			<select type="text" class="form-control" name="inputCategory" id="inputCategory">
				<?php
					while($row = $statement->fetch()){
						echo "<option value='".$row['category_name']."'>".$row['category_name']."</option>";
					}
				?>
			</select>
		</div>
	</div>
	
	<?php
		$query = "SELECT type_name FROM type ORDER BY `type_name`";
		$statement = $mysqlConn->prepare($query);
		$statement->execute();
	?>
	
	<div class="form-group">
		<label for="inputType" class="col-sm-4 control-label text-left">Type:</label>
		<div class="col-sm-6">
			<select type="text" class="form-control" name="inputType" id="inputType">
				<?php
					while($row = $statement->fetch()){
						echo "<option value='".$row['type_name']."'>".$row['type_name']."</option>";
					}
				?>
			</select>
		</div>
	</div>
	
	<div class="form-group">
		<label for="inputLicenseKey" class="col-sm-4 control-label text-left">License Key:</label>
		<div class="col-sm-6">
			<input type="text" class="form-control" name="inputLicenseKey" id="inputLicenseKey"  placeholder="Enter License Key" maxlength="50" required />
		</div>
	</div>
	
	<div class="form-group">
		<label for="inputPurchaseDate" class="col-sm-4 control-label text-left">Purchase Date:</label>
		<div class="col-sm-5">
			<input type="text" class="form-control" name="inputPurchaseDate" id="datepickerPurchase"  placeholder="Enter Purchase Date" required />
		</div>
		<div class="col-sm-3">
			<input type="checkbox" onclick="disableCheckbox('datepickerPurchase', this)" id="purchaseDateCheck" /> No Date
		</div>
	</div>
	
	<div class="form-group">
		<label for="inputExpirationDate" class="col-sm-4 control-label text-left">Expiration Date:</label>
		<div class="col-sm-5">
			<input type="text" class="form-control" name="inputExpirationDate" id="datepickerExpiration"  placeholder="Enter Expiration Date" required />
		</div>
		<div class="col-sm-3">
			<input type="checkbox" onclick="disableCheckbox('datepickerExpiration', this)" id="expirationDateCheck" /> No Date
		</div>
	</div>
	
	<?php
		$query = "SELECT vendor_name FROM vendor ORDER BY `vendor_name`";
		$statement = $mysqlConn->prepare($query);
		$statement->execute();
	?>
	
	<div class="form-group">
		<label for="inputPurchaseVendor" class="col-sm-4 control-label text-left">Purchase Vendor:</label>
		<div class="col-sm-6">
			<select type="text" class="form-control" name="inputPurchaseVendor" id="inputPurchaseVendor">
				<?php
					while($row = $statement->fetch()){
						echo "<option value='".$row['vendor_name']."'>".$row['vendor_name']."</option>";
					}
				?>
			</select>
		</div>
	</div>
	
	<div class="form-group">
		<label for="inputQuoteNumber" class="col-sm-4 control-label text-left">Quote Number:</label>
		<div class="col-sm-6">
			<input type="text" class="form-control" name="inputQuoteNumber" id="inputQuoteNumber"  placeholder="Enter Quote Number" maxlength="50" />
		</div>
	</div>
	
	<div class="form-group">
		<label for="inputPricePerUnit" class="col-sm-4 control-label text-left">Price Per Unit:</label>
		<div class="col-sm-6">
			<input type="number" step="any" class="form-control" name="inputPricePerUnit" id="inputPricePerUnit"  placeholder="Enter Price Per Unit" maxlength="50" />
		</div>
	</div>
	
	<div class="form-group">
		<label for="inputQuantity" class="col-sm-4 control-label text-left">Quantity:</label>
		<div class="col-sm-6">
			<input type="number" step="any" class="form-control" name="inputQuantity" id="inputQuantity"  placeholder="Enter Quantity" maxlength="50" />
		</div>
	</div>
	
	<div class="form-group">
		<label for="inputQuantityInUse" class="col-sm-4 control-label text-left">Quantity In Use:</label>
		<div class="col-sm-6">
			<input type="number" step="any" class="form-control" name="inputQuantityInUse" id="inputQuantityInUse"  placeholder="Enter Quantity In Use" maxlength="50" />
		</div>
	</div>
	
	<center><div class="form-group login-submit">
		<input type='submit' class='btn btn-lg btn-primary btn-block' name='submit' value='Add License'>
	</div></center>
</form>

<?php 	

	/* Close MySQL connection. Since in PDO $mysqlConn is an object, we must assign a value to it
	which in our case should be NULL in order to 'destroy' the connection */
	$mysqlConn = null;
?>

<!-- Footer -->
<?php boxFooter(); 
require_once('footer.php'); ?>