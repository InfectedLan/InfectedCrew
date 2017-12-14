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
require_once 'developer.php';

class DeveloperSwitchUserPage extends DeveloperPage {
    public function canAccess(User $user): bool{
        return $user->hasPermission('developer.switch-user');
    }

    public function hasParent(): bool {
		return true;
	}

	public function getTitle(): ?string {
		return 'Bytt bruker';
	}

    public function getContent(User $user = null): string {
		$content = null;
        $content .= '<div class="row">';
            $content .= '<div class="col-md-4">';
                $content .= '<div class="box">';
                    $content .= '<div class="box-body">';
                        $content .= '<p>Funksjonalitet for utviklere, lar deg logge inn som en annen bruker.</p>';
                        $content .= '<p>Denne skal <b>ikke</b> skal misbrukes, kun i debug- eller feilsøkings sammenheng. Alt vil bli loggført.</p>';

                        $content .= '<form class="developer-switch-user">';
                            $content .= '<div class="input-group">';
                                $content .= '<select class="form-control select2" name="userId" autofocus>';
                                    $userList = UserHandler::getUsers();

                                    foreach ($userList as $user) {
                                        $content .= '<option value="' . $user->getId() . '">' . $user->getDisplayName() . '</option>';
                                    }

                                $content .= '</select>';
                                $content .= '<span class="input-group-btn">';
                                    $content .= '<button type="submit" class="btn btn-info btn-flat">Bytt</button>';
                                $content .= '</span>';
                            $content .= '</div>';
                        $content .= '</form>';
                    $content .= '</div>';
                $content .= '</div>';
            $content .= '</div>';
        $content .= '</div>';

        $content .= '<script src="pages/scripts/developer-switch-user.js"></script>';

		return $content;
	}
}