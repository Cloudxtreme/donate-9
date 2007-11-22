<?php

	 require_once($_SERVER['DOCUMENT_ROOT'] . "/donate/classes/friend.class.php");
	 require_once($_SERVER['DOCUMENT_ROOT'] . "/donate/classes/contribution.class.php");
	
	ob_start();	
	$req = 'cmd=_notify-validate';
	
	foreach ($_POST as $key => $value) {
	$value = urlencode(stripslashes($value));
	$req .= "&$key=$value";
	}
	
	
	// post back to PayPal system to validate
	$header .= "POST /testing/ipntest.php HTTP/1.0\r\n";
	$header .= "Content-Type: application/x-www-form-urlencoded\r\n";
	$header .= "Content-Length: " . strlen($req) . "\r\n\r\n";
	$fp = fsockopen ('www.eliteweaver.co.uk', 80, $errno, $errstr, 30);
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
		if (strcmp ($lines[0], "VERIFIED") == 0) {
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
			$comment = $keyarray['option_selection1'];
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
				if ($checkContribution->selectContributionExists($transactionID) == 0)
				{
					$dateExpired = date("Y-m-d H:m:s", strtotime("+1 year"));
					//Check to see if user already exists in friends
					$checkFriends = new Friend();
					$bugzillaID = $checkFriends->getBugzillaIDFromEmail($bugzillaEmail);
					$friendID = $checkFriends->selectFriend("bugzilla_id", $bugzillaID);
					if ($friendID != 0)
					{
						// FriendID does not equal 0 so we have an existing user. We need to add a new contribution

						$insertContribution = new Contribution();
						$insertContribution->setFriendID($friendID);
						$insertContribution->setAmount($amount);
						$insertContribution->setMessage($message);
						$insertContribution->setDateExpired($dateExpired);
						$insertContribution->setTransactionID($transactionID);
						$insertContribution->insertContribution();
						//Record Inserted
					}
					else {
						// No friendID found so add a new friend record then add the contribution record.
						$now = date("Y-m-d H:i:s");
						$newFriend = new Friend();
						$newFriend->setFirstName($firstname);
						$newFriend->setLastName($lastname);
						$newFriend->setBugzillaID($bugzillaID);
						$newFriend->setDateJoined($now);
						$newFriend->setIsAnonymous($anonymousValue);
						$newFriend->setIsBenefit($benefit);	
						$newFriendID = $newFriend->insertUpdateFriend();
						
						$insertContribution = new Contribution();
						$insertContribution->setFriendID($newFriendID);
						$insertContribution->setAmount($amount);
						$insertContribution->setMessage($comment);
						$insertContribution->setDateExpired($dateExpired);
						$insertContribution->setTransactionID($transactionID);
						$insertContribution->insertContribution();
					}
				}
			}
			?>
			
			
			<div id="midcolumn">
				<p><h1>Thank you for your donation!</h1></p>
			
				<b>Donation Details</b><br>
				<ul>
					<li>Name: <?=$firstname;?> <?=$lastname;?></li>
					<li>Bugzilla ID: <?=$bugzillaEmail;?></li>
					<li>Amount: <?=$amount;?></li>
					<li>Anonymity: <?=$anonymous;?></li>
					<li>Comment: <?=$comment;?></li>
				</ul>
				<p>View our <a href="donorlist.php">Donor List</a></p>
				<p>Your transaction has been completed, and a receipt for your purchase has been emailed to you.<br> You may log into your account at <a href='https://www.paypal.com'>www.paypal.com</a> to view details of this transaction.<br>
				<p>If you wish to link to the Friends of Eclipse Logo on your website or blog please use this code below</p>
				<textarea rows="2" cols="60"><img src="http://www.eclipse.org/donate/images/friendslogo.jpg"></textarea>
			</div>
			
			<?
		}
		else if (strcmp ($lines[0], "FAIL") == 0) {
			echo "<pre>" . var_dump($lines) . "</pre>";
		}
	}
	
	fclose ($fp);
	$html = ob_get_clean();
	echo $html;
?>


