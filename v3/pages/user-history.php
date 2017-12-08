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
require_once 'handlers/userhistoryhandler.php';
require_once 'page.php';

class UserHistoryPage extends Page {
    private $historyUser;

    public function __construct() {
        $this->historyUser = isset($_GET['id']) ? UserHandler::getUser($_GET['id']) : Session::getCurrentUser();
    }

    public function canAccess(User $user): bool {
        return $user->hasPermission('*') || $user->equals($this->historyUser);
    }

	public function getTitle(): ?string {
		if (Session::isAuthenticated()) {
			$user = Session::getCurrentUser();

			if ($user->equals($this->historyUser)) {
				return 'Min bruker historikk';
			} else if ($user->hasPermission('*')) {
				return $this->historyUser->getDisplayName() . '\'s historikk';
			}
		}

		return 'Bruker historikk';
	}

    public function getContent(User $user = null): string {
        $content = null;

        $eventList = UserHistoryHandler::getEventsByUser($this->historyUser);

        if (!empty($eventList)) {
            $content .= '<div class="row">';
                $content .= '<div class="col-md-6">';
                    $content .= '<div class="box">';
                        $content .= '<div class="box-body">';
                            $content .= '<p>Denne brukeren har deltatt på følgende arrangementer:</p>';
                            $content .= '<table class="table table-bordered">';
                                $content .= '<tr>';
                                    $content .= '<th>Arrangement</th>';
                                    $content .= '<th>Rolle</th>';
                                $content .= '</tr>';

                                foreach ($eventList as $event) {
                                    $content .= '<tr>';
                                        $content .= '<td>' . $event->getTitle() . '</td>';
                                        $content .= '<td>' . $this->historyUser->getRoleByEvent($event) . '</td>';
                                    $content .= '</tr>';
                                }

                            $content .= '</table>';
                        $content .= '</div>';
                    $content .= '</div>';
                $content .= '</div>';
            $content .= '</div>';
        } else {
            $content .= '<div class="box">';
                $content .= '<div class="box-body">';
                    $content .= '<p>Denne brukeren har ikke noe historie enda.</p>';
                $content .= '</div>';
            $content .= '</div>';
        }

		return $content;
	}
}