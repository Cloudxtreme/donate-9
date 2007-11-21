<?php

function sideDonorList($_numrows) {
	require_once($_SERVER['DOCUMENT_ROOT'] . "/eclipse.org-common/system/smartconnection.class.php");
	require_once("classes/friendsContributionsList.class.php");
	
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
		$anonymous = $friend->getIsAnonymous();
		if ($anonymous != 1)
			$name = $friend->getFirstName() . " " . $friend->getLastName();
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
	echo "<div class=\"more\"><a href=\"donorlist.php\">Donor List</a></div>";
}

?>