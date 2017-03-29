<?php
	/*
	 *	Palm Tran Login Handler.
	 */
	
	/* Block IE 7 and below */
	if(STRPOS($_SERVER['HTTP_USER_AGENT'], 'Trident/3.') !== FALSE) {
		echo"
			<center>
				You are using IE 7.0 which was released in 2006. 
				We cannot support this browser version. 
				Please upgrade to a newer version, or alternatively install a browser such as Google Chrome.
			</center>
		";
		exit();
	}
	 
	/* Per Page resizing - Uncomment if you require this */
	$GLOBALS['tempPageDimension'] = "col-md-6 col-md-offset-3";
	
	/* Include our page header */
	require_once('head.php');

	/* User is already logged in */
	if(ISSET($_SESSION['auth'])) { 
		redirectTo("index.php");
		exit();
	}
		
	/* Tell them they've been logged out */
	if(ISSET($_GET['logout'])) {
		alertSuccess("You have been logged out.");
	}
		
	/* 
	 * Check for the a posted submit variable. 
	 * If it doesn't exist, the user hasn't submitted the login form.
	 */
	if(ISSET($_POST['submit'])) {
		/* Grab the posted username and password */
		$inputUserName = ldap_escape($_POST['inputUserName'], NULL, LDAP_ESCAPE_FILTER);
		$inputPassword = ldap_escape($_POST['inputPassword'], NULL, LDAP_ESCAPE_FILTER);
		
		/* Domain Controller Connection */
		$adServer = $GLOBALS['domainController'];
		$ldap = ldap_connect($adServer, "389");
		
		/* OU to check */
		$ldaprdn = 'pbcgov' . "\\" . $inputUserName;
		$ldap_dn = "OU=Users,OU=PALM,OU=Enterprise,DC=pbcgov,DC=org";
		
		/* LDAP Options */
		ldap_set_option($ldap, LDAP_OPT_PROTOCOL_VERSION, 3);
		ldap_set_option($ldap, LDAP_OPT_REFERRALS, 0);

		/* Bind to LDAP with submitted credentials */
		$bind = ldap_bind($ldap, $ldaprdn, $inputPassword);
		
		/* If $bind == TRUE, then the credentials are valid for this domain */
		if($bind) {
			$result = ldap_search($ldap,$ldap_dn, "(samaccountname=$inputUserName)");// or die ("Error in search query: ".ldap_error($ldapconn));
			$data = ldap_get_entries($ldap, $result);
			
			/* Username/password match was successful */
			if($data["count"] == 1) {
				/* Check if we have this user in our database */
				$query = "SELECT `userID` 
							FROM `users` 
								WHERE `username` = ?";
				
				$statement = $mysqlConn->prepare($query);
				
				$statement->execute();
				
				/* Users need to be strict, thus checking for their privilege is important */
				if($statement->rowCount() == 1) { 
					$_SESSION["userID"] = $row["userID"];
					$_SESSION["username"] =  $row["username"];
					$_SESSION["isAdmin"] = $row["isAdmin"];

				} else if($statement->rowCount() == 0) { /* User does not exists. We output an error message */
					echo "You are not authenticated to use this system. Please contact IT.";
				}
				
				/* Session variables */
				$_SESSION['auth'] =  "true"; /* Used for session check */
				
				
				/* Send the user to the main page */
				redirectTo("index.php");
				exit();
			} else {
				/* The credentials are valid but were not a match in Palm Tran's user OU. */
				alertDanger("Error: Are you a Palm Tran Employee?");
			}
		} else {
			alertDanger("Incorrect username or password");
		}
	}
	/* Display the page information box (enable or disable in config.php */
	displayInfoBox()
?>

	<?php boxHeader("Sign In"); ?>
		<form action="login.php" method="post">
		
			<div class="form-group">
				<label for="inputUserName" class="cols-sm-2 control-label text-left">Username:</label>
				<div class="cols-sm-10">
					<div class="input-group login-input">
						<span class="input-group-addon"><i class="fa fa-user fa" aria-hidden="true"></i></span>
						<input type="text" class="form-control" name="inputUserName" id="inputUserName" placeholder="Enter your username" />
					</div>
				</div>
			</div>
			
			<div class="form-group">
				<label for="inputPassword" class="cols-sm-2 control-label">Password:</label>
				<div class="cols-sm-10">
					<div class="input-group login-input">
						<span class="input-group-addon"><i class="fa fa-lock fa-lg" aria-hidden="true"></i></span>
						<input type="password" class="form-control" name="inputPassword" id="inputPassword"  placeholder="Enter your password"/>
					</div>
				</div>
			</div>
		
			<div class="form-group login-submit">
				<input type="submit" class="btn btn-lg btn-primary btn-block" name="submit" value="Sign In">
			</div>
   
			<div class="login-message">Please login with your PBCGOV credentials.</div>
		</form>   
	<?php boxFooter(); ?>
	
<?php
	require_once('footer.php');
?>