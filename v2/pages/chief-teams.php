<?php
require_once 'session.php';

$returnPage = basename(__FILE__, '.php');

if (Session::isAuthenticated()) {
	$user = Session::getCurrentUser();
	
	if ($user->isGroupMember()) {
		$group = $user->getGroup();
	
		if ($user->hasPermission('chief.group') ||
			$user->isGroupLeader() ||
			$user->hasPermission('admin') ||
			$user->hasPermission('crew-admin')) {
			echo '<h1>Lag</h1>';
			
			$teamList = $user->getGroup()->getTeams();
			
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
							echo '<form action="scripts/process_team.php?action=3&groupId=' . $group->getId() . '&teamId=' . $team->getId() . '&returnPage=' . $returnPage . '" method="post">';
								echo '<td>' . $group->getTitle() . ':<input type="text" name="title" value="' . $team->getTitle() . '"></td>';
								echo '<td>' . count($team->getMembers()) . '</td>';
								echo '<td><input type="text" name="description" value="' . $team->getDescription() . '"></td>';
								echo '<td>';
									echo '<select name="leader">';
										if ($team->getLeader() != null) {
											echo '<option value="0">Ingen</option>';
										} else {
											echo '<option value="0" selected>Ingen</option>';
										}
										
										foreach ($userList as $key => $user) {
											$leader = $team->getLeader();
											
											if ($leader != null && $user->getId() == $leader->getId()) {
												echo '<option value="' . $user->getId() . '" selected>' . $user->getFirstname() . ' "' . $user->getNickname() . '" ' . $user->getLastname() . '</option>';
											} else {
												echo '<option value="' . $user->getId() . '">' . $user->getFirstname() . ' "' . $user->getNickname() . '" ' . $user->getLastname() . '</option>';
											}
										}
									echo '</select>';
								echo '</td>';
								echo '<td><input type="submit" value="Endre"></td>';
							echo '</form>';
							echo '<td>';
								echo '<form action="scripts/process_team.php?action=2&groupId=' . $group->getId() . '&teamId=' . $team->getId() . '&returnPage=' . $returnPage . '" method="post">';
									echo '<input type="submit" value="Slett">';
								echo '</form>';
							echo '</td>';
						echo '</tr>';
					}
				echo '</table>';
				
				echo '<h3>Legg til et nytt lag i "' . $group->getTitle() . '"</h3>';
				echo '<form action="scripts/process_team.php?action=1&groupId=' . $group->getId() . '&returnPage=' . $returnPage . '" method="post">';
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
				
				foreach ($teamList as $team) {
					$memberList = $team->getMembers();
					
					echo '<h4>' . $group->getTitle() . ':' . $team->getTitle() . '</h4>';
					echo '<ul>';
						if (!empty($memberList)) {
							foreach ($memberList as $member) {
								echo '<li>';
									echo '<form action="scripts/process_team.php?action=5&userId=' . $member->getId() . '&returnPage=' . $returnPage . '" method="post">';
										echo $member->getFirstname() . ' "' . $member->getNickname() . '" ' . $member->getLastname() . ' <input type="submit" value="Fjern">';
									echo '</form>';
								echo '</li>';
							}
						} else {
							echo '<i>Det er ingen medlemmer i ' . $group->getTitle() . ':' . $team->getTitle() . '.</i>';
						}
						
						$freeUserList = getFreeUsers($group);
						
						if (!empty($freeUserList)) {
							echo '<li style="list-style-type: none;">';
								echo '<form action="scripts/process_team.php?action=4&teamId=' . $team->getId() . '&returnPage=' . $returnPage . '" method="post">';
									echo '<select name="userId">';
										foreach ($freeUserList as $user) {
											echo '<option value="' . $user->getId() . '">' . $user->getFirstname() . ' "' . $user->getNickname() . '" ' . $user->getLastname() . '</option>';
										}
									echo '</select>';
									echo '<input type="submit" value="Legg til">';
								echo '</form>';
							echo '</li>';
						}
					echo '</ul>';
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