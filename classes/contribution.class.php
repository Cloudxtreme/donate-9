<?php
/*******************************************************************************
 * Copyright (c) 2007 Eclipse Foundation and others.
 * All rights reserved. This program and the accompanying materials
 * are made available under the terms of the Eclipse Public License v1.0
 * which accompanies this distribution, and is available at
 * http://www.eclipse.org/legal/epl-v10.html
 *
 * Contributors:
 *    Nathan Gervais (Eclipse Foundation)- initial API and implementation
 *******************************************************************************/
require_once($_SERVER['DOCUMENT_ROOT'] . "/eclipse.org-common/system/smartconnection.class.php");
require_once("/home/data/httpd/eclipse-php-classes/system/dbconnection_rw.class.php");

class Contribution {
	
	private $friend_id = "";
	private $contribution_id = "";
	private $date_expired = "";
	private $amount = "";
	private $message = "";
	private $transaction_id = "";
	
	function getFriendID(){
		return $this->friend_id;
	}
	function getContributionID(){
		return $this->contribution_id;
	}
	function getDateExpired(){
		return $this->date_expired;
	}
	function getAmount(){
		return $this->amount;
	}
	function getMessage(){
		return $this->message;
	}
	function getTransactionID(){
		return $this->transaction_id;
	}
	
	function setFriendID($_friend_id){
		$this->friend_id = $_friend_id;
	}
	function setContributionID($_contribution_id){
		$this->contribution_id = $_contribution_id;
	}
	function setDateExpired($_date_expired){
		$this->date_expired = $_date_expired;
	}
	function setAmount($_amount){
		$this->amount = $_amount;
	}
	function setMessage($_message){
		$this->message = $_message;
	}
	function setTransactionID($_transaction_id){
		$this->transaction_id = $_transaction_id;
	}
	
	function insertContribution(){
		$result = 0;
		$App = new App();
		$dbc = new DBConnectionRW();
		$dbh = $dbc->connect();
		
		if ($this->selectContributionExists($this->getTransactionID())){
			$result = -1;
		}
		else
		{
			# insert
			$sql = "INSERT INTO friends_contributions (
					friend_id,
					contribution_id,
					date_expired,
					amount,
					message,
					transaction_id)
					VALUES (
					" . $App->returnQuotedString($this->getFriendID()) . ",
					" . $App->returnQuotedString($this->getContributionID()) . ",
					" . $App->returnQuotedString($this->getDateExpired()) . ",
					" . $App->returnQuotedString($this->getAmount()) . ",
					" . $App->returnQuotedString($this->getMessage()) . ",
					" . $App->returnQuotedString($this->getTransactionID()) . ")";
			mysql_query($sql, $dbh);
		}

		$dbc->disconnect();
		return $result;
	}
	
	function selectContributionExists($_transaction_id){
		$result = 0;
		if ($_transaction_id != "")
		{
			$App = new App();

			$dbc = new DBConnectionRW();
			$dbh = $dbc->connect();

			$sql = "SELECT transactionID
					FROM friends_contributions
					WHERE transaction_id = " . $App->returnQuotedString($_transaction_id);

			$result = mysql_query($sql, $dbh);
			if ($result)
			{	
				$myrow = mysql_fetch_array($result);
				$result = $myrow['RecordCount'] > 1 ? 1 : 0;
			}

			$dbc->disconnect();

		}
		return $result;			
	}
	
	function selectContribution($_contribution_id)
	{
		if($_contribution_id != "")  {
			$App = new App();

			$dbc = new DBConnectionRW();
			$dbh = $dbc->connect();

			$sql = "SELECT friend_id,
							contribution_id,
							date_expired,
							amount,
							message,
							transaction,
					FROM friends_contributions 
					WHERE contribution_id = " . $App->returnQuotedString($_contribution_id);

			$result = mysql_query($sql, $dbh);

			if ($myrow = mysql_fetch_array($result))	{
				$this->setFriendID			($myrow["friend_id"]);
				$this->setContributionID	($myrow["contribution_id"]);
				$this->setDateExpired		($myrow["date_expired"]);
				$this->setAmount			($myrow["amount"]);
				$this->setMessage			($myrow["message"]);
				$this->setTransactionID		($myrow["transaction_id"]);
			}
			$dbc->disconnect();
		}
	}
}