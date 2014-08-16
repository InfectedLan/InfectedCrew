<?php
require_once 'session.php';
require_once 'handlers/userhandler.php';

$id = isset($_GET['id']) ? $_GET['id'] : Session::getCurrentUser()->getId();

if (Session::isAuthenticated()) {
	$user = Session::getCurrentUser();
	$profile = UserHandler::getUser($id);
	
	if ($profile != null) {
		if ($user->hasPermission('*') ||
			$user->hasPermission('crew-admin') ||
			$user->hasPermission('tickets-admin') ||
			$user->getId() == $profile->getId()) {

			echo '<h1>' . $profile->getFirstname() . ' "' . $profile->getNickname() . '" ' . $profile->getLastname() . '</h1>';
			echo '<img src="' . $profile->getAvatar()->getFile() . '" width="1" height="1" style="margin-left: 50%; position: relative; width: 400px; height: 300px;">';

			echo '<table style="position: relative; width: 50%; top: -300px;">';
				echo '<tr>';
					echo '<td>Navn:</td>';
					echo '<td>' . $profile->getFirstname() . ' ' . $profile->getLastname() . '</td>';
				echo '</tr>';
			
				if ($user->hasPermission('*')) {
					echo '<tr>';
						echo '<td>Brukernavn:</td>';
						echo '<td>' . $profile->getUsername() . '</td>';
					echo '</tr>';
				}
				
				echo '<tr>';
					echo '<td>E-post:</td>';
					echo '<td>' . $profile->getEmail() . '</td>';
				echo '</tr>';
				echo '<tr>';
					echo '<td>Alder:</td>';
					echo '<td>' . $profile->getAge() . ' år</td>';
				echo '</tr>';
				
				if ($user->hasPermission('*')) {
					echo '<tr>';
						echo '<td>Fødselsdato</td>';
						echo '<td>' . date('d.m.Y', $profile->getBirthdate()) . '</td>';
					echo '</tr>';
				}
				
				echo '<tr>';
					echo '<td>Kjønn:</td>';
					echo '<td>' . $profile->getGenderName() . '</td>';
				echo '</tr>';
				
				if ($user->hasPermission('*')) {
					echo '<tr>';
						echo '<td>Telefon:</td>';
						echo '<td>' . $profile->getPhone() . '</td>';
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
					
					if (!isset($postalCode)) {
						echo '<tr>';
							echo '<td></td>';
							echo '<td>' . sprintf("%04d", $postalCode) . ' ' . $profile->getCity() . '</td>';
						echo '</tr>';
					}
				}
				
				echo '<tr>';
					echo '<td>Kallenavn:</td>';
					echo '<td>' . $profile->getNickname() . '</td>';
				echo '</tr>';
				
				if ($user->hasPermission('*') &&
					$profile->hasEmergencyContact()) {
					echo '<tr>';
						echo '<td>Foresatte\'s telefon:</td>';
						echo '<td>' . $profile->getEmergencyContact()->getPhone() . '</td>';
					echo '</tr>';
				}
				
				echo '<tr>';
					echo '<td>Gruppe:</td>';
					echo '<td>';
						if ($profile->isGroupMember()) {
							echo $profile->getGroup()->getTitle();
						} else {
							echo '<i>Ingen</i>';
						}
					echo '</td>';
				echo '</tr>';
				echo '<tr>';
					echo '<td>Lag:</td>';
					echo '<td>';
						if ($profile->isTeamMember()) {
							echo $profile->getTeam()->getTitle();
						} else {
							echo '<i>Ingen</i>';
						}
					echo '</td>';
				echo '</tr>';
			
				if ($user->hasTicket()) {
					$ticket = $user->getTicket();
					$seat = $ticket->getSeat();
					$row = $seat->getRow();
					
					echo '<tr>';
						echo '<td>Sete:</td>';
						echo '<td>' . $seat->getNumber() . ' </td>';
					echo '</tr>';
					echo '<tr>';
						echo '<td>Rad:</td>';
						echo '<td>' . $row->getNumber() . '</td>';
					echo '</tr>';
				}
			
				if ($profile->getId() == $user->getId()) {
					echo '<tr>';
						echo '<td></td>';
						echo '<td><a href="index.php?page=edit-profile">Endre bruker</a></td>';
					echo '</tr>';
				}
			echo '</table>';
		} else {
			echo 'Kun administratorer har lov til å se på vanlige deltagere!';
		}
	} else {
		echo 'Brukeren du spør etter finnes ikke.';
	}
} else {
	echo 'Du er ikke logget inn!';
}
?>