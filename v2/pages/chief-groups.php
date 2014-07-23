<?php
require_once 'utils.php';
require_once 'handlers/userhandler.php';
require_once 'handlers/grouphandler.php';

$returnPage = basename(__FILE__, '.php');

if (Utils::isAuthenticated()) {
	$user = Utils::getUser();
	
	if ($user->hasPermission('chief.group') ||
		$user->isGroupMember() && $user->isGroupChief() ||
		$user->hasPermission('admin') ||
		$user->hasPermission('crew-admin')) {
		echo '<h1>Grupper</h1>';
		
		$groupList = GroupHandler::getGroups();
		
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
						echo '<form action="scripts/process_group.php?action=3&groupId=' . $group->getId() . '&returnPage=' . $returnPage . '" method="post">';
							echo '<td><input type="text" name="title" value="' . $group->getTitle() . '"></td>';
							echo '<td>' . count($group->getMembers()) . '</td>';
							echo '<td><input type="text" name="description" value="' . $group->getDescription() . '"></td>';
							echo '<td>';
								echo '<select name="chief">';
									if ($group->getChief() != null) {
										echo '<option value="0">Ingen</option>';
									} else {
										echo '<option value="0" selected>Ingen</option>';
									}
									
									foreach ($userList as $value) {
										$chief = $group->getChief();
										
										if ($chief != null && $value->getId() == $chief->getId()) {
											echo '<option value="' . $value->getId() . '" selected>' . $value->getFirstname() . ' "' . $value->getNickname() . '" ' . $value->getLastname() . '</option>';
										} else {
											echo '<option value="' . $value->getId() . '">' . $value->getFirstname() . ' "' . $value->getNickname() . '" ' . $value->getLastname() . '</option>';
										}
									}
								echo '</select>';
							echo '</td>';
							echo '<td><input type="submit" value="Endre"></td>';
							echo '</form>';
						echo '<td>';
							echo '<form action="scripts/process_group.php?action=2&groupId=' . $group->getId() . '&returnPage=' . $returnPage . '" method="post">';
								echo '<input type="submit" value="Slett">';
							echo '</form>';
						echo '</td>';
					echo '</tr>';
				}
			echo '</table>';
			
			echo '<h3>Legg til et ny gruppe</h3>';
			echo '<form action="scripts/process_group?action=1&returnPage=' . $returnPage . '" method="post">';
				echo '<table>';
					echo '<tr>';
						echo '<td>Navn:</td>';
						echo '<td><input type="text" name="title"></td>';
					echo '<tr>';
					echo '</tr>';
					echo '<tr>';
						echo '<td>Beskrivelse:</td>';
						echo '<td><input type="text" name="description"></td>';
					echo '</tr>';
					echo '<tr>';
						echo '<td>Chief:</td>';
						echo '<td>';
							echo '<select name="chief">';
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
			
			foreach ($groupList as $group) {
				$memberList = $group->getMembers();
				
				echo '<h4>' . $group->getTitle() . '</h4>';
				echo '<table>';
					if (!empty($memberList)) {
						foreach ($memberList as $member) {
							echo '<tr>';
								echo '<td>' . $member->getDisplayName() . '</td>';
								echo '<td>';
									echo '<form action="scripts/process_group.php?action=5&userId=' . $member->getId() . '&returnPage=' . $returnPage . '" method="post">';
										echo '<input type="submit" value="Fjern">';
									echo '</form>';
								echo '</td>';
							echo '</tr>';
						}
					} else {
						echo '<i>Det er ingen medlemmer i ' . $group->getTitle() . '.</i>';
					}
					
					if (!empty($freeUserList)) {
						echo '<tr>';
							echo '<form action="scripts/process_group.php?action=4&groupId=' . $group->getId() . '&returnPage=' . $returnPage . '" method="post">';
								echo '<td>';
									echo '<select name="userId">';
										foreach ($freeUserList as $user) {
											echo '<option value="' . $user->getId() . '">' . $user->getDisplayName() . '</option>';
										}
									echo '</select>';
								echo '</td>';
								echo '<td><input type="submit" value="Legg til"></td>';
							echo '</form>';
						echo '</tr>';
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