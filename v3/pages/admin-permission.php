<?php
/**
 * This file is part of InfectedCrew.
 *
 * Copyright (C) 2017 Infected <http://infected.no/>.
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

require_once 'session.php';
require_once 'handlers/userhandler.php';
require_once 'handlers/permissionhandler.php';
require_once 'admin.php';

class AdminPermissionPage extends AdminPage {
	public function hasParent(): bool {
		return true;
	}

	public function getTitle(): string {
		if (isset($_GET['userId'])) {
			$permissionUser = UserHandler::getUser($_GET['userId']);

			if ($permissionUser != null) {
				return $permissionUser->getFullName() . '\'s rettigheter</h3>';
			}
		}

		return 'Rettigheter';
	}

	public function getContent(): string {
		$content = null;

		if (Session::isAuthenticated()) {
			$user = Session::getCurrentUser();

			if ($user->hasPermission('admin.permission')) {
				if (isset($_GET['userId'])) {
					$permissionUser = UserHandler::getUser($_GET['userId']);

					if ($permissionUser != null) {
						$content .= '<div class="box">';
							$content .= '<div class="box-body">';
								$content .= '<p>Du velger rettigheter for denne brukeren ved å huke av rettighetene under, og klikke på "Lagre" knappen.</p>';

								$content .= '<form class="admin-permissions-edit" method="post">';
									$content .= '<input type="hidden" name="id" value="' . $permissionUser->getId() . '">';
									$content .= '<table class="table table-bordered">';
										$content .= '<tr>';
											$content .= '<th>Valg</th>';
											$content .= '<th>Verdi</th>';
											$content .= '<th>Beskrivelse</th>';
										$content .= '</tr>';

										foreach (PermissionHandler::getPermissions() as $permission) {
											if ($user->hasPermission('*') ||
												$user->hasPermission($permission->getValue())) {
												$content .= '<tr>';
													$content .= '<td>';

														if (in_array($permission, $permissionUser->getPermissions())) {
															$content .= '<input type="checkbox" name="checkbox_' . $permission->getId() . '" value="' . $permission->getId() . '" checked>';
														} else {
															$content .= '<input type="checkbox" name="checkbox_' . $permission->getId() . '" value="' . $permission->getId() . '">';
														}

													$content .= '</td>';
													$content .= '<td>' . $permission->getValue() . '</td>';
													$content .= '<td>' . wordwrap($permission->getDescription(), 100, '<br>') . '</td>';
												$content .= '</tr>';
											}
										}

									$content .= '</table>';
									$content .= '<button type="submit" class="btn btn-primary">Lagre</button>';
								$content .= '</form>';
							$content .= '</div>';
						$content .= '</div>';
					} else {
						$content .= '<div class="box">';
							$content .= '<div class="box-body">';
								$content .= '<p>Brukeren finnes ikke.</p>';
							$content .= '</div>';
						$content .= '</div>';
					}
				} else {
				  $permissionUserList = UserHandler::getPermissionUsers();

					if (!empty($permissionUserList)) {
						$content .= '<div class="box">';
							$content .= '<div class="box-body">';
								$content .= '<p>Under ser du en liste med alle brukere som har spesielle rettigheter.</p>';
							$content .= '</div>';
						$content .= '</div>';

						$content .= '<div class="row">';
							$content .= '<div class="col-md-4">';

								foreach ($permissionUserList as $permissionUser) {
									if ($permissionUser != null) {
										$permissionCount = count($permissionUser->getPermissions());

										$content .= '<div class="box">';
											$content .= '<div class="box-header with-border">';
												$content .= '<h3 class="box-title"><a href="?page=user-profile&id=' . $permissionUser->getId() . '">' . $permissionUser->getDisplayName() . '</a></h3>';
											$content .= '</div>';
											$content .= '<div class="box-body">';
												$content .= '<p class="pull-left">Denne brukeren har ' . $permissionCount . ' ' . ($permissionCount > 1 ? 'tilganger' : 'tilgang') . '.</p>';
												$content .= '<div class="btn-group pull-right" role="group" aria-label="...">';
													$content .= '<button class="btn btn-primary" onClick="editUserPermissions(' . $permissionUser->getId() . ')">Endre</button>';
													$content .= '<button class="btn btn-primary" onClick="removeUserPermissions(' . $permissionUser->getId() . ')">Inndra rettigheter</button>';
												$content .= '</div>';
											$content .= '</div>';
										$content .= '</div>';
									}
								}
							} else {
								$content .= '<div class="box">';
									$content .= '<div class="box-body">';
										$content .= '<p>Det finnes ingen brukere med rettigheter.</p>';
									$content .= '</div>';
								$content .= '</div>';
							}

						$content .= '</div>';
					$content .= '</div>';
				}
			} else {
				$content .= '<div class="box">';
					$content .= '<div class="box-body">';
						$content .= '<p>Du har ikke rettigheter til dette!</p>';
					$content .= '</div>';
				$content .= '</div>';
			}
		} else {
			$content .= '<div class="box">';
				$content .= '<div class="box-body">';
					$content .= '<p>Du er ikke logget inn!</p>';
				$content .= '</div>';
			$content .= '</div>';
		}

		$content .= '<script src="scripts/admin-permission.js"></script>';

		return $content;
	}
}
?>
