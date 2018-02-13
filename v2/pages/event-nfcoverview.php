<?php
/**
 * This file is part of InfectedCrew.
 *
 * Copyright (C) 2015 Infected <http://infected.no/>.
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
require_once 'handlers/bongtypehandler.php';
require_once 'handlers/roomhandler.php';

if (Session::isAuthenticated()) {
    $user = Session::getCurrentUser();

    if ($user->hasPermission('nfc.management')) {
        echo '<h3>Nfc-oversikt</h3>';
        echo '<p>Dette er en placeholder for et bedre system i v3, slik at du skal kunne ha oversikt over hva som skjer i nfc-verden</p>';

        echo '<h3>Rom</h3>';
        $rooms = RoomHandler::getRooms();
        foreach($rooms as $room) {
            echo '<h4>' . $room->getName() . '</h4>';
            $entries = RoomHandler::getLogEntriesInRoom($room);
            echo "<table>";
            echo "<tr>";
            echo "<td>Bruker</td>";
            echo "<td>Tidspunkt</td>";
            echo "<td>Gyldig?</td>";
            echo "</tr>";
            foreach($entries as $entry) {
                echo "<tr>";
                $card = $entry->getCard();
                echo "<td>" . $card->getUser()->getDisplayName() . "</td>";
                echo "<td>" . date('Y-m-d H:i:s', $entry->getTime() ). "</td>";
                echo "<td>" . ($entry->isLegalPass() ? "Ja" : "Nei") . "</td>";
                //echo "<td>" . $transaction->getTimestamp() . "</td>";
                echo "</tr>";
            }
            echo "<tr>";
            echo "<td>Totalt " . count($entries) ." brukere.</td>";
            echo "</tr>";
            echo "</table>";
        }
    } else {
        echo '<p>Du har ikke tilgang til dette.</p>';
    }
}
?>
