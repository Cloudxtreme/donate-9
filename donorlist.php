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
	require_once("classes/friendsContributionsList.class.php");
	ob_start();	
	$start = $App->getHTTPParameter("start");
	$pageValue = 10;
	if ($start == "")
		$start = 0;
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
				// Get total number of items so we can know whether to page or not.
				$totalContributionCount = new FriendsContributionsList();
				$totalContributionCount->selectFriendsContributionsList();
				$pageCount = $totalContributionCount->getCount();
				$totalContributionCount = NULL;
				
				$friendsContributionsList = new FriendsContributionsList();
				$friendsContributionsList->selectFriendsContributionsList($start, $pageValue);
				
				$friend = new Friend();
				$contribution = new Contribution();
				$fcObject = new FriendsContributions();
				$count = $friendsContributionsList->getCount();
				for ($i=0; $i < $count; $i++)
				{
					$fcObject = $friendsContributionsList->getItemAt($i);
					$friend = $fcObject->getFriendObject();
					$contribution = $fcObject->getContributionObject();
					$anonymous = $friend->getIsAnonymous();
					if ($anonymous != 1)
						$name = $friend->getFirstName() . " " . $friend->getLastName();
					else 
						$name = "Anonymous";
					$benefit = $friend->getIsBenefit();
					if ($benefit != 0)
						$benefit = " <img src=\"images/star.jpg\">";
					else
						$benefit = "";
					$amount = $contribution->getAmount();	
					if (strpos($amount, ".") == 0)
					{
						$amount = $amount . ".00";
					}
					$comment = strip_tags($contribution->getMessage());	
					$date = $friend->getDateJoined();			
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
			<?
				if ($start >= $pageValue)
				{
					?><a href="donorlist.php?start=<?=$start-$pageValue;?>">Previous Page</a><?
				}
				if ((($pageCount - $start)% $pageValue) > 0)
				{
					?><a href="donorlist.php?start=<?=$start+$pageValue;?>">Next Page</a><?
				}
			?>
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
	