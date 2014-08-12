<?php
require_once 'session.php';
require_once 'handlers/userhandler.php';
require_once 'handlers/grouphandler.php';

if (Session::isAuthenticated()) {
	$user = Session::getCurrentUser();
	
	if ($user->hasPermission('admin') ||
		$user->isGroupMember() && $user->isGroupLeader()) {
		$groupList = GroupHandler::getGroups();
		echo '<script src="scripts/chief-groups.js"> </script>';
		echo '<h1>Grupper</h1>';
		
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
						// TODO: Pass $group->getId() to JavaScript.
						echo '<form class="chief-groups-edit" action="" method="post">';
							//THIS is how we pass id if we want to use form submitting(Which isnt that bad of an idea, actually)
							echo '<input type="hidden" name="id" value="' . $group->getId() . '" />';
							echo '<td><input type="text" name="title" value="' . $group->getTitle() . '"></td>';
							echo '<td>' . count($group->getMembers()) . '</td>';
							echo '<td><input type="text" name="description" value="' . $group->getDescription() . '"></td>';
							echo '<td>';
								echo '<select name="leader">';
									if ($group->getleader() != null) {
										echo '<option value="0">Ingen</option>';
									} else {
										echo '<option value="0" selected>Ingen</option>';
									}
									
									foreach ($userList as $value) {
										$leader = $group->getLeader();
										
										if ($leader != null && $value->getId() == $leader->getId()) {
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
							// TODO: Pass $group->getId() to JavaScript.
							/*echo '<form class="chief-groups-remove" action="" method="post">';
								echo '<input type="submit" value="Slett">';
							echo '</form>';*/
							echo '<input type="button" value="Slett" onClick="removeGroup(' . $group->getId() . ')" />';
						echo '</td>';
					echo '</tr>';
				}
			echo '</table>';
			
			echo '<h3>Legg til et ny gruppe</h3>';
			echo '<form class="chief-groups-add" action="" method="post">';
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
							echo '<select name="leader">';
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
								echo '<td>' . $member->getFirstname() . ' "' . $member->getNickname() . '" ' . $member->getLastname() . '</td>';
								echo '<td>';
									// TODO: Pass $member->getId() to JavaScript.
									echo '<form class="chief-groups-removeuser" action="" method="post">';
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
							// TODO: Pass $group->getId() to JavaScript.
							echo '<form class="chief-groups-adduser" action="" method="post">';
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