<?php

	/* This page can be smaller since there is only inputs */
	$GLOBALS['tempPageDimension'] = "col-md-8 col-md-offset-2";
	
	/* Display an information page for the maintenance user */
	$GLOBALS['tempPageInfo'] = "Admin page on which you can edit the privilege for the users.
								<br />Here you can maintain VENDORS, CATEGORY and TYPE for the licenses.
								<br />If there need to be changes to other values on a license, please click
								on the ID field of the index page to be able to change those values";
	
	/* import head.php file. It contains config.php file as well */
	require_once("head.php");
	
	/* File required to store the changes made */
	require_once("changeLogFunc.php");
?>

<!-- JS for dataTable -->
<script>
	$(document).ready(function () {
      var table = $('#dataTable').DataTable({
            "order": [[ 0, "asc" ]]
        });
	});
</script>

<!-- Confirm Delete function for users -->
<script>
	function confirmDelete(name) {
		var result = confirm("Do you really want to delete " + name + "?");
		if(result) {
			window.location.href = "adminLicensing.php?username=" + name + "&confirmDelete=1";
		}
	}
</script>

<!-- Display Info Box. Let the user know how to correctly enter the data -->
<?php
	//displayInfoBox();
?>


<?php
/* Code for deleting a license */ 

	if(ISSET($_POST['deleteLicense'])) {
		$deleteLicense = $_POST['licenseDelete'];
		$query = "DELETE FROM `licenses`
					WHERE `software_name` = '$deleteLicense'";
		$statement = $mysqlConn->prepare($query);
		
		if($statement->execute()){
			insertUserChangeLogData($mysqlConn, $_SESSION['username'], $deleteLicense, "Delete License", $deleteLicense, NULL);
			alertSuccess("License Deleted Successfully!");
		}
		else {
			alertDanger("Error! Please contact IT");
		}
	}
?>

<?php
	/* Code for adding new values to the form(Add Values) */

	if(ISSET($_POST['addCategory'])){
		$addCategory = $_POST['category'];
		
		$query = "INSERT INTO `category` (`ID`, `category_name`)
					VALUES (DEFAULT, '$addCategory')";
		$statement = $mysqlConn->prepare($query);
		
		if($statement->execute()){
			insertUserChangeLogData($mysqlConn, $_SESSION['username'], $addCategory, "New Category", NULL, $addCategory);
			alertSuccess("New Category Added Successfully!");
		}
		else {
			alertDanger("Error! Please contact IT");
		}
	}
	
	else if(ISSET($_POST['addType'])){
		$addType = $_POST['type'];
		
		$query = "INSERT INTO `type` (`ID`, `type_name`)
					VALUES (DEFAULT, '$addType')";
		$statement = $mysqlConn->prepare($query);
		
		if($statement->execute()){
			insertUserChangeLogData($mysqlConn, $_SESSION['username'], $addType, "New Type", NULL, $addType);
			alertSuccess("New Type Added Successfully!");
		}
		else {
			alertDanger("Error! Please contact IT");
		}
	}
	
	else if(ISSET($_POST['addVendor'])){
		$addVendor = $_POST['vendor'];
		
		$query = "INSERT INTO `vendor` (`ID`, `vendor_name`)
					VALUES (DEFAULT, '$addVendor')";
		$statement = $mysqlConn->prepare($query);
		
		if($statement->execute()){
			insertUserChangeLogData($mysqlConn, $_SESSION['username'], $addVendor, "New Vendor", NULL, $addVendor);
			alertSuccess("New Vendor Added Successfully!");
		}
		else {
			alertDanger("Error! Please contact IT");
		}
	}
	
?>

<?php

	/* Code for deleting values from form(Delete Values) */
	
	if(ISSET($_POST['deleteCategory'])) {
		$deleteCategory = $_POST['category'];

		$query = "DELETE FROM `category`
					WHERE `category_name` = '$deleteCategory'";
		$statement = $mysqlConn->prepare($query);
		
		if($statement->execute()){
			insertUserChangeLogData($mysqlConn, $_SESSION['username'], $deleteCategory, "Delete Category", $deleteCategory, NULL);
			alertSuccess("Selected Category Deleted Successfully");
		}
		
		else {
			alertDanger("Error. Please contact IT!");
		}
	}
	
	else if(ISSET($_POST['deleteType'])) {
		$deleteType = $_POST['type'];
		
		$query = "DELETE FROM `type`
					WHERE `type_name` = '$deleteType'";
		$statement = $mysqlConn->prepare($query);
		
		if($statement->execute()){
			insertUserChangeLogData($mysqlConn, $_SESSION['username'], $deleteType, "Delete Type", $deleteType, NULL);
			alertSuccess("Selected Type Deleted Successfully");
		}
		
		else {
			alertDanger("Error. Please contact IT!");
		}
	}
	
	else if(ISSET($_POST['deleteVendor'])) {
		$deleteVendor = $_POST['vendor'];
		
		$query = "DELETE FROM `vendor`
					WHERE `vendor_name` = '$deleteVendor'";
		$statement = $mysqlConn->prepare($query);
		
		if($statement->execute()){
			insertUserChangeLogData($mysqlConn, $_SESSION['username'], $deleteVendor, "Delete Vendor", $deleteVendor, NULL);
			alertSuccess("Selected Vendor Deleted Successfully");
		}
		
		else {
			alertDanger("Error. Please contact IT!");
		}
	}
?>

<?php
	
	/* Code for changing the isAdmin column on the user table */
	if(ISSET($_GET['isAdmin'])){
		$userID = $_GET['userID'];
		$query = "SELECT `username` FROM `users` WHERE `userID` = '$userID'";
		$statement = $mysqlConn->prepare($query);
		$statement->execute();
		$row = $statement->fetch();
		$effectOn = $row['username'];
		$isAdmin = $_GET['isAdmin'];
		
		if($isAdmin == 0) {
			insertUserChangeLogData($mysqlConn, $_SESSION['username'], $effectOn, "Admin Rights", $isAdmin, "1");
			$query = "UPDATE `users`
						SET `isAdmin` = 1
							WHERE `userID` = $userID";
		}
		else if($isAdmin == 1) {
			insertUserChangeLogData($mysqlConn, $_SESSION['username'], $effectOn, "Admin Rights", $isAdmin, "0");
			$query = "UPDATE `users`
						SET `isAdmin` = 0
							WHERE `userID` = $userID";	
		}
		
		$statement = $mysqlConn->prepare($query);
		$statement->execute();
	}
?>

<?php

	/* Code for adding new user to the database */
	if(ISSET($_POST['addUser'])){
		$newUsername = $_POST['username'];
		$isAdmin = $_POST['isAdmin'];
		
		if($isAdmin == "Yes"){
			$newIsAdmin = 1;
		}
		else {
			$newIsAdmin = 0;
		}
		
		$query = "INSERT INTO `users`(`userID`, `username`, `isAdmin`)
					VALUES (DEFAULT, '$newUsername', $newIsAdmin)";
		$statement = $mysqlConn->prepare($query);
		if($statement->execute()){
			insertUserChangeLogData($mysqlConn, $_SESSION['username'], $newUsername, "New User", NULL, $newUsername);
			alertSuccess("New User Added Successfully");
		}
		else {
			alertDanger("Error!");
		}
	}
?>

<?php

	/* Code for deleting existing user from the datatable */
	if(ISSET($_GET['username'])) {
		if($_GET['confirmDelete'] == 1) {
			$username = $_GET['username'];
			
			$query = "DELETE FROM `users` 
						WHERE `username` = '$username'";
			$statement = $mysqlConn->prepare($query);
			
			if($statement->execute()) {
				insertUserChangeLogData($mysqlConn, $_SESSION['username'], $username, "Delete User", $username, NULL);
				alertSuccess("User Deleted Successfully");
			}
			else {
				alertDanger("Error");
			}
		}
	}
?>

<!-- Header for User Maintenance -->
<?php boxHeader("User Maintenance");?>

<!-- User Maintenance table and code -->
<?php
	/************************************************************************
	*************************************************************************
	************************************************************************/
	
	$username = $_SESSION['username']; // Addition to omit themselves from the datatable
	
	$query = "SELECT `userID`, `username`, `isAdmin`
				FROM `users`
					WHERE `username` != '$username'";
	$statement = $mysqlConn->prepare($query);
	$statement->execute();
	
	echo "	
		<table id='dataTable' class='display compact cell-border' cellspacing='0' width='100%'>
			<thead>
				<tr>
					<th><center>ID</center></th>
					<th><center>Username</center></th>
					<th><center>Is Admin</center></th>
					<th><center>Delete</center></th>
				</tr>
			</thead>
		<tbody>
	";
	
	if($statement){
		while($row = $statement->fetch()){
			$username = $row['username'];
			$isAdminValue = $row['isAdmin'];
			if($isAdminValue == 1){
				$isAdmin = "<span style='color:green'>Yes</span>";
			}
			else {
				$isAdmin = "<span style='color:red'>No</span>";
			}
			echo "
				<tr>
					<td>" . $row['userID'] . "</td>
					<td>" . $row['username'] . "</td>
					<td><a href='adminLicensing.php?userID=".$row['userID']."&isAdmin=".$row['isAdmin']."'><u>".$isAdmin."</u></td>
					<td><input type='submit' class='btn btn-danger' onclick='confirmDelete(\"$username\")' name='deleteUser' value='Delete' /></td>
				</tr>
				";
		}
	}
	
	echo "	
		</tbody>
	</table>
	";
?>

<!-- Footer for User Maintenance --> 
<?php boxFooter(); ?>

<!-- Header for Add Users -->
<?php boxHeader("Add Users"); ?>

<!-- Code for adding new users -->
<form action='adminLicensing.php' method='post'>
	<div class="container-fluid form-group" >
	
		<!-- Username -->
		<div class="row" style="margin:5px">
		
			<div class="col-sm-6">
				<label for="username" class="control-label text-left">Username:</label>
			</div>
			<div class="col-sm-6">
				<input type="text" class="form-control" name="username" id="username" placeholder="Enter username" required />
			</div>
		</div>
		
		<!-- isAdmin -->
		<div class="row" style="margin:5px">
			<div class="col-sm-6">
				<label for="isAdmin" class="control-label text-left">Is Admin:</label>
			</div>
			<div class="col-sm-6">
				<select type="text" class="form-control" name="isAdmin" id="isAdmin" >
					<option value='Yes'>Yes</option>
					<option value='No'>No</option>
				</select>
			</div>
		</div>
		
		<!-- Add User Button -->
		<div class="col-sm-6 col-sm-offset-3">
				<input type='submit' class='btn btn-md btn-primary btn-block' name='addUser' value='Add New User' />
		</div>
	</div>
</form>

<!-- Footer for Add Users -->
<?php boxFooter(); ?>

<!-- Add and Delete Values form header -->
<?php boxHeader("Add & Delete Values"); ?>

<!-- Add and Delete Values main form -->
<form action="adminLicensing.php" method="post">
	<div class="container-fluid form-group">
	
		<div style="border-bottom:double; border-width:1px; margin:5px;">
			<!-- Category Label -->
			<div class="col-sm-6 col-sm-offset-3">
				<label for="category" class="control-label text-left">Category</label>
			</div>
			
			<!-- Add Category -->
			<div class="row" style="padding:10px; margin-bottom:10px;">
				<div class="col-sm-4">
					<input type="text" class="form-control" name="category" id="category" placeholder="Enter new category" />
				</div>
				<div class="col-sm-2">
					<input type='submit' class='btn btn-md btn-primary btn-block' name='addCategory' value='Add' />
				</div>
				
				<!-- Delete Category -->
				<?php
					$query = "SELECT category_name FROM category ORDER BY `category_name`";
					$statement = $mysqlConn->prepare($query);
					$statement->execute();
				?>
			
				<div class="col-sm-4">
					<select type="text" class="form-control" name="category" id="category" >
						<?php
							while($row = $statement->fetch()){
								echo "<option value='".$row['category_name']."'>".$row['category_name']."</option>";
							}
						?>
					</select>
				</div>
				<div class="col-sm-2">
					<input type='submit' class='btn btn-md btn-danger btn-block' name='deleteCategory' value='Delete' />
				</div>
			</div>
		</div>
		
		<div style="border-bottom:double; border-width:1px; margin:5px;">
			<!-- Type Label -->
			<div class="col-sm-6 col-sm-offset-3">
				<label for="type" class="control-label text-left">Type</label>
			</div>
			
			<!-- Add Type -->
			<div class="row" style="padding:10px; margin-bottom:10px;">
				<div class="col-sm-4">
					<input type="text" class="form-control" name="type" id="type" placeholder="Enter new type" />
				</div>
				<div class="col-sm-2">
					<input type='submit' class='btn btn-md btn-primary btn-block' name='addType' value='Add' />
				</div>
				
				<!-- Delete Type -->
				<?php
					$query = "SELECT type_name FROM type ORDER BY `type_name`";
					$statement = $mysqlConn->prepare($query);
					$statement->execute();
				?>

				<div class="col-sm-4">
					<select type="text" class="form-control" name="type" id="type" >
						<?php
							while($row = $statement->fetch()){
								echo "<option value='".$row['type_name']."'>".$row['type_name']."</option>";
							}
						?>
					</select>
				</div>
				<div class="col-sm-2">
					<input type='submit' class='btn btn-md btn-danger btn-block' name='deleteType' value='Delete' />
				</div>
			</div>
		</div>
		
		<div style="border-bottom:hidden; margin:5px;">
			<!-- Vendor Label -->
			<div class="col-sm-6 col-sm-offset-3">
				<label for="vendor" class="control-label text-left">Vendor</label>
			</div>
			
			<!-- Add Vendor -->
			<div class="row" style="padding:10px; margin-bottom:10px;">
				
				<div class="col-sm-4">
					<input type="text" class="form-control" name="vendor" id="vendor" placeholder="Enter new vendor" />
				</div>
				<div class="col-sm-2">
					<input type='submit' class='btn btn-md btn-primary btn-block' name='addVendor' value='Add' />
				</div>
			
				<!-- Delete Vendor -->
				<?php
					$query = "SELECT vendor_name FROM vendor ORDER BY `vendor_name`";
					$statement = $mysqlConn->prepare($query);
					$statement->execute();
				?>
			
				<div class="col-sm-4">
					<select type="text" class="form-control" name="vendor" id="vendor" >
						<?php
							while($row = $statement->fetch()){
								echo "<option value='".$row['vendor_name']."'>".$row['vendor_name']."</option>";
							}
						?>
					</select>
				</div>
				<div class="col-sm-2">
					<input type='submit' class='btn btn-md btn-danger btn-block' name='deleteVendor' value='Delete' />
				</div>
			</div>
		</div>
	</div>
</form>

<!-- Add and Delete Values form footer -->
<?php boxFooter(); ?>

<!-- Box header for delete licenses -->
<?php boxHeader("Delete License"); ?>

<!-- Main code for delete licenses -->
<form action="adminLicensing.php" method="POST">
	<div class="container-fluid form-group">
	
	<?php
		$query = "SELECT `software_name` 
					FROM `licenses` 
						ORDER BY `software_name`";
		$statement = $mysqlConn->prepare($query);
		$statement->execute();
	?>
		<!-- Licenses Ordered by name -->
		<div class="row" style="margin:10px">
			<div class="col-sm-3">
				<label for="license" class="control-label text-left">License Name:</label>
			</div>
			<div class="col-sm-5">
				<select type="text" class="form-control" name="licenseDelete" id="licenseDelete" >
					<?php
						while($row = $statement->fetch()){
							echo "<option value='".$row['software_name']."'>".$row['software_name']."</option>";
						}
					?>
				</select>
			</div>
			<div class="col-sm-4">
				<input type='submit' class='btn btn-md btn-danger btn-block' name='deleteLicense' value='Delete' />
			</div>
		</div>
	</div>
</form>

<!-- Box Footer for delete licenses -->
<?php boxFooter(); ?>

<!-- Close mySQL connection -->
<?php $mysqlConn = NULL; ?>

<!-- Page Footer -->
<?php require_once('footer.php'); ?>