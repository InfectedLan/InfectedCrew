<?php
require_once 'session.php';

if (Session::isAuthenticated()) {
	$user = Session::getCurrentUser();
	
	if ($user->isGroupMember()) {
		$group = $user->getGroup();
	
		if ($user->hasPermission('*') ||
			$user->hasPermission('chief.teams') ||
			$user->isGroupLeader()) {
			$teamList = $user->getGroup()->getTeams();
			echo '<script src="scripts/chief-teams.js"></script>';
			echo '<h3>Lag</h3>';
			
			if (!empty($teamList)) {
				echo '<table>';
					echo '<tr>';
						echo '<th>Navn</th>';
						echo '<th>Medlemmer</th>';
						echo '<th>Beskrivelse</th>';
						echo '<th>Chief</th>';
					echo '</tr>';
					
					$userList = $group->getMembers();
					
					foreach ($teamList as $team) {
						echo '<tr>';
							echo '<form class="chief-teams-edit" action="" method="post">';
								echo '<input type="hidden" name="teamId" value="' . $team->getId() . '">';
								echo '<input type="hidden" name="groupId" value="' . $group->getId() . '">';
								echo '<td>' . $group->getTitle() . ':<input type="text" name="title" value="' . $team->getTitle() . '"></td>';
								echo '<td>' . count($team->getMembers()) . '</td>';
								echo '<td><input type="text" name="description" value="' . $team->getDescription() . '"></td>';
								echo '<td>';
									echo '<select class="chosen-select" name="leader">';
										$leader = $team->getLeader();
										
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
							echo '<td><input type="button" value="Slett" onClick="removeTeam(' . $group->getId() . ', ' . $team->getId() . ')"></td>';
						echo '</tr>';
					}
				echo '</table>';
				
				echo '<h3>Legg til et nytt lag i "' . $group->getTitle() . '"</h3>';
				echo '<form class="chief-teams-add" action="" method="post">';
					echo '<input type="hidden" name="groupId" value="' . $group->getId() . '">';
					echo '<table>';
						echo '<tr>';
							echo '<td>Navn:</td>';
							echo '<td><input type="text" name="title" required></td>';
						echo '<tr>';
						echo '</tr>';
						echo '<tr>';
							echo '<td>Beskrivelse:</td>';
							echo '<td><input type="text" name="description" required></td>';
						echo '</tr>';
						echo '<tr>';
							echo '<td>Chief:</td>';
							echo '<td>';
								echo '<select class="chosen-select" name="leader">';
									echo '<option value="0" selected>Ingen</option>';
									
									foreach ($userList as $value) {
										echo '<option value="' . $value->getId() . '">' . $value->getFirstname() . ' "' . $value->getNickname() . '" ' . $value->getLastname() . '</option>';
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
				
				$freeUserList = getFreeUsers($group);
						
				if (!empty($freeUserList)) {
					echo '<table>';
						echo '<tr>';
							echo '<form class="chief-teams-adduser" action="" method="post">';
								echo '<td>';
									echo '<select class="chosen-select" name="userId">';
										foreach ($freeUserList as $user) {
											echo '<option value="' . $user->getId() . '">' . $user->getFirstname() . ' "' . $user->getNickname() . '" ' . $user->getLastname() . '</option>';
										}
									echo '</select>';
								echo '</td>';
								echo '<td>';
									echo '<select name="teamId">';
										foreach ($teamList as $team) {
											echo '<option value="' . $team->getId() . '">' . $team->getTitle() . '</option>';
										}
									echo '</select>';
								echo '</td>';
								echo '<td><input type="submit" value="Legg til"></td>';
							echo '</form>';
						echo '</tr>';
					echo '</table>';
				} else {
					echo '<p>Alle medlemmer av "' . $group->getTitle() . '"crew er allerede med i et lag.</p>';
				}
				
				foreach ($teamList as $team) {
					$memberList = $team->getMembers();
					
					echo '<h4>' . $group->getTitle() . ':' . $team->getTitle() . '</h4>';
					echo '<table>';
						if (!empty($memberList)) {
							foreach ($memberList as $member) {
								echo '<tr>';
									echo '<td>' . $member->getFirstname() . ' "' . $member->getNickname() . '" ' . $member->getLastname() . '</td>';
									echo '<td><input type="button" value="Fjern" onClick="removeUserFromTeam(' . $member->getId() . ')"></td>';
								echo '</tr>';
							}
							
							if (count($teamList) > 1) {
								echo '<tr>';
									echo '<td><input type="button" value="Fjern alle" onClick="removeUsersFromTeam(' . $team->getId() . ')"></td>';
								echo '</tr>';
							}
						} else {
							echo '<i>Det er ingen medlemmer i ' . $group->getTitle() . ':' . $team->getTitle() . '.</i>';
						}
					echo '</table>';
				}
			} else {
				echo '<p>Det finnes ikke noen lag i denne gruppen.</p>';
			}
		} else {
			echo '<p>Du har ikke rettigheter til dette!</p>';
		}
	} else {
		echo 'Du er ikke i noen gruppe!';
	}
} else {
	echo '<p>Du er ikke logget inn!</p>';
}

function getFreeUsers($group) {
	$freeUserList = $group->getMembers();
	
	foreach ($freeUserList as $key => $freeUser) {
		if ($freeUser->getTeam() != null) {
			unset($freeUserList[$key]);
		}
	}
	
	return $freeUserList;
}
?>