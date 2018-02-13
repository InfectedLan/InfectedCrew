<?php
/**
 * This file is part of InfectedCrew.
 *
 * Copyright (C) 2018 Infected <https://infected.no/>.
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

if (Session::isAuthenticated()) {
    $user = Session::getCurrentUser();

    if ($user->hasPermission('event.nfcmgmt')) {
        echo '<h1>Koble NFC-kort til bruker</h1>';

        echo '<p>Her kan du manuelt koble et NFC-kort til en bruker.</p>';
        echo '<form method="post" class="nfc-submit">';
            echo '<select class="chosen-select" name="userId" data-placeholder="Velg en bruker...">';
            echo '<option value="0"></option>';
            $userList = UserHandler::getMemberUsers();
            foreach ($userList as $userValue) {
                echo '<option value="' . $userValue->getId() . '">' . $userValue->getDisplayName() . '</option>';
            }
            echo '</select>';
            echo '<input type="text" minlength="16" maxlength="16" name="cardId"><input type="submit" value="Legg til kort" >';
        echo '</form>';

        echo '<script>$(document).ready(function()
            {
                $(".nfc-submit").on("submit", function(event) {
                    event.preventDefault();
                    $.post("../api/rest/nfc/user/create.php", $(this).serialize(), function(result){ 
                        if(result.result) {
                            info("Suksess! Kortet ble koblet til.");
                        } else {
                            error(result.message);
                        } 
                    });
                })
            });
            </script>';

    } else {
        echo 'Du har ikke rettigheter til dette.';
    }
} else {
    echo 'Du er ikke logget inn.';
}
?>
