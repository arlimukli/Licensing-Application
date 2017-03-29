<?php
	function actionChangeLogFinalize($_SESSIONTEMP, $mysqlConn, $contractID) {
		actionChangeLogInsert($_SESSIONTEMP, $mysqlConn, $contractID, "Contract Finalization", "Not Finalized", "Finalized");
	}
	
	function actionChangeLogBillingData($_SESSIONTEMP, $mysqlConn, $contractID, $newQuantity, $newWeeks, $displayType, $newProductionRate, $newSpaceRate, $billRateID) {
		$query = "
				SELECT `quantity`, `displayType`, `productionRate`, `spaceRate`, `weeks` from `contract_billing`
						WHERE `contractID` = '".$contractID."'
						AND `billRateID` = '".$billRateID."'
		";		

		$actionChangeLogQuery = $mysqlConn->query($query);
		
		if($actionChangeLogQuery->num_rows > 0) {
			$row = $actionChangeLogQuery->fetch_assoc(); 	
			
			//$displayType
			$oldQuantity = $row["quantity"];
			$oldProductionRate = $row["productionRate"];
			$oldSpaceRate = $row["spaceRate"];
			$oldWeeks = $row["weeks"];
			
			if($oldWeeks != $newWeeks) {
				actionChangeLogInsert($_SESSIONTEMP, $mysqlConn, $contractID, "Weeks", $oldWeeks, $newWeeks, $displayType);
			}
			if($oldQuantity != $newQuantity) {
				actionChangeLogInsert($_SESSIONTEMP, $mysqlConn, $contractID, "Quantity", $oldQuantity, $newQuantity, $displayType);
			}
			if($oldProductionRate != $newProductionRate) {
				actionChangeLogInsert($_SESSIONTEMP, $mysqlConn, $contractID, "Production Rate", $oldProductionRate, $newProductionRate, $displayType);
			}
			if($oldSpaceRate != $newSpaceRate) {
				actionChangeLogInsert($_SESSIONTEMP, $mysqlConn, $contractID, "Space Rate", $oldSpaceRate, $newSpaceRate, $displayType);	
			}
		}
	}
	
	function actionChangeLogActualTermStart($_SESSIONTEMP, $mysqlConn, $contractID, $changedTo) {
		$query = "
				SELECT `actualTermStart` from `contracts`
						WHERE `contractID` = '".$contractID."'
		";

		$actionChangeLogQuery = $mysqlConn->query($query);
		
		if($actionChangeLogQuery->num_rows > 0) {
			$row = $actionChangeLogQuery->fetch_assoc(); 	
			$changedFrom = $row["actualTermStart"];
			actionChangeLogInsert($_SESSIONTEMP, $mysqlConn, $contractID, "Actual Term Start", $changedFrom, $changedTo);
		}
	}
	
	function actionChangeLogActualTermEnd($_SESSIONTEMP, $mysqlConn, $contractID, $changedTo) {
		$query = "
				SELECT `actualTermEnd` from `contracts`
						WHERE `contractID` = '".$contractID."'
		";

		$actionChangeLogQuery = $mysqlConn->query($query);
		
		if($actionChangeLogQuery->num_rows > 0) {
			$row = $actionChangeLogQuery->fetch_assoc(); 	
			$changedFrom = $row["actualTermEnd"];
			actionChangeLogInsert($_SESSIONTEMP, $mysqlConn, $contractID, "Actual Term End", $changedFrom, $changedTo);
		}
	}
	
	function actionChangeLogAttachmentApproval($_SESSIONTEMP, $mysqlConn, $contractID, $changedTo, $attachmentID) {
		$query = "
				SELECT `approved`, `fileName` from `contracts_attachments`
						WHERE `contractID` = '".$contractID."'
							AND `attachmentID` = '".$attachmentID."'
		";

		$actionChangeLogQuery = $mysqlConn->query($query);

		/* Format the Approval Status */
		if($changedTo == 1) {
			$changedTo = "Approved";
		}
		else if($changedTo == 2) {
			$changedTo = "Denied";
		}
		else {
			$changedTo = "Pending";
		}
		
		if($actionChangeLogQuery->num_rows > 0) {
			$row = $actionChangeLogQuery->fetch_assoc(); 	
			$changedFrom = $row["approved"];
			$filename = $row["fileName"];
			
			/* Format the Approval Status */
			if($changedFrom == 1) {
				$changedFrom = "Approved";
			}
			else if($changedFrom == 2) {
				$changedFrom = "Denied";
			}
			else {
				$changedFrom = "Pending";
			}

			actionChangeLogInsert($_SESSIONTEMP, $mysqlConn, $contractID, "Attachment Approval", $changedFrom, $changedTo, $filename);
		}
	}

	function actionChangeLogDeviation($_SESSIONTEMP, $mysqlConn, $contractID, $changedTo) {
		$query = "
				SELECT `deviationNeeded` from `contracts`
						WHERE `contractID` = '".$contractID."'
		";

		$actionChangeLogQuery = $mysqlConn->query($query);

		/* Format the Approval Status */
		if($changedTo == 1) {
			$changedTo = "True";
		}
		else {
			$changedTo = "False";
		}
		
		if($actionChangeLogQuery->num_rows > 0) {
			$row = $actionChangeLogQuery->fetch_assoc(); 	
			$changedFrom = $row["deviationNeeded"];
			
			/* Format the Approval Status */
			if($changedFrom == 1) {
				$changedFrom = "True";
			}
			else {
				$changedFrom = "False";
			}

			actionChangeLogInsert($_SESSIONTEMP, $mysqlConn, $contractID, "Deviation Needed", $changedFrom, $changedTo);
		}
	}
	
	function actionChangeLogContractApproval($_SESSIONTEMP, $mysqlConn, $contractID, $changedTo) {
		$query = "
				SELECT `approvalStatus` from `contracts`
						WHERE `contractID` = '".$contractID."'
		";

		$actionChangeLogQuery = $mysqlConn->query($query);

		/* Format the Approval Status */
		if($changedTo == 1) {
			$changedTo = "Approved";
		}
		else if($changedTo == 2) {
			$changedTo = "Denied";
		}
		else {
			$changedTo = "Pending";
		}
		
		if($actionChangeLogQuery->num_rows > 0) {
			$row = $actionChangeLogQuery->fetch_assoc(); 	
			$changedFrom = $row["approvalStatus"];
			
			/* Format the Approval Status */
			if($changedFrom == 1) {
				$changedFrom = "Approved";
			}
			else if($changedFrom == 2) {
				$changedFrom = "Denied";
			}
			else {
				$changedFrom = "Pending";
			}

			actionChangeLogInsert($_SESSIONTEMP, $mysqlConn, $contractID, "Contract Approval", $changedFrom, $changedTo);
		}
	}
	
	function actionChangeLogBusNumbers($_SESSIONTEMP, $mysqlConn, $contractID, $changedTo, $busID) {
		$query = "
				SELECT bill.`displayType`, bus.`busNumber` 
						from `contract_bus` bus, `contract_billing` bill
							WHERE bus.`billRateID` = bill.`billRateID`
								AND bus.`contractID` = '".$contractID."'
								AND bus.`busID` = '".$busID."'
		";
		
		$actionChangeLogQuery = $mysqlConn->query($query);
		
		if($actionChangeLogQuery->num_rows > 0) {
			$row = $actionChangeLogQuery->fetch_assoc(); 	
			$changedFrom = $row["busNumber"];
			actionChangeLogInsert($_SESSIONTEMP, $mysqlConn, $contractID, "Bus Number", $changedFrom, $changedTo, $row["displayType"]);
		}
	}

	function actionChangeLogMarketSector($_SESSIONTEMP, $mysqlConn, $contractID, $changedTo) {
		$query = "
				SELECT `marketSector` 
						FROM `contracts`
							WHERE `contractID` = '".$contractID."'
		";

		$actionChangeLogQuery = $mysqlConn->query($query);
		
		if($actionChangeLogQuery->num_rows > 0) {
			$row = $actionChangeLogQuery->fetch_assoc(); 	
			$changedFrom = $row["marketSector"];
			actionChangeLogInsert($_SESSIONTEMP, $mysqlConn, $contractID, "Market Sector", $changedFrom, $changedTo);
		}
	}
	
	function actionChangeLogInsert($_SESSIONTEMP, $mysqlConn, $contractID, $changeType, $changedFrom, $changedTo, $fieldData = 'N/A') {
		$changeDate = date('Y-m-d H:i:s');
		$query = "
				INSERT INTO `action_changelog` (`changeID`, `contractID`, `changeDate`, `changeType`, `userName`, `changedFrom`, `changedTo`, `fieldData`)
				VALUES (	DEFAULT,
					'" . $contractID . "',
					'" . $changeDate . "',
					'" . $changeType . "',
					'" . $_SESSIONTEMP['username'] . "',
					'" . $changedFrom . "',
					'" . $changedTo . "',
					'" . $fieldData . "'  
				);
		";
		$actionChangeLogQuery = $mysqlConn->query($query);
	}
?>