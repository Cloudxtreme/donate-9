<?php require_once($_SERVER['DOCUMENT_ROOT'] . "/eclipse.org-common/system/app.class.php");	require_once($_SERVER['DOCUMENT_ROOT'] . "/eclipse.org-common/system/nav.class.php"); 	require_once($_SERVER['DOCUMENT_ROOT'] . "/eclipse.org-common/system/menu.class.php"); 	$App 	= new App();	$Nav	= new Nav();	$Menu 	= new Menu();		include($App->getProjectCommon());    # All on the same line to unclutter the user's desktop'

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


	 //require_once($_SERVER['DOCUMENT_ROOT'] . "/eclipse.org-common/classes/friends/friend.class.php");
	 //require_once($_SERVER['DOCUMENT_ROOT'] . "/eclipse.org-common/classes/friends/contribution.class.php");
	 require_once("test/friends/friend.class.php");
	 require_once("test/friends/contribution.class.php");
	 #require_once("/home/data/httpd/eclipse-php-classes/system/authcode.php");
	 
	ob_start();	
	// read the post from PayPal system and add 'cmd'
	$req = 'cmd=_notify-synch';
	
	//remove when not using sandbox
	$auth_token = 'V8qE_sev7DTOjOHjTv9JdDUBy2wocTb6W96-h2CuQJO9kx_FKHz00gFq1ri';
	
	
	$tx_token = $_GET['tx'];
	$req .= "&tx=$tx_token&at=$auth_token";
	
	
	// post back to PayPal system to validate
	$header .= "POST /cgi-bin/webscr HTTP/1.0\r\n";
	$header .= "Content-Type: application/x-www-form-urlencoded\r\n";
	$header .= "Content-Length: " . strlen($req) . "\r\n\r\n";
	$fp = fsockopen ('http://www.sandbox.paypal.com/cgi-bin/webscr', 80, $errno, $errstr, 30);
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
			
			//if first_name, last_name, and anonymous are empty I will need to insert a form to get that information from the user.			
			if(empty($keyarray['first_name']) || empty($keyarray['last_name']) || empty($keyarray['option_selection2'])){
				$showform = TRUE;
				$anonymous = NULL;
			}else{
				$showform = FALSE;
				$firstname = $keyarray['first_name'];
				$lastname = $keyarray['last_name'];
				$itemname = $keyarray['item_name'];
				$bugzillaEmail = $keyarray['item_number'];					
				$anonymous = $keyarray['option_selection2'];
				$comment = strip_tags($keyarray['option_selection1']);				
			}
			
			$amount = $keyarray['payment_gross'];			
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
			
			//if I need to show the donation information form do not apply membership yet.
			//I will do that on the donation_process.php page.
			if ($paymentStatus == "Completed" and !$showform)
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
						// Lets Update the Friend Information
						$newFriend = new Friend();
						$newFriend->setFirstName($firstname);
						$newFriend->setLastName($lastname);
						$newFriend->setBugzillaID($bugzillaID);
						$newFriend->setIsAnonymous($anonymousValue);
						$newFriend->setIsBenefit($benefit);	
						$newFriend->setFriendID($friendID);
						$newFriendID = $newFriend->insertUpdateFriend();
						
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
				<p>Your transaction has been completed and a receipt for your purchase has been emailed to you.<br/>		
				<div class="homeitem">
				<?php if(!$showform){?>
				<h3><b>Donation Details</b></h3>
				<ul>
					<li>Name: <?=$firstname;?> <?=$lastname;?></li>
					<?if ($bugzillaEmail != "")  { ?><li>Bugzilla Login: <?=$bugzillaEmail;?></li> <? } ?>
					<li>Amount: <?=$amount;?></li>
					<li>Anonymity: <?=$anonymous;?></li>
					<li>Comment: <?=$comment;?></li>
				</ul>
				<?php }else{?>
<script type="text/javascript" src="functions.js"></script>
<link rel="stylesheet" type="text/css" href="style.css" media="screen" />
	
<h2>Support Eclipse</h2>
			<p>Support Eclipse and help the Eclipse Foundation provide services for the Eclipse community, such as*: 
				<ul class="paddedList">
					<li>Providing more bandwidth for users and committers</li> 
					<li>Purchasing additional servers to host Eclipse projects</li> 
					<li>Sending students to EclipseCon</li>
					<li>Sponsoring Eclipse community events</li>
				</ul>
			</p>
			<p>Feel free to donate any amount you'd like.  Donations of $35 or more will receive special Friends of Eclipse benefits (described below).  We have made it easy to use a credit card through <img src="images/paypal.gif" align="absbottom" alt="PayPal">.
			Please note, the Eclipse Foundation is a not-for-profit organization, not a charitable organization, so we are unable to provide charitable tax receipts.</p>  

<h3>Complete this form to receive special Friends of Eclipse benefits</h3>			
<form action="/donate/typ_donation_process.php" name="donation_info" method="POST">
<table width="100%">
	<tr>
		<td width="280">First Name<span class="required">*</span>:
		</td>
		<td><input type="text" name="first_name" size="30" id="first_name"
			maxlength="64">
		</td>
	</tr>
	<tr>
		<td width="280">Last Name<span class="required">*</span>:
		</td>
		<td><input type="text" name="last_name" size="30" id="last_name"
			maxlength="64">
		</td>
	</tr>
	<tr>
		<td>Donation Amount<span class="required">*</span>:
		</td>
		<td><?php print $amount;?> US$</td>
	</tr>
	<tr>
		<td>Message:</td>
		<td><input type="text" id="os0" name="os0" maxlength=200 size="30">
		</td>
	</tr>
	<tr>
		<td valign="top" style="font-size: 10px">(200 Characters)</td>
		<td><input type="radio" id="os1" name="os1" value="Public"
			checked="checked">List My Name<br />
		<input type="radio" id="os1" name="os1" value="Anonymous">List
			Anonymously</td>
	</tr>
	<tr>
		<td colspan=2><br />
		<b>Friend Of Eclipse Login</b>
		</td>
	</tr>
	<tr>
		<td colspan=2>
			<div id="WhatsThis">
				When you donate US$35 or more you will have access to the Friends of
				Eclipse Mirror Site. You will need an Eclipse.org account to gain
				you access to this mirror. If you need an account <a
					href="https://dev.eclipse.org/site_login/createaccount.php"
					target="_blank">click here</a> to launch the registration page in a
				new window.
			</div>
		</td>
	</tr>
	<? if ($benefit == 1) { ?>	
	<tr>
		<td>Eclipse.org login:
			<div id="verify"></div>
		</td>
		<td><input class="disabled" type="text" name="item_number"
			id="bugzilla" size="30" disabled>
		</td>
	</tr>
	<?php } ?>
	<tr>
		<td colspan="2"><br />
		</td>
	</tr>
	<tr>
		<td><div class="required" style="display: inline;">* Required</div>
		</td>
		<td><input type="button" value="Donate"
			onclick="verifyBugzillaLogin();" /> <img src="images/paypal.gif"
			align="absbottom" alt="PayPal">
		</td>
	</tr>
</table>
<input type="hidden" name="tx_token" id="tx_token" value="<?php print $tx_token;?>" />
<input type="hidden" name="amount" id="amount" value="<?php print $amount;?>" />
<input type="hidden" value="txn_id" name="<?php print $transactionID;?>">
<input type="hidden" value="payment_status" name="<?php print $paymentStatus;?>">
</form>
				<?php } //end of donation information form. ?>
				<br/><br/><div align="middle"><b style="font-size:120%">View our <a href="http://www.eclipse.org/donate/donorlist.php">Donor List</a></b></div>
				</div>
				<? if ($benefit == 1) { ?>
				<div class="homeitem">
					<h3>Friends of Eclipse</h3>
					<div style="padding-left:5px;">
						<p><h2>Login</h2>
						Visit <a href="http://dev.eclipse.org/site_login">http://dev.eclipse.org/site_login</a> to get access to the Friends of Eclipse Mirror</p>
					<br/>		
						<p><h2>Logo</h2>If you wish to link to the Friends of Eclipse Logo on your website or blog please use of the codes below</p>
						<table width="100%">
							<tr>
								<td><img src="http://www.eclipse.org/donate/images/friendslogo.jpg"/></td>
								<td align="right"><textarea><img src="http://www.eclipse.org/donate/images/friendslogo.jpg"/></textarea></td>
							</tr>
							<tr>
								<td><img src="http://www.eclipse.org/donate/images/friendslogo200.jpg"></td>
								<td align="right"><textarea><img src="http://www.eclipse.org/donate/images/friendslogo200.jpg"></textarea></td>
							</tr>
							<tr>
								<td><img src="http://www.eclipse.org/donate/images/friendslogo160.jpg"></td>
								<td align="right"><textarea><img src="http://www.eclipse.org/donate/images/friendslogo160.jpg"></textarea></td>
							</tr>
						</table>
					</div>
				</div>
				<? } ?>
				<div class="clearer"></div>
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

