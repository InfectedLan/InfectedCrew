<?php
require_once 'session.php';
require_once 'handlers/pagehandler.php';

$site = 'http://infected.no/v7/';

if (Session::isAuthenticated()) {
	$user = Session::getCurrentUser();
	
	if ($user->hasPermission('*') ||
		$user->hasPermission('admin.website')) {
		$pageList = PageHandler::getPages();
		echo '<script src="scripts/admin-website.js"></script>';
		echo '<h3>Sider:</h3>';
		
		echo '<table>';	
			// Loop through the pages.
			foreach ($pageList as $page) {
				// Add the current page to the page view.
				echo '<tr>';
					echo '<td>' . $page->getTitle() . '</td>';
					echo '<td><a href="' . $site . 'pages/' . $page->getName() . '.html">Vis</a></td>';
					echo '<td><input type="button" value="Endre" onClick="editPage(' . $page->getId() . ')"></td>';
					
					if ($user->hasPermission('*')) {
						echo '<td><input type="button" value="Slett" onClick="removePage(' . $page->getId() . ')"></td>';
					}
				echo '</tr>';
			}
		echo '</table>';
		
		echo '<h3>Legg til ny side:</h3>';
		echo '<p>Fyll ut feltene under for å legge til en ny side.</p>';
		echo '<p>For å få innholdet i bokser, kan du bruke HTML-kode.<br>';
		echo 'Du putter hvilken type boks du vil inn i feltet "class", du finner alle tyoer bokser i tabellen under: <br>';
		echo '&lt;article class="Putt type boks inn her!"&gtInnhold her&lt;/article&gt</pre></p><br>';
		
		echo '<table>';
			echo '<tr>';
				echo '<th>Type boks:</th>';
				echo '<th>Kode:</th>';
			echo '</tr>';
			echo '<tr>';
				echo '<td>Tekst</td>';
				echo '<td>contentBox</td>';
			echo '</tr>';
			echo '<tr>';
				echo '<td>Venstre-stillt tekst</td>';
				echo '<td>contentLeftBox</td>';
			echo '</tr>';
			echo '<tr>';
				echo '<td>Høyre-stillt tekst</td>';
				echo '<td>contentRightBox</td>';
			echo '</tr>';
			echo '<tr>';
				echo '<td>Venstre-stillt bilde</td>';
				echo '<td>contentLeftImageBox</td>';
			echo '</tr>';
			echo '<tr>';
				echo '<td>Høyre-stillt bilde</td>';
				echo '<td>contentRightImageBox</td>';
			echo '</tr>';
		echo '</table>';
		
		echo '<form class="fadmin-website-add" method="post">';
			echo '<table>';
				echo '<tr>';
					echo '<td>Tittel:</td>';
					echo '<td><input type="text" name="title"></td>';
				echo '</tr>';
				echo '<tr>';
					echo '<td>Tekst:</td>';
					echo '<td><textarea name="content" rows="10" cols="80"></textarea></td>';
				echo '</tr>';
				echo '<tr>';
					echo '<td></td>';
					echo '<td><input type="submit" value="Publiser"></td>';
				echo '</tr>';
			echo '</table>';
		echo '</form>';
	} else {
		echo 'Du har ikke rettigheter til dette!';
	}
} else {
	echo 'Du er ikke logget inn!';
}
?>