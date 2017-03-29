 <?php
	/*
	 *	Palm Tran Logout Handler.
	 */
	 
	/* Include our configs */
	require_once('config.php');
	
	/* Start the session */
	session_name($GLOBALS['sessionName']);
	session_start();
	
	/* If no session exists, send them to the login page. If it does, destroy the sesion and send them with a logout flag */
	if(!ISSET($_SESSION['auth'])) { 
		redirectTo("login.php");
	} else {
		session_destroy();
		redirectTo("login.php?logout=1");
	}
?>