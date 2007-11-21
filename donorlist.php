<?php  																														require_once($_SERVER['DOCUMENT_ROOT'] . "/eclipse.org-common/system/app.class.php");	require_once($_SERVER['DOCUMENT_ROOT'] . "/eclipse.org-common/system/nav.class.php"); 	require_once($_SERVER['DOCUMENT_ROOT'] . "/eclipse.org-common/system/menu.class.php"); 	$App 	= new App();	$Nav	= new Nav();	$Menu 	= new Menu();		include($App->getProjectCommon());    # All on the same line to unclutter the user's desktop'

	#*****************************************************************************
	#
	# donorlist.php (/friends)
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
	$pageTitle 		= "Donor List";
	$pageKeywords	= "friends of eclipse, donation, contribution";
	$pageAuthor		= "Nathan Gervais";
	require_once($_SERVER['DOCUMENT_ROOT'] . "/eclipse.org-common/system/smartconnection.class.php");
	$dbc = new DBConnection;
	$dbc->connect();
	ob_start();	
	?>
	<link rel="stylesheet" type="text/css" href="style.css" media="screen" />
	<div id="midcolumn">
		<h1><?=$pageTitle;?></h1>
		
		<table class="donorList" cellspacing=0>
			<tr class="donorHeader">
				<td colspan="2" width="60%">Name and Message</td>
				<td width="20%">Date</td>
				<td width="20%" align="right">Amount</td>
			</tr>
			<?
				$sql = "SELECT FC.amount, FC.message, F.first_name, F.last_name, F.is_anonymous, F.is_benefit, F.date_joined 
						FROM friends_contributions as FC 
						LEFT JOIN friends as F on FC.friend_id = F.friend_id
						ORDER BY F.date_joined DESC LIMIT 0, 25";
				$donorRes = mysql_query($sql) or die ("DonorList Failed: " . mysql_error());
				while ($rr = mysql_fetch_array($donorRes))
				{
					$name = $rr['first_name'] . " " . $rr['last_name'];
					if ($rr['is_anonymous'] == 1)
						$name = "Anonymous";
					$benefit = $rr['is_benefit'];
					if ($benefit != 0)
						$benefit = " <img src=\"images/star.jpg\">";
					else
						$benefit = "";
					$date = $rr['date_joined'];
					$amount = $rr['amount'];
					if (strpos($amount, ".") == 0)
					{
						$amount = $amount . ".00";
					}
					$comment = $rr['message'];
				?>
				<tr class="donorRecord">
					<td><?=$benefit;?></td>
					<td><b><?=$name;?></b><br/><?=$comment;?></td>
					<td><?=$date;?></td>
					<td align="right">$<?=$amount;?> USD</td>
				</tr>		
				<?}	?>
		</table><br/><br/>
		<div id="pager">
		</div>
	</div>
	<div id="rightcolumn">
		<div class="sideitem">
		<h6>Related Links</h6>
			<ul>
				<li><a href="index.php">Donate to Eclipse</a></li>
				<li><a href="http://dev.eclipse.org/site_login">Friends of Eclipse Login</a></li>
				<li><a href="faq.php">Donation FAQ</a></li>
			</ul>
		</div>
		<div class="sideitem">
		<h6>Legend</h6>
			<p align="center">
				<img src="images/star.jpg" align="absbottom">Friend of Eclipse
			</p>
		</div>
	</div>	
	<?
	$html = ob_get_clean();
	# Generate the web page
	$App->generatePage($theme, $Menu, $Nav, $pageAuthor, $pageKeywords, $pageTitle, $html);
?>
	