<?php
	
	require_once("head.php");
	
	/* Per Page resizing - Uncomment if you require this */
	$GLOBALS['tempPageDimension'] = "col-md-6 col-md-offset-3";
	
	/* User is already logged in */
	if(ISSET($_SESSION['auth'])) { 
		redirectTo("index.php");
		exit();
	}
		
	/* Tell them they've been logged out */
	if(ISSET($_GET['logout'])) {
		alertSuccess("You have been logged out.");
	}
	
	if(ISSET($_POST['submit'])) {
		$username = $_POST['inputUserName'];
		$password = $_POST['inputPassword'];
		
		if($username == 'root' && $password == 'pass') {
			$_SESSION['auth'] = "true";
			$_SESSION['username'] = "root";
			$_SESSION['isAdmin'] = 1;
			/* Send the user to the main page */
			redirectTo("index.php");
			exit();
		}
		else {
			alertDanger("You are not authenticated to use this system");
		}
	}
?>
<?php boxHeader("Sign In"); ?>

		<form action="login.php" method="post">
		
			<div class="form-group">
				<label for="inputUserName" class="cols-md-4 control-label text-left">Username:</label>
				<div class="cols-md-6">
					<div class="input-group login-input">
						<span class="input-group-addon"><i class="fa fa-user fa" aria-hidden="true"></i></span>
						<input type="text" class="form-control" name="inputUserName" id="inputUserName" placeholder="Enter your username" value= "root" />
					</div>
				</div>
			</div>
			
			<div class="form-group">
				<label for="inputPassword" class="cols-md-4 control-label">Password:</label>
				<div class="cols-md-6">
					<div class="input-group login-input">
						<span class="input-group-addon"><i class="fa fa-lock fa-lg" aria-hidden="true"></i></span>
						<input type="password" class="form-control" name="inputPassword" id="inputPassword"  placeholder="Enter your password" value="pass" />
					</div>
				</div>
			</div>
		
			<div class="form-group login-submit">
				<input type="submit" class="btn btn-lg btn-primary btn-block" name="submit" value="Sign In">
			</div>
		</form>   
		
<?php boxFooter(); ?>
	
<?php
	require_once('footer.php');
?>