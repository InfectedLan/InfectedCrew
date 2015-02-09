<?php
require_once 'session.php';
require_once 'handlers/restrictedpagehandler.php';

if (Session::isAuthenticated()) {
	$user = Session::getCurrentUser();
	
	if ($user->hasPermission('*') ||
		$user->hasPermission('chief.my-crew')) {
		if (isset($_GET['id']) &&
			is_numeric($_GET['id'])) {
			$page = RestrictedPageHandler::getPage($_GET['id']);
			
			if ($page != null) {
				if ($user->hasPermission('*') ||
					$user->hasPermission('chief.my-crew') &&
					($page->getGroup()->getId() == $user->getGroup()->getId())) {
					echo '<script src="scripts/edit-restricted-page.js"></script>';
					echo '<h3>Du endrer nå siden "' . $page->getTitle() . '"</h3>';
				
					echo '<form class="edit-restricted-page-edit" method="post">';
						echo '<input type="hidden" name="id" value="' . $page->getId() . '">';
						echo '<table>';
							echo '<tr>';
								echo '<td>Tittel:</td>';
								echo '<td><input type="text" name="title" value="' . $page->getTitle() . '"> (Dette blir også navnet på linken til siden).</td>';
							echo '</tr>';
						echo '</table>';
						echo '<textarea name="content" class="editor" rows="10" cols="80">' . $page->getContent() . '</textarea>';
						echo '<input type="submit" value="Endre">';
					echo '</form>';
				} else {
					$message = 'Du har ikke rettighet er til dette.';
				}
			} else {
				echo '<p>Siden finnes ikke.</p>';
			}
		}
	}
}
?>