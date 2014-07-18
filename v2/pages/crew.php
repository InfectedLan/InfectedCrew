<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/api/handlers/grouphandler.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/api/handlers/teamhandler.php';

$groupId = isset($_GET['id']) ? $_GET['id'] : 0;
$teamId = isset($_GET['teamId']) ? $_GET['teamId'] : 0;

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
?>