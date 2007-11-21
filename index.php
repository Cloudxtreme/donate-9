<?php  																														require_once($_SERVER['DOCUMENT_ROOT'] . "/eclipse.org-common/system/app.class.php");	require_once($_SERVER['DOCUMENT_ROOT'] . "/eclipse.org-common/system/nav.class.php"); 	require_once($_SERVER['DOCUMENT_ROOT'] . "/eclipse.org-common/system/menu.class.php"); 	$App 	= new App();	$Nav	= new Nav();	$Menu 	= new Menu();		include($App->getProjectCommon());    # All on the same line to unclutter the user's desktop'

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
	
	#
	# Begin: page-specific settings.  Change these. 
	$pageTitle 		= "Donate to Eclipse";
	$pageKeywords	= "friends of eclipse, donation, contribution";
	$pageAuthor		= "Nathan Gervais";
	include ("functions.php");		
	ob_start();	
	?>
	<script language="javascript">
		function whatsThisToggle(i) {
		var e = document.getElementById(i);
 		var t = e.className;
 		if (t.match('invisible')) { t = t.replace(/invisible/gi, 'visible'); }
 		else { t = t.replace(/visible/gi, 'invisible'); }
 		e.className = t;
	}		
	</script>
	<link rel="stylesheet" type="text/css" href="style.css" media="screen" />
	<div id="midcolumn">
		<h1><?=$pageTitle ?></h1>
		
		<div >
			<!-- <p>Financially contribute to the Eclipse Foundation and help support the vibrant Eclipse ecosystem and open source community.</p> -->

			<p>Donations will be used to help the Eclipse Foundation provide services for the Eclipse community, such as*: 
				<ul class="paddedList">
					<li>Providing more bandwidth for users and committers</li> 
					<li>Purchasing additional servers to host Eclipse projects</li> 
					<li>Sending students to EclipseCon</li>
					<li>Sponsoring Eclipse community events</li>
				</ul>
			</p>
			<p>Feel free to donate any amount you like.  We have made it easy to use a credit card through <img src="images/paypal.gif" align="absbottom" alt="PayPal">.</p>  
		</div>
		<div class="homeitem">
		<h3>Donate Now</h3>
				<form ACTION="https://www.sandbox.paypal.com/cgi-bin/webscr" METHOD="POST">
				<input type="hidden" name="business" value="nathan_1195237105_biz@eclipse.org">
				<!--  <input type="hidden" name="business" value="lynn@eclipse.org">  
				<input type="hidden" name="return" value="http://www.eclipse.org/friends/thankyou.php">
				<input type="hidden" name="rm" value="2"> -->
				<input type="hidden" name="item_name" value="Donation">
				<input type="hidden" name="no_shipping" value="1">
				<input type="hidden" name="on0" value="Comment">
				<input type="hidden" name="on1" value="Anonymity">
				<INPUT TYPE="hidden" NAME="lc" VALUE="US">
				<INPUT TYPE="hidden" NAME="cmd" VALUE="_xclick">
				<input type="hidden" name="currency_code" value="USD">
					<table width="100%">
						<tr>
							<td width="280">First Name<span class="required">*</span>:</td>
							<td><input type="text" name="first_name" size="30"></td>
						</tr>
						<tr>
							<td width="280">Last Name<span class="required">*</span>:</td>
							<td><input type="text" name="last_name" size="30"></td>
						</tr>
						<tr>
							<td>Donation Amount<span class="required">*</span>:</td>
							<td><input type="text" name="amount" size=10>USD</td>
						</tr>						
						<tr>
							<td>Message:</td>
							<td><input type="text" id="os0" name="os0" maxlength=200 size="30"></td>
						</tr>
						<tr>
							<td valign="top" style="font-size:10px">(200 Characters)</td>
							<td><input type="radio" id="os1" name="os1" value="Public" checked="checked">List My Name<br/><input type="radio" id="os1" name="os1" value="Anonymous">List Anonymously</td>
						</tr>
						<tr>
							<td colspan=2><br/><b>Friend Of Eclipse Login</b> <a style="font-size:75%;cursor:pointer;" onClick="whatsThisToggle('WhatsThis');">(What's This For?)</a></td>
						</tr>
						<tr>
							<td colspan=2>
								<div id="WhatsThis" class="invisible">
									When you donate $35 or more you will have access to the Friends of Eclipse Mirror Site.  Providing your Bugzilla Login will give you access to this mirror. 
								</div>
							</td>
						</tr>
						<tr>
							<td>Bugzilla Login:</td>
							<td><input type="text" name="item_number" size="30"></td>
						</tr>
						<tr>
							<td colspan=2><br/></td>
						</tr>
	
						<tr>
							<td><div class="required" style="display:inline;"> * Required</div></td>
							<td>						
								<input type="submit" value="Donate"/> <img src="images/paypal.gif" align="absbottom" alt="PayPal"></td>
						</tr>
					</table>
				</form>
		</div>	
		<div class="homeitem">
			<h3>Become a Friend of Eclipse</h3>

			<p>Donate $35 USD or more and you will be identified as a Friend of Eclipse. Benefits of Friends Include:
				<ul class="paddedList">
					<li><span class="friend">Friends of Eclipse Mirror Site</span>  - This will allow you to download new versions of Eclipse faster**.</li>
					<li><span class="friend">"Friend of Eclipse" logo</span> </li>
				</ul>
			</p>
			<p align="center"><img src="images/friendslogo.jpg"></p>
		</div>		
		<div style="clear:both">
			<p class="footNote">*Eclipse Foundation Inc. is a not-for-profit, member supported corporation. Please note that contributions or gifts to the Ecilpse Foundation Inc. are not tax deductible as charitable contributions, but may be tax deductible to you or your organization as a trade or business expense. Contributions will not be restricted to the activities described, but will be put into a general operating fund.</p>
			<p class="footNote">**Eclipse Foundation Inc. cannot guarantee that the Friends mirror will be faster than its other mirrors, however it will give users of this mirror priority.</p>

		</div>
	</div>
	<div id="rightcolumn">
		<div class="sideitem">
		<h6>Related Links</h6>
			<ul>
				<li><a href="faq.php">Donation FAQ</a></li>
				<li><a href="http://dev.eclipse.org/site_login">Friends of Eclipse Login</a></li>				
			</ul>
		</div>	
		<div class="sideitem">
			<h6>Recent Donations</h6>
			<ul>
				<? sideDonorList(10); ?>
			</ul>
		</div>
			
	</div>
	<?
	$html = ob_get_clean();
	# Generate the web page
	$App->generatePage($theme, $Menu, $Nav, $pageAuthor, $pageKeywords, $pageTitle, $html);
?>
	