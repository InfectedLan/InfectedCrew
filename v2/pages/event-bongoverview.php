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
require_once 'handlers/bongentitlementhandler.php';
require_once 'handlers/grouphandler.php';
require_once 'objects/bongentitlement.php';

if (Session::isAuthenticated()) {
    $user = Session::getCurrentUser();

    if ($user->hasPermission('nfc.bong.management')) {
        echo '<h3>Bong-oversikt</h3>';
        echo '<p>Dette er en placeholder for et bedre system i v3, slik at du skal kunne ha oversikt over hva som skjer i bong-verden</p>';

        echo '<h3>Bong-entitlements</h3>';
        $bongs = BongTypeHandler::getBongTypes();
        foreach($bongs as $bong) {
            $entitlements = BongEntitlementHandler::getBongEntitlements($bong);
            echo '<h4>' . $bong->getName() . '</h4>';
            echo '<i>' . $bong->getDescription() . '</i>';
            echo "<table>";
            echo "<tr>";
            echo "<td>Type</td>";
            echo "<td>Argument</td>";
            echo "<td>Mengde</td>";
            echo "<td>Type</td>";
            echo "</tr>";
            foreach($entitlements as $entitlement) {
                echo "<tr>";
                echo "<td>" . ($entitlement->getEntitlementType() == BongEntitlement::ENTITLEMENT_TYPE_USER ? "Bruker" : "Crew") . "</td>";
                echo "<td>";
                    if($entitlement->getEntitlementType() == BongEntitlement::ENTITLEMENT_TYPE_USER) {
                        $user = UserHandler::getUser($entitlement->getEntitlementArg());
                        if($user == null) {
                            echo '<b>FEIL</b>';
                        } else {
                            echo $user->getDisplayName();
                        }
                    } else {
                        if($entitlement->getEntitlementArg() == 0) {
                            echo 'Alle crew';
                        } else {
                            $group = GroupHandler::getGroup($entitlement->getEntitlementArg());
                            if($group == null) {
                                echo '<b>FEIL</b>';
                            } else {
                                echo $group->getName();
                            }
                        }
                    }
                echo "</td>";
                echo "<td>" . $entitlement->getEntitlementAmt() . "</td>";
                echo "<td>" . ($entitlement->getEntitlementType() == BongEntitlement::APPEND_TYPE_ADDITIVE ? "Additiv" : "Eksklusiv") . "</td>";
                //echo "<td>" . $transaction->getTimestamp() . "</td>";
                echo "</tr>";
            }
            echo "</table>";
        }
    } else {
        echo '<p>Du har ikke tilgang til dette.</p>';
    }
}
?>
