<?php
require_once 'session.php';
require_once 'handlers/userhandler.php';
require_once 'handlers/seatmaphandler.php';
require_once 'handlers/eventhandler.php';
require_once 'handlers/tickethandler.php';

$id = isset($_GET['id']) ? $_GET['id'] : Session::getCurrentUser()->getId();

if (Session::isAuthenticated()) {
	$user = Session::getCurrentUser();
	$profileUser = UserHandler::getUser($id);
	
	if ($profileUser != null) {
		if ($user->hasPermission('*') ||
			$user->hasPermission('search.users') ||
			$user->equals($profileUser)) {
			echo '<link rel="stylesheet" href="../api/styles/seatmap.css">';

			echo '<h3>' . $profileUser->getDisplayName(). '</h3>';
			echo '<table style="float: left;">';
				if ($user->hasPermission('*')) {
					echo '<tr>';
						echo '<td>Id:</td>';
						echo '<td>' . $profileUser->getId() . '</td>';
					echo '</tr>';
				}

				echo '<tr>';
					echo '<td>Navn:</td>';
					echo '<td>' . $profileUser->getFullName() . '</td>';
				echo '</tr>';
				echo '<tr>';
					echo '<td>Brukernavn:</td>';
					echo '<td>' . $profileUser->getUsername() . '</td>';
				echo '</tr>';
				echo '<tr>';
					echo '<td>E-post:</td>';
					echo '<td><a href="mailto:' . $profileUser->getEmail() . '">' . $profileUser->getEmail() . '</a></td>';
				echo '</tr>';
				echo '<tr>';
					echo '<td>Fødselsdato</td>';
					echo '<td>' . date('d.m.Y', $profileUser->getBirthdate()) . '</td>';
				echo '</tr>';
				echo '<tr>';
					echo '<td>Kjønn:</td>';
					echo '<td>' . $profileUser->getGenderAsString() . '</td>';
				echo '</tr>';
				echo '<tr>';
					echo '<td>Alder:</td>';
					echo '<td>' . $profileUser->getAge() . ' år</td>';
				echo '</tr>';
				echo '<tr>';
					echo '<td>Telefon:</td>';
					echo '<td>' . $profileUser->getPhoneAsString() . '</td>';
				echo '</tr>';
				echo '<tr>';
					echo '<td>Adresse:</td>';
						$address = $profileUser->getAddress();
						
						if (!empty($address)) {
							echo '<td>' . $address . '</td>';
						} else {
							echo '<td><i>Ikke oppgitt</i></td>';
						}
				echo '</tr>';
			
				$postalCode = $profileUser->getPostalCode();
				
				if ($postalCode != 0) {
					echo '<tr>';
						echo '<td></td>';
						echo '<td>' . $postalCode . ' ' . $profileUser->getCity() . '</td>';
					echo '</tr>';
				}
				
				echo '<tr>';
					echo '<td>Kallenavn:</td>';
					echo '<td>' . $profileUser->getNickname() . '</td>';
				echo '</tr>';
				
				if ($profileUser->hasEmergencyContact()) {
					echo '<tr>';
						echo '<td>Foresatte\'s telefon:</td>';
						echo '<td>' . $profileUser->getEmergencyContact()->getPhoneAsString() . '</td>';
					echo '</tr>';
				}
				
				if ($profileUser->isGroupMember()) {
					echo '<tr>';
						echo '<td>Crew:</td>';
						echo '<td>';
							if ($profileUser->isGroupMember()) {
								echo $profileUser->getGroup()->getTitle();
							} else {
								echo '<i>Ingen</i>';
							}
						echo '</td>';
					echo '</tr>';
					
					if ($profileUser->isTeamMember()) {
						echo '<tr>';
							echo '<td>Lag:</td>';
							echo '<td>';
								
									echo $profileUser->getTeam()->getTitle();
							echo '</td>';
						echo '</tr>';
					}	
				}
				
				$ticketCount = count($profileUser->getTickets());
				
				if (TicketHandler::hasTicket($profileUser)) {
					echo '<tr>';
						echo '<td>Antall billett(er):</td>';
						echo '<td>' . $ticketCount . ' </td>';
					echo '</tr>';
				}
			
				if (TicketHandler::hasTicket($profileUser) &&
					$profileUser->hasSeat()) {
					$ticket = $profileUser->getTicket();
					$seat = $ticket->getSeat();
					$row = $seat->getRow();
					
					echo '<tr>';
						echo '<td>Plass:</td>';
						echo '<td>R' . $row->getNumber() . 'S' . $seat->getNumber() . '</td>';
					echo '</tr>';
				}
				
				if ($user->hasPermission('*')) {
					echo '<tr>';
						echo '<td>Dato registrert:</td>';
						echo '<td>' . date('d.m.Y', $profileUser->getRegisteredDate()) . '</td>';
					echo '</tr>';
					echo '<tr>';
						echo '<td>Aktivert:</td>';
						echo '<td>';
							echo $profileUser->isActivated() ? 'Ja' : 'Nei';
						echo '</td>';
					echo '</tr>';
				}
				
				if ($user->hasPermission('*') ||
					$user->equals($profileUser)) {
					echo '<tr>';
						echo '<td></td>';
						echo '<td>';
							echo '<a href="index.php?page=edit-profile&id=' . $profileUser->getId() . '">Endre bruker</a> ';
						echo '</td>';
					echo '</tr>';
				}
				
				if ($user->equals($profileUser)) {
					echo '<tr>';
						echo '<td></td>';
						echo '<td>';
							echo '<a href="index.php?page=edit-avatar">Endre avatar</a> ';
						echo '</td>';
					echo '</tr>';
				}
					
				if ($user->hasPermission('*') ||
					$user->hasPermission('admin.permissions')) {
					echo '<tr>';
						echo '<td></td>';
						echo '<td>';
							echo '<a href="index.php?page=admin-permissions&id=' . $profileUser->getId() . '">Endre rettigheter</a> ';
						echo '</td>';
					echo '</tr>';
				}
			echo '</table>';
			
			$avatarFile = null;
			
			if ($profileUser->hasValidAvatar()) {
				$avatarFile = $profileUser->getAvatar()->getHd();
			} else {
				$avatarFile = AvatarHandler::getDefaultAvatar($profileUser);
			}
		
			echo '<img src="../api/' . $avatarFile . '" width="550px" style="float: right;">';

			if (($user->hasPermission('*') ||
				$user->hasPermission('search.users') ||
				$user->hasPermission('chief.tickets')) && 
				TicketHandler::hasTicket($profileUser)) {
				$ticket = $profileUser->getTicket();
				$seat = $ticket->getSeat();

				echo '<h3>Omplasser bruker</h3>';
				echo '<div id="seatmapCanvas"></div>';
				echo '<script src="../api/scripts/seatmapRenderer.js"></script>';
				echo '<script src="scripts/my-profile.js"></script>';

				echo '<script>';
					echo 'var seatmapId = ' . $ticket->getEvent()->getSeatmap()->getId() . ';';
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