<?php  	error_reporting(E_ALL);																													require_once($_SERVER['DOCUMENT_ROOT'] . "/eclipse.org-common/system/app.class.php");	require_once($_SERVER['DOCUMENT_ROOT'] . "/eclipse.org-common/system/nav.class.php"); 	require_once($_SERVER['DOCUMENT_ROOT'] . "/eclipse.org-common/system/menu.class.php"); 	$App 	= new App();	$Nav	= new Nav();	$Menu 	= new Menu();		include($App->getProjectCommon());    # All on the same line to unclutter the user's desktop'

	#*****************************************************************************
	#
	# index.php (/friends)
	#
	# Author: 		Nathan Gervais
	# Date:			2007-11-07
	#
	# Description: Type your page comments here - these are not sent to the browser
	#
	#
	#****************************************************************************
	error_reporting(E_ALL);		
	#
	# Begin: page-specific settings.  Change these. 
	$pageTitle 		= "Thank You";
	$pageKeywords	= "friends of eclipse, donation, contribution";
	$pageAuthor		= "Nathan Gervais";


	 require_once($_SERVER['DOCUMENT_ROOT'] . "/eclipse.org-common/classes/friends/friend.class.php");
	 require_once($_SERVER['DOCUMENT_ROOT'] . "/eclipse.org-common/classes/friends/contribution.class.php");
	
	ob_start();	
	// read the post from PayPal system and add 'cmd'
	$req = 'cmd=_notify-synch';
	
	$tx_token = $_GET['tx'];
	//$auth_token = "IwvbOjr-0QFrWwQ-v4npMGcesdQ2JHq90pjTQQdIc2D1SuJ1VHUwTUFXINu"; // Live
	$auth_token = "83cDSIxlE-KGhQsTKd93YAY79iVD7RjNMyl71n3FY4LvRYAiPRdcVs6Ash0"; // Sandbox
	$req .= "&tx=$tx_token&at=$auth_token";
	
	
	// post back to PayPal system to validate
	$header .= "POST /cgi-bin/webscr HTTP/1.0\r\n";
	$header .= "Content-Type: application/x-www-form-urlencoded\r\n";
	$header .= "Content-Length: " . strlen($req) . "\r\n\r\n";
	$fp = fsockopen ('www.sandbox.paypal.com', 80, $errno, $errstr, 30);
	// If possible, securely post back to paypal using HTTPS
	// Your PHP server will need to be SSL enabled
	// $fp = fsockopen ('ssl://www.paypal.com', 443, $errno, $errstr, 30);
	
	if (!$fp) {
		echo "FsockOpen seems to have failed. <br/>";
	} 
	else {
		fputs ($fp, $header . $req);
		// read the body data
		$res = '';
		$headerdone = false;
		while (!feof($fp)) {
			$line = fgets ($fp, 1024);
			if (strcmp($line, "\r\n") == 0) {
				// read the header
				$headerdone = true;
			}
			else if ($headerdone)
			{
				// header has been read. now read the contents
				$res .= $line;
			}
		}
		// parse the data
		$lines = explode("\n", $res);
		$keyarray = array();
		if (strcmp ($lines[0], "SUCCESS") == 0) {
			for ($i=1; $i<count($lines);$i++){
				list($key,$val) = explode("=", $lines[$i]);
				$keyarray[urldecode($key)] = urldecode($val);
			}
			// check the payment_status is Completed
			// check that txn_id has not been previously processed
			// check that receiver_email is your Primary PayPal email
			// check that payment_amount/payment_currency are correct
			// process payment

			$firstname = $keyarray['first_name'];
			$lastname = $keyarray['last_name'];
			$itemname = $keyarray['item_name'];
			$amount = $keyarray['payment_gross'];
			$bugzillaEmail = $keyarray['item_number'];
			
			$anonymous = $keyarray['option_selection2'];
			$comment = strip_tags($keyarray['option_selection1']);
			$transactionID = $keyarray['txn_id'];
			$paymentStatus = $keyarray['payment_status'];

			if ($anonymous == "Public")
			{
				$anonymousValue = 0;
			}
			else 
			{
				$anonymousValue = 1;
			}
			if ($amount >= 35)
			{
				$benefit = 1;			
			}
			if (strpos($amount, ".") == 0)
			{
				$amount = $amount . ".00";
			}
			
			if ($paymentStatus == "Completed")
			{

					
				// Check to see if this transaction has already been processed.
				$checkContribution = new Contribution();
				$checkTrans = $checkContribution->selectContributionExists($transactionID);
				if ($checkTrans == FALSE)
				{
					//Check to see if user already exists in friends
					$checkFriends = new Friend();
					$bugzillaID = $checkFriends->getBugzillaIDFromEmail($bugzillaEmail);
					$friendID = $checkFriends->selectFriendID("bugzilla_id", $bugzillaID);
					if ($friendID != 0)
					{
						// FriendID does not equal 0 so we have an existing user. We need to add a new contribution

						$insertContribution = new Contribution();
						$insertContribution->setFriendID($friendID);
						$insertContribution->setAmount($amount);
						$insertContribution->setMessage($comment);
						$insertContribution->setTransactionID($transactionID);
						$insertContribution->insertContribution();
						//Record Inserted
					}
					else {
						// No friendID found so add a new friend record then add the contribution record.
						$newFriend = new Friend();
						$newFriend->setFirstName($firstname);
						$newFriend->setLastName($lastname);
						$newFriend->setBugzillaID($bugzillaID);
						$newFriend->setIsAnonymous($anonymousValue);
						$newFriend->setIsBenefit($benefit);	
						$newFriendID = $newFriend->insertUpdateFriend();
						
						$insertContribution = new Contribution();
						$insertContribution->setFriendID($newFriendID);
						$insertContribution->setAmount($amount);
						$insertContribution->setMessage($comment);
						$insertContribution->setTransactionID($transactionID);
						$insertContribution->insertContribution();
					}
				}
			}
			?>
			
			
			<div id="midcolumn">
				<p><h1>Thank you for your donation!</h1></p>
				<p>Your transaction has been completed and a receipt for your purchase has been emailed to you.<br/><br/>			
				<h3><b>Donation Details</b></h3>
				<ul>
					<li>Name: <?=$firstname;?> <?=$lastname;?></li>
					<?if ($bugzillaEmail != "")  { ?><li>Bugzilla Login: <?=$bugzillaEmail;?></li> <? } ?>
					<li>Amount: <?=$amount;?></li>
					<li>Anonymity: <?=$anonymous;?></li>
					<li>Comment: <?=$comment;?></li>
				</ul>
				<br/>
				<h3>View our <a href="donorlist.php">Donor List</a></h3><br/><br/>
				
				<? if ($benefit == 1) { ?>
					<h3>Friends of Eclipse Login</h3>
						<p>Visit <a href="http://dev.eclipse.org/site_login">http://dev.eclipse.org/site_login</a> to get access to the Friends of Eclipse Mirror</p>
					<br/><br/>					
					<h3>Friends of Eclipse Logo</h3>	
						<p>If you wish to link to the Friends of Eclipse Logo on your website or blog please use this code below<br/><img src="images/friendslogo.jpg"/>
						<pre><img src="http://www.eclipse.org/donate/images/friendslogo.jpg"></pre></p>
				<? } ?>
			</div>
			
			<?
		}
		else if (strcmp ($lines[0], "FAIL") == 0) {
			?><p>There was an error in processing your transaction. Please contact <a href="mailto:friends@eclipse.org">friends@eclipse.org</a> with the transaction information from PayPal.</p><?
		}
	}
	
	fclose ($fp);
?>

	
<? $html = ob_get_clean();
	# Generate the web page
	$App->generatePage($theme, $Menu, $Nav, $pageAuthor, $pageKeywords, $pageTitle, $html);
?>

