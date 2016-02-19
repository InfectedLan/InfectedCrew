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
require_once 'handlers/compohandler.php';
require_once 'handlers/eventhandler.php';
require_once 'handlers/clanhandler.php';

if (Session::isAuthenticated()) {
    $user = Session::getCurrentUser();

    if ($user->hasPermission('compo.management')) {
	echo '<script src="scripts/compo.js"></script>';
        $id = $_GET["id"];
        $clan = ClanHandler::getClan($id);
	$compo = $clan->getCompo();

        $playingMembers = ClanHandler::getPlayingClanMembers($clan);
        $stepins = ClanHandler::getStepInClanMembers($clan);

        echo '<h1>' . $clan->getTag() . ' ' . $clan->getName() . '</h1>';

        echo '<h3>Medlemmer</h3>';

        echo '<ul>';
        foreach($playingMembers as $member) {
            echo '<li><a href="index.php?page=user-profile&id=' . $member->getId() . '">' . $member->getCompoDisplayName() . '</a></li>';
        }
        echo '</ul>';

        echo '<h3>Step-in medlemmer</h3>';
        
        echo '<ul>';
        foreach($stepins as $member) {
            echo '<li><a href="index.php?page=user-profile&id=' . $member->getId() . '">' . $member->getCompoDisplayName() . '</a></li>';
        }
        echo '</ul>';

	echo '<h3>Admin-valg</h3>';
	if($clan->isQualified($compo)) {
	    echo '<input type="button" value="Diskvalifiser" onClick="disqualifyClan(' . $clan->getId() . ')" />';
	} else {
	    echo '<input type="button" value="Kvalifiser" onClick="qualifyClan(' . $clan->getId() . ')" />';
	}
	echo '<input type="button" value="Slett" onClick="deleteClan(' . $clan->getId() . ', ' . (ClanHandler::getClanMemberCount($clan) > 0 ? 'true' : 'false') . ')" />';

    } else {
	echo '<p>Du har ikke rettigheter til dette!</p>';
    }
} else {
    echo '<p>Du er ikke logget inn!</p>';
}
?>
