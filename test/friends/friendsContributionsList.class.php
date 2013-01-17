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
require_once("friendsContributions.class.php");


class FriendsContributionsList {
	
	var $list = array();
	function getList() {
		return $this->$list;
	} 
	function setList($_list) {
		$this->list = $_list;
	}
	function add($_contribution) {
            $this->list[count($this->list)] = $_contribution;
    }
    function getCount() {
            return count($this->list);
    }

    function getItemAt($_pos) {
            if($_pos < $this->getCount()) {
                    return $this->list[$_pos];
            }
    }    
    
	function selectFriendsContributionsList($_start = -1, $_numrows = -1, $_where=NULL) {
		
		$App = new App();
		# the IF() for date_expired is a bad hack to accommodate the donor list, should we decide to extend all our friends by one month 
	    $sql = "SELECT 
	    		F.first_name,
	    		F.last_name,
	    		F.bugzilla_id,
	    		F.is_anonymous,
	    		F.is_benefit,
	    		F.date_joined,
				FC.friend_id,
	    		IF(FC.date_expired > DATE_ADD(NOW(), INTERVAL 1 YEAR), DATE_SUB(FC.date_expired, INTERVAL 1 MONTH), FC.date_expired) AS date_expired,
	    		FC.contribution_id,
	    		FC.transaction_id,
	    		FC.amount,
	    		FC.message
	    		FROM chris_friends_contributions as FC
	    		LEFT JOIN friends as F on FC.friend_id = F.friend_id";
	    if ($_where != NULL) {
	    	$sql .= " " . $_where;
	    }
	    		$sql .= " ORDER by date_expired DESC";
	    if ($_start >= 0)
	    {
			$sql .= " LIMIT $_start";
			if ($_numrows > 0)
				$sql .= ", $_numrows";
	    }
	    
	    $App->sqlSanitize($sql);
	    $result = $App->eclipse_sql($sql);
		
	    while($myrow = mysql_fetch_array($result))
	    {
			$Friend = new Friend();
			$Friend->setFriendID			($myrow['friend_id']);
			$Friend->setBugzillaID			($myrow['bugzilla_id']);
			$Friend->setDateJoined			($myrow['date_joined']);
			$Friend->setFirstName			($myrow['first_name']);
			$Friend->setLastName			($myrow['last_name']);
			$Friend->setIsAnonymous			($myrow['is_anonymous']);
			$Friend->setIsBenefit			($myrow['is_benefit']);
	    	
			$Contribution = new Contribution();
			$Contribution->setFriendID($myrow['friend_id']);
			$Contribution->setContributionID($myrow['contribution_id']);
			$Contribution->setDateExpired($myrow['date_expired']);
			$Contribution->setMessage($myrow['message']);
			$Contribution->setAmount($myrow['amount']);
			$Contribution->setTransactionID($myrow['transaction_id']);			
				
			$FriendsContributions = new FriendsContributions();
			$FriendsContributions->setFriendID($myrow['friend_id']);
			$FriendsContributions->setContributionID($myrow['contribution_id']);
			$FriendsContributions->setFriendObject($Friend);
			$FriendsContributions->setContributionObject($Contribution);
			
            $this->add($FriendsContributions);
	    }
	    	
	    $result = null;
	    $myrow	= null;
	}

    
}