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
require_once 'handlers/bongtransactionhandler.php';
require_once 'handlers/bongtypehandler.php';

if (Session::isAuthenticated()) {
    $user = Session::getCurrentUser();

    if ($user->hasPermission('event.nfcmgmt')) {
        echo '<h3>Bongtransaksjoner</h3>';
        echo '<p>Liste over alle transaksjoner som har skjedd for dette eventet.</p>';
        $bongs = BongTypeHandler::getBongTypes();
        foreach($bongs as $bong) {
            echo "<h3>" . $bong->getName() . "</h3>";
            $transactions = BongTransactionHandler::getBongTransactions($bong);
            echo "<table>";
            echo "<tr>";
                echo "<td>Bruker</td>";
                echo "<td>Mengde</td>";
                echo "<td>Tidspunkt</td>";
                echo "<td>Ansvarlig</td>";
            echo "</tr>";
            foreach($transactions as $transaction) {
                echo "<tr>";
                    echo "<td>" . $transaction->getUser()->getDisplayName() . "</td>";
                    echo "<td>" . $transaction->getTransactionAmount() . "</td>";
                    echo "<td>" . date('Y-m-d H:i:s', $transaction->getTimestamp() ) . "</td>";
                    echo "<td>" . $transaction->getTransactionHandler()->getDisplayName() . "</td>";
                echo "</tr>";
            }
            echo "<tr>";
            echo "<td>Totalt " . count($transactions) ." transaksjoner.</td>";
            echo "</tr>";
            echo "</table>";
        }
    } else {
        echo '<p>Du har ikke tilgang til dette.</p>';
    }
}
?>
