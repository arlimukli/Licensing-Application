<?php
 	/*
	 *	Palm Tran 
	 */
 
 	/* Per Page resizing - Uncomment if you require this */
	//$GLOBALS['tempPageDimension'] = "col-md-6 col-md-offset-3";
	
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
	
    $(document).ready(function () {
      var table = $('#dataTable').DataTable({
            "order": [[ 1, "asc" ]],
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
</script>

<?php
	/************************************************************************
	*************************************************************************
	************************************************************************/
	
	boxHeader("Active Licenses");
	$query = "SELECT * FROM `licenses`"; // change * to SELECT ...

	$statement = $mysqlConn->prepare($query);
	
	echo "	
		<table class='dataTable' id='dataTable' cellspacing='0' width='100%'>
			<thead>
				<tr>
					<th>Expand</th>
					<th>ID</th>
					<th>Software Name</th>
					<th>License Key</th>
					<th>Purchase Date</th>
					<th>Expiration Date</th>
					<th>Quote Number</th>
					<th>Price Per Unit</th>
				</tr>
			</thead>
		<tbody>
	";
	
	if($statement && $statement->rowCount() > 0) {
		while($row = $statement->fetch()) {
			$version = $row['version'];
			$category = $row['category'];
			$type = $row['type'];
			$purchase_vendor = $row['purchase_vendor'];
			$quantity = $row['quantity'];
			$quantity_in_use = $row['quantity_in_use'];
			
			$childData = "	<b>Version: </b> $version <br />
							<b>Category: </b> $category <br />
							<b>Type: </b> $type <br />
							<b>Purchase Vendor: </b> $purchase_vendor <br />
							<b>Quantity: </b> $quantity <br />
							<b>Quantity In Use: </b> $quantity_in_use <br />";
			echo "	
				<tr data-child-value='$childData'>
					<td style='text-align:left;' class='details-control'></td>
					<td>" .$row['ID'] ."</td>
					<td>" .$row['software_name'] . "</td>
					<td>" .$row['license_key'] ."</td>
					<td>" .$row['purchase_date'] ."</td>
					<td>" .$row['expiration_date'] ."</td>
					<td>" .$row['quote_number'] ."</td>
					<td>" .$row['price_per_unit'] ."</td>
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
	
	boxHeader("Inactive Licenses");
	boxFooter();
	
	/* Close our MySQL connection */
	$mysqlConn->close();
	require_once('footer.php');
?>