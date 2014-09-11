<?php
require_once 'session.php';
require_once 'handlers/userhandler.php';
require_once 'handlers/seatmaphandler.php';

echo '<link rel="stylesheet" href="../api/style/seatmap.css">';

$id = isset($_GET['id']) ? $_GET['id'] : Session::getCurrentUser()->getId();

if (Session::isAuthenticated()) {
	$user = Session::getCurrentUser();
	$profile = UserHandler::getUser($id);
	
	if ($profile != null) {
		if ($user->hasPermission('*') ||
			$user->hasPermission('functions.search-users') ||
			$user->getId() == $profile->getId()) {
			echo '<script src="scripts/my-profile.js"></script>';

			echo '<h3>' . $profile->getDisplayName(). '</h3>';
			echo '<table style="float: left;">';
				echo '<tr>';
					echo '<td>Navn:</td>';
					echo '<td>' . $profile->getFullName() . '</td>';
				echo '</tr>';
				echo '<tr>';
					echo '<td>Brukernavn:</td>';
					echo '<td>' . $profile->getUsername() . '</td>';
				echo '</tr>';
				echo '<tr>';
					echo '<td>E-post:</td>';
					echo '<td><a href="mailto:' . $profile->getEmail() . '">' . $profile->getEmail() . '</a></td>';
				echo '</tr>';
				echo '<tr>';
					echo '<td>Fødselsdato</td>';
					echo '<td>' . date('d.m.Y', $profile->getBirthdate()) . '</td>';
				echo '</tr>';
				echo '<tr>';
					echo '<td>Kjønn:</td>';
					echo '<td>' . $profile->getGenderName() . '</td>';
				echo '</tr>';
				echo '<tr>';
					echo '<td>Alder:</td>';
					echo '<td>' . $profile->getAge() . ' år</td>';
				echo '</tr>';
				echo '<tr>';
					echo '<td>Telefon:</td>';
					echo '<td>' . $profile->getPhoneString() . '</td>';
				echo '</tr>';
				echo '<tr>';
					echo '<td>Adresse:</td>';
						$address = $profile->getAddress();
						
						if (!empty($address)) {
							echo '<td>' . $address . '</td>';
						} else {
							echo '<td><i>Ikke oppgitt</i></td>';
						}
				echo '</tr>';
			
				$postalCode = $profile->getPostalCode();
				
				if ($postalCode != 0) {
					echo '<tr>';
						echo '<td></td>';
						echo '<td>' . $postalCode . ' ' . $profile->getCity() . '</td>';
					echo '</tr>';
				}
				
				echo '<tr>';
					echo '<td>Kallenavn:</td>';
					echo '<td>' . $profile->getNickname() . '</td>';
				echo '</tr>';
				
				if ($profile->hasEmergencyContact()) {
					echo '<tr>';
						echo '<td>Foresatte\'s telefon:</td>';
						echo '<td>' . $profile->getEmergencyContact()->getPhoneString() . '</td>';
					echo '</tr>';
				}
				
				if ($profile->isGroupMember()) {
					echo '<tr>';
						echo '<td>Crew:</td>';
						echo '<td>';
							if ($profile->isGroupMember()) {
								echo $profile->getGroup()->getTitle();
							} else {
								echo '<i>Ingen</i>';
							}
						echo '</td>';
					echo '</tr>';
					
					if ($profile->isTeamMember()) {
						echo '<tr>';
							echo '<td>Lag:</td>';
							echo '<td>';
								
									echo $profile->getTeam()->getTitle();
							echo '</td>';
						echo '</tr>';
					}	
				}
				
				$ticketCount = count($profile->getTickets());
				
				if ($profile->hasTicket()) {
					echo '<tr>';
						echo '<td>Antall billett(er):</td>';
						echo '<td>' . $ticketCount . ' </td>';
					echo '</tr>';
				}
			
				if ($profile->hasTicket() &&
					$profile->hasSeat()) {
					$ticket = $profile->getTicket();
					$seat = $ticket->getSeat();
					$row = $seat->getRow();
					
					echo '<tr>';
						echo '<td>Plass:</td>';
						echo '<td>R' . $row->getNumber() . 'S' . $seat->getNumber() . '</td>';
					echo '</tr>';
				}
				
				if ($user->hasPermission('*') ||
					$profile->getId() == $user->getId()) {
					echo '<tr>';
						echo '<td></td>';
						echo '<td><a href="index.php?page=edit-profile&id=' . $profile->getId() . '">Endre bruker</a></td>';
					echo '</tr>';
				}
				
				if ($profile->getId() == $user->getId()) {
					echo '<tr>';
						echo '<td></td>';
						echo '<td><a href="index.php?page=edit-avatar">Endre avatar</a></td>';
					echo '</tr>';
				}
					
				if ($user->hasPermission('*') ||
					$user->hasPermission('admin.permissions')) {
					echo '<tr>';
						echo '<td></td>';
						echo '<td><a href="index.php?page=admin-permissions&id=' . $profile->getId() . '">Endre rettigheter</a></td>';
					echo '</tr>';
				}
				
				if ($user->hasPermission('*')) {
					echo '<tr>';
						echo '<td></td>';
						echo '<td><td><input type="button" value="Slett" onClick="removeUser(' . $profile->getId() . ')"></td>';
					echo '</tr>';
				}
			echo '</table>';
			
			$avatarFile = null;
			
			if ($profile->hasValidAvatar()) {
				$avatarFile = $profile->getAvatar()->getHd();
			} else {
				$avatarFile = AvatarHandler::getDefaultAvatar($profile);
			}
		
			echo '<img src="../api/' . $avatarFile . '" width="550px" style="float: right;">';

			if ( ($user->hasPermission('*') ||
					$user->hasPermission('chief.tickets') ) && $profile->hasTicket() )  {

				$ticket = $profile->getTicket();
				$seat = $ticket->getSeat();
				echo '<br />';
				echo '<br />';
				echo '<h3>Omplasser bruker</h3>';
				echo '<div id="seatmapCanvas"></div>';
				echo '<script src="../api/scripts/seatmapRenderer.js"></script>';

				echo '<script>';
					echo 'var seatmapId = ' . SeatmapHandler::getSeatmap($ticket->getEvent()->getSeatmap())->getId() . ';';
					echo 'var ticketId = ' . $ticket->getId() . ';';
					echo '$(document).ready(function() {';
						echo 'downloadAndRenderSeatmap("#seatmapCanvas", seatHandlerFunction, callback);';
					echo '});';
				echo '</script>';
			}
		} else {
			echo '<p>Du har ikke rettigehter til dette.</p>';
		}
	} else {
		echo '<p>Brukeren du ser etter finnes ikke.</p>';
	}
} else {
	echo '<p>Du er ikke logget inn!</p>';
}
?>