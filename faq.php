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
	$pageTitle 		= "Friends of Eclipse FAQ";
	$pageKeywords	= "friends of eclipse, donation, faq";
	$pageAuthor		= "Nathan Gervais";
	ob_start();
	?>
	<div id="fullcolumn">
	<div id="midcolumn">
		<h2><?=$pageTitle ?></h2>
		<div class="homeitem3col">
			<h3>Frequently Asked Questions</h3>
			<ul>
				<br/><li><b>Do I get a charitable tax receipt for my donation? </b><br/><br/>
				No.  The Eclipse Foundation is a not-for-profit organization, 
				not a charitable organization, so we are unable to provide charitable tax receipts.<br/><br/></li>
				
				<li><b>What methods of payment do you accept for contributions?</b><br/><br/>
				We are using PayPal to process your donation.   PayPal enables you to use a credit card or a PayPal account.<br/><br/></li>
				
				<li><b>What if I want to pay by cheque or direct deposit?</b><br/><br/>
				At this time, we are only set up to accept contributions through credit cards and PayPal.  It is free to 
				sign up for an account at www.paypal.com.<br/><br/></li>
				
				<li><b>What is the difference between contributing money to Eclipse and becoming a Friend of Eclipse?</b><br/><br/>
				Individuals can contribute any amount of money they want to the Eclipse Foundation.  However, those 
				that give US$35 or more qualify for "Friend" status.  "Friend" status lasts for a period of 12 
				months and provides access to a Friends of Eclipse mirror and use of the "Friend" logo.<br/><br/></li>
				
				<li><b>What amounts may I contribute?</b><br/><br/>
				You may contribute any amount you like. If you contribute US$35 or more, you also qualify for the 
				Friend of Eclipse benefits.<br/><br/></li>
				
				<li><b>What do you plan to do with the money?</b><br/><br/>
				We plan to use these donations to increase the services provided by the Eclipse Foundation.  Examples 
				of activities would include providing more bandwidth to users, hosting more servers for project 
				websites and sending students to EclipseCon. <br/><br/></li>
				
				<li><b>What should I do if I made a mistake when submitting my contribution?</b><br/><br/>
				Please email <a href="mailto:friends@eclipse.org">friends at eclipse.org</a> with all your contact 
				information and an explanation of what you need corrected.<br/><br/></li>
				
				<li><b>What is the Friends of Eclipse Mirror?</b><br/><br/>
				The Friends of Eclipse mirror is a high-capacity download server for Friends of Eclipse. Unlike our 
				main downloads servers, the Friends mirror has priority access to our bandwidth, typically resulting 
				in faster downloads. Although a local mirror in your country may still provide a faster download due 
				to the proximity,  the Friends mirror is our way of saying 'thank you' for your donation.<br/><br/></li>
				
				<li><b>Does this mean the Eclipse download site will be slower?</b><br/><br/>
				The Eclipse download site's performance varies from day to day, depending on new project releases. 
				Although Friends' downloads get priority bandwidth, the Eclipse Foundation will add bandwidth as 
				required to ensure an adequate level of performance from our main download site.<br/><br/></li>
				<li><b>How do I get access to the Friends of Eclipse mirror?</b><br/><br/>
				Everyone that donates US$35 or more to the Eclipse Foundation will automatically be flagged as a 
				Friend of Eclipse.  This gives them access to the Friends of Eclipse Mirror for 12 months.   
				When you register your donation, you will be asked to enter your Eclipse.org account email address.  This will be used 
				to login to the Friends of Eclipse Mirror.<br/><br/></li>
				
				<li><b>Who can use the Friend of Eclipse Logo?</b><br/><br/>
				Any individual that has donated more than US$35 to the Eclipse Foundation may use the Friend of 
				Eclipse logo on a personal blog or web site.  The Friends of Eclipse program is for individuals, so 
				the logo should not be used on an organization web site. <br/><br/></li>	
			</ul>
		</div>
	</div>
	<div id="rightcolumn">
		<div class="sideitem">
		<h6>Related Links</h6>
			<ul>
				<li><a href="index.php">Friends of Eclipse</a></li>
				<li><a href="donorlist.php">Public List of Donors</a></li>
			</ul>
		</div>
	</div>
	</div>	
<? $html = ob_get_clean();
	# Generate the web page
	$App->generatePage('Nova', $Menu, NULL, $pageAuthor, $pageKeywords, $pageTitle, $html);
?>