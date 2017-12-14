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
require_once 'admin.php';

class AdminMemberListPage extends AdminPage {
    public function canAccess(User $user): bool{
        return $user->hasPermission('admin.memberlist');
    }

    public function hasParent(): bool {
		return true;
	}

    public function getTitle(): ?string {
 		return 'Medlemsliste';
 	}

    public function getContent(User $user = null): string {
 		$content = null;
        $content .= '<div class="row">';
            $content .= '<div class="col-md-4">';
                $content .= '<div class="box">';
                    $content .= '<div class="box-body">';
                        $content .= '<p>Velg år du vil hente ut medlemsliste for, maksimal alder på medlemmene du vil ha med og et format du vil ha listen i.</p>';
                        $content .= '<form class="memberlist">';
                            $content .= '<div class="form-group">';
                                $content .= '<label>År</label>';
                                $content .= '<select class="form-control" name="year">';
                                    $events = EventHandler::getEvents();

                                    for ($year = date('Y', reset($events)->getStartTime()); $year <= date('Y'); $year++) {
                                        if ($year == date('Y')) {
                                            $content .= '<option value="' . $year . '" selected>' . $year . '</option>';
                                        } else {
                                            $content .= '<option value="' . $year . '">' . $year . '</option>';
                                        }
                                    }

                                $content .= '</select>';
                            $content .= '</div>';
                            $content .= '<div class="form-group">';
                                $content .= '<label>Aldersgrense</label>';
                                $content .= '<select class="form-control" name="ageLimit">';

                                    for ($age = 1; $age <= 100; $age++) {
                                        if ($age == 20) {
                                            $content .= '<option value="' . $age . '" selected>' . $age . ' År</option>';
                                        } else {
                                            $content .= '<option value="' . $age . '">' . $age . ' År</option>';
                                        }
                                    }

                                $content .= '</select>';
                            $content .= '</div>';
                            $content .= '<div class="form-group">';
                                $content .= '<label>Format</label>';
                                $content .= '<select class="form-control" name="format">';
                                    $content .= '<option value="html" selected>Tekst (.html)</option>';
                                    $content .= '<option value="csv">Regneark (.csv)</option>';
                                $content .= '</select>';
                            $content .= '</div>';
                            $content .= '<button type="submit" class="btn btn-primary">Hent</button>';
                        $content .= '</form>';
                    $content .= '</div>';
                $content .= '</div>';
            $content .= '</div>';
		$content .= '</div>';

    	$content .= '<script src="pages/scripts/admin-memberlist.js"></script>';

		return $content;
	}
}