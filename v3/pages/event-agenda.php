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
require_once 'handlers/agendahandler.php';
require_once 'event.php';

class EventAgendaPage extends EventPage {
    public function canAccess(User $user): bool{
        return $user->hasPermission('event.agenda');
    }

	public function hasParent(): bool {
		return true;
	}

	public function getTitle(): ?string {
		return 'Agenda';
	}

    public function getContent(User $user = null): string {
		$content = null;
        //$content .= '<div class="row">';
            //$content .= '<div class="col-md-6">';

                /*
                $agendaList = AgendaHandler::getAgendas();

                if (!empty($agendaList)) {
                    foreach ($agendaList as $agenda) {
                        $content .= '<div class="box box-default">';
                            $content .= '<div class="box-header with-border">';
                                $content .= '<h3 class="box-title">' . $agenda->getTitle() . '</h3>';
                                $content .= '<div class="box-tools pull-right">';
                                    $content .= '<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>';
                                $content .= '</div>';
                            $content .= '</div>';
                            $content .= '<div class="box-body">';
                                $content .= '<form class="agenda-edit" method="post">';
                                    $content .= '<input type="hidden" name="id" value="' . $agenda->getId() . '">';
                                    $content .= '<div class="form-group">';
                                        $content .= '<label>Navn</label>';
                                        $content .= '<input type="text" class="form-control" name="title" placeholder="Skriv inn et navn her..." value="' . $agenda->getTitle() . '" required>';
                                    $content .= '</div>';
                                    $content .= '<div class="form-group">';
                                        $content .= '<label>Beskrivelse</label>';
                                        $content .= '<textarea class="form-control" rows="3" name="description" placeholder="Skriv inn en beskrivese her..." required>';

                                            $content .= $agenda->getDescription();

                                        $content .= '</textarea>';
                                    $content .= '</div>';
                                    $content .= '<div class="form-group">';
                                        $content .= '<label>Tid og dato:</label>';
                                        $content .= '<div class="input-group">';
                                            $content .= '<div class="input-group-addon">';
                                                $content .= '<i class="fa fa-clock-o"></i>';
                                            $content .= '</div>';
                                            $content .= '<input type="text" class="form-control pull-right" name="datetime" id="datetime" value="' . date('Y-m-d H:i:s', $agenda->getStartTime()) . '" required>';
                                        $content .= '</div><!-- /.input group -->';
                                    $content .= '</div><!-- /.form group -->';
                                    $content .= '<div class="btn-group" role="group" aria-label="...">';
                                        $content .= '<button type="submit" class="btn btn-primary">Endre</button>';
                                        $content .= '<button type="button" class="btn btn-primary" onClick="removeAgenda(' . $agenda->getId() . ')">Fjern</button>';
                                    $content .= '</div>';
                                $content .= '</form>';
                            $content .= '</div>';
                        $content .= '</div>';
                    }
                } else {
                    $content .= '<div class="box">';
                        $content .= '<div class="box-body">';
                            $content .= '<p>Det har ikke blitt opprettet noen agenda\'er enda.</p>';
                        $content .= '</div>';
                    $content .= '</div>';
                }

            /*

            $content .= '</div><!--/.col (left) -->';
            $content .= '<div class="col-md-6">';
                $content .= '<div class="box">';
                    $content .= '<div class="box-header">';
                        $content .= '<h3 class="box-title">Legg til ny agenda</h3>';
                    $content .= '</div><!-- /.box-header -->';
                    $content .= '<div class="box-body">';

                $content .= '<form class="agenda-add" method="post">';
                            $content .= '<div class="form-group">';
                                $content .= '<label>Navn</label>';
                                $content .= '<input type="text" class="form-control" name="title" placeholder="Skriv inn et navn her..." required>';
                            $content .= '</div>';
                            $content .= '<div class="form-group">';
                                $content .= '<label>Beskrivelse</label>';
                                $content .= '<textarea class="form-control" rows="3" name="description" placeholder="Skriv inn en beskrivese her..." required></textarea>';
                            $content .= '</div>';
                            $content .= '<div class="form-group">';
                                $content .= '<label>Tid og dato:</label>';
                                $content .= '<div class="input-group">';
                                    $content .= '<div class="input-group-addon">';
                                        $content .= '<i class="fa fa-clock-o"></i>';
                                    $content .= '</div>';
                                    $content .= '<input type="text" class="form-control pull-right" id="datetime" required>';
                                $content .= '</div><!-- /.input group -->';
                            $content .= '</div><!-- /.form group -->';
                            $content .= '<button type="submit" class="btn btn-primary">Legg til</button>';
                        $content .= '</form>';
                    $content .= '</div><!-- /.box-body -->';
                $content .= '</div><!-- /.box -->';
            $content .= '</div><!--/.col (right) -->';
            */

        $content .= '<div class="row">';
            $content .= '<div class="col-md-6">';
                $content .= '<div class="box box-default">';
                    $content .= '<div class="box-header with-border">';
                        $content .= '<h3 class="box-title">Legg til ny agenda</h3>';
                        $content .= '<div class="box-tools pull-right">';
                            $content .= '<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>';
                        $content .= '</div>';
                    $content .= '</div>';
                    $content .= '<div class="box-body">';
                        $content .= '<form class="event-agenda-create">';
                            $content .= $this->getForm();
                            $content .= '<button type="submit" class="btn btn-primary">Legg til</button>';
                        $content .= '</form>';
                    $content .= '</div>';
                $content .= '</div>';
            $content .= '</div>';

            $agendas = AgendaHandler::getAgendas();

            // Sort this array so that we show newest agendas first.
            rsort($agendas);

            if (!empty($agendas)) {
                foreach ($agendas as $agenda) {
                    $content .= '<div class="col-md-6">';
                        $content .= '<div class="box box-default">';
                            $content .= '<div class="box-header with-border">';
                                $content .= '<h3 class="box-title">' . $agenda->getTitle() . '</h3>';
                                $content .= '<div class="box-tools pull-right">';
                                    $content .= '<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>';
                                    $content .= '<div class="btn-group">';
                                        $content .= '<button type="button" class="btn btn-box-tool dropdown-toggle" data-toggle="dropdown"><i class="fa fa-wrench"></i></button>';
                                        $content .= '<ul class="dropdown-menu" role="menu">';
                                            $content .= '<li><a onClick="deleteAgenda(' . $agenda->getId() . ')">Delete</a></li>';
                                        $content .= '</ul>';
                                    $content .= '</div>';
                                $content .= '</div>';
                            $content .= '</div>';
                            $content .= '<div class="box-body">';
                                $content .= '<form class="event-agenda-edit">';
                                    $content .= '<input type="hidden" name="id" value="' . $agenda->getId() . '">';
                                    $content .= $this->getForm();
                                    $content .= '<div class="btn-group" role="group" aria-label="...">';
                                        $content .= '<button type="submit" class="btn btn-primary">Endre</button>';
                                        $content .= '<button type="button" class="btn btn-primary" onClick="removeAgenda(' . $agenda->getId() . ')">Fjern</button>';
                                    $content .= '</div>';
                                $content .= '</form>';
                            $content .= '</div>';
                        $content .= '</div>';
                    $content .= '</div>';
                }
            }

        $content .= '</div>';

        $content .= '<script src="pages/scripts/event-agenda.js"></script>';

		return $content;
    }

    private function getForm(Agenda $agenda = null): string {
        $content = null;
        $content .= '<div class="form-group">';
            $content .= '<label>Navn</label>';
            $content .= '<input type="text" class="form-control" name="title" placeholder="Skriv inn et navn her..." ' . ($agenda != null ? $agenda->getTitle() : null) . ' required>';
        $content .= '</div>';
        $content .= '<div class="form-group">';
            $content .= '<label>Beskrivelse</label>';
            $content .= '<textarea class="form-control" rows="3" name="description" placeholder="Skriv inn en beskrivese her..." ' . ($agenda != null ? $agenda->getDescription() : null) . ' required></textarea>';
        $content .= '</div>';
        $content .= '<div class="row">';
            $content .= '<div class="col-md-6">';
                $content .= '<div class="form-group">';
                    $content .= '<label>Tid og dato:</label>';
                    $content .= '<div class="input-group">';
                        $content .= '<div class="input-group-addon">';
                            $content .= '<i class="fa fa-calendar"></i>';
                        $content .= '</div>';
                        $content .= '<input type="date" class="form-control" name="date" value="' . date('Y-m-d', $agenda != null ? $agenda->getStartTime() : time()) . '" required>';
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
                        $content .= '<input type="time" class="form-control" name="time" value="' . date('H:i:s', $agenda != null ? $agenda->getStartTime() : time()) . '" required>';
                    $content .= '</div>';
                $content .= '</div>';
            $content .= '</div>';
        $content .= '</div>';

        return $content;
    }
}
