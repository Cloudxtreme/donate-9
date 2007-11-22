<?php

	 require_once($_SERVER['DOCUMENT_ROOT'] . "/donate/classes/friend.class.php");
	 require_once($_SERVER['DOCUMENT_ROOT'] . "/donate/classes/contribution.class.php");

	// read the post from PayPal system and add 'cmd'
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
	
	
	
	if (!$fp) {
	// HTTP ERROR
	} else {
	fputs ($fp, $header . $req);
	while (!feof($fp)) {
		$res = fgets ($fp, 1024);
		if (strcmp ($res, "VERIFIED") == 0) {
		// check the payment_status is Completed
		// check that txn_id has not been previously processed
		// check that receiver_email is your Primary PayPal email
		// check that payment_amount/payment_currency are correct
		// process payment
		
		
		// echo the response
		echo "The response from IPN was: <b>" .$res ."</b><br><br>";
		
		//loop through the $_POST array and print all vars to the screen.
		
		foreach($_POST as $key => $value){
	        echo $key." = ". $value."<br>";
		}
		var_dump($res);
		$lines = explode("\n", $res);
		$keyarray = array();
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
	
	}
	else if (strcmp ($res, "INVALID") == 0) {
	// log for manual investigation
	
	// echo the response
	echo "The response from IPN was: <b>" .$res ."</b>";
	
	  }
	
	}
	fclose ($fp);
	}


	 
	$html = ob_get_clean();
	echo $html;
?>


