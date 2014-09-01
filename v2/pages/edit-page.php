<?php
require_once 'session.php';
require_once 'handlers/restrictedpagehandler.php';

$id = isset($_GET['id']) ? $_GET['id'] : 0;

if (Session::isAuthenticated()) {
	$user = Session::getCurrentUser();
	
	if ($user->hasPermission('*') ||
		$user->hasPermission('functions.my-crew') || 
		$user->hasPermission('functions.edit-page') || 
		$user->isGroupLeader()) {
		if (isset($_GET['id']) &&
			is_numeric($_GET['id'])) {
			$page = RestrictedPageHandler::getPage($id);
				
			if ($page != null) {
				echo '<script src="scripts/edit-page.js"></script>';
				echo '<h3>Du endrer nå siden "' . $page->getTitle() . '"</h3>';
				
				echo '<form class="edit-page-edit" method="post">';
					echo '<input type="hidden" name="id" value="' . $page->getId() . '">';
					echo '<table>';
						echo '<tr>';
							echo '<td>Tittel:</td>';
							echo '<td><input type="text" name="title" value="' . $page->getTitle() . '"> (Dette blir også navnet på linken til siden).</td>';
						echo '</tr>';
					echo '</table>';
					echo '<textarea name="content" id="editor1" rows="10" cols="80">' . $page->getContent() . '</textarea>';
					echo '<script>';
						// Replace the <textarea id="editor1"> with a CKEditor
						// instance, using default configuration.
						echo 'CKEDITOR.replace(\'editor1\');';
					echo '</script>';
					echo '<input type="submit" value="Endre">';
				echo '</form>';
			} else {
				echo '<p>Siden finnes ikke.</p>';
			}
		}
	}
}
?>