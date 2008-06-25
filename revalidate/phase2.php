<?php  																														require_once($_SERVER['DOCUMENT_ROOT'] . "/eclipse.org-common/system/app.class.php");	require_once($_SERVER['DOCUMENT_ROOT'] . "/eclipse.org-common/system/nav.class.php"); 	require_once($_SERVER['DOCUMENT_ROOT'] . "/eclipse.org-common/system/menu.class.php"); 	$App 	= new App();	$Nav	= new Nav();	$Menu 	= new Menu();		include($App->getProjectCommon());    # All on the same line to unclutter the user's desktop'

	#*****************************************************************************
	#
	# index.php (/donate)
	#
	# Author: 		Nathan Gervais
	# Date:			2007-11-07
	#
	# Description: Type your page comments here - these are not sent to the browser
	#
	#
	#****************************************************************************
	
	#
	# Begin: page-specific settings.  Change these. 
	$pageTitle 		= "Friends of Eclipse";
	$pageKeywords	= "friends of eclipse, donation, contribution";
	$pageAuthor		= "Nathan Gervais";
	require_once($_SERVER['DOCUMENT_ROOT'] . "/eclipse.org-common/classes/friends/friend.class.php");
	require_once($_SERVER['DOCUMENT_ROOT'] . "/eclipse.org-common/classes/friends/contribution.class.php");
	require_once("/home/data/httpd/eclipse-php-classes/system/dbconnection_rw.class.php");
	$dbc = new DBConnectionRW();
	$dbh = $dbc->connect();
	ob_start();	
	
	$email = $App->getHTTPParameter('email', 'POST');
	$transactionID = $App->getHTTPParameter('transactionid', 'POST');
	
	$result = mysql_query("SELECT * FROM friends_contributions WHERE transaction_id = '$transactionID'") or die(mysql_error());
	$rr = mysql_fetch_object($result);
	
	$friend = new Friend();
	$friend->selectFriend($rr->friend_id);
	$bugzillaID = $friend->getBugzillaIDFromEmail($email);
	$amount = $rr->amount;
	var_dump($friend);
	if ($amount >= 35)
	{
		$friend->setBugzillaID($bugzillaID);
		$SQL = "UPDATE friends SET bugzilla_id = $bugzillaID WHERE friend_id = ". $friend->getFriendID();
		echo $SQL;
		$result = mysql_query($SQL,$dbh) or die(mysql_error());
		
		echo "Friend Updated";
	}
	?>
	<script type="text/javascript" src="functions.js"></script>
	<link rel="stylesheet" type="text/css" href="style.css" media="screen" />
	<div id="midcolumn">
		<h1><?=$pageTitle;?></h1>
		<p>This page is used to revalidate an account for Friends Priviledges.</p>							
		<form action="phase3.php">
			<table>
				<tr>
					<td>Bugzilla ID:</td>
					<td><?=$bugzillaID;?></td>
				</tr>
				<tr>
					<td>Amount:</td>
					<td><?=$amount;?></td>
				</tr>
			</table>
		</form>
		
	</div> 
	<?
	$html = ob_get_clean();
	# Generate the web page
	$App->generatePage($theme, $Menu, $Nav, $pageAuthor, $pageKeywords, $pageTitle, $html);
?>
	