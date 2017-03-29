<?php

	/* import head.php file. It contains config.php file as well */
	require_once("head.php");
	
	/* File required to store the changes made */
	require_once("changeLogFunc.php");

?>

<!-- View all users that use the parsed software name from index.php -->
<?php 
	if(ISSET($_GET['licenseName'])) {
		$licenseName = $_GET['licenseName'];
	}
?>

<!-- Datatable script -->
<script>
	$(document).ready(function () {
		/* We twick the datatable so that we can search for specific license name when needed */
		var licenseName = "<?php echo $licenseName; ?>"; // assign a PHP variable to a JQuery
		$('#dataTableAssignUsers').DataTable({
			"search": {
				"search": licenseName
			}
        });
	});
</script>

<!-- Delete Row Script -->
<script>
	function confirmDelete(ID) {
		window.location.href = "assignUsers.php?ID=" + ID;
		var result = confirm("Do you really want to delete the row with ID= " + ID + "?");
	}
</script>

<?php
	if(ISSET($_POST["submit"])) {
		$licenseName = $_POST['inputLicenseName'];
		$date = date("Y-m-d");
		$computerName = $_POST['inputComputerName'];
		$userName = $_POST['inputUserName'];
		
		$query = "INSERT INTO `assign_users` (`license_name`, `date`, `computer_name`, `user_name`)
					VALUES (?, ?, ?, ?)";
		$statement = $mysqlConn->prepare($query);
		
		$values = array($licenseName, $date, $computerName, $userName);
			
			for($i = 0; $i < count($values); $i++){
				//echo "Index: " . ($i+1) . " " . " Value: " . $values[$i] . "<br />";
				$statement->bindParam(($i + 1), $values[$i]);
			}
		if($statement->execute()) {
			alertSuccess("User Assigned Successfully!");
		}
		else {
			alertDanger("There was an error! Please contact IT");
		}
	}
?>

<?php
	if(ISSET($_GET["ID"])) {
		$ID = $_GET['ID'];
		
		$query = "DELETE FROM `assign_users` 
					WHERE `ID` = $ID";
		$statement = $mysqlConn->prepare($query);
		
		if($statement->execute()) {
			insertUserChangeLogData($mysqlConn, $_SESSION['username'], $ID, "Delete Assigned User", $ID, NULL);
			alertSuccess("User Deleted Successfully");
		}
		else {
			alertDanger("Error");
		}
	}
?>

<?php
	/* Assign user header */
	boxHeader("Assign Users");
?>

<!-- Main Form -->
<form action="assignUsers.php" method="POST" class="form-horizontal">

	
	<div class="form-group">
	
		<!-- Select License -->
		<?php
			$query = "SELECT `software_name`
						FROM `licenses`
							ORDER BY `software_name`";
			$statement = $mysqlConn->prepare($query);
			$statement->execute();
		?>
		<div class="col-sm-3">
			<select type="text" class="form-control" name="inputLicenseName" id="inputLicenseName" >
				<?php
					while($row = $statement->fetch()) {
						echo "<option value='". $row['software_name'] ."'>". $row['software_name'] ."</option>";
					}
				?>
			</select>
		</div>
		
		<!-- Input Computer Name -->
		<div class="col-sm-3">
			<input type="text" class="form-control" name="inputComputerName" id="inputComputerName" placeholder="Enter Computer Name" maxlength="50" />
		</div>
		
		<!-- Input User Name -->
		<div class="col-sm-3">
			<input type="text" class="form-control" name="inputUserName" id="inputUserName" placeholder="Enter User Name" maxlength="50" required />
		</div>
		
		<!-- Assign User -->
		<div class="form-group login-submit col-sm-3">
			<input type='submit' class='btn btn-md btn-primary btn-block' name='submit' value='Assign'>
		</div>
	</div>
	
</form>
<!-- Main Form end -->

<?php 
	/* Assign user footer */
	boxFooter();
?>

<!-- Users and Licenses Datatable Header -->
<?php boxHeader("Assigned Licenses"); ?>

<!-- User and Licenses Main -->
<?php

	$query = "SELECT *
				FROM `assign_users`";
	$statement = $mysqlConn->prepare($query);
	$statement->execute();
	
	echo "	
		<table id='dataTableAssignUsers' class='display compact cell-border' cellspacing='0' width='100%'>
			<thead>
				<tr>
					<th><center>ID</center></th>
					<th><center>License Name</center></th>
					<th><center>Date</center></th>
					<th><center>Computer Name</center></th>
					<th><center>User Name</center></th>
					<th><center>Delete</center></th>
				</tr>
			</thead>
		<tbody>
	";
	
	while($row = $statement->fetch()) {
		$ID = $row['ID'];
		echo "
			<tr>
				<td>" . $row['ID'] . "</td>
				<td>" . $row['license_name'] . "</td>
				<td>" . $row['date'] . "</td>
				<td><span style='color:blue; font-weight:bold'>" . $row['computer_name'] . "</span></td>
				<td>" . $row['user_name'] . "</td>
				<td><input type='submit' class='btn btn-danger' onclick='confirmDelete(\"$ID\")' name='deleteAssignedUser' value='Delete' /></td>
			</tr>
		";
	}
	
	echo "
		</tbody>
	</table>
	";
	
?>

<!-- Users and Licenses Datatable Footer -->
<?php boxFooter(); ?>

<?php
	/* Close connection */
	$mysqlConn = NULL;
?>

<?php
	require_once('footer.php');
?>