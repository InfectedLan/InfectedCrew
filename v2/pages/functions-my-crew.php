<?php
require_once 'session.php';
require_once 'handlers/restrictedpagehandler.php';

if (Session::isAuthenticated()) {
	$user = Session::getCurrentUser();
	
	if ($user->isGroupMember()) {
		$group = $user->getGroup();
		
		if ($user->hasPermission('*') || 
			$user->hasPermission('functions.my-crew') || 
			$user->isGroupLeader()) {
			echo '<script src="scripts/functions-my-crew.js"></script>';
			echo '<h3>Mine sider</h3>';
			
			$pageList = RestrictedPageHandler::getPagesForGroup($group->getId());
			
			if (!empty($pageList)) {
				$teamList = $group->getTeams();
				$teamNameList = array();
				
				foreach ($teamList as $team) {
					array_push($teamNameList, strtolower($team->getName()));
				}
				
				echo '<table>';
					// Loop through the pages.
					foreach ($pageList as $page) {
						// Add the current page to the page view.
						
						echo '<tr>';
							if ($page->getName() == strtolower($group->getName())) {
								echo '<td>Hovedside</td>';
							} else{
								echo '<td>' . $page->getTitle() . '</td>';
							}
							
							echo '<td><a href="index.php?page=' . $page->getName() . '">Vis</a></td>';
							echo '<td><input type="button" value="Endre" onClick="editPage(' . $page->getId() . ')"></td>';
							
							if ($page->getName() != strtolower($group->getName()) &&
								!in_array(strtolower($page->getName()), $teamNameList)) {
								echo '<td><input type="button" value="Slett" onClick="removePage(' . $page->getId() . ')"></td>';
							}
						echo '</tr>';
					}
				echo '</table>';
			} else {
				echo '<p>Det er ikke opprettet noen sider enda, du kan legge til en ny side under.</p>';
			}
			
			echo '<h3>Legg til ny side:</h3>';
			echo '<p>Fyll ut feltene under for å legge til en ny side.</p>';
			
			if ($user->isGroupLeader() || 
				$user->hasPermission('*') || 
				$user->hasPermission('crew-admin') ||
				$user->hasPermission('function-my-crew')) {
				echo '<form action="scripts/process_page.php?site=1&action=1&teamId=0&returnPage=' . $returnPage . '" method="post">';
			} else {
				echo '<form action="scripts/process_page.php?site=1&action=1&returnPage=' . $returnPage . '" method="post">';
			}
			
				echo '<table>';
					echo '<tr>';
						echo '<td>Tittel:</td>';
						echo '<td><input type="text" name="title"> (Dette blir også navnet på linken til siden).</td>';
					echo '</tr>';
				echo '</table>';
				
				echo '<textarea id="editor1" name="content" rows="10" cols="80"></textarea>';
				echo '<script>';
					// Replace the <textarea id="editor1"> with a CKEditor
					// instance, using default configuration.
					echo 'CKEDITOR.replace(\'editor1\');';
				echo '</script>';
				echo '<input type="submit" value="Legg til">';
			echo '</form>';
		} else {
			echo '<p>Du har ikke rettigheter til dette!</p>';
		}
	} else {
		echo '<p>Du er ikke medlem av en gruppe.</p>';
	}
} else {
	echo '<p>Du er ikke logget inn!</p>';
}
?>