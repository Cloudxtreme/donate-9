<?php

function sideDonorList($_numrows) {
	require_once($_SERVER['DOCUMENT_ROOT'] . "/eclipse.org-common/system/smartconnection.class.php");
	require_once($_SERVER['DOCUMENT_ROOT'] . "/eclipse.org-common/classes/friends/friendsContributionsList.class.php");
	
	$friendsContributionsList = new FriendsContributionsList();
	$friendsContributionsList->selectFriendsContributionsList(0, $_numrows);
	
	$friend = new Friend();
	$contribution = new Contribution();
	$fcObject = new FriendsContributions();
	
	$count = $friendsContributionsList->getCount();
	for ($i=0; $i < $count; $i++)
	{
		$fcObject = $friendsContributionsList->getItemAt($i);
		$friend = $fcObject->getFriendObject();
		$contribution = $fcObject->getContributionObject();
		$date = $contribution->getDateExpired();
		$date = strtotime($date);
		$date = strtotime("-1 year", $date);
		$now = strtotime("now");
		if ($date <= $now) {
			$anonymous = $friend->getIsAnonymous();
			if ($anonymous != 1) {
				$name = $friend->getFirstName() . " " . $friend->getLastName();
				$name = htmlentities($name);
			}
			else 
				$name = "Anonymous";
			$benefit = $friend->getIsBenefit();
			if ($benefit)
				$style = "style=\"list-style-image:url(images/starbullet.jpg);\"";
			else 
				$style = "";
			$amount = $contribution->getAmount();
			echo "<li $style>$name - $" . $amount . "</li>";
		}
	}
	echo "<div class=\"more\"><a href=\"donorlist.php\">Donor List</a></div>";
}

function displayPager($_start, $_pageValue, $_pageCount, $_showAll = NULL)
{
	if ($_showAll == 0)
	{$showAll = "&showAll=0";}
	ob_start();
	?>
	<table class="pager">
			<tr>
				<td style="text-align:left">
			<?
				if ($_start >= $_pageValue)
				{
					?><a href="<?=$_SERVER['PHP_SELF'];?>?start=<?=$_start-$_pageValue;?><?=$showAll;?>"><< Previous Page</a><?
				}
			?>&nbsp;</td>
				<td style="text-align:right">
			<?
				if (($_start + $_pageValue) < $_pageCount)
				{
					?><a href="<?=$_SERVER['PHP_SELF'];?>?start=<?=$_start+$_pageValue;?><?=$showAll;?>">Next Page >></a><?
				}
			?>
				</td>
			</tr>
		</table>
	<?
	return ob_get_clean();
}	
?>