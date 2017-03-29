<?php
	/*
		This is the maintenance page. Here the user can change the values of a certain
		license and update the database appropriately.
	*/
	
	/* This page can be smaller since there is only inputs */
	$GLOBALS['tempPageDimension'] = "col-md-8 col-md-offset-2";
	
	/* Display an information page for the maintenance user */
	$GLOBALS['tempPageInfo'] = "Change the values in the categories below. If something does not need to be changed,
							you can leave it as is.";
	
	/* import head.php file. It contains config.php file as well */
	require_once("head.php");
	
	/* File required to store the changes made */
	require_once("changeLogFunc.php");
?>

<!-- Display Info Box. Let the user know how to correctly enter the data -->
<?php
	displayInfoBox();
?>

<?php
	/* Code for when Update Values button is pressed */
	if(ISSET($_POST['submit'])){
		$licenseId = $_GET['ID'];
		
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
		
		if(strlen($purchase_date) == 0 || strlen($purchase_date) == 1) {
			$purchase_date = "1969-12-31";
		}
		if(strlen($expiration_date) == 0 || strlen($expiration_date) == 1) {
			$expiration_date = "1969-12-31";
		}
		
		$username = $_SESSION['username'];
		
		$query = "SELECT * FROM `licenses` WHERE `ID` = $licenseId";
		$statement = $mysqlConn->prepare($query);
		$statement->execute();
		$row = $statement->fetch();
		
		/* ChangeLog functionality */
		if($row['software_name'] != $software_name) {
			insertLicenseChangeLogData($mysqlConn, $_SESSION['username'], $licenseId, 'Software Name', $row['software_name'], $software_name);
		}
		if($row['version'] != $version) {
			insertLicenseChangeLogData($mysqlConn, $_SESSION['username'], $licenseId, 'Version', $row['version'], $version);
		}
		if($row['category'] != $category) {
			insertLicenseChangeLogData($mysqlConn, $_SESSION['username'], $licenseId, 'Category', $row['category'], $category);
		}
		if($row['type'] != $type) {
			insertLicenseChangeLogData($mysqlConn, $_SESSION['username'], $licenseId, 'Type', $row['type'], $type);
		}
		if($row['license_key'] != $license_key) {
			insertLicenseChangeLogData($mysqlConn, $_SESSION['username'], $licenseId, 'License Key', $row['license_key'], $license_key);
		}
		if($row['purchase_date'] != $purchase_date) {
			insertLicenseChangeLogData($mysqlConn, $_SESSION['username'], $licenseId, 'Purchase Date', $row['purchase_date'], $purchase_date);
		}
		if($row['expiration_date'] != $expiration_date) {
			insertLicenseChangeLogData($mysqlConn, $_SESSION['username'], $licenseId, 'Expiration Date', $row['expiration_date'], $expiration_date);
		}
		if($row['purchase_vendor'] != $purchase_vendor) {
			insertLicenseChangeLogData($mysqlConn, $_SESSION['username'], $licenseId, 'Purchase Vendor', $row['purchase_vendor'], $purchase_vendor);
		}
		if($row['quote_number'] != $quote_number) {
			insertLicenseChangeLogData($mysqlConn, $_SESSION['username'], $licenseId, 'Quote Number', $row['quote_number'], $quote_number);
		}
		if($row['price_per_unit'] != $price_per_unit) {
			insertLicenseChangeLogData($mysqlConn, $_SESSION['username'], $licenseId, 'Price Per Unit', $row['price_per_unit'], $price_per_unit);
		}
		if($row['quantity'] != $quantity) {
			insertLicenseChangeLogData($mysqlConn, $_SESSION['username'], $licenseId, 'Quantity', $row['quantity'], $quantity);
		}
		if($row['quantity_in_use'] != $quantity_in_use) {
			insertLicenseChangeLogData($mysqlConn, $_SESSION['username'], $licenseId, 'Quantity In Use', $row['quantity_in_use'], $quantity_in_use);
		}
		
		$query = "UPDATE `licenses` 
					SET `software_name` = ?, 
					`version` = ?, 
					`category` = ?,
					`type` = ?,
					`license_key` = ?,
					`purchase_date` = ?,
					`expiration_date` = ?,
					`purchase_vendor` = ?,
					`quote_number` = ?,
					`price_per_unit` = ?,
					`quantity` = ?,
					`quantity_in_use` = ?
						WHERE 
							`ID` = ?
					";
					
		$statement = $mysqlConn->prepare($query);
		$result = $statement->execute(array($software_name, $version, $category, $type, $license_key,
											$purchase_date, $expiration_date, $purchase_vendor,
											$quote_number, $price_per_unit, $quantity, $quantity_in_use, $licenseId));
		
		if($result) {
			alertSuccess ("Record Updated Succesfully!");
		}
		else {
			alertDanger("There was an error updating! Please contact IT");
		}
	}

?>

<!-- Header -->
<?php boxHeader("License Maintenance");?>

<!-- Main part of the page will go here -->
<?php
	if(ISSET($_GET['ID'])) {
		$ID = $_GET['ID'];
	}
	
	$query = "SELECT * FROM `licenses` WHERE `ID` = $ID";
	$statement = $mysqlConn->prepare($query);
	$statement->execute();
	$row = $statement->fetch();
	
	// Grab this variables so we can create the dropdown function
	$categoryForThisId = $row['category'];
	$typeForThisId = $row['type'];
	$vendorForThisId = $row['purchase_vendor'];
	
	if($row['purchase_date'] == "1969-12-31") {
		echo "<script>
				document.getElementById('purchaseDateCheck').checked;
				</script>";
	}
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

<form action="maintenance.php?ID=<?php echo $ID; ?>" method="post" class="form-horizontal">

	<div class="form-group">
		<label for="SoftwareName" class="col-sm-4 control-label text-left">Software Name:</label>
		<div class="col-sm-8">
			<input type="text" class="form-control" name="inputSoftwareName" id="inputSoftwareName" value=<?php echo $row['software_name']; ?> />
		</div>
	</div>
	
	<div class="form-group">
		<label for="inputVersion" class="col-sm-4 control-label text-left">Version:</label>
		<div class="col-sm-8">
			<input type="text" class="form-control" name="inputVersion" id="inputVersion" value=<?php echo $row['version']; ?> />
		</div>
	</div>
	
	<?php
		$query2 = "SELECT category_name FROM category WHERE `category_name` <> '$categoryForThisId'";
		$statement2 = $mysqlConn->prepare($query2);
		$statement2->execute();
	?>
	
	<div class="form-group">
		<label for="Category" class="col-sm-4 control-label text-left">Category:</label>
		<div class="col-sm-8">
			<select type="text" class="form-control" name="inputCategory" id="inputCategory" >
				<?php
					echo "<option value='".$categoryForThisId."'>".$categoryForThisId."</option>";
					while($row2 = $statement2->fetch()){
						echo "<option value='".$row2['category_name']."'>".$row2['category_name']."</option>";
					}
				?>
			</select>
		</div>
	</div>
	
	<?php
		$query2 = "SELECT type_name FROM type WHERE `type_name` <> '$typeForThisId'";
		$statement2 = $mysqlConn->prepare($query2);
		$statement2->execute();
	?>
	
	<div class="form-group">
		<label for="Type" class="col-sm-4 control-label text-left">Type:</label>
		<div class="col-sm-8">
			<select type="text" class="form-control" name="inputType" id="inputType" >
				<?php
					echo "<option value='".$typeForThisId."'>".$typeForThisId."</option>";
					while($row2 = $statement2->fetch()){
						echo "<option value='".$row2['type_name']."'>".$row2['type_name']."</option>";
					}
				?>
			</select>
		</div>
	</div>
	
	<div class="form-group">
		<label for="LicenseKey" class="col-sm-4 control-label text-left">License Key:</label>
		<div class="col-sm-8">
			<input type="text" class="form-control" name="inputLicenseKey" id="inputLicenseKey" value=<?php echo $row['license_key']; ?> />
		</div>
	</div>
	
	<div class="form-group">
		<label for="PurchaseDate" class="col-sm-4 control-label text-left">Purchase Date:</label>
		<div class="col-sm-5">
			<input type="text" class="form-control" name="inputPurchaseDate" id="datepickerPurchase" value=<?php echo $row['purchase_date']; ?> />
		</div>
		<div class="col-sm-3">
			<input type="checkbox" onclick="disableCheckbox('datepickerPurchase', this)" id="purchaseDateCheck" /> No Date
		</div>
	</div>
	
	<div class="form-group">
		<label for="ExpirationDate" class="col-sm-4 control-label text-left">Expiration Date:</label>
		<div class="col-sm-5">
			<input type="text" class="form-control" name="inputExpirationDate" id="datepickerExpiration" value=<?php echo $row['expiration_date']; ?> />
		</div>
		<div class="col-sm-3">
			<input type="checkbox" onclick="disableCheckbox('datepickerExpiration', this)" id="expirationDateCheck" /> No Date
		</div>
	</div>
	
	<?php
		$query2 = "SELECT vendor_name FROM vendor WHERE `vendor_name` <> '$vendorForThisId'";
		$statement2 = $mysqlConn->prepare($query2);
		$statement2->execute();
	?>
	
	<div class="form-group">
		<label for="PurchaseVendor" class="col-sm-4 control-label text-left">Purchase Vendor:</label>
		<div class="col-sm-8">
			<select type="text" class="form-control" name="inputPurchaseVendor" id="inputPurchaseVendor" >
				<?php
					echo "<option value='".$vendorForThisId."'>".$vendorForThisId."</option>";
					while($row2 = $statement2->fetch()){
						echo "<option value='".$row2['vendor_name']."'>".$row2['vendor_name']."</option>";
					}
				?>
			</select>
		</div>
	</div>
	
	<div class="form-group">
		<label for="QuoteNumber" class="col-sm-4 control-label text-left">Quote Number:</label>
		<div class="col-sm-8">
			<input type="text" class="form-control" name="inputQuoteNumber" id="inputQuoteNumber" 
				value=<?php echo $row['quote_number']; ?> />
		</div>
	</div>
	
	<div class="form-group">
		<label for="PricePerUnit" class="col-sm-4 control-label text-left">Price Per Unit:</label>
		<div class="col-sm-8">
			<input type="text" class="form-control" name="inputPricePerUnit" id="inputPricePerUnit" 
				value=<?php echo $row['price_per_unit']; ?> />
		</div>
	</div>
	
	<div class="form-group">
		<label for="Quantity" class="col-sm-4 control-label text-left">Quantity:</label>
		<div class="col-sm-8">
			<input type="text" class="form-control" name="inputQuantity" id="inputQuantity" 
				value=<?php echo $row['quantity']; ?> />
		</div>
	</div>
	
	<div class="form-group">
		<label for="QuantityInUse" class="col-sm-4 control-label text-left">Quantity In Use:</label>
		<div class="col-sm-8">
			<input type="text" class="form-control" name="inputQuantityInUse" id="inputQuantityInUse" 
				value=<?php echo $row['quantity_in_use']; ?> />
		</div>
	</div>
	
	<center><div class="form-group login-submit">
		<input type='submit' class='btn btn-lg btn-primary btn-block' name='submit' value='Update Values' />
	</div></center>
</form>

<?php 	
	/* Close the connection */
	$mysqlConn = null; 
?>

<!-- Footer -->
<?php boxFooter(); 
require_once('footer.php'); ?>