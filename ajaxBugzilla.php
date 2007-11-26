<?php
	require_once($_SERVER['DOCUMENT_ROOT'] . "/eclipse.org-common/system/app.class.php");	$App 	= new App();	
	require_once($_SERVER['DOCUMENT_ROOT'] . "/eclipse.org-common/classes/friends/friend.class.php");
	
	$bugzillaLogin = $_GET['bugzillaLogin'];
	$friend = new Friend();
	$bugzillaID = $friend->getBugzillaIDFromEmail($bugzillaLogin);
	if ($bugzillaID != 0)
		echo "Verified!";
	else
		echo "Invalid Login";
?>