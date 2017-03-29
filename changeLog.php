<?php
	
	/* This is the change log page. We keep track of all the changes made to the database */

	/* import the header file */
	require_once("head.php");
?>

<!-- JS for dataTable -->
<script>
	$(document).ready(function () {
      var table = $('#dataTableLicense').DataTable({
            "order": [[ 0, "desc" ]]
        });
	});
	
	$(document).ready(function () {
      var table = $('#dataTableUser').DataTable({
            "order": [[ 0, "desc" ]]
        });
	});
</script>

<!-- Header for License Change Log -->
<?php boxHeader("License Change Log"); ?>

<?php
	
	/* License Change Log datatable and code */

	$query = "SELECT *
				FROM `license_changelog`";
	$statement = $mysqlConn->prepare($query);
	$statement->execute();
	
	echo "	
		<table id='dataTableLicense' class='display compact cell-border' cellspacing='0' width='100%'>
			<thead>
				<tr>
					<th><center>ID</center></th>
					<th><center>User</center></th>
					<th><center>License ID</center></th>
					<th><center>Date & Time</center></th>
					<th><center>Field Changed</center></th>
					<th><center>Old Value</center></th>
					<th><center>New Value</center></th>
				</tr>
			</thead>
		<tbody>
	";
	
	while($row = $statement->fetch()) {
		echo "
			<tr>
				<td>" . $row['ID'] . "</td>
				<td>" . $row['user'] . "</td>
				<td>" . $row['license_id'] . "</td>
				<td>" . $row['date_time'] . "</td>
				<td><span style='color:blue;'>" . $row['field_changed'] . "</span></td>
				<td><span style='color:red;'>" . $row['old_value'] . "</span></td>
				<td><span style='color:green;'>" . $row['new_value'] . "</span></td>
			</tr>
		";
	}
	
	echo "
		</tbody>
	</table>
	";
?>

<!-- Footer for License Change Log -->
<?php boxFooter(); ?>

<!-- Header for Users Change Log -->
<?php boxHeader("Users & Data Change Log"); ?>

<?php

	/*User change log dataTable and code */
	
	$query = "SELECT *
				FROM `users_changelog`";
	$statement = $mysqlConn->prepare($query);
	$statement->execute();
	
	echo "	
		<table id='dataTableUser' class='display compact cell-border' cellspacing='0' width='100%'>
			<thead>
				<tr>
					<th><center>ID</center></th>
					<th><center>User</center></th>
					<th><center>Effect On</center></th>
					<th><center>Date & Time</center></th>
					<th><center>Action</center></th>
					<th><center>Old Value</center></th>
					<th><center>New Value</center></th>
				</tr>
			</thead>
		<tbody>
	";
	
	while($row = $statement->fetch()) {
		echo "
			<tr>
				<td>" . $row['ID'] . "</td>
				<td>" . $row['user'] . "</td>
				<td>" . $row['effect_on'] . "</td>
				<td>" . $row['date_time'] . "</td>
				<td><span style='color:blue;'>" . $row['action'] . "</span></td>
				<td><span style='color:red;'>" . $row['old_value'] . "</span></td>
				<td><span style='color:green;'>" . $row['new_value'] . "</span></td>
			</tr>
		";
	}
	
	echo "
		</tbody>
	</table>
	";
?>

<!-- Footer for Users Change Log -->
<?php boxFooter(); ?>

<!-- Close mySQL Connection -->
<?php $mysqlConn = NULL; ?>

<!-- Page Footer -->
<?php require_once('footer.php'); ?>
