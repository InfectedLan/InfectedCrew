<?php
	require_once 'scripts/database.php';
	require_once 'scripts/utils.php';
	
	$database = new Database();
	$utils = new Utils();
	
	$teamId = isset($_GET['teamId']) ? $_GET['teamId'] : 0;
	
	if ($utils->isAuthenticated()) {
		$user = $utils->getUser();
		
		if ($user->getGroup()->getId() != 0) {
			if (isset($_GET['teamId'])) {
				$team = $database->getTeam($teamId);
				
				if ($team != null) {
					$page = $database->getPageByName($team->getName());
					
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
					$page = $database->getPageByName($group->getName());
					
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