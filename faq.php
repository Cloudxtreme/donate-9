<?php  																														require_once($_SERVER['DOCUMENT_ROOT'] . "/eclipse.org-common/system/app.class.php");	require_once($_SERVER['DOCUMENT_ROOT'] . "/eclipse.org-common/system/nav.class.php"); 	require_once($_SERVER['DOCUMENT_ROOT'] . "/eclipse.org-common/system/menu.class.php"); 	$App 	= new App();	$Nav	= new Nav();	$Menu 	= new Menu();		include($App->getProjectCommon());    # All on the same line to unclutter the user's desktop'

	#*****************************************************************************
	#
	# faq.php (/friends)
	#
	# Author: 		Nathan Gervais
	# Date:			2007-11-21
	#
	# Description: Type your page comments here - these are not sent to the browser
	#
	#
	#****************************************************************************
	
	#
	# Begin: page-specific settings.  Change these. 
	$pageTitle 		= "Donate to Eclipse FAQ";
	$pageKeywords	= "friends of eclipse, donation, faq";
	$pageAuthor		= "Nathan Gervais";
	ob_start();
	?>
	<div id="midcolumn">
		<h2><?=$pageTitle ?></h2>
		<div class="homeitem3col">
			<h3>Frequently Asked Questions</h3>
			<ul>
				<li><b>Are my contributions tax-deductible?</b><br/><br/>No, contributions are not tax-deductible.  The Eclipse Foundation is a not for profit organization, and not a charitable organization.  We are unable to provide tax receipts.</li>
				<li><b>What methods of payment do you accept for contributions?</b><br/><br/>You may make a donation with your credit card or through your PayPal account.</li>
				<li><b>What if I want to pay by cheque or direct deposit?</b><br/><br/>At this time, we are only set up to accept contributions through credit cards and PayPal.  It is free to sign up for an account at www.paypal.com..</li>
				<li><b>What is the difference between contributing money to Eclipse and becoming a Friend of Eclipse?</b><br/><br/>Individuals can contribute any amount of money they want to the Eclipse Foundation.  However, those that give $35 USD or more qualify for "Friends" status and thus qualify for added benefits such as dedicated bandwidth  and use of the "Friends" logo.</li>
				<li><b>What amounts may I contribute?</b><br/><br/>You may contribute any amount you like. Once you contribute $35 USD or more, you also qualify for the Friend of Eclipse benefits.</li>
				<li><b>If I’m unable to contribute financially, what else can I do to support the Foundation?</b><br/><br/>There are many ways to show your support, and your time is just as important as a monetary contribution. You can report bugs on Bugzilla, participate in community newsgroups, blog about Eclipse, and create multi-media content for Eclipse Live.</li>
				<li><b>What do you plan to do with the money?</b><br/><br/>We plan to use contributions to increase support for the Eclipse community.  Examples of activities would include providing more bandwidth to users, hosting more servers for project websites and sending students to EclipseCon. </li>
				<li><b>What should I do if I made a mistake when submitting my contribution?</b><br/><br/>Please email <a href="mailto:friends@eclipse.org">friends at eclipse.org</a> with all your contact information and an explanation of what you need corrected.</li>
				<li><b>What is the Friends of Eclipse Mirror?</b><br/><br/>The Friends of Eclipse mirror is a high-capacity download server for Friends of Eclipse. Unlike our main downloads servers, the Friends mirror has priority access to our bandwidth, typically resulting in faster downloads. Although a local mirror in your country may still provide a faster download due to the proximity,  the Friends mirror is our way of saying 'thank you' for your donation.</li>
				<li><b>Does this mean the Eclipse download site will be slower?</b>The Eclipse download site's performance varies from day to day, depending on new project releases. Although Friends' downloads get priority bandwidth, the Eclipse Foundation will add bandwidth as required to ensure an adequate level of performance from our main download site.</li>
				<li><b>How do I get access?</b>Everyone that donates $35 USD or more to the Eclipse Foundation will automatically be flagged as a ‘Friend of Eclipse’.  This gives them access to the Friends of Eclipse Mirror for 12 months.   When you register you donation, you will be asked to enter your Bugzilla Login ID.  This will be used to login to the Friends of Eclipse Mirror.</li>
			</ul>
		</div>
	</div>
	<div id="rightcolumn">
		<div class="sideitem">
		<h6>Related Links</h6>
			<ul>
				<li><a href="index.php">Donate to Eclipse</a></li>
				<li><a href="donorlist.php">Public List of Donors</a></li>
			</ul>
		</div>
	</div>
	
<? $html = ob_get_clean();
	# Generate the web page
	$App->generatePage($theme, $Menu, $Nav, $pageAuthor, $pageKeywords, $pageTitle, $html);
?>