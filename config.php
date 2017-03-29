<?php
	/*
	 * Palm Tran  Config File
	 */
	 
	/************************************************************************
	*************************************************************************
	************************************************************************/
	/* Enable maintenace mode page manually */
	$maintenanceMode = false;
	
	/************************************************************************
	*************************************************************************
	************************************************************************/
	/* Connect to our database */
	if($maintenanceMode != true) {
		/* Our MySQL connection information */
		$host = "";
		$dbUser = "";
		$dbPass = "";
		$dbName = "";
		/* Connect to our MySQL database */
		try {
			$mysqlConn = new PDO("mysql:host=$host;dbname=$dbName", $dbUser, $dbPass);
			/* Set the PDO error mode to exception */
			$mysqlConn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		} catch(PDOException $e) {
			//echo "Error: " . $e->getMessage();
			//require_once('dbOffline.php'); // Display Maintenance Page
			exit(); // Don't continue
		}
	} else {
		//require_once('dbOffline.php'); // Display Maintenance Page
		exit(); // Don't continue
	}
	
	/************************************************************************
	*************************************************************************
	************************************************************************/
	/* Global variables */
	$GLOBALS['siteURL'] = "";
	$GLOBALS['siteTitle'] = "Licensing Application";
	$GLOBALS['sessionName'] = "license";
	$GLOBALS['mainBodyDimension'] = "col-md-10 col-md-offset-1";
	$GLOBALS['domainController'] = "";
	
	/* Comment this out to disable the page information box */
	$GLOBALS['pageInfo'] = "This site displays all the licenses purchased, both active and inactive.";
	
	/************************************************************************
	*************************************************************************
	************************************************************************/
	/* Per page override of dimensions */
	if(ISSET($GLOBALS['tempPageDimension'])) {
		$GLOBALS['mainBodyDimension'] = $GLOBALS['tempPageDimension'];
	}
	/* Per page override of info box */
	if(ISSET($GLOBALS['tempPageInfo'])) {
		$GLOBALS['pageInfo'] = $GLOBALS['tempPageInfo'];
	}
	
	/************************************************************************
	*************************************************************************
	************************************************************************/
	/* Functions */
	function boxHeader($header) {
		echo "
			<div class='container-fluid'>
				<div class='row'>
					<div class='" .$GLOBALS['mainBodyDimension'] . "'>
						<div class='text-center panel panel-primary panel-margin'>
							<div class='panel-heading'>
								<h3 class='panel-title'>" . $header . "</h3>
							</div>
							<div class='panelPadding'>
			";
	}
	
	function boxFooter(){
		echo "			
							</div>
						</div>
					</div>
				</div>
			</div>
		";
	}
	
	function alertDanger($message) {
		echo "
			<div class='container-fluid'>
				<div class='row'>
					<div class='".$GLOBALS['mainBodyDimension']."'>
						<div class='alert alert-danger text-center alert-margin-fix'>
							<a class='close' data-dismiss='alert' href='#'>×</a>
							" . $message . "
						</div>
					</div>
				</div>
			</div>
		";
	}
	
	function alertSuccess($message) {
		echo "
			<div class='container-fluid'>
				<div class='row'>
					<div class='".$GLOBALS['mainBodyDimension']."'>
						<div class='alert alert-success text-center alert-margin-fix'>
							<a class='close' data-dismiss='alert' href='#'>×</a>
							" . $message . "
						</div>
					</div>
				</div>
			</div>
		";
	}
	
	function displayInfoBox() {
		if(ISSET($GLOBALS['pageInfo'])) {
			boxHeader("Page Information");
				echo $GLOBALS['pageInfo'];
			boxFooter();
		}
	}
	
	function redirectTo($page) {
		$redirectTo = "<META HTTP-EQUIV='Refresh' Content=\"0; URL='" . $GLOBALS['siteURL'] . "" . $page . "'\" />"; 
		echo $redirectTo;
	}
	
	/************************************************************************
	*************************************************************************
	************************************************************************/
?>
