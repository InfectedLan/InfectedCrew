<?php
require_once 'session.php';
require_once 'handlers/restrictedpagehandler.php'; 

if (Session::isAuthenticated()) {
	$user = Session::getCurrentUser();
	
	if ($user->isGroupMember()) {
		if (isset($_GET['teamId'])) {
			$team = TeamHandler::getTeam($_GET['teamId']);
			
			if ($team != null) {
				$page = RestrictedPageHandler::getPageByName($team->getName());
				
				if ($page != null) {
					echo '<h3>' . $page->getTitle() . '</h3>';
					echo $page->getContent();
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
					echo '<h3>' . $page->getTitle() . '</h3>';
					echo $page->getContent();
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