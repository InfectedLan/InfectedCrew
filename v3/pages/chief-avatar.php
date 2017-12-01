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
require_once 'handlers/avatarhandler.php';
require_once 'chief.php';

class ChiefAvatarPage extends ChiefPage {
	public function hasParent(): bool {
		return true;
	}

	public function getTitle(): string {
		return 'Profilbilder';
	}

	public function getContent(): string {
		$content = null;

		if (Session::isAuthenticated()) {
			$user = Session::getCurrentUser();

			if ($user->hasPermission('chief.avatar') ||
				$user->isGroupLeader() ||
				$user->isGroupCoLeader()) {

				$content .= '<div class="row">';
					$content .= '<div class="col-md-6">';
						$content .= '<div class="box">';
							$content .= '<div class="box-header">';
						  		$content .= '<h3 class="box-title">Godkjenn profilbilder</h3>';
							$content .= '</div><!-- /.box-header -->';
							$content .= '<div class="box-body">';

						  		$pendingAvatarList = AvatarHandler::getPendingAvatars();

								if (!empty($pendingAvatarList)) {
									$index = 0;

									$content = '<div class="row">';

										foreach ($pendingAvatarList as $avatar) {
											$avatarUser = $avatar->getUser();

											$content .= '<div class="col-md-3">';
												$content .= '<div class="thumbnail">';
											  		$content .= '<img src="../api/' . $avatarUser->getAvatar()->getSd() . '" class="img-circle" alt="' . $user->getDisplayName() . '\'s profilbilde">';
											  		$content .= '<div class="caption">';
											  			$content .= '<p class="text-center">';
															$content .= '<small>' . $user->getDisplayName() . '</small><br>';
														$content .= '</p>';
											  		$content .= '</div>';
											  		$content .= '<div class="btn-group" role="group" aria-label="...">';
												   		$content .= '<button type="button" class="btn btn-primary" onClick="acceptAvatar(' . $avatar->getId() . ')">Godta</button>';
												   		$content .= '<button type="button" class="btn btn-primary" onClick="rejectAvatar(' . $avatar->getId() . ')">Avslå</button>';
											  		$content .= '</div>';
										   		$content .= '</div>';
										  	$content .= '</div>';

											$index++;
										}

										$content .= '</div><!-- /.row -->';

								} else {
									$content .= '<p>Det er ingen profilbilder som trenger godkjenning akkurat nå.</p>';
								}

							$content .= '</div><!-- /.box-body -->';
						$content .= '</div><!-- /.box -->';
					$content .= '</div><!--/.col (left) -->';
				$content .= '</div><!-- /.row -->';

				$content .= '<script src="scripts/chief-avatar.js"></script>';
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
}
?>
