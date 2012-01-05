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
	header("Cache-control: no-cache");
	require_once($_SERVER['DOCUMENT_ROOT'] . "/eclipse.org-common/system/smartconnection.class.php");
	require_once($_SERVER['DOCUMENT_ROOT'] . "/eclipse.org-common/classes/friends/friendsContributionsList.class.php");
	require_once("functions.php");
	ob_start();	
	
	$showAll = $App->getHTTPParameter('showAll');
	if ($showAll == NULL) {
		$showAll = 1;
	}
	if ($showAll == 0)
	{ 
		$showAll = 0;
		$where = 'WHERE F.is_benefit = 1';
	}
	else {
		$showAll = 1;
		$where = NULL;
	}
	$start = $App->getHTTPParameter('start');
	$pageValue = 25;
	if (!$start)
		$start = 0;
	$totalContributionCount = new FriendsContributionsList();
	$totalContributionCount->selectFriendsContributionsList(-1, -1 /*, 'WHERE F.is_benefit = 1'*/);
	$pageCount = $totalContributionCount->getCount();
	$totalContributionCount = NULL;		
	?>
	<link rel="stylesheet" type="text/css" href="style.css" media="screen" />
	<div id="midcolumn">
		<h1><?=$pageTitle;?></h1>
		<? if (!$showAll){ ?>
		<a href="<?=$SERVER['PHP_SELF'];?>?showAll=1">Show All</a>
		<? } else { ?>
		<a href="donorlist.php?showAll=0">Show Friends Only</a>
		<? } ?>
		<?=displayPager($start, $pageValue, $pageCount, $showAll);?>
		<table class="donorList" cellspacing=0>
			<tr class="donorHeader">
				<td colspan="2" width="60%">Name and Message</td>
				<td width="20%">Date</td>
				<td width="20%" align="right">Amount</td>
			</tr>
			<?
				// Get total number of items so we can know whether to page or not.

				
				$friendsContributionsList = new FriendsContributionsList();
				$friendsContributionsList->selectFriendsContributionsList($start, $pageValue, $where);
				
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
					$comment =  stripslashes(strip_tags($contribution->getMessage()));	
					if (strlen($comment) > 80)
						if (strpos($comment, ' ') == 0 )
						{
							$commentArray = str_split($comment, 80);
							$comment = 0;
							foreach ($commentArray as $value)
							{
								$comment .= $value . " ";
							}
						}
					$date = strtotime("-1 year", strtotime($contribution->getDateExpired()));
					$now = strtotime("now");
					if ($date <= $now) {
						$date = date("Y-m-d", $date);
						//$date = $friend->getDateJoined();		
						if ($showAll == 1 || $benefit != "") {						
				?>
				<tr class="donorRecord">
					<td width="25"><?=$benefit;?></td>
					<td width="59%"><b><?=$name;?></b><br/><?=$comment;?></td>
					<td><?=$date;?></td>
					<td align="right">$<?=$amount;?> USD</td>
				</tr>		
				<?		} 
					} 
				}?>
		</table>
		<?=displayPager($start, $pageValue, $pageCount, $showAll);?>
		<br/><br/>				
	</div>
	<div id="rightcolumn">
		<div style="text-align:center">
			<a href="/donate/"><img src="/donate/images/donate.jpg" alt="Donate to Eclipse"/></a><br/><br/>
		</div>
		<div class="sideitem">
			<h6>Total Donations</h6>
			<div style="text-align:center;font-size:24px;padding:5px 0px;"><?=$pageCount;?></div>
		</div>
		<div class="sideitem">
		<h6>Legend</h6>
			<p align="center">
				<img src="images/star.jpg" align="absbottom">Friend of Eclipse
			</p>
		</div>
	</div>	
	<?
	header('Content-Type: text/html; charset=ISO-8859-1;');
	$html = ob_get_clean();
//	$html = mb_convert_encoding($html, "HTML-ENTITIES", "UTF-8");
	$Nav->addCustomNav("Donate to Eclipse", 		"index.php", 			"_self", 1);
	$Nav->addCustomNav("Friends of Eclipse Login", 		"http://dev.eclipse.org/site_login", 			"_self", 1);
	$Nav->addCustomNav("Donation FAQ", 		"faq.php", 			"_self", 1);
	# Generate the web page
	$App->generatePage("Nova", $Menu, $Nav, $pageAuthor, $pageKeywords, $pageTitle, $html);
?>
	