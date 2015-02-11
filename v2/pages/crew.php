<?php
require_once 'session.php';
require_once 'handlers/grouphandler.php';
require_once 'handlers/teamhandler.php';

if (Session::isAuthenticated()) {
	$user = Session::getCurrentUser();

	if (isset($_GET['id'])) {
		if ($user->isGroupMember()) {	
			if (isset($_GET['teamId'])) {
				$team = TeamHandler::getTeam($_GET['teamId']);

				if ($team != null) {
					$team->displayWithInfo();
				}
			} else {
				$group = GroupHandler::getGroup($_GET['id']);

				if ($group != null) {
					$group->displayWithInfo();
				}
			}
		} else {
			echo 'Du er ikke i crew.';
		}
	} else {
		$groupList = GroupHandler::getGroups();
		
		foreach ($groupList as $group) {	
			$group->displayWithInfo();
		}
	}
} else {
	echo '<p>Du er ikke logget inn!</p>';
}
?>