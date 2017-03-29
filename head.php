<?php
	/*
	 * Palm Tran 
	 */
	 
	/* Include our configs */
	require_once('config.php');

	/* Begin the session */
	if(!ISSET($_SESSION)) {
		session_name($GLOBALS['sessionName']);
		session_start();
	}
	
	/* Set the timezone */
	date_default_timezone_set('America/New_York'); 
	
	/*
	 * If this person is not logged in send them to the login page.
	 */ 
	if(basename($_SERVER['PHP_SELF']) != 'login.php') {
		if(!ISSET($_SESSION['auth'])) {
			redirectTo("login.php"); 
			exit();
		}
	}
?>
<!DOCTYPE HTML>
<html lang="en">
<head>
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title><?php echo $GLOBALS['siteTitle']; ?></title>

	<!-- CSS -->
	<link href="css/bootstrap.css" rel="stylesheet" type="text/css" >
	<link href="css/bootstrap-theme.min.css" rel="stylesheet" type="text/css" >
	<link href="css/datatables.min.css" rel="stylesheet" type="text/css" >
	<link href="css/datepicker.css" rel="stylesheet" type="text/css" >
	<link href="css/custom.css" rel="stylesheet" type="text/css" >
	<link href="css/font-awesome.min.css" rel="stylesheet" type="text/css" >
	
	<!-- JS -->
	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
	<script type="text/javascript" src="js/datatables.min.js"></script>
	<script type="text/javascript" src="js/bootstrap.js"></script>

	<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
		<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->
</head>
<body>
	<div class="container-fluid">
	
		<div class="row">
			<div class="<?php echo $GLOBALS["mainBodyDimension"]; ?>">
				<div class="text-center panel-primary panel-margin-bot">
					<div class="panel-heading panel-skinny">
						<h3 class="panel-title font-bold globalnav"><?php echo $GLOBALS['siteTitle']; ?></h3>
					</div>
				</div>
			</div>
		</div>
	
		<?php
			/*<div class="row">
				<div class="<?php echo $GLOBALS["mainBodyDimension"]; ?>">
					<div class="text-center panel-primary panel-margin-bot">
						<div class="panel-heading panel-skinny">
								<span class="font-bold">Palm Beach County:</span>
								<a class="globalnav" target="_blank" href="http://discover.pbcgov.org/">Home</a> |   
								<a class="globalnav" target="_blank" href="http://discover.pbcgov.org/Pages/Jobs.aspx">Jobs</a> |   
								<a class="globalnav" target="_blank" href="http://discover.pbcgov.org/Pages/Links.aspx">Links</a> |   
								<a class="globalnav" target="_blank" href="http://discover.pbcgov.org/publicaffairs/Pages/default.aspx">Public Affairs</a> |    
								<a class="globalnav" target="_blank" href="http://discover.pbcgov.org/Pages/Departments.aspx">Site Index</a> |   
								<a class="globalnav" target="_blank" href="http://discover.pbcgov.org/publicaffairs/Pages/contact.aspx">Contact PBC</a>
						</div>
					</div>
				</div>
			</div>
			*/
		?>

		<div class="row">
			<div class="headerImg <?php echo $GLOBALS["mainBodyDimension"]; ?>">
				<img src="images/PBCGOV.png" alt="Palm Beach County" class="pbcgovLogo">
			</div>
		</div>

		<div class="row">
			<div class="<?php echo $GLOBALS["mainBodyDimension"]; ?>">
				<div class="text-center panel-primary panel-margin-bot panel-margin-top">
					<div class="panel-heading panel-skinny">
						<?php
							if(ISSET($_SESSION['auth'])) {
								echo "
									<a class='globalnav' href='index.php'>Home</a>
									|
									<a class='globalnav' href='addLicense.php'>New License</a>";
									if($_SESSION['isAdmin'] == 1){
										echo "
											|
											<a class='globalnav' href='adminLicensing.php'>Admin Page</a>
											|
											<a class='globalnav' href='assignUsers.php'>Assign Users</a>
											|
											<a class='globalnav' href='changeLog.php'>Change Log</a>";
									}
									echo "
									|
									<a class='globalnav' href='logout.php'>Logout</a>
									";
							}
							else {
								echo "
									<a class='globalnav' href='http://discover.pbcgov.org/palmtran/Pages/default.aspx' target='_blank'>Palm Tran Home</a>
								";
							}
						?>
					</div>
				</div>
			</div>
		</div>

	</div>