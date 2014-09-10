<?php
require_once 'session.php';
require_once 'handlers/restrictedpagehandler.php'; 

if (Session::isAuthenticated()) {
	$user = Session::getCurrentUser();
	
	if ($user->isGroupMember()) {
		if (isset($_GET['teamId'])) {
			$team = TeamHandler::getTeam($_GET['teamId']);
			
			if ($team != null) {
				$team->displayWithInfo();
			} else {
				echo '<p>Dette laget finnes ikke!</p>';
			}
		} else {
			$group = $user->getGroup();
			
			if ($group != null) {
				echo '<h3>' . $group->getTitle() . '</h3>';
			
				$page = RestrictedPageHandler::getPageByName($group->getName());
			
				if ($page != null) {
					echo $page->getContent();
				}
				
				echo  $group->getDescription();
				
				$group->display();
			} else {
				echo '<p>Dette crewet finnes ikke!</p>';
			}
		}
	} else {
		echo '<p>Du er ikke i noe crew!</p>';
	}
} else {
	echo '<p>Du er ikke logget inn!</p>';
}
?>