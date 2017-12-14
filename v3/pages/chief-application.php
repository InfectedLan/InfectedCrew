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

require_once 'handlers/applicationhandler.php';
require_once 'chief.php';

class ChiefApplicationPage extends ChiefPage {
	public function canAccess(User $user): bool{
        return $user->hasPermission('chief.application') || $user->isGroupLeader();
    }

    public function hasParent(): bool {
		return true;
	}

	public function getTitle(): ?string {
		return 'Søknader';
	}

    public function getContent(User $user = null): string {
		$content = null;
        $pendingApplications = null;
        $queuedApplications = null;
        $acceptedApplications = null;

        if ($user->hasPermission('*')) {
            $pendingApplications = ApplicationHandler::getPendingApplications();
            $queuedApplications = ApplicationHandler::getQueuedApplications();
            $acceptedApplications = ApplicationHandler::getAcceptedApplications();
        } else if ($this->canAccess($user) && $user->isGroupMember()) {
            $group = $user->getGroup();

            $pendingApplications = ApplicationHandler::getPendingApplicationsByGroup($group);
            $queuedApplications = ApplicationHandler::getQueuedApplicationsByGroup($group);
            $acceptedApplications = ApplicationHandler::getAcceptedApplicationsByGroup($group);
        }

        $content .= '<div class="row">';
            $content .= '<div class="col-md-6">';
                $content .= '<div class="box box-default">';
                    $content .= '<div class="box-header with-border">';
                        $content .= '<h3 class="box-title">Åpne søknader</h3>';
                        $content .= '<div class="box-tools pull-right">';
                            $content .= '<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>';
                        $content .= '</div>';
                    $content .= '</div>';
                    $content .= '<div class="box-body">';

                        if (!empty($pendingApplications)) {
                            $content .= '<table class="table table-bordered table-striped" data-table>';
                                $content .= '<thead>';
                                    $content .= '<tr>';
                                        $content .= '<th>Søker\'s navn</th>';
                                        $content .= '<th>Crew</th>';
                                        $content .= '<th>Dato søkt</th>';
                                        $content .= '<th>Valg</th>';
                            $content .= '<th>Status</th>';
                                    $content .= '</tr>';
                                $content .= '</thead>';
                                $content .= '<tbody>';

                                    foreach ($pendingApplications as $application) {
                                        $applicationUser = $application->getUser();

                                        $content .= '<tr>';
                                            $content .= '<td><a href="?page=user-profile&userId=' . $applicationUser->getId() . '">' . $applicationUser->getFullName() . '</a></td>';
                                            $content .= '<td>' . $application->getGroup()->getTitle() . '</td>';
                                            $content .= '<td>' . date('d.m.Y H:i', $application->getOpenedTime()) . '</td>';
                                            $content .= '<td>' . $application->getStateAsString() . '</td>';
                                            $content .= '<td>';
                                                $content .= '<div class="btn-group" role="group" aria-label="...">';
                                                    $content .= '<button type="button" class="btn btn-primary" onClick="viewApplication(' . $application->getId() . ')">Vis</button>';
                                                    $content .= '<button type="button" class="btn btn-primary" onClick="queueApplication(' . $application->getId() . ')">Sett i kø</button>';
                                                $content .= '</div>';
                                            $content .= '</td>';
                                        $content .= '</tr>';
                                    }

                                $content .= '</tbody>';
                                $content .= '<tfoot>';
                                    $content .= '<tr>';
                                        $content .= '<th>Søker\'s navn</th>';
                                        $content .= '<th>Crew</th>';
                                        $content .= '<th>Dato søkt</th>';
                                        $content .= '<th>Valg</th>';
                            $content .= '<th>Status</th>';
                                    $content .= '</tr>';
                                $content .= '</tfoot>';
                            $content .= '</table>';
                        } else {
                            $content .= '<p>Det er ingen søknader som venter på godkjenning.</p>';
                        }

                    $content .= '</div>';
                $content .= '</div>';
                $content .= '<div class="box box-default">';
                    $content .= '<div class="box-header with-border">';
                        $content .= '<h3 class="box-title">Tidligere søknader</h3>';
                        $content .= '<div class="box-tools pull-right">';
                            $content .= '<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>';
                        $content .= '</div>';
                    $content .= '</div>';
                    $content .= '<div class="box-body">';

                        if (!empty($acceptedApplications)) {
                            $content .= '<table class="table table-bordered table-striped" data-table>';
                                $content .= '<thead>';
                                    $content .= '<tr>';
                                        $content .= '<th>Arrangement</th>';
                                        $content .= '<th>Søker\'s navn</th>';
                                        $content .= '<th>Crew</th>';
                                        $content .= '<th>Dato søkt</th>';
                                        $content .= '<th>Valg</th>';
                                    $content .= '</tr>';
                                $content .= '</thead>';
                                $content .= '<tbody>';

                                    foreach ($acceptedApplications as $application) {
                                        $applicationUser = $application->getUser();

                                        $content .= '<tr>';
                                            $content .= '<td>' . $application->getEvent()->getTitle() . '</td>';
                                            $content .= '<td><a href="index.php?page=user-profile&userId=' . $applicationUser->getId() . '">' . $applicationUser->getFullName() . '</a></td>';
                                            $content .= '<td>' . $application->getGroup()->getTitle() . '</td>';
                                            $content .= '<td>' . date('d.m.Y H:i', $application->getOpenedTime()) . '</td>';
                                            $content .= '<td>';
                                                $content .= '<button type="button" class="btn btn-primary" onClick="viewApplication(' . $application->getId() . ')">Vis</button>';
                                            $content .= '</td>';
                                        $content .= '</tr>';
                                    }

                                $content .= '</tbody>';
                                $content .= '<tfoot>';
                                    $content .= '<tr>';
                                        $content .= '<th>Arrangement</th>';
                                        $content .= '<th>Søker\'s navn</th>';
                                        $content .= '<th>Crew</th>';
                                        $content .= '<th>Dato søkt</th>';
                                        $content .= '<th>Valg</th>';
                                    $content .= '</tr>';
                                $content .= '</tfoot>';
                            $content .= '</table>';
                        } else {
                            $content .= '<p>Det er ingen godkjente søknader i arkivet.</p>';
                        }

                    $content .= '</div>';
                $content .= '</div>';
            $content .= '</div>';
            $content .= '<div class="col-md-6">';
                $content .= '<div class="box box-default">';
                    $content .= '<div class="box-header with-border">';
                        $content .= '<h3 class="box-title">Søknader i kø</h3>';
                        $content .= '<div class="box-tools pull-right">';
                            $content .= '<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>';
                        $content .= '</div>';
                    $content .= '</div>';
                    $content .= '<div class="box-body">';

                        if (!empty($queuedApplications)) {
                            $content .= '<table class="table table-bordered table-striped" data-table>';
                                $content .= '<thead>';
                                    $content .= '<tr>';
                                        $content .= '<th>Plass</th>';
                                        $content .= '<th>Søker\'s navn</th>';
                                        $content .= '<th>Crew</th>';
                                        $content .= '<th>Dato søkt</th>';
                                        $content .= '<th>Status</th>';
                                        $content .= '<th>Valg</th>';
                                    $content .= '</tr>';
                                $content .= '</thead>';
                                $content .= '<tbody>';

                                    foreach ($queuedApplications as $index => $application) {
                                        $applicationUser = $application->getUser();

                                        $content .= '<tr>';
                                            $content .= '<td>' . ($index + 1) . '</td>';
                                            $content .= '<td><a href="?page=user-profile&userId=' . $applicationUser->getId() . '">' . $applicationUser->getFullName() . '</a></td>';
                                            $content .= '<td>' . $application->getGroup()->getTitle() . '</td>';
                                            $content .= '<td>' . date('d.m.Y H:i', $application->getOpenedTime()) . '</td>';
                                            $content .= '<td>' . $application->getStateAsString() . '</td>';
                                            $content .= '<td>';
                                                $content .= '<div class="btn-group" role="group" aria-label="...">';
                                                    $content .= '<button type="button" class="btn btn-primary" onClick="viewApplication(' . $application->getId() . ')">Vis</button>';
                                                    $content .= '<button type="button" class="btn btn-primary" onClick="unqueueApplication(' . $application->getId() . ')">Fjern fra kø</button>';
                                                $content .= '</div>';
                                            $content .= '</td>';
                                        $content .= '</tr>';
                                    }

                                $content .= '</tbody>';
                                $content .= '<tfoot>';
                                    $content .= '<tr>';
                                        $content .= '<th>Plass</th>';
                                        $content .= '<th>Søker\'s navn</th>';
                                        $content .= '<th>Crew</th>';
                                        $content .= '<th>Dato søkt</th>';
                                        $content .= '<th>Status</th>';
                                        $content .= '<th>Valg</th>';
                                    $content .= '</tr>';
                                $content .= '</tfoot>';
                            $content .= '</table>';
                        } else {
                            $content .= '<p>Det er ingen søknader i køen.</p>';
                        }

                    $content .= '</div>';
                $content .= '</div>';
            $content .= '</div>';
        $content .= '</div>';

        $content .= '<script src="pages/scripts/chief-application.js"></script>';

		return $content;
	}
}