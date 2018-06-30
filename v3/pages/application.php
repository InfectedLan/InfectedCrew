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

require_once 'localization.php';
require_once 'handlers/applicationhandler.php';
require_once 'page.php';

class ApplicationPage extends Page {
    private $application;

    public function __construct() {
        $this->application = isset($_GET['id']) ? ApplicationHandler::getApplication($_GET['id']) : null;
    }

	public function canAccess(User $user): bool {
        return $user->hasPermission('chief.application') || $user->isGroupLeader() || !$user->isGroupMember();
    }

	public function getTitle(): ?string {
        return 'Søknad';
	}

    public function getContent(User $user = null): string {
		$content = null;

		if($this->application == null) {
            $groups = GroupHandler::getGroups();

            $content .= '<div class="col-md-6">';
                $content .= '<div class="box box-danger">';
                    $content .= '<div class="box-header with-border">';
                        $content .= '<h3 class="box-title">Feil</h3>';
                    $content .= '</div>';
                $content .= '<div class="box-body">';
                    $content .= '<p>Søknaden finnes ikke.</p>';
                    $content .= '</div>';
                $content .= '</div>';
            $content .= '</div>';

        } else {
            $groups = GroupHandler::getGroups();

            $content .= '<div class="col-md-8">';
                $content .= '<div class="box box-default">';
                    $content .= '<div class="box-header with-border">';
                        $content .= '<h3 class="box-title">Send en ny søknad</h3>';
                        $content .= '<div class="box-tools pull-right">';
                            $content .= '<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>';
                        $content .= '</div>';
                    $content .= '</div>';
                    $content .= '<div class="box-body">';
                        $content .= '<p>Velkommen! Som crew vil du oppleve ting du aldri ville som deltaker, få erfaringer du kan bruke på din CV, <br>';
                        $content .= 'og møte nye spennende mennesker. Dersom det er første gang du skal søke til crew på ' . Settings::getValue("name") . ', <br>';
                        $content .= 'anbefaler vi at du leser igjennom beskrivelsene av våre ' . count($groups) . ' forksjellige crew <a href="?page=crew">her</a>.</p>';

                        $content .= '<p>Klar til å søke? Fyll ut skjemaet under!</p>';
                        $content .= '<form class="application-create">';
                            $content .= '<div class="row">';
                                $content .= '<div class="col-md-6">';
                                    $content .= '<div class="form-group">';
                                        $content .= '<label>Velg hvilket crew du vil søke til</label>';
                                        $content .= '<select class="form-control" name="groupId" required>';

                                            foreach ($groups as $group) {
                                                $content .= '<option value="' . $group->getId() . '">' . $group->getTitle() . '</option>';
                                            }

                                        $content .= '</select>';
                                    $content .= '</div>';
                                $content .= '</div>';
                            $content .= '</div>';
                            $content .= '<div class="form-group">';
                                $content .= '<label>Søknad tekst</label>';
                                $content .= '<textarea class="form-control" name="content" rows="10" cols="80" placeholder="Skriv en kort søknad om hvorfor du vil søke her. Skriv gjerne hvilke ferdigheter og egenskaper du har." required></textarea>';
                            $content .= '</div>';
                            $content .= '<button type="submit" class="btn btn-primary">Send søknad</button>';
                        $content .= '</form>';
                    $content .= '</div>';
                $content .= '</div>';
            $content .= '</div>';
            $applicationUser = $this->application->getUser();
            $userAvatar = $applicationUser->getAvatar()->getThumbnail();
            $content .= '<div class="col-md-4">';
                $content .= '<div class="box box-default">';
                    $content .= '<div class="box-header with-border">';
                        $content .= '<h3 class="box-title">Om brukeren</h3>';
                    $content .= '</div>';
                    $content .= '<div class="box-body">';
                        $content .= '<h3>' . $applicationUser->getDisplayName() . '</h3>';
                        $content .= <<<EOD

                                <img src="../dynamic/$userAvatar"> </img>
EOD;
                    $content .= '</div>';
                $content .= '</div>';
            $content .= '</div>';
        }

		return $content;
	}
}