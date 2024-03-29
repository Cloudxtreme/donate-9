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
	ob_start();	
	?>
	<script type="text/javascript" src="functions.js"></script>
	<link rel="stylesheet" type="text/css" href="style.css" media="screen" />
	<div id="midcolumn">
		<h1><?=$pageTitle;?></h1>
		<p>This page is used to revalidate an account for Friends Privledges.</p>							
		<form action="phase2.php" method="post">
			<table>
				<tr>
					<td>Email Address:</td>
					<td><input type="text" name="email"></td>
				</tr>
				<tr>
					<td>Transaction ID:</td>
					<td><input type="text" name="transactionid"></td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td><input type="submit" name="submit" value="Validate"></td>
				</tr>						
			</table>
		</form>
		
	</div> 
	<?
	$html = ob_get_clean();
	# Generate the web page
	$App->generatePage($theme, $Menu, $Nav, $pageAuthor, $pageKeywords, $pageTitle, $html);
?>
	