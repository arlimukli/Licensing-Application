<?php

	/* This file contains all the functions and tools to keep track of the changes */

	function insertLicenseChangeLogData($mysqlConn, $user, $licenseID, $fieldChanged, $oldValue, $newValue) {
		$changeDate = date('m-d-Y h:i:A');
		$query = "INSERT INTO `license_changelog` (`ID`, `user`, `license_id`, `date_time`, `field_changed`, `old_value`, `new_value`)
					VALUES (DEFAULT, '$user', '$licenseID', '$changeDate', '$fieldChanged', '$oldValue', '$newValue')";
		$statement = $mysqlConn->prepare($query);
		$statement->execute();
	}
	
	function insertUserChangeLogData($mysqlConn, $user, $effectOn, $action, $oldValue, $newValue) {
		$changeDate = date('m-d-Y h:i:A');
		$query = "INSERT INTO `users_changelog` (`ID`,`user`, `effect_on`, `date_time`, `action`, `old_value`, `new_value`)
					VALUES (DEFAULT, '$user', '$effectOn', '$changeDate', '$action', '$oldValue', '$newValue')";
		$statement = $mysqlConn->prepare($query);
		$statement->execute();
	}

?>