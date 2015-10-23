<?php
/**
 * This file is part of InfectedCrew.
 *
 * Copyright (C) 2015 Infected <http://infected.no/>.
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 3.0 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library.  If not, see <http://www.gnu.org/licenses/>.
 */

require_once 'chief.php';
require_once 'session.php';
require_once 'handlers/userhandler.php';
require_once 'handlers/grouphandler.php';
require_once 'interfaces/page.php';

class ChiefGroupPage extends ChiefPage implements IPage {
	public function getTitle() {
		if (Session::isAuthenticated()) {
			$user = Session::getCurrentUser();

			if (isset($_GET['id'])) {
				$group = GroupHandler::getGroup($_GET['id']);

				if ($user->hasPermission('chief.group') ||
					$user->equals($group->getLeader()) ||
					$user->equals($group->getCoLeader())) {

					return $group->getTitle();
				}
			}
		}

		return 'Crew';
	}

	public function getContent() {
		$content = null;

		if (Session::isAuthenticated()) {
			$user = Session::getCurrentUser();

			$content .= '<script src="scripts/chief-group.js"></script>';

			if ($user->hasPermission('*') &&
				!isset($_GET['id'])) {
				$groupList = GroupHandler::getGroups();

				$content .= '<div class="row">';
					$content .= '<div class="col-md-6">';
						$content .= '<div class="box">';
							$content .= '<div class="box-header">';
								$content .= '<h3 class="box-title">Oversikt</h3>';
							$content .= '</div><!-- /.box-header -->';
							$content .= '<div class="box-body">';
								$content .= '<table class="table table-bordered">';
									$content .= '<tr>';
										$content .= '<th>Navn</th>';
										$content .= '<th>Beskrivelse</th>';
										$content .= '<th>Antall medlemmer</th>';
									$content .= '</tr>';

									foreach ($groupList as $group) {
										$content .= '<tr>';
											$content .= '<td>' . $group->getTitle() . '</td>';
											$content .= '<td>' . $group->getDescription() . '</td>';
											$content .= '<td><span class="badge">' . count($group->getMembers()) . '</span></td>';
											$content .= '<td><button type="button" class="btn btn-primary" onClick="viewGroup(' . $group->getId() . ')">Vis</button></td>';
										$content .= '</tr>';
									}

								$content .= '</table>';
							$content .= '</div><!-- /.box-body -->';
						$content .= '</div><!-- /.box -->';

						if (!empty($groupList)) {
							$userList = UserHandler::getMemberUsers();

							foreach ($groupList as $group) {
								$content .= '<div class="box">';
									$content .= '<div class="box-header">';
										$content .= '<h3 class="box-title">' . $group->getTitle() . '</h3>';
									$content .= '</div><!-- /.box-header -->';
									$content .= '<div class="box-body">';
										$content .= self::getGroupEditForm($group);
									$content .= '</div><!-- /.box-body -->';
								$content .= '</div><!-- /.box -->';
							}
						}

					$content .= '</div><!--/.col (left) -->';
					$content .= '<div class="col-md-6">';
						$content .= '<div class="box">';
							$content .= '<div class="box-header">';
								$content .= '<h3 class="box-title">Legg til et nytt crew</h3>';
							$content .= '</div><!-- /.box-header -->';
							$content .= '<div class="box-body">';
								$content .= '<p>Fyll ut feltene under for å legge til en ny gruppe.</p>';
								$content .= self::getAddGroupForm();
							$content .= '</div><!-- /.box-body -->';
						$content .= '</div><!-- /.box -->';
					$content .= '</div><!--/.col (right) -->';
				$content .= '</div><!-- /.row -->';
			} else if ($user->hasPermission('chief.group') ||
				$user->equals($group->getLeader()) ||
				$user->equals($group->getCoLeader())) {
				$group = isset($_GET['id']) ? GroupHandler::getGroup($_GET['id']) : $user->getGroup();

				$content .= '<div class="row">';
					$content .= '<div class="col-md-6">';
						$content .= '<div class="box">';
							$content .= '<div class="box-header">';
								$content .= '<h3 class="box-title">Legg til en bruker i ' . $group->getTitle() . '</h3>';
							$content .= '</div><!-- /.box-header -->';
							$content .= '<div class="box-body">';
								$content .= self::getAddUserToGroupForm($group);
							$content .= '</div><!-- /.box-body -->';
						$content .= '</div><!-- /.box -->';
						$content .= '<div class="box">';
							$content .= '<div class="box-header">';
						  	$content .= '<h3 class="box-title">Medlemmer i ' . $group->getTitle() . '</h3>';
							$content .= '</div><!-- /.box-header -->';
							$content .= '<div class="box-body">';

								if (!empty($memberList)) {
									foreach ($memberList as $userValue) {
										$content .= $userValue->getDisplayName();
										$content .= '<button type="button" class="btn btn-primary" onClick="removeUserFromGroup(' . $userValue->getId() . ')">Fjern</button>';
									}

									if (count($groupList) > 1) {
										$content .= '<button type="button" class="btn btn-primary" onClick="removeUsersFromGroup(' . $group->getId() . ')">Fjern alle</button>';
									}
								} else {
									$content .= '<p>Det er ingen medlemmer i ' . $group->getTitle() . '.</p>';
								}

							$content .= '</div><!-- /.box-body -->';
						$content .= '</div><!-- /.box -->';
					$content .= '</div><!--/.col (left) -->';
					$content .= '<div class="col-md-6">';
						$content .= '<div class="box">';
							$content .= '<div class="box-header">';
						  	$content .= '<h3 class="box-title">Legg til et nytt lag i ' . $group->getTitle() . '</h3>';
							$content .= '</div><!-- /.box-header -->';
							$content .= '<div class="box-body">';
								$content .= self::getAddTeamToGroupForm($group);
							$content .= '</div><!-- /.box-body -->';
						$content .= '</div><!-- /.box -->';

						foreach ($group->getTeams() as $team) {
							$content .= '<div class="box">';
								$content .= '<div class="box-header">';
									$content .= '<h3 class="box-title">' . $group->getTitle() . ':' . $team->getTitle() . '</h3>';
								$content .= '</div><!-- /.box-header -->';
								$content .= '<div class="box-body">';
									$content .= '<div class="btn-group" role="group" aria-label="...">';
										$content .= '<button type="button" class="btn btn-primary" onClick="viewTeam(' . $group->getId() . ')">Vis</button>';
										$content .= '<button type="submit" class="btn btn-primary">Endre</button>';
									$content .= '</div>';
								$content .= '</div><!-- /.box-body -->';
							$content .= '</div><!-- /.box -->';
						}

					$content .= '</div><!-- ./col (right) -->';
				$content .= '</div><!-- /.row -->';
			} else {
				$content .= '<div class="box">';
					$content .= '<div class="box-body">';
						$content .= '<p>Du har ikke rettigheter til dette!</p>';
					$content .= '</div><!-- /.box-body -->';
				$content .= '</div><!-- /.box -->';
			}
		} else {
			$content .= '<div class="box">';
				$content .= '<div class="box-body">';
					$content .= '<p>Du er ikke logget inn!</p>';
				$content .= '</div><!-- /.box-body -->';
			$content .= '</div><!-- /.box -->';
		}

		return $content;
	}

	private function getAddGroupForm() {
		$content = null;

		$content .= '<form class="chief-group-add" method="post">';
			$content .= '<div class="form-group">';
				$content .= '<label>Navn</label>';
				$content .= '<input type="text" class="form-control" name="title" placeholder="Skriv inn et navn her..." required>';
			$content .= '</div><!-- /.form group -->';
			$content .= '<div class="form-group">';
				$content .= '<label>Beskrivelse</label>';
				$content .= '<textarea class="form-control" rows="3" name="description" placeholder="Skriv inn en beskrivese her..." required></textarea>';
			$content .= '</div><!-- /.form group -->';
			$content .= '<div class="form-group">';
				$content .= '<label>Chief</label>';
				$content .= '<select class="form-control" name="leader" required>';
					$content .= '<option value="0">Ingen</option>';

					foreach ($userList as $userValue) {
						if ($group->hasLeader() && $userValue->equals($group->getLeader())) {
							$content .= '<option value="' . $userValue->getId() . '" selected>' . $userValue->getDisplayName() . '</option>';
						} else {
							$content .= '<option value="' . $userValue->getId() . '">' . $userValue->getDisplayName() . '</option>';
						}
					}

				$content .= '</select>';
			$content .= '</div>';
			$content .= '<div class="form-group">';
				$content .= '<label>Co-chief</label>';
				$content .= '<select class="form-control" name="coleader" required>';
					$content .= '<option value="0">Ingen</option>';

					foreach ($userList as $userValue) {
						if ($group->hasCoLeader() && $userValue->equals($group->getCoLeader())) {
							$content .= '<option value="' . $userValue->getId() . '" selected>' . $userValue->getDisplayName() . '</option>';
						} else {
							$content .= '<option value="' . $userValue->getId() . '">' . $userValue->getDisplayName() . '</option>';
						}
					}

				$content .= '</select>';
			$content .= '</div>';
			$content .= '<button type="submit" class="btn btn-primary">Legg til</button>';
		$content .= '</form>';

		return $content;
	}

	private function getGroupEditForm(Group $group) {
		$content = null;

		$content .= '<form class="chief-group-edit" method="post">';
			$content .= '<input type="hidden" name="id" value="' . $group->getId() . '">';
			$content .= '<div class="form-group">';
				$content .= '<label>Navn</label>';
				$content .= '<input type="text" class="form-control" name="title" value="' . $group->getTitle() . '" required>';
			$content .= '</div>';
			$content .= '<div class="form-group">';
				$content .= '<label>Antall medlemmer <span class="badge">' . count($group->getMembers()) . '</span></label>';
			$content .= '</div>';
			$content .= '<div class="form-group">';
				$content .= '<label>Beskrivelse</label>';
				$content .= '<textarea class="form-control" rows="3" name="description" placeholder="Skriv inn en beskrivese her..." required>';
					$content .= $group->getDescription();
				$content .='</textarea>';
			$content .= '</div>';
			$content .= '<div class="form-group">';
				$content .= '<label>Chief</label>';
				$content .= '<select class="form-control" name="leader" required>';
					$content .= '<option value="0">Ingen</option>';

					foreach ($userList as $userValue) {
						if ($group->hasLeader() && $userValue->equals($group->getLeader())) {
							$content .= '<option value="' . $userValue->getId() . '" selected>' . $userValue->getDisplayName() . '</option>';
						} else {
							$content .= '<option value="' . $userValue->getId() . '">' . $userValue->getDisplayName() . '</option>';
						}
					}

				$content .= '</select>';
			$content .= '</div>';
			$content .= '<div class="form-group">';
				$content .= '<label>Co-chief</label>';
				$content .= '<select class="form-control" name="leader" required>';
					$content .= '<option value="0">Ingen</option>';

					foreach ($userList as $userValue) {
						if ($group->hasCoLeader() && $userValue->equals($group->getCoLeader())) {
							$content .= '<option value="' . $userValue->getId() . '" selected>' . $userValue->getDisplayName() . '</option>';
						} else {
							$content .= '<option value="' . $userValue->getId() . '">' . $userValue->getDisplayName() . '</option>';
						}
					}

				$content .= '</select>';
			$content .= '</div>';
			$content .= '<div class="btn-group" role="group" aria-label="...">';
				$content .= '<button type="button" class="btn btn-primary" onClick="viewGroup(' . $group->getId() . ')">Vis</button>';
				$content .= '<button type="button" class="btn btn-primary" onClick="viewTeam(' . $group->getId() . ')">Vis lag</button>';
				$content .= '<button type="submit" class="btn btn-primary">Endre</button>';
				/*
				$content .= '<button type="button" class="btn btn-primary" onClick="removeGroup(' . $group->getId() . ')">Slett</button>';
				*/
			$content .= '</div>';
		$content .= '</form>';

		return $content;
	}

	private function getAddUserToGroupForm(Group $group) {
		$content = null;
		$freeUserList = UserHandler::getNonMemberUsers();

		if (!empty($freeUserList)) {
			$content .= '<form class="chief-group-adduser" method="post">';
				$content .= '<input type="hidden" name="groupId" value="' . $group->getId() . '">';
				$content .= '<div class="form-group">';
					$content .= '<label>Velg bruker</label>';
					$content .= '<div class="input-group">';
						$content .= '<select class="form-control" name="userId" required>';

							foreach ($freeUserList as $userValue) {
								$content .= '<option value="' . $userValue->getId() . '">' . $userValue->getDisplayName() . '</option>';
							}

						$content .= '</select>';
						$content .= '<span class="input-group-btn">';
							$content .= '<button type="submit" class="btn btn-primary btn-flat">Legg til</button>';
						$content .= '</span>';
						$content .= '</div>';
				$content .= '</div>';
			$content .= '</form>';
		} else {
			$content .= '<p>Alle registrerte medlemmer er allerede med i et crew.</p>';
		}

		return $content;
	}

	private function getAddTeamToGroupForm(Group $group) {
		$content = null;

		$content .= '<form class="chief-team-add" method="post">';
			$content .= '<input type="hidden" name="groupId" value="' . $group->getId() . '">';
			$content .= '<div class="form-group">';
				$content .= '<label>Navn</label>';
				$content .= '<input type="text" class="form-control" name="title" placeholder="Skriv inn et navn her..." required>';
			$content .= '</div>';
			$content .= '<div class="form-group">';
				$content .= '<label>Beskrivelse</label>';
				$content .= '<textarea class="form-control" rows="3" name="description" placeholder="Skriv inn en beskrivese her..." required></textarea>';
			$content .= '</div>';
			$content .= '<div class="form-group">';
				$content .= '<label>Shift-leder</label>';
				$content .= '<select class="form-control" name="leader" required>';
					$content .= '<option value="0">Ingen</option>';

					foreach ($userList as $userValue) {
						$content .= '<option value="' . $userValue->getId() . '">' . $userValue->getDisplayName() . '</option>';
					}

				$content .= '</select>';
			$content .= '</div>';
			$content .= '<button type="submit" class="btn btn-primary">Endre</button>';
		$content .= '</form>';

		return $content;
	}
}
?>
