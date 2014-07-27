<?php
require_once 'session.php';
require_once 'handlers/grouphandler.php';
require_once 'handlers/teamhandler.php';

$groupId = isset($_GET['id']) ? $_GET['id'] : 0;
$teamId = isset($_GET['teamId']) ? $_GET['teamId'] : 0;

if (Session::isAutenticated()) {
	if (isset($_GET['id'])) {
		if (isset($_GET['teamId'])) {
			$team = TeamHandler::getTeam($teamId);
			$team->displayWithInfo();
		} else {
			$group = GroupHandler::getGroup($groupId);
			$group->displayWithInfo();
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