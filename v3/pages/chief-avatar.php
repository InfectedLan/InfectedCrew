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

require_once 'handlers/avatarhandler.php';
require_once 'chief.php';

class ChiefAvatarPage extends ChiefPage {
	public function canAccess(User $user): bool{
        return $user->hasPermission('chief.avatar') || $user->isGroupLeader();
    }

    public function hasParent(): bool {
		return true;
	}

	public function getTitle(): ?string {
		return 'Profilbilder';
	}

    public function getContent(User $user = null): string {
		$content = null;
        $content .= '<div class="row">';
            $pendingAvatars = AvatarHandler::getPendingAvatars();

            $content .= '<div class="col-md-6">';
                $content .= '<div class="box box-default">';
                    $content .= '<div class="box-header with-border">';
                        $content .= '<h3 class="box-title">Godkjenn profilbilder</h3>';
                        $content .= '<div class="box-tools pull-right">';
                            $content .= '<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>';
                        $content .= '</div>';
                    $content .= '</div>';
                    $content .= '<div class="box-body">';

                        if (!empty($pendingAvatars)) {
                            $content .= '<p>Godkjenn eller avslå profilbildene til brukeren. Bilder som er støtende eller ikke viser ansikt skal avslåes.</p>';
                        } else {
                            $content .= '<p>Det er ingen profilbilder til godkjenning akkurat nå.</p>';
                        }

                    $content .= '</div>';
                $content .= '</div>';
            $content .= '</div>';
        $content .= '</div>';
        $content .= '<div class="row">';

            if (!empty($pendingAvatars)) {
                foreach ($pendingAvatars as $avatar) {
                    $avatarUser = $avatar->getUser();

                    $content .= '<div class="col-md-3">';
                        $content .= '<div class="box box-default">';
                            $content .= '<div class="box-header with-border">';
                            $content .= '<h3 class="box-title">' . $user->getDisplayName() . '</h3>';
                            $content .= '<div class="box-tools pull-right">';
                                $content .= '<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>';
                            $content .= '</div>';
                        $content .= '</div>';
                        $content .= '<div class="box-body">';
                            $content .= '<div class="thumbnail">';
                                $content .= '<img src="../dynamic/' . $avatarUser->getAvatar()->getSd() . '" class="img-roundedRectangle" alt="' . $user->getDisplayName() . '\'s profilbilde">';
                            $content .= '</div>';
                        $content .= '</div>';
                        $content .= '<div class="box-footer">';
                            $content .= '<div class="btn-group" role="group" aria-label="...">';
                                $content .= '<button type="button" class="btn btn-success" onClick="acceptAvatar(' . $avatar->getId() . ')">Godta</button>';
                                $content .= '<button type="button" class="btn btn-danger" onClick="rejectAvatar(' . $avatar->getId() . ')">Avslå</button>';
                            $content .= '</div>';
                        $content .= '</div>';
                    $content .= '</div>';
                }
            }

        $content .= '</div>';

        $content .= '<script src="pages/scripts/chief-avatar.js"></script>';

		return $content;
	}
}