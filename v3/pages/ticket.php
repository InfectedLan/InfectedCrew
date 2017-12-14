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
require_once 'handlers/tickethandler.php';
require_once 'page.php';

class TicketPage extends Page {
    private $ticket;

    public function __construct() {
        $this->ticket = isset($_GET['id']) ? TicketHandler::getTicket($_GET['id']) : null;
    }

	public function canAccess(User $user): bool {
        return $user->hasPermission('user.ticket');
    }

	public function getTitle(): ?string {
        return 'Billett';
	}

    public function getContent(User $user = null): string {
		$content = null;
        $content .= '<div class="row">';
            $content .= '<div class="col-md-6">';

                if ($this->ticket != null) {
                    $content .= '<div class="box box-default">';
                        $content .= '<div class="box-header with-border">';
                            $content .= '<h3 class="box-title">Billett #' . $this->ticket->getId() . '</h3>';
                            $content .= '<div class="box-tools pull-right">';
                                $content .= '<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>';
                            $content .= '</div>';
                        $content .= '</div>';
                        $content .= '<div class="box-body">';
                            $content .= '<table class="table">';
                                $content .= '<tr>';
                                    $content .= '<th>Billettnummer</th>';
                                    $content .= '<td>' . $this->ticket->toString() . '</td>';
                                $content .= '</tr>';
                                $content .= '<tr>';
                                    $content .= '<th>Type</th>';
                                    $content .= '<td>' . $this->ticket->getType()->getTitle() . '</td>';
                                $content .= '</tr>';
                                $content .= '<tr>';
                                    $buyerUser = $this->ticket->getBuyer();

                                    $content .= '<th>Kjøpt av</th>';
                                    $content .= '<td><a href="index.php?page=user-profile&userId=' . $buyerUser->getId()  . '">' . $buyerUser->getFullname() . '</a></td>';
                                $content .= '</tr>';
                                $content .= '<tr>';
                                    $ticketUser = $this->ticket->getUser();

                                    $content .= '<th>Brukes av</th>';
                                    $content .= '<td><a href="index.php?page=user-profile&userId=' . $ticketUser->getId()  . '">' . $ticketUser->getFullname() . '</a></td>';
                                $content .= '</tr>';
                                $content .= '<tr>';
                                    $seaterUser = $this->ticket->getSeater();

                                    $content .= '<th>Plasseres av</th>';
                                    $content .= '<td><a href="index.php?page=user-profile&userId=' . $seaterUser->getId()  . '">' . $seaterUser->getFullname() . '</a></td>';
                                $content .= '</tr>';

                                if ($this->ticket->isSeated()) {
                                    $content .= '<tr>';
                                        $content .= '<th>Plass</th>';
                                        $content .= '<td>' . $this->ticket->getSeat()->getString() . '</td>';
                                    $content .= '</tr>';
                                }

                                $content .= '<tr>';
                                    $content .= '<th>Kan returneres?</th>';
                                    $content .= '<td>' . Localization::getLocale($this->ticket->isRefundable() ? 'yes' : 'no') . '</td>';
                                $content .= '</tr>';
                                $content .= '<tr>';
                                    $content .= '<th>Sjekket inn?</th>';
                                    $content .= '<td>' . Localization::getLocale($this->ticket->isCheckedIn() ? 'yes' : 'no') . '</td>';
                                $content .= '</tr>';
                            $content .= '</table>';
                        $content .= '</div>';
                    $content .= '</div>';
                } else {
                    $content .= '<div class="box">';
                        $content .= '<div class="box-body">';
                            $content .= '<p>Denne billetten finnes ikke.</p>';
                        $content .= '</div>';
                    $content .= '</div>';
                }

            $content .= '</div>';
        $content .= '</div>';

		return $content;
	}
}