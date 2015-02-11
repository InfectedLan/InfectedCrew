<?php
require_once 'session.php';
require_once 'handlers/pagehandler.php';

if (Session::isAuthenticated()) {
	$user = Session::getCurrentUser();
	
	if ($user->hasPermission('*') ||
		$user->hasPermission('admin.website')) {
		if (isset($_GET['id']) &&
			is_numeric($_GET['id'])) {
			$page = PageHandler::getPage($_GET['id']);
				
			if ($page != null) {
				echo '<script src="scripts/edit-page.js"></script>';
				echo '<h3>Du endrer nå siden "' . $page->getTitle() . '"</h3>';
				
				echo '<form class="edit-page" method="post">';
					echo '<input type="hidden" name="id" value="' . $page->getId() . '">';
					echo '<table>';
						echo '<tr>';
							echo '<td>Tittel:</td>';
							echo '<td><input type="text" name="title" value="' . $page->getTitle() . '"> (Dette blir også navnet på linken til siden).</td>';
						echo '</tr>';
					echo '</table>';
					echo '<textarea name="content" rows="10" cols="80">' . $page->getContent() . '</textarea>';
					echo '<input type="submit" value="Endre">';
				echo '</form>';
			} else {
				echo '<p>Siden finnes ikke.</p>';
			}
		}
	}
}
?>