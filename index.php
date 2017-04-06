<?php
 	/*
	 *	Palm Tran 
	 */
 
 	/* Per Page resizing - Uncomment if you require this */
	$GLOBALS['tempPageDimension'] = "col-md-12";
	
	/* Display an information page for the maintenance user */
	$GLOBALS['tempPageInfo'] = "Displayed are the active and inactive licenses purchased from Palm Tran.";
	
	/************************************************************************
	*************************************************************************
	************************************************************************/
	
	/* Include our page header */
	require_once('head.php');
	
	/************************************************************************
	*************************************************************************
	************************************************************************/
	/*
		Some DataTable options:
			"paging":   false,
			"order": [[ 0, "desc" ]],
			"info":     false,
			"bFilter":  false,
			"lengthMenu": [[30, 50, 100, 150, -1], [30, 50, 100, 150, "All"]],
			"scrollX": true
	*/
?>

<!-- Display Info Box. -->
<?php
	//displayInfoBox();
?>

<style type="text/css">
    td.details-control {
        background: url('images/details_open.png') no-repeat center center;
        cursor: pointer;
    }
    tr.shown td.details-control {
        background: url('images/details_close.png') no-repeat center center;
    }
    td.data-child-value {
        text-align:left;
    }

</style>

<script type="text/javascript">
    function format(value) {
        return "<div style='text-align:left'>" + value + "</div>";
    }
	
	/* This is the javascript for the first dataTable */
    $(document).ready(function () {
      var table = $('#dataTable').DataTable({
           // "order": [[ 1, "asc" ]],
            "scrollX": true
        });
       // Add event listener for opening and closing details
      $('#dataTable').on('click', 'td.details-control', function () {
          var tr = $(this).closest('tr');
          var row = table.row(tr);
 
          if (row.child.isShown()) {
              // This row is already open - close it
              row.child.hide();
              tr.removeClass('shown');
          } else {
              // Open this row
              row.child(format(tr.data('child-value'))).show();
              tr.addClass('shown');
          }
      });

    });
	
	/* Javascript function for the second dataTable. We need separate functions because 
		we use ID for identifying both tables. Using class created errors in proper functioning */
	 $(document).ready(function () {
      var table = $('#dataTable2').DataTable({
            "order": [[ 1, "asc" ]],
            "scrollX": true
        });
       // Add event listener for opening and closing details
      $('#dataTable2').on('click', 'td.details-control', function () {
          var tr = $(this).closest('tr');
          var row = table.row(tr);
 
          if (row.child.isShown()) {
              // This row is already open - close it
              row.child.hide();
              tr.removeClass('shown');
          } else {
              // Open this row
              row.child(format(tr.data('child-value'))).show();
              tr.addClass('shown');
          }
      });

    });
</script>

<?php
	/************************************************************************
	*************************************************************************
	************************************************************************/
	
	/* Active Licenses */
	
	boxHeader("Active Licenses");
	$query = "SELECT * FROM `licenses` 
				WHERE `expiration_date` >= CURRENT_DATE
					OR `expiration_date` = '1969-12-31'
						ORDER BY ABS(DATEDIFF(`expiration_date`, NOW()))";

	$statement = $mysqlConn->prepare($query);
	$statement->execute();
	
	echo "	
		<table id='dataTable' class='display compact cell-border' cellspacing='0' width='100%'>
			<thead>
				<tr>
					<th>Expand</th>
					<th>ID</th>
					<th>Software Name</th>
					<th>License Key</th>
					<th>Expiration Date</th>
					<th>Version</th>
					<th>Category</th>
					<th>Quantity</th>
					<th>Quantity In Use</th>";
					if($_SESSION['isAdmin'] == 1) {
						echo "
					<th>Edit</th>
					<th>Users</th>";
					}
				echo "
				</tr>
			</thead>
		<tbody>
	";
	
	if($statement) {
		while($row = $statement->fetch()) {
			$ID = $row['ID'];
			$licenseName = $row['software_name'];
			$type = $row['type'];
			$purchaseVendor = $row['purchase_vendor'];
			$quoteNumber = $row['quote_number'];
			$pricePerUnit = $row['price_per_unit'];
			
			/* we are using this variables to calculate the expiration dates for licenses */
			$expirationDate = new DateTime($row['expiration_date']);
			$today = new DateTime(date('Y-m-d'));
			$dateDifference = $today->diff($expirationDate)->format("%r%a");
			
			/* purchase date formating */
			if($row['purchase_date'] == "1969-12-31") {
				$purchaseFormat = "No Date";
			}
			else {
				$purchaseDate = $row['purchase_date'];
				$dateConversion = strtotime($purchaseDate);
				$purchaseFormat = date('m-d-Y', $dateConversion);
			}
			
			if($row['expiration_date'] == '1969-12-31') {
				$expirationFormat = "No date";
			}
			else {
				/* expiration date formating */
				$expiration = $row['expiration_date'];
				$dateConversion = strtotime($expiration);
				$expirationFormat = date('m-d-Y', $dateConversion);
			}	
			
			$childData = "	<b>Type: </b> $type <br />
							<b>Purchase Date: </b> $purchaseFormat <br />
							<b>Purchase Vendor: </b> $purchaseVendor <br />
							<b>Quote Number: </b> $quoteNumber <br />
							<b>Price Per Unit: </b> $pricePerUnit <br />";
			echo "
				<tr data-child-value='$childData'>
					<td style='text-align:left;' class='details-control'></td>
					<td>" . $row['ID'] . "</td>
					<td>" . $row['software_name'] . "</td>
					<td>" . $row['license_key'] . "</td>";
					if(abs($dateDifference) <= 30) {
						echo "<td style='color:red;'>" . $expirationFormat . "</td>";
					}
					else {
						echo "<td style='color:green;'>" . $expirationFormat . "</td>";
					}
				echo "
					<td>" . $row['version'] ."</td>
					<td>" . $row['category'] ."</td>
					<td>" . $row['quantity'] ."</td>
					<td>" . $row['quantity_in_use'] ."</td>";
					if($_SESSION['isAdmin'] == 1){
						echo "
					<td><u><a href='maintenance.php?ID=$ID'>" . "EDIT" . "</a></u></td>
					<td><u><a href='assignUsers.php?licenseName=$licenseName'>" . "VIEW" . "</a></u></td>
					";
					}
				echo "
				</tr>
			";
					
		}
	}
	
	echo "	
		</tbody>
	</table>
	";
	
	boxFooter();
	
	/************************************************************************
	*************************************************************************
	************************************************************************/
	
	/* Inactive Licenses */
	
	boxHeader("Inactive Licenses");
	
	echo "	
		<table id='dataTable2' class='display compact cell-border' cellspacing='0' width='100%'>
			<thead>
				<tr>
					<th>Expand</th>
					<th>ID</th>
					<th>Software Name</th>
					<th>License Key</th>
					<th>Expiration Date</th>
					<th>Version</th>
					<th>Category</th>
					<th>Quantity</th>
					<th>Quantity In Use</th>";
					if($_SESSION['isAdmin'] == 1) {
						echo "
					<th>Edit</th>";
					}
				echo "
				</tr>
			</thead>
		<tbody>
	";
	
	$query = "SELECT * FROM `licenses` 
				WHERE `expiration_date` < CURRENT_DATE
					AND `expiration_date` <> '1969-12-31'";
	
	$statement = $mysqlConn->prepare($query);
	$statement->execute();
	
	if($statement) {
		while($row = $statement->fetch()) {
			$ID = $row['ID'];
			$type = $row['type'];
			$purchaseVendor = $row['purchase_vendor'];
			$quoteNumber = $row['quote_number'];
			$pricePerUnit = $row['price_per_unit'];
			
			/* purchase date formating */
			$purchaseDate = $row['purchase_date'];
			$dateConversion = strtotime($purchaseDate);
			$purchaseFormat = date('m-d-Y', $dateConversion);
			
			/* expiration date formating */
			$expiration = $row['expiration_date'];
			$dateConversion = strtotime($expiration);
			$expirationFormat = date('m-d-Y', $dateConversion);
			
			$childData = "	<b>Type: </b> $type <br />
							<b>Purchase Date: </b> $purchaseFormat <br />
							<b>Purchase Vendor: </b> $purchaseVendor <br />
							<b>Quote Number: </b> $quoteNumber <br />
							<b>Price Per Unit: </b> $pricePerUnit <br />";
			echo "	
				<tr data-child-value='$childData'>
					<td style='text-align:left;' class='details-control'></td>
					<td>" . $row['ID'] . "</td>
					<td>" . $row['software_name'] . "</td>
					<td>" . $row['license_key'] . "</td>
					<td style='color:red;'>" . $expirationFormat ."</td>
					<td>" . $row['version'] . "</td>
					<td>" . $row['category'] . "</td>
					<td>" . $row['quantity'] . "</td>
					<td>" . $row['quantity_in_use'] . "</td>";
					if($_SESSION['isAdmin'] == 1){
						echo "
						<td><u><a href='maintenance.php?ID=$ID'>" . "EDIT" . "</a></u></td>
						";
					}
				echo "
				</tr>
			";
			
		}
	}
		echo "	
		</tbody>
	</table>
	";

	/* Close our MySQL connection */
	$mysqlConn = null;
?>

<!-- Footer -->
<?php boxFooter(); 
require_once('footer.php'); ?>