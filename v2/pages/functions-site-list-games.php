<?php
require_once 'scripts/siteDatabase.php';
require_once 'scripts/utils.php';

$database = new SiteDatabase();
$utils = new Utils();

$site = 'https://infected.no/v7/';
$returnPage = basename(__FILE__, '.php');

if ($utils->isAuthenticated()) {
	$user = $utils->getUser();

	if ($user->hasPermission('functions.site-list-games') || 
		$user->getGroup()->getId() == 26 ||
		$user->hasPermission('admin') || 
		$user->hasPermission('site-admin')) {
		echo '<h3>Spill:</h3>';
		
		$gameList = $database->getGames();
		
		if (!empty($gameList)) {
			echo '<table>';
				echo '<tr>';
					echo '<th>Navn</th>';
					echo '<th>Pris</th>';
					echo '<th>Mode</th>';
					echo '<th>Beskrivelse</th>';
					echo '<th>Påmeldingsfrist</th>';
				echo '</tr>';
				
				foreach ($gameList as $game) {
					echo '<tr>';
						echo '<form action="scripts/process_game.php?action=3&id=' . $game->getId() . '&returnPage=' . $returnPage . '" method="post">';
							echo '<td><input type="text" name="title" value="' . $game->getTitle() . '"></td>';
							echo '<td><input type="text" name="price" value="' . $game->getPrice() . '"></td>';
							echo '<td><input type="text" name="mode" value="' . $game->getMode() . '"></td>';
							echo '<td><input type="text" name="description" value="' . $game->getDescription() . '"></td>';
							echo '<td>Den <input type="date" name="deadlineDate" value="' . date('Y-m-d', $game->getDeadline()) . '"> klokken <input type="time" name="deadlineTime" value="' . date('H:i', $game->getDeadline()) . '"></td>';
							if ($game->isPublished()) {
								echo '<td><input type="checkbox" name="published" value="1" checked></td>';
							} else {
								echo '<td><input type="checkbox" name="published" value="1"></td>';
							}
							echo '<td><input type="submit" value="Endre"></td>';
						echo '</form>';
						
						if ($user->isGroupChief() || 
							$user->hasPermission('admin') || 
							$user->hasPermission('site-admin')) {
							echo '<form action="scripts/process_game.php?action=2&id=' . $game->getId() . '&returnPage=' . $returnPage . '" method="post">';
								echo '<td><input type="submit" value="Slett"></td>';
							echo '</form>';
						}
					echo '</tr>';
				}
			echo '</table>';
		}
		
		if ($user->isGroupChief() || 
			$user->hasPermission('admin') || 
			$user->hasPermission('site-admin')) {
			echo '<form action="scripts/process_game.php?action=1&returnPage=' . $returnPage . '" method="post">';
				echo '<table>';
					echo '<tr>';
						echo '<td>Navn:</td>';
						echo '<td><input type="text" name="title"></td>';
						echo '<td>(Full tittel på spillet).</td>';
					echo '</tr>';
					echo '<tr>';
						echo '<td>Premie:</td>';
						echo '<td><input type="text" name="price"></td>';
						echo '<td>kr</td>';
					echo '</tr>';
					echo '<tr>';
						echo '<td>Modus:</td>';
						echo '<td><input type="text" name="mode"></td>';
						echo '<td>(Hvilket oppsett har vi? Eks. 1on1).</td>';
					echo '</tr>';
					echo '<tr>';
						echo '<td>Beskrivelse:</td>';
						echo '<td><input type="text" name="description"></td>';
						echo '<td>(Ekstrainformasjon som vises bak premie på hovedsiden).</td>';
					echo '</tr>';
					echo '<tr>';
						echo '<td>Registreringsfrist:</td>';
						echo '<td><input type="date" name="deadlineDate" placeholder="dd.mm.åååå"></td>';
						echo '<td><input type="time" name="deadlineTime" placeholder="time:minutt"></td>';
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
		}

		echo '<h3>Compoer:</h3>';
		
		if (!empty($gameList)) {
			foreach ($gameList as $game) {
				$gameApplicationList = $database->getGameApplications($game->getId());
				
				echo '<h3><a href="' . $site . 'index.php?viewPage=game&id=' . $game->getId() . '">' . $game->getTitle() . '</a></h3>';
				echo '<table>';
				
				if (!empty($gameApplicationList)) {
					echo '<tr>';
						echo '<th>Clan:</th>';
						echo '<th>Tag:</th>';
						echo '<th>Navn:</th>';
						echo '<th>Nick:</th>';
						echo '<th>Telefon:</th>';
						echo '<th>E-post:</th>';
					echo '</tr>';
					
					foreach ($gameApplicationList as $value) {
						echo '<tr>';
							echo '<td>' . $value->getName() . '</td>';
							echo '<td>' . $value->getTag() . '</td>';
							echo '<td>' . $value->getContactname() . '</td>';
							echo '<td>' . $value->getContactnick() . '</td>';
							echo '<td>' . $value->getPhone() . '</td>';
							echo '<td>' . $value->getEmail() . '</td>';
							
							if ($user->isGroupChief() || 
								$user->hasPermission('admin') || 
								$user->hasPermission('site-admin')) {
								echo '<form name="input" action="scripts/process_gameApplication.php?action=2&id=' . $value->getId() . '&returnPage=' .  $returnPage . '" method="post">';
									echo '<td><input type="submit" value="Slett"></td>';
								echo '</form>';
							}
						echo '</tr>';
					}
				} else {
					echo '<tr>';
						echo '<td>Ingen har meldt seg på compo i <i>' . $game->getTitle() . '</i> enda.</td>';
					echo '</tr>';
				}
				
				echo '</table>';
			}
		} else {
			echo 'Ingen spill er registrert enda.';
		}
	} else {
		echo 'Du har ikke rettigheter til dette!';
	}
} else {
	echo 'Du er ikke logget inn!';
}
?>