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
	    $title = 'Tilganger';

		if (isset($_GET['userId'])) {
			$permissionUser = UserHandler::getUser($_GET['userId']);

			if ($permissionUser != null) {
				$title .= ' for ' . $permissionUser->getFullName();
			}
		}

		return $title;
	}

	public function getContent(): string {
		$content = null;

		if (Session::isAuthenticated()) {
			$user = Session::getCurrentUser();

			if ($user->hasPermission('admin.permission')) {
				if (isset($_GET['userId'])) {
					$permissionUser = UserHandler::getUser($_GET['userId']);

					if ($permissionUser != null) {
                        $content .= '<div class="row">';
                            $content .= '<div class="col-md-6">';
                                $content .= '<div class="box box-default">';
                                    $content .= '<div class="box-header with-border">';
                                        $content .= '<h3 class="box-title">Gi ny tilgang</h3>';
                                        $content .= '<div class="box-tools pull-right">';
                                            $content .= '<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>';
                                        $content .= '</div>';
                                    $content .= '</div>';
                                    $content .= '<div class="box-body">';
                                        $content .= '<div class="row">';
                                            $content .= '<div class="col-md-6">';
                                                $content .= '<form class="admin-permission-create">';
                                                    $content .= '<input type="hidden" name="userId" value="' . $permissionUser->getId() . '">';
                                                    $content .= '<div class="form-group">';
                                                        $content .= '<label>Tilgang</label>';
                                                        $content .= '<div class="input-group">';
                                                            $content .= '<select class="form-control" name="permissionId" required>';

                                                                foreach (PermissionHandler::getPermissions() as $permission) {
                                                                    $content .= '<option value="' . $permission->getId() . '">' . $permission->getValue() . '</option>';
                                                                }

                                                                $content .= '</select>';
                                                            $content .= '<span class="input-group-btn">';
                                                                $content .= '<button type="submit" class="btn btn-primary">Gi tilgang</button>';
                                                            $content .= '</span>';
                                                        $content .= '</div>';
                                                    $content .= '</div>';
                                                $content .= '</form>';
                                            $content .= '</div>';
                                        $content .= '</div>';
                                    $content .= '</div>';
                                $content .= '</div>';
                            $content .= '</div>';
                            $content .= '<div class="col-md-6">';
                                /*
                                $content .= '<div class="box box-default">';
                                    $content .= '<div class="box-header with-border">';
                                        $content .= '<h3 class="box-title">Tidligere rettigheter</h3>';
                                        $content .= '<div class="box-tools pull-right">';
                                            $content .= '<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>';
                                        $content .= '</div>';
                                    $content .= '</div>';
                                    $content .= '<div class="box-body">';
                                        // TODO: Draw graph here.
                                    $content .= '</div>';
                                $content .= '</div>';
                                */
                            $content .= '</div>';
                        $content .= '</div>';
                        $content .= '<div class="row">';
                            $content .= '<div class="col-md-6">';
                                $content .= '<div class="box box-default">';
                                    $content .= '<div class="box-header with-border">';
                                        $content .= '<h3 class="box-title">Nåværende tilganger</h3>';
                                        $content .= '<div class="box-tools pull-right">';
                                            $content .= '<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>';
                                        $content .= '</div>';
                                    $content .= '</div>';
                                    $content .= '<div class="box-body">';
                                        $content .= '<table class="table table-bordered">';
                                            $content .= '<tr>';
                                                $content .= '<th>Verdi</th>';
                                                $content .= '<th>Beskrivelse</th>';
                                            $content .= '</tr>';

                                            foreach ($permissionUser->getPermissions() as $permission) {
                                                if ($user->hasPermission('*') ||
                                                    $user->hasPermission($permission->getValue())) {
                                                    $content .= '<tr>';
                                                        $content .= '<td>' . $permission->getValue() . '</td>';
                                                        $content .= '<td>' . wordwrap($permission->getDescription(), 100, '<br>') . '</td>';
                                                        $content .= '<td><button type="submit" class="btn btn-primary" onClick="removePermission(' . $permissionUser->getId() . ', ' . $permission->getId() . ')">Inndra</button></td>';
                                                    $content .= '</tr>';
                                                }
                                            }

                                        $content .= '</table>';
                                    $content .= '</div>';
                                $content .= '</div>';
                            $content .= '</div>';
                        $content .= '</div>';
					} else {
						$content .= '<div class="box">';
							$content .= '<div class="box-body">';
								$content .= '<p>' . Localization::getLocale('this_user_does_not_exist') . '</p>';
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

										$content .= '<div class="box box-primary">';
											$content .= '<div class="box-header with-border">';
												$content .= '<h3 class="box-title"><a href="?page=user-profile&id=' . $permissionUser->getId() . '">' . $permissionUser->getDisplayName() . '</a></h3>';
											$content .= '</div>';
											$content .= '<div class="box-body">';
												$content .= '<p class="pull-left">Denne brukeren har ' . $permissionCount . ' ' . ($permissionCount > 1 ? 'tilganger' : 'tilgang') . '.</p>';
												$content .= '<div class="btn-group pull-right" role="group" aria-label="...">';
													$content .= '<button class="btn btn-primary" onClick="redirectToUser(' . $permissionUser->getId() . ')">Endre</button>';
													$content .= '<button class="btn btn-primary" onClick="removePermissions(' . $permissionUser->getId() . ')">Inndra alle tilganger</button>';
												$content .= '</div>';
											$content .= '</div>';
										$content .= '</div>';
									}
								}
                            $content .= '</div>';
                        $content .= '</div>';
					} else {
						$content .= '<div class="box">';
							$content .= '<div class="box-body">';
							    $content .= '<p>Det finnes ingen brukere med rettigheter.</p>';
							$content .= '</div>';
						$content .= '</div>';
					}
				}
			}
		}

		$content .= '<script src="pages/scripts/admin-permission.js"></script>';

		return $content;
	}
}
