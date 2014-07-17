<?php
require_once 'scripts/siteDatabase.php';
require_once 'scripts/database.php';
require_once 'scripts/utils.php';

$site = isset($_GET['site']) ? $_GET['site'] : 0;

if ($site == 0) {
	$database = new SiteDatabase();
} else if ($site == 1) {
	$database = new Database();
}

$utils = new Utils();

$id = isset($_GET['id']) ? $_GET['id'] : 0;
$returnPage = isset($_GET['returnPage']) ? $_GET['returnPage'] : '.';

if (isset($_GET['returnPage']) && $utils->isAuthenticated()) {
	$user = $utils->getUser();
	
	if ($user->hasPermission('functions.site-list-pages') || 
		$user->hasPermission('functions.mycrew') || 
		$user->hasPermission('functions.edit-page') || 
		$user->isGroupChief() || 
		$user->hasPermission('admin') || 
		$user->hasPermission('site-admin') || 
		$user->hasPermission('crew-admin')) {
		if (isset($_GET['site']) && isset($_GET['id'])) {
			$page = $database->getPage($id);
			
			echo '<h3>Du endrer nå siden "' . $page->getTitle() . '"</h3>';
			
			echo '<form action="scripts/process_page.php?site=' . $site . '&action=3&id=' . $page->getId() . '&returnPage=' . $returnPage . '" method="post">';
				echo '<table>';
					echo '<tr>';
						echo '<td>Tittel:</td>';
						echo '<td><input type="text" name="title" value="' . $page->getTitle() . '"> (Dette blir også navnet på linken til siden).</td>';
					echo '</tr>';
				echo '</table>';
				
				if ($site == 0) {
					echo '<textarea name="content" rows="10" cols="80">' . $page->getContent() . '</textarea>';
				} else if ($site == 1) {
					echo '<textarea id="editor1" name="content" rows="10" cols="80">' . $page->getContent() . '</textarea>';
					echo '<script>';
						// Replace the <textarea id="editor1"> with a CKEditor
						// instance, using default configuration.
						echo 'CKEDITOR.replace(\'editor1\');';
					echo '</script>';
				}
				
				echo '<input type="submit" value="Endre">';
			echo '</form>';
		}
	}
}
?>