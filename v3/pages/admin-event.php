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
require_once 'handlers/eventhandler.php';
require_once 'handlers/locationhandler.php';
require_once 'admin.php';

class AdminEventPage extends AdminPage {
    public function canAccess(User $user): bool{
        return $user->hasPermission('admin.event');
    }

    public function hasParent(): bool {
		return true;
	}

	public function getTitle(): ?string {
		return 'Arrangementer';
	}

	public function getContent(User $user = null): string {
		$content = null;
        $content .= '<div class="row">';
            $content .= '<div class="col-md-6">';
                $content .= '<div class="box box-default">';
                    $content .= ' <div class="box-header with-border">';
                        $content .= '<h3 class="box-title">Legg til et nytt arrangement</h3>';
                        $content .= '<div class="box-tools pull-right">';
                            $content .= '<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>';
                        $content .= '</div>';
                    $content .= '</div>';
                    $content .= '<div class="box-body">';
                        $content .= '<p>Fyll ut feltene under for å legge til et nytt arrangement.</p>';
                        $content .= $this->getCreateForm();
                    $content .= '</div>';
                $content .= '</div>';
            $content .= '</div>';
            $content .= '<div class="col-md-6">';
                $content .= '<div class="box box-default">';
                    $content .= ' <div class="box-header with-border">';
                        $content .= '<h3 class="box-title">Deltakere per arrangement</h3>';
                        $content .= '<div class="box-tools pull-right">';
                            $content .= '<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>';
                        $content .= '</div>';
                    $content .= '</div>';
                    $content .= '<div class="box-body">';
                        $content .= '<div class="chart">';
                            $content .= '<canvas id="areaChart" style="height:250px"></canvas>';
                        $content .= '</div>';
                    $content .= '</div>';
                $content .= '</div>';
            $content .= '</div>';
        $content .= '</div>';
        $content .= '<div class="row">';

            $events = EventHandler::getEvents();

            // Sort this array so that we show newest events first.
            rsort($events);

            if (!empty($events)) {
                foreach ($events as $event) {
                    $content .= '<div class="col-md-6">';
                        $content .= '<div class="' . ($event->equals(EventHandler::getCurrentEvent()) ? 'box box-success' : 'box box-primary') . ' collapsed-box">';
                            $content .= '<div class="box-header">';
                                $content .= '<h3 class="box-title">' . $event->getTitle() . '</h3>';
                                $content .= '<div class="box-tools pull-right">';
                                    $content .= '<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>';
                                    $content .= '<div class="btn-group">';
                                        $content .= '<button type="button" class="btn btn-box-tool dropdown-toggle" data-toggle="dropdown"><i class="fa fa-wrench"></i></button>';
                                        $content .= '<ul class="dropdown-menu" role="menu">';
                                            $content .= '<li><a onClick="deleteEvent(' . $event->getId() . ')">Delete</a></li>';
                                        $content .= '</ul>';
                                    $content .= '</div>';
                                $content .= '</div>';
                            $content .= '</div>';
                            $content .= '<div class="box-body">';
                                $content .= $this->getEditForm($user, $event);
                            $content .= '</div>';
                        $content .= '</div>';
                    $content .= '</div>';
                }
            } else {
                $content .= '<div class="col-md-6">';
                    $content .= '<div class="box">';
                        $content .= '<div class="box-body">';
                            $content .= '<p>Det har ikke blitt opprettet noen arrangementer enda.</p>';
                        $content .= '</div>';
                    $content .= '</div>';
                $content .= '</div>';
            }

        $content .= '</div>';

		$content .= '<script src="pages/scripts/admin-event.js"></script>';

		return $content;
	}

    private function getCreateForm(): string {
        $content = null;

        $content .= '<form class="admin-event-create">';
            $content .= $this->getForm();
            $content .= '<button type="submit" class="btn btn-primary">Legg til</button>';
        $content .= '</form>';

        return $content;
    }

    private function getEditForm(User $user, Event $event): string {
        $content = null;

        $content .= '<form class="admin-event-edit">';
            $content .= '<input type="hidden" name="id" value="' . $event->getId() . '">';
            $content .= $this->getForm($event);
            $content .= '<div class="btn-group" role="group" aria-label="...">';
                $content .= '<button type="submit" class="btn btn-primary">Endre</button>';

                if ($user->hasPermission('*')) {
                    $currentEvent = EventHandler::getCurrentEvent();

                    // Prevent users from removing events that have already started, we don't want to delete old tickets etc.
                    if ($event->getBookingTime() >= $currentEvent->getBookingTime()) {
                        $content .= '<button type="button" class="btn btn-primary" onClick="deleteEvent(' . $event->getId() . ')">Fjern</button>';
                    }

                    // Allow user to transfer members from previus event if this event is the current one.
                    if ($event->equals($currentEvent)) {
                        $previousEvent = EventHandler::getPreviousEvent();

                        $content .= '<button type="button" class="btn btn-primary" onClick="copyMembers(' . $previousEvent->getId() . ')">Kopier medlemmer fra forrige arrangement</button>';
                    }
                }

                if ($event->getSeatmap() != null) {
                    $content .= '<button type="button" class="btn btn-primary" onClick="viewSeatmap(' . $event->getSeatmap()->getId() . ')">Vis setekart</button>';
                }

            $content .= '</div>';
        $content .= '</form>';

        return $content;
    }

	private function getForm(Event $event = null): string {
	    $event = $event ?? EventHandler::getCurrentEvent();
		$content = null;

        $content .= '<div class="row">';
            $content .= '<div class="col-md-6">';
                $content .= '<div class="form-group">';
                    $content .= '<label>Sted</label>';
                    $content .= '<select class="form-control" name="locationId" required>';

                        foreach (LocationHandler::getLocations() as $location) {
                            $content .= '<option value="' . $location->getId() . '">' . $location->getTitle() . '</option>';
                        }

                    $content .= '</select>';
                $content .= '</div>';
            $content .= '</div>';
            $content .= '<div class="col-md-6">';
                $content .= '<div class="form-group">';
                    $content .= '<label>Antall deltakere</label>';
                    $content .= '<input type="number" class="form-control" name="participantCount" value="' . $event->getParticipants() . '" required>';
                $content .= '</div>';
            $content .= '</div>';
        $content .= '</div>';
        $content .= '<div class="row">';
            $content .= '<div class="col-md-6">';
                $content .= '<div class="form-group">';
                    $content .= '<label>Billetsalg dato</label>';
                    $content .= '<div class="input-group">';
                        $content .= '<div class="input-group-addon">';
                            $content .= '<i class="fa fa-calendar"></i>';
                        $content .= '</div>';
                        $content .= '<input type="date" class="form-control" name="bookingDate" value="' . date('Y-m-d', $event->getBookingTime()) . '" required>';
                    $content .= '</div>';
                $content .= '</div>';
            $content .= '</div>';
            $content .= '<div class="col-md-6">';
                $content .= '<div class="form-group">';
                    $content .= '<label>Billetsalg tid</label>';
                    $content .= '<div class="input-group">';
                        $content .= '<div class="input-group-addon">';
                            $content .= '<i class="fa fa-clock-o"></i>';
                        $content .= '</div>';
                        $content .= '<input type="time" class="form-control" name="bookingTime" value="' . date('H:i:s', $event->getBookingTime()) . '" required>';
                    $content .= '</div>';
                $content .= '</div>';
            $content .= '</div>';
        $content .= '</div>';
        $content .= '<div class="row">';
            $content .= '<div class="col-md-6">';
                $content .= '<div class="form-group">';
                    $content .= '<label>Gruppe-seating dato</label>';
                    $content .= '<div class="input-group">';
                        $content .= '<div class="input-group-addon">';
                            $content .= '<i class="fa fa-calendar"></i>';
                        $content .= '</div>';
                        $content .= '<input type="date" class="form-control" name="prioritySeatingDate" value="' . date('Y-m-d', $event->getPrioritySeatingTime()) . '" required>';
                    $content .= '</div>';
                $content .= '</div>';
            $content .= '</div>';
            $content .= '<div class="col-md-6">';
                $content .= '<div class="form-group">';
                    $content .= '<label>Gruppe-seating tid</label>';
                    $content .= '<div class="input-group">';
                        $content .= '<div class="input-group-addon">';
                            $content .= '<i class="fa fa-clock-o"></i>';
                        $content .= '</div>';
                        $content .= '<input type="time" class="form-control" name="prioritySeatingTime" value="' . date('H:i:s', $event->getPrioritySeatingTime()) . '" required>';
                    $content .= '</div>';
                $content .= '</div>';
            $content .= '</div>';
        $content .= '</div>';
        $content .= '<div class="row">';
            $content .= '<div class="col-md-6">';
                $content .= '<div class="form-group">';
                    $content .= '<label>Seating dato</label>';
                    $content .= '<div class="input-group">';
                        $content .= '<div class="input-group-addon">';
                            $content .= '<i class="fa fa-calendar"></i>';
                        $content .= '</div>';
                        $content .= '<input type="date" class="form-control" name="seatingDate" value="' . date('Y-m-d', $event->getSeatingTime()) . '" required>';
                    $content .= '</div>';
                $content .= '</div>';
            $content .= '</div>';
            $content .= '<div class="col-md-6">';
                $content .= '<div class="form-group">';
                    $content .= '<label>Seating tid</label>';
                    $content .= '<div class="input-group">';
                        $content .= '<div class="input-group-addon">';
                            $content .= '<i class="fa fa-clock-o"></i>';
                        $content .= '</div>';
                        $content .= '<input type="time" class="form-control" name="seatingTime" value="' . date('H:i:s', $event->getSeatingTime()) . '" required>';
                    $content .= '</div>';
                $content .= '</div>';
            $content .= '</div>';
        $content .= '</div>';
        $content .= '<div class="row">';
            $content .= '<div class="col-md-6">';
                $content .= '<div class="form-group">';
                    $content .= '<label>Start dato</label>';
                    $content .= '<div class="input-group">';
                        $content .= '<div class="input-group-addon">';
                            $content .= '<i class="fa fa-calendar"></i>';
                        $content .= '</div>';
                        $content .= '<input type="date" class="form-control" name="startDate" value="' . date('Y-m-d', $event->getStartTime()) . '" required>';
                    $content .= '</div>';
                $content .= '</div>';
            $content .= '</div>';
            $content .= '<div class="col-md-6">';
                $content .= '<div class="form-group">';
                    $content .= '<label>Start tid</label>';
                    $content .= '<div class="input-group">';
                        $content .= '<div class="input-group-addon">';
                            $content .= '<i class="fa fa-clock-o"></i>';
                        $content .= '</div>';
                        $content .= '<input type="time" class="form-control" name="startTime" value="' . date('H:i:s', $event->getStartTime()) . '" required>';
                    $content .= '</div>';
                $content .= '</div>';
            $content .= '</div>';
        $content .= '</div>';
        $content .= '<div class="row">';
            $content .= '<div class="col-md-6">';
                $content .= '<div class="form-group">';
                    $content .= '<label>Slutt dato</label>';
                    $content .= '<div class="input-group">';
                        $content .= '<div class="input-group-addon">';
                            $content .= '<i class="fa fa-calendar"></i>';
                        $content .= '</div>';
                        $content .= '<input type="date" class="form-control" name="endDate" value="' . date('Y-m-d', $event->getEndTime()) . '" required>';
                    $content .= '</div>';
                $content .= '</div>';
            $content .= '</div>';
            $content .= '<div class="col-md-6">';
                $content .= '<div class="form-group">';
                    $content .= '<label>Slutt tid</label>';
                    $content .= '<div class="input-group">';
                        $content .= '<div class="input-group-addon">';
                            $content .= '<i class="fa fa-clock-o"></i>';
                        $content .= '</div>';
                        $content .= '<input type="time" class="form-control" name="endTime" value="' . date('H:i:s', $event->getEndTime()) . '" required>';
                    $content .= '</div>';
                $content .= '</div>';
            $content .= '</div>';
        $content .= '</div>';

		return $content;
	}
}