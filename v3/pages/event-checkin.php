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

require_once 'settings.php';
require_once 'handlers/eventhandler.php';
require_once 'event.php';

class EventCheckInPage extends EventPage {
    public function canAccess(User $user): bool {
        return $user->hasPermission('event.checkin');
    }

	public function hasParent(): bool {
		return true;
	}

	public function getTitle(): ?string {
		return 'Innsjekk';
	}

    public function getContent(User $user = null): string {
		$content = null;
        $content .= '<div class="row">';
            $content .= '<div class="col-md-6">';
                $content .= '<div class="box">';
                    $content .= '<div class="box-header with-border">';
                        $content .= '<h3 class="box-title">Sjekk inn en billett</h3>';
                        $content .= '<div class="box-tools pull-right">';
                            $content .= '<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>';
                        $content .= '</div>';
                    $content .= '</div>';
                    $content .= '<div class="box-body">';

                        $event = EventHandler::getCurrentEvent();
                        $season = date('m', $event->getStartTime()) == 2 ? 'Vinter' : 'Høst';
                        $eventName = !empty($event->getTheme()) ? $event->getTheme() : $season . '_' . date('Y', $event->getStartTime());

                        $content .= '<form class="event-checkin-fetch">';
                            $content .= '<div class="form-group">';
                                $content .= '<label>Billettnummer</label>';
                                $content .= '<div class="input-group">';
                                    $content .= '<input type="text" class="form-control" name="ticketId" placeholder="Skriv inn billettnummer her...">';
                                    $content .= '<span class="input-group-btn">';
                                        $content .= '<button type="submit" class="btn btn-primary">Sjekk inn</button>';
                                    $content .= '</span>';
                                $content .= '</div>';
                            $content .= '</div>';
                        $content .= '</form>';
                        $content .= '<div class="ticket-details">Dette er en placeholder.</div>';
                    $content .= '</div>';
                $content .= '</div>';
            $content .= '</div>';
            $content .= '<div class="col-md-6">';
                $content .= '<div class="box box-default">';
                    $content .= '<div class="box-header">';
                        $content .= '<h3 class="box-title">Innsjekkede deltakere (%)</h3>';
                        $content .= '<div class="box-tools pull-right">';
                            $content .= '<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>';
                        $content .= '</div>';
                    $content .= '</div>';
                    $content .= '<div class="box-body">';

                        // TODO: Draw chart of checked in tickets here, pie chart or knob showing percentage.

                    $content .= '</div>';
                $content .= '</div>';
            $content .= '</div>';
        $content .= '</div>';

        $content .= '<script src="pages/scripts/event-checkin.js"></script>';

		return $content;
	}
}
