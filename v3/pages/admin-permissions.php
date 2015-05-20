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

require_once 'admin.php';
require_once 'session.php';
require_once 'handlers/userhandler.php';
require_once 'handlers/permissionhandler.php';
require_once 'interfaces/page.php';

class AdminPermissionsPage extends AdminPage implements IPage {
	public function getTitle() {
		return 'Rettigheter';
	}

	public function getContent() {
		$content = null;

		if (Session::isAuthenticated()) {
			$user = Session::getCurrentUser();
			
			if ($user->hasPermission('*') ||
				$user->hasPermission('admin.permissions')) {
				$content .= '<script src="scripts/admin-permissions.js"></script>';
				
				if (isset($_GET['id'])) {
					$permissionUser = UserHandler::getUser($_GET['id']);

					if ($permissionUser != null) {
						$content .= '<h3>Du endrer nå "' . $permissionUser->getFullName() . '" sine rettigheter</h3>';
						
						$content .= '<form class="admin-permissions-edit" method="post">';
							$content .= '<input type="hidden" name="id" value="' . $permissionUser->getId() . '">';
							$content .= '<table>';
								foreach (PermissionHandler::getPermissions() as $permission) {
									if ($user->hasPermission('*') ||
										$user->hasPermission($permission->getValue())) {
										$content .= '<tr>';
											$content .= '<td>';
												if (in_array($permission, $permissionUser->getPermissions())) {
													$content .= '<input type="checkbox" name="checkbox_' . $permission->getId() . '" value="' . $permission->getId() . '" checked>' . $permission->getValue();
												} else {
													$content .= '<input type="checkbox" name="checkbox_' . $permission->getId() . '" value="' . $permission->getId() . '">' . $permission->getValue();
												}
											$content .= '</td>';
											$content .= '<td>' . wordwrap($permission->getDescription(), 100, '<br>') . '</td>';
										$content .= '</tr>';
									}
								}
								
								$content .= '<tr>';
									$content .= '<td><input type="submit" value="Lagre"></td>';
								$content .= '</tr>';
							$content .= '</table>';
						$content .= '</form>';
					} else {
						$content .= '<p>Brukeren finnes ikke.</p>';
					}
				} else {
					$content .= '<h3>Rettigheter</h3>';
					$content .= '<p>Under ser du en liste med alle brukere som har spesielle rettigheter.</p>';
					
					$userList = UserHandler::getPermissionUsers();
					
					if (!empty($userList)) {
						$content .= '<table>';
							$content .= '<tr>';
								$content .= '<th>Navn</th>';
								$content .= '<th>Antall tilganger</th>';
							$content .= '</tr>';
							
							foreach ($userList as $userValue) {
								if ($userValue != null) {
									$content .= '<tr>';
										$content .= '<td><a href="index.php?page=my-profile&id=' . $userValue->getId() . '">' . $userValue->getDisplayName() . '</a></td>';
										$content .= '<td>' . count($userValue->getPermissions()) . '</td>';
										$content .= '<td><input type="button" value="Endre" onClick="editUserPermissions(' . $userValue->getId() . ')"></td>';
										$content .= '<td><input type="button" value="Inndra rettigheter" onClick="removeUserPermissions(' . $userValue->getId() . ')"></td>';
									$content .= '</tr>';
								}
							}
						$content .= '</table>';
					} else {
						$content .= '<p>Det finnes ingen brukere med rettigheter.</p>';
					}
				}
			} else {
				$content .= '<p>Du har ikke rettigheter til dette!</p>';
			}
		} else {
			$content .= '<p>Du er ikke logget inn!</p>';
		}

		return $content;
	}
}
?>