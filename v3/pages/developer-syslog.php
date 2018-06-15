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
require_once 'handlers/sysloghandler.php';
require_once 'developer.php';

class DeveloperSyslogPage extends DeveloperPage {
    public function canAccess(User $user): bool{
        return $user->hasPermission('developer.syslog');
    }

    public function hasParent(): bool {
        return true;
    }

    public function getTitle(): ?string {
        return 'Systemlogg';
    }

    public function getContent(User $user = null): string {
        $lineCount = 100;

        $content = null;
        $content .= '<div class="box box-default">';
            $content .= '<div class="box-header with-border">';
                $content .= '<h3 class="box-title">Logg - siste ' . $lineCount . ' meldinger</h3>';
                $content .= '<div class="box-tools pull-right">';
                    $content .= '<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>';
                $content .= '</div>';
            $content .= '</div>';
            $content .= '<div class="box-body">';
                $content .= '<table class="table table-bordered table-striped" data-table>';
                    $content .= '<thead>';
                        $content .= '<tr>';
                            $content .= '<th>Alvorsgrad</th>';
                            $content .= '<th>Kilde</th>';
                            $content .= '<th>Melding</th>';
                            $content .= '<th>Klokkeslett</th>';
                            $content .= '<th>Bruker</th>';
                            $content .= '<th>Metadata</th>';
                        $content .= '</tr>';
                    $content .= '</thead>';
                    $content .= '<tbody>';

                        foreach (SyslogHandler::getLastEntries($lineCount) as $entry) {
                            $causingUser = $entry->getUser();

                            $content .= '<tr>';
                                $content .= '<td>' . SyslogHandler::getSeverityString($entry->getSeverity()) . '</td>';
                                $content .= '<td>' . $entry->getSource() . '</td>';
                                $content .= '<td>' . $entry->getMessage() . '</td>';
                                $content .= '<td>' . date('Y-m-d H:i:s', $entry->getTimestamp()) . '</td>';
                                $content .= '<td>' . ($causingUser != null ? '<a href="index.php?page=user-profile&userId=' . $causingUser->getId() . '">' . $causingUser->getUsername() . '(' . $causingUser->getId() . ')</a>' : 'Ingen') . '</td>';

                                if (!is_array($entry->getMetadata()) || count($entry->getMetadata()) > 0) {
                                    $content .= '<td><textarea rows="2" cols="25">' . json_encode($entry->getMetadata(), JSON_PRETTY_PRINT) . '</textarea></td>';
                                } else {
                                    $content .= '<td><i>Ingen metadata</i></td>';
                                }

                            $content .= '</tr>';
                        }
                    $content .= '</tbody>';
                    $content .= '<tfoot>';
                        $content .= '<tr>';
                            $content .= '<th>Alvorsgrad</th>';
                            $content .= '<th>Kilde</th>';
                            $content .= '<th>Melding</th>';
                            $content .= '<th>Klokkeslett</th>';
                            $content .= '<th>Bruker</th>';
                            $content .= '<th>Metadata</th>';
                        $content .= '</tr>';
                    $content .= '</tfoot>';
                $content .= '</table>';
            $content .= '</div>';
        $content .= '</div>';

        $content .= '<script src="pages/scripts/developer-syslog.js"></script>';

        return $content;
    }
}