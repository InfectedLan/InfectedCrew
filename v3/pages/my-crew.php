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
require_once 'handlers/restrictedpagehandler.php';
require_once 'utils/crewutils.php';
require_once 'page.php';

class MyCrewPage extends Page {
    public function canAccess(User $user): bool {
        return $user->isGroupMember();
    }

	public function getTitle(): ?string {
		return 'Mitt crew';
	}

    public function getContent(User $user = null): string {
        $content = null;

        $event = EventHandler::getCurrentEvent();
        $content .= '<div class="row">';
            $content .= '<div class="col-md-3 col-sm-6 col-xs-12">';
                $content .= '<div class="info-box">';
                    $content .= '<span class="info-box-icon bg-yellow"><i class="ion ion-ios-people-outline"></i></span>';
                    $content .= '<div class="info-box-content">';
                        $content .= '<span class="info-box-text">Deltakere</span>';
                        $content .= '<span class="info-box-number">' . $event->getTicketCount() . '</span>';
                    $content .= '</div>';
                $content .= '</div>';
            $content .= '</div>';
            $content .= '<div class="col-md-3 col-sm-6 col-xs-12">';
                $content .= '<div class="info-box">';
                    $content .= '<span class="info-box-icon bg-aqua"><i class="ion ion-ios-gear-outline"></i></span>';
                    $content .= '<div class="info-box-content">';
                        $content .= '<span class="info-box-text">Crew</span>';
                        $content .= '<span class="info-box-number">' . count(UserHandler::getMemberUsers($event)) . '</span>';
                    $content .= '</div>';
                $content .= '</div>';
            $content .= '</div>';
        $content .= '</div>';

        if (isset($_GET['teamId'])) {
            $team = TeamHandler::getTeam($_GET['teamId']);

            if ($team != null) {
                $content .= '<div class="box">';
                    $content .= '<div class="box-header with-border">';
                        $content .= '<h3 class="box-title">' . $team->getTitle() . '</h3>';
                    $content .= '</div>';
                    $content .= '<div class="box-body">';

                        $content .= $team->getDescription();

                    $content .= '</div>';
                $content .= '</div>';

                $content .= CrewUtils::displayTeam($team);
            }
        } else {
            $group = $user->getGroup();

            if ($group != null) {
                $content .= '<div class="box">';
                    $content .= '<div class="box-header with-border">';
                        $content .= '<h3 class="box-title">' . $group->getTitle() . '</h3>';
                    $content .= '</div>';
                    $content .= '<div class="box-body">';

                        $page = RestrictedPageHandler::getPageByName($group->getName());

                        if ($page != null) {
                            $content .= $page->getContent();
                        }

                        $content .=  $group->getDescription();

                    $content .= '</div>';
                $content .= '</div>';

                $content .= CrewUtils::displayGroup($group);
            }
        }

		return $content;
	}
}