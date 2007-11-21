<?php
/*******************************************************************************
 * Copyright (c) 2006 Eclipse Foundation and others.
 * All rights reserved. This program and the accompanying materials
 * are made available under the terms of the Eclipse Public License v1.0
 * which accompanies this distribution, and is available at
 * http://www.eclipse.org/legal/epl-v10.html
 *
 * Contributors:
 *    Denis Roy (Eclipse Foundation)- initial API and implementation
 *******************************************************************************/
require_once($_SERVER['DOCUMENT_ROOT'] . "/eclipse.org-common/system/smartconnection.class.php");
require_once("/home/data/httpd/eclipse-php-classes/system/dbconnection_bugs_ro.class.php");
require_once("/home/data/httpd/eclipse-php-classes/system/dbconnection_rw.class.php");

class Friend {

	private $friend_id 		= 0;
	private $bugzilla_id	= "";
	private $first_name		= "";
	private $last_name		= "";
	private $date_joined	= "";
	private $is_anonymous	= 0;
	private $is_benefit		= 0;


	function getFriendID() {
		return $this->friend_id;
	}
	function getBugzillaID() {
		return $this->bugzilla_id;
	}
	function getFirstName() {
		return $this->first_name;
	}
	function getLastName() {
		return $this->last_name;
	}
	function getDateJoined() {
		return $this->date_joined;
	}
	function getIsAnonymous() {
		return $this->is_anonymous;
	}
	function getIsBenefit() {
		return $this->is_benefit;
	}
	


	function setFriendID($_friend_id) {
		$this->friend_id = $_friend_id;
	}
	function setBugzillaID($_bugzilla_id) {
		$this->bugzilla_id = $_bugzilla_id;
	}
	function setFirstName($_first_name) {
		$this->first_name = $_first_name;
	}
	function setLastName($_last_name) {
		$this->last_name = $_last_name;
	}
	function setDateJoined($_date_joined) {
		$this->date_joined = $_date_joined;
	}
	function setIsAnonymous($_is_anonymous) {
		$this->is_anonymous = $_is_anonymous;
	}
	function setIsBenefit($_is_benefit) {
		$this->is_benefit = $_is_benefit;
	}
	
	function insertUpdateFriend() {
		$result = 0;

		$App = new App();
		#$ModLog = new ModLog();
		#$ModLog->setLogTable("Person");
		#$ModLog->setPK1($this->getPersonID());

		$dbc = new DBConnectionRW();
		$dbh = $dbc->connect();

		if($this->selectFriendExists("friend_id", $this->getFriendID())) {
			# update
			$sql = "UPDATE friends SET
						bugzilla_id = " . $App->returnQuotedString($this->getBugzillaID()) . ",
						first_name = " . $App->returnQuotedString($this->getFirstName()) . ",
						last_name = " . $App->returnQuotedString($this->getLastName()) . ",
						date_joinded = " . $App->returnQuotedString($this->getDateJoined()) . ",
						is_anonymous = " . $App->returnQuotedString($this->getIsAnonymous()) . ",
						is_benefit = " . $App->returnQuotedString($this->getIsBenefit()) . "
					WHERE
						friend_id = " . $this->getFriendID();

				mysql_query($sql, $dbh);
				$result = $this->friend_id;
				#$ModLog->setLogAction("UPDATE");
				#$ModLog->insertModLog();

				# Set the Primary Employer ID
		}
		else {
			# insert
			$sql = "INSERT INTO friends (
						bugzilla_id,
						first_name,
						last_name,
						date_joined,
						is_anonymous,
						is_benefit)
					VALUES (
						" . $App->returnQuotedString($this->getBugzillaID()) . ",
						" . $App->returnQuotedString($this->getFirstName()) . ",
						" . $App->returnQuotedString($this->getLastName()) . ",
						" . $App->returnQuotedString($this->getDateJoined()) . ",
						" . $App->returnQuotedString($this->getIsAnonymous()) . ",
						" . $App->returnQuotedString($this->getIsBenefit()) . ")";
			mysql_query($sql, $dbh);
			$result = mysql_insert_id($dbh);
			#$ModLog->setLogAction("INSERT");
			#$ModLog->insertModLog();
		}

		$dbc->disconnect();
	return $result;
	}


	function selectFriend($_friend_id) {

		if($_friend_id != "") {
			$App = new App();

			$dbc = new DBConnectionRW();
			$dbh = $dbc->connect();

			$sql = "SELECT friend_id,
							bugzilla_id,
							first_name,
							last_name,
							date_joined,
							is_anonymous,
							is_benefit
					FROM friends 
					WHERE friend_id = " . $App->returnQuotedString($_friend_id);

			$result = mysql_query($sql, $dbh);

			if ($myrow = mysql_fetch_array($result))	{
				$this->setFriendID		($myrow["friend_id"]);
				$this->setBugzillaID	($myrow["bugzilla_id"]);
				$this->setFirstName		($myrow["first_name"]);
				$this->setLastName		($myrow["last_name"]);
				$this->setDateJoined	($myrow["date_joined"]);
				$this->setIsAnonymous	($myrow["is_anonymous"]);
				$this->setIsBenefit		($myrow["is_benefit"]);
			}
			$dbc->disconnect();
		}
	}
	
	function selectFriendExists($_fieldname, $_searchfor) {
		$result = 0;

		if( ($_fieldname != "") && ($_searchfor != "")) {
			$App = new App();

			$dbc = new DBConnectionRW();
			$dbh = $dbc->connect();

			$sql = "SELECT friend_id
					FROM friends
					WHERE $_fieldname = " . $App->returnQuotedString($_searchfor);

			$result = mysql_query($sql, $dbh);
			if ($result){
				$myrow = mysql_fetch_array($result);
				$result = $myrow['RecordCount'] > 1 ? $myrow['friend_id'] : 0;
			}

			$dbc->disconnect();

		}
		return $result;
	}
	
	function getBugzillaIDFromEmail($_email) {
		$result = 0;

		if($_email != "") {
			$App = new App();

			$dbc = new DBConnectionBugs();
			$dbh = $dbc->connect();

			$sql = "SELECT userid
					FROM profiles
					WHERE login_name = " . $App->returnQuotedString($_email);

			$result = mysql_query($sql, $dbh);
			$myrow = mysql_fetch_array($result);

			$result = $myrow['userid'];
			$dbc->disconnect();
		}
		return $result;
	}


	function authenticate($email, $password) {
	/**
	 * Authenticate user using bugzilla credentials
	 * 
	 * @author droy
	 * @param string Email address
	 * @param string password
	 * @return boolean
	 * @since 2007-11-20
	 * 
	 */
		$rValue = false;
		
		if($email != "" && $password != "") {
			if (eregi('^[a-zA-Z0-9._-]+@[a-zA-Z0-9._-]+\.[a-zA-Z.]{2,5}$', $email)) {
				$sql = "SELECT
							userid,
							LEFT(realname, @loc:=LENGTH(realname) - LOCATE(' ', REVERSE(realname))) AS first_name, 
							SUBSTR(realname, @loc+2) AS last_name
					FROM 
						profiles 
					WHERE login_name = '$email' 
						AND cryptpassword = ENCRYPT('$password', cryptpassword)
						AND disabledtext = ''";
				$dbc = new DBConnectionBugs();
				$dbh = $dbc->connect();
				$result = mysql_query($sql, $dbh);
				if($result && mysql_num_rows($result) > 0) {
					$rValue = true;
					$myrow = mysql_fetch_assoc($result);
					$this->setBugzillaID($myrow['userid']);
					$this->setFirstName($myrow['first_name']);
					$this->setLastName($myrow['last_name']);					
				}
				$dbc->disconnect();
			}
		}
		
		return $rValue;
	}
}
?>