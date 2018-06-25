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
        $content .= '<div class="row">';

            if (!$user->isGroupMember()) {
                if ($user->hasCroppedAvatar()) {
                    $groups = GroupHandler::getGroups();

                    $content .= '<div class="col-md-6">';
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

                    $applications = ApplicationHandler::getUserApplications($user);

                    if (!empty($applications)) {
                        $content .= '<div class="col-md-6">';
                            $content .= '<div class="box box-default">';
                                $content .= '<div class="box-header with-border">';
                                    $content .= '<h3 class="box-title">Du har sendt inn søknader til følgende crew</h3>';
                                    $content .= '<div class="box-tools pull-right">';
                                        $content .= '<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>';
                                    $content .= '</div>';
                                $content .= '</div>';
                                $content .= '<div class="box-body">';

                                    foreach ($applications as $application) {
                                        $content .= '<ul>';
                                            // TODO: Users should be able to look at their own applications.
                                            //$content .= '<li><a href="?page=application&id=' . $application->getId() . '">' . $application->getGroup()->getTitle() . '</a></li>';
                                            $content .= '<li>' . $application->getGroup()->getTitle() . '</a></li>';
                                        $content .= '</ul>';
                                    }

                                $content .= '</div>';
                            $content .= '</div>';
                        $content .= '</div>';
                    }
                } else {
                    $content .= '<p>Du er nødt til å laste opp et profilbilde for å søke. Dette gjør du <a href="?page=edit-avatar">her</a>.</p>';
                }
            } else {
                if ($this->application != null) {
                    $applicationUser = $this->application->getUser();

                    $content .= '<div class="col-md-6">';
                        $content .= '<div class="box box-default">';
                            $content .= '<div class="box-header with-border">';
                                $content .= '<h3 class="box-title">Søknad til <i>' . $this->application->getGroup()->getTitle() . '</i> fra <i>' . $applicationUser->getDisplayName() . '</i></h3>';
                                $content .= '<div class="box-tools pull-right">';
                                    $content .= '<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>';
                                $content .= '</div>';
                            $content .= '</div>';
                            $content .= '<div class="box-body">';
                                $content .= '<table class="table">';
                                    $content .= '<tr>';
                                        $content .= '<th>Status</th>';
                                        $content .= '<td>' . $this->application->getStateAsString() . '</td>';
                                    $content .= '</tr>';
                                    $content .= '<tr>';
                                        $content .= '<th>Crew</th>';
                                        $content .= '<td>' . $this->application->getGroup()->getTitle() . '</td>';
                                    $content .= '</tr>';
                                    $content .= '<tr>';
                                        $content .= '<th>Søkers navn</th>';
                                        $content .= '<td><a href="?page=user-profile&userId=' . $applicationUser->getId() . '">' . $applicationUser->getFullname(). '</a></td>';
                                    $content .= '</tr>';
                                    $content .= '<tr>';
                                        $content .= '<th>Alder</th>';
                                        $content .= '<td>' . $applicationUser->getAge() . '</td>';
                                    $content .= '</tr>';
                                    $content .= '<tr>';
                                        $content .= '<th>Dato søkt</th>';
                                        $content .= '<td>' . date('d.m.Y H:i', $this->application->getOpenedTime()) . '</td>';
                                    $content .= '</tr>';

                                    if ($this->application->getState() != ApplicationHandler::STATE_NEW) {
                                        $content .= '<tr>';
                                        $content .= '<th>Dato lukket</th>';
                                        $content .= '<td>' . date('d.m.Y H:i', $this->application->getClosedTime()) . '</td>';
                                        $content .= '</tr>';
                                    }

                                    $content .= '<tr>';
                                        $content .= '<th>E-post</th>';
                                        $content .= '<td><a href="mailto:' . $applicationUser->getEmail() . '">' . $applicationUser->getEmail() . '</a></td>';
                                    $content .= '</tr>';
                                    $content .= '<tr>';
                                        $content .= '<th>Telefon</th>';
                                        $content .= '<td>' . $applicationUser->getPhoneAsString() . '</td>';
                                    $content .= '</tr>';
                                    $content .= '<tr>';
                                        $content .= '<th>Søknad tekst</th>';
                                        $content .= '<td>' . $this->application->getContent() . '</td>';
                                    $content .= '</tr>';

                                    if ($this->application->getState() == ApplicationHandler::STATE_REJECTED) {
                                        $content .= '<tr>';
                                            $content .= '<th>Begrunnelse for avslåelse</th>';
                                            $content .= '<td><i>' . $this->application->getComment() . '</i></td>';
                                        $content .= '</tr>';
                                    }

                                $content .= '</table>';

                                if ($this->application->getState() == ApplicationHandler::STATE_NEW) {
                                    $content .= '<form class="application-reject">';
                                        $content .= '<input type="hidden" name="applicationId" value="' . $this->application->getId() . '">';
                                        $content .= '<div class="form-group">';
                                            $content .= '<label>Begrunnelse for avslag</label>';
                                            $content .= '<textarea class="form-control" rows="3" name="comment" placeholder="Skriv hvorfor du vil avslå søknaden her..."></textarea>';
                                        $content .= '</div>';
                                        $content .= '<div class="btn-group" role="group" aria-label="...">';
                                            $content .= '<button type="button" class="btn btn-primary" onClick="acceptApplication(' . $this->application->getId() . ')">Godta</button>';
                                            $content .= '<button type="submit" class="btn btn-primary">Avslå</button>';
                                        $content .= '</div>';
                                    $content .= '</form>';
                                }

                            $content .= '</div>';
                        $content .= '</div>';
                    $content .= '</div>';

                    $applications = ApplicationHandler::getUserApplications($applicationUser);

                    if (count($applications) > 1) {
                        $content .= '<div class="col-md-6">';
                            $content .= '<div class="box box-default">';
                                $content .= '<div class="box-header with-border">';
                                    $content .= '<h3 class="box-title"><i>' . $applicationUser->getDisplayName() . '</i> har også levert søknader til andre crew</h3>';
                                    $content .= '<div class="box-tools pull-right">';
                                        $content .= '<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>';
                                    $content .= '</div>';
                                $content .= '</div>';
                                $content .= '<div class="box-body">';
                                    $content .= '<table class="table">';
                                        $content .= '<tr>';
                                            $content .= '<th>Crew</th>';
                                            $content .= '<th>Status</th>';
                                        $content .= '</tr>';

                                            foreach ($applications as $application) {
                                                if (!$this->application->equals($application)) {
                                                    $content .= '<tr>';
                                                        $content .= '<td><a href="?page=application&id=' . $application->getId() . '">' . $application->getGroup()->getTitle() . '</a></td>';
                                                        $content .= '<td>' . $application->getStateAsString() . '</td>';
                                                    $content .= '</tr>';
                                                }
                                            }

                                    $content .= '</table>';
                                $content .= '</div>';
                            $content .= '</div>';
                        $content .= '</div>';
                    }
                } else {
                    $content .= '<div class="col-md-6">';
                        $content .= '<div class="box">';
                            $content .= '<div class="box-body">';
                                $content .= '<p>Søknaden som ble spesifisert finnes ikke.</p>';
                            $content .= '</div>';
                        $content .= '</div>';
                    $content .= '</div>';
                }
            }

        $content .= '</div>';
        $content .= '<script src="pages/scripts/application.js"></script>';

		return $content;
	}
}