<?php
require_once 'session.php';
require_once 'handlers/restrictedpagehandler.php'; 

$teamId = isset($_GET['teamId']) ? $_GET['teamId'] : 0;

if (Session::isAuthenticated()) {
	$user = Session::getCurrentUser();
	
	if ($user->isGroupMember()) {
		if (isset($_GET['teamId'])) {
			$team = TeamHandler::getTeam($teamId);
			
			if ($team != null) {
				$page = RestrictedPageHandler::getPageByName($team->getName());
				
				if ($page != null) {
					$page->display();
				}
				
				$team->display();
			} else {
				echo '<p>Denne gruppen finnes ikke!</p>';
			}
		} else {
			$group = $user->getGroup();
			
			if ($group != null) {
				$page = RestrictedPageHandler::getPageByName($group->getName());
				
				if ($page != null) {
					$page->display();
				}
				
				$group->display();
			} else {
				echo '<p>Dette crewet finnes ikke!</p>';
			}
		}
	} else {
		echo '<p>Du er ikke i noen gruppe!</p>';
	}
} else {
	echo '<p>Du er ikke logget inn!</p>';
}
?>