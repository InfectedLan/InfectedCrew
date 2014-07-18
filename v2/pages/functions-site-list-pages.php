<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/api/utils.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/api/handlers/mainpagehandler.php';

$site = 'https://infected.no/v7/';
$returnPage = basename(__FILE__, '.php');

if (Utils::isAuthenticated()) {
	$user = Utils::getUser();
	
	if ($user->hasPermission('functions.site-list-pages') || 
		$user->hasPermission('admin') || 
		$user->hasPermission('site-admin')) {
		$pageList = MainPageHandler::getPages();
		
		echo '<article class="contentBox">';
			echo '<h3>Sider:</h3>';
			echo '<table>';	
				// Loop through the pages.
				foreach ($pageList as $value) {
					// Add the current page to the page view.
					echo '<tr>';
						echo '<td>' . $value->getTitle() . '</td>';
						echo '<td><a href="' . $site . 'index.php?viewPage=' . $value->getName() . '">Vis</a></td>';
						echo '<form name="input" action="index.php?page=edit-page&site=0&id=' . $value->getId() . '&returnPage=' . $returnPage . '" method="post">';
							echo '<td><input type="submit" value="Endre"></td>';
						echo '</form>';
						
						if ($user->hasPermission('admin')) {
							echo '<form name="input" action="scripts/process_page.php?site=0&action=2&id=' . $value->getId() . '&returnPage=' . $returnPage . '" method="post">';
								echo '<td><input type="submit" value="Slett"></td>';
							echo '</form>';
						}
					echo '</tr>';
				}
			echo '</table>';
		echo '</article>';
		echo '<article class="contentBox">';
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
			echo '</table><br>';
			echo '<form action="scripts/process_page.php?site=0&action=1&returnPage=' . $returnPage . '" method="post">';
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
		echo '</article>';
	} else {
		echo '<article class="contentBox"';
			echo 'Du har ikke rettigheter til dette!';
		echo '</article>';
	}
} else {
	echo '<article class="contentBox"';
		echo 'Du er ikke logget inn!';
	echo '</article>';
}
?>