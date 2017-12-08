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
require_once 'handlers/permissionhandler.php';
require_once 'admin.php';

class AdminWebSocketConsolePage extends AdminPage {
    public function canAccess(User $user): bool{
        return $user->hasPermission('admin.websocket');
    }

    public function hasParent(): bool {
        return true;
    }

    public function getTitle(): ?string {
        return 'Websocket-konsoll';
    }

    public function getContent(User $user = null): string {
        $content = null;
        $content .= '<div class="row">';
            $content .= '<div class="col-md-6">';
                $content .= '<div class="box box-default">';
                    $content .= '<div class="box-header with-border">';
                        $content .= '<h3 class="box-title">Console</h3>';
                        $content .= '<div class="box-tools pull-right">';
                            $content .= '<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>';
                        $content .= '</div>';
                    $content .= '</div>';
                    $content .= '<div class="box-body">';
                        $content .= '<div class="consoleArea">Vennligst vent...<br></div>';
                        $content .= '<div class="inputArea">';
                            $content .= '<input type="text" style="width: 100%;" placeholder="Skriv kommandoer her">';
                        $content .= '</div>';
                    $content .= '</div>';
                $content .= '</div>';
            $content .= '</div>';
        $content .= '</div>';

        $content .= '<script src="../api/scripts/websocket.js"></script>';
        $content .= '<script src="pages/scripts/admin-websocket-console.js"></script>';

        return $content;
    }
}