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
	
	ob_start();	
	
	$email = $App->getHTTPParameter('email', 'POST');
	$transactionID = $App->getHTTPParameter('transactionid', 'POST');
	var_dump ($transactionID);
	
	$contribution = new Contribution();
	$contribution->selectContributionExists($transactionID);
	$friend = new Friend();
	$friend->selectFriend($contribution->getFriendID());
	$bugzillaID = $friend->getBugzillaIDFromEmail($email);
	$amount = $contribution->getAmount();
	
	?>
	<script type="text/javascript" src="functions.js"></script>
	<link rel="stylesheet" type="text/css" href="style.css" media="screen" />
	<div id="midcolumn">
		<h1><?=$pageTitle;?></h1>
		<p>This page is used to revalidate an account for Friends Privledges.</p>							
		<form action="phase2.php">
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
	