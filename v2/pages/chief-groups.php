<?php
require_once 'session.php';
require_once 'handlers/userhandler.php';
require_once 'handlers/grouphandler.php';

if (Session::isAuthenticated()) {
	$user = Session::getCurrentUser();
	
	if ($user->hasPermission('*') ||
		$user->hasPermission('chief.groups') ||
		$user->isGroupLeader()) {
		$groupList = GroupHandler::getGroups();
		echo '<script src="scripts/chief-groups.js"></script>';
		echo '<h3>Crewene</h3>';
		
		if (!empty($groupList)) {
			echo '<table>';
				echo '<tr>';
					echo '<th>Navn</th>';
					echo '<th>Medlemmer</th>';
					echo '<th>Beskrivelse</th>';
					echo '<th>Chief</th>';
				echo '</tr>';
				
				$userList = UserHandler::getMemberUsers();
				
				foreach ($groupList as $group) {
					echo '<tr>';
						echo '<form class="chief-groups-edit" method="post">';
							echo '<input type="hidden" name="id" value="' . $group->getId() . '">';
							echo '<td><input type="text" name="title" value="' . $group->getTitle() . '" required></td>';
							echo '<td>' . count($group->getMembers()) . '</td>';
							echo '<td><input type="text" name="description" value="' . $group->getDescription() . '" required></td>';
							echo '<td>';
								echo '<select class="chosen-select" name="leader">';
									$leader = $group->getLeader();
									
									if ($leader != null) {
										echo '<option value="0">Ingen</option>';
									} else {
										echo '<option value="0" selected>Ingen</option>';
									}
									
									foreach ($userList as $key => $userValue) {
										if ($leader != null && $userValue->getId() == $leader->getId()) {
											echo '<option value="' . $userValue->getId() . '" selected>' . $userValue->getDisplayName() . '</option>';
										} else {
											echo '<option value="' . $userValue->getId() . '">' . $userValue->getDisplayName() . '</option>';
										}
									}
								echo '</select>';
							echo '</td>';
							echo '<td><input type="submit" value="Endre"></td>';
						echo '</form>';
						echo '<td><input type="button" value="Slett" onClick="removeGroup(' . $group->getId() . ')"></td>';
					echo '</tr>';
				}
			echo '</table>';
			
			echo '<h3>Legg til et nytt crew</h3>';
			echo '<form class="chief-groups-add" method="post">';
				echo '<table>';
					echo '<tr>';
						echo '<td>Navn:</td>';
						echo '<td><input type="text" name="title" required></td>';
					echo '</tr>';
					echo '<tr>';
						echo '<td>Beskrivelse:</td>';
						echo '<td><input type="text" name="description" required></td>';
					echo '</tr>';
					echo '<tr>';
						echo '<td>Chief:</td>';
						echo '<td>';
							echo '<select class="chosen-select" name="leader">';
								echo '<option value="0">Ingen</option>';
								
								foreach ($userList as $value) {
									echo '<option value="' . $value->getId() . '">' . $value->getDisplayName() . '</option>';
								}
							echo '</select>';
						echo '</td>';
					echo '</tr>';
					echo '<tr>';
						echo '<td><input type="submit" value="Legg til"></td>';
					echo '</tr>';
				echo '</table>';
			echo '</form>';
			
			echo '<h3>Medlemmer</h3>';
			
			$freeUserList = UserHandler::getNonMemberUsers();
			
			if (!empty($freeUserList)) {
				echo '<table>';
					echo '<tr>';
						echo '<form class="chief-groups-adduser" method="post">';
							echo '<td>';
								echo '<select class="chosen-select" name="userId">';
									foreach ($freeUserList as $user) {
										echo '<option value="' . $user->getId() . '">' . $user->getDisplayName() . '</option>';
									}
								echo '</select>';
							echo '</td>';
							echo '<td>';
								echo '<select class="chosen-select" name="groupId">';
									foreach ($groupList as $group) {
										echo '<option value="' . $group->getId() . '">' . $group->getTitle() . '</option>';
									}
								echo '</select>';
							echo '</td>';
							echo '<td><input type="submit" value="Legg til"></td>';
						echo '</form>';
					echo '</tr>';
				echo '</table>';
			} else {
				echo '<p>Alle registrerte medlemmer er allerede med i et crew.</p>';
			}
			
			foreach ($groupList as $group) {
				$memberList = $group->getMembers();
				
				echo '<h4>' . $group->getTitle() . '</h4>';
				echo '<table>';
					if (!empty($memberList)) {
						foreach ($memberList as $member) {
							echo '<tr>';
								echo '<td>' . $member->getDisplayName(). '</td>';
								echo '<td><input type="button" value="Fjern" onClick="removeUserFromGroup(' . $member->getId() . ')"></td>';
							echo '</tr>';
						}
						
						echo '<tr>';
							echo '<td><input type="button" value="Fjern alle" onClick="removeUsersFromGroup(' . $group->getId() . ')"></td>';
						echo '</tr>';
					} else {
						echo '<i>Det er ingen medlemmer i ' . $group->getTitle() . '.</i>';
					}
				echo '</table>';
			}
		} else {
			echo '<p>Det finnes ingen grupper enda!</p>';
		}
	} else {
		echo '<p>Du har ikke rettigheter til dette!</p>';
	}
} else {
	echo '<p>Du er ikke logget inn!</p>';
}
?>