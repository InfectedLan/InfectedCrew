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
require_once 'objects/compo.php';

if (Session::isAuthenticated()) {
	$user = Session::getCurrentUser();

	if ($user->hasPermission('compo.management')) {
        $id = $_GET["id"];
        $compo = CompoHandler::getCompo($id);

        if($compo != null) {
            //echo '<h1>' . $compo->getTitle() . '</h1>';
            $plugin = CompoPluginHandler::getPluginObjectOrDefault($compo->getPluginName());
            echo '<hr>';
            echo '<a href="index.php?page=compo-view&id=' . $compo->getId() . '">Oversikt</a> ';
            echo '<a href="index.php?page=compo-clans&id=' . $compo->getId() . '">Påmeldte klaner</a> ';
            echo '<a href="index.php?page=compo-matches&id=' . $compo->getId() . '">Matcher(Liste)</a> ';
            if($user->hasPermission('compo.bracketmanagement')) {
                echo '<a href="index.php?page=compo-brackets&id=' . $compo->getId() . '">Rediger brackets</a> ';
            }
            if($user->hasPermission('compo.chat')) {
                echo '<a href="index.php?page=compo-chat&id=' . $compo->getId() . '">Chatter</a> ';
            }
            if($user->hasPermission('compo.edit') && $compo->getConnectionType() == Compo::CONNECTION_TYPE_SERVER) {
                echo '<a href="index.php?page=compo-servers&id=' . $compo->getId() . '">Servere</a> ';		
            }
            foreach($plugin->getAdminHeaderEntries() as $headerKey => $headerEntry) {
                echo '<a href="index.php?page=compo-pluginpage&id=' . $compo->getId() . '&pluginPage=' . $headerEntry . '">' . $headerKey . '</a>';
            }
            echo '<hr>';
            echo '<h1>' . $compo->getTag() . ' - ' . $compo->getTitle() . '(<i>' . $compo->getName() . '</i>)</h1>';
            echo '<p>Registrering ender ' . date('Y-m-d H:m:s', $compo->getRegistrationEndTime()) . ' (<span class="compoTimer' . 0 . '"></span>)</p>';
            $timerList[] = array("id" => 0, "when" => $compo->getRegistrationEndTime());
        
            echo '<p>Compoen starter ' . date('Y-m-d H:m:s', $compo->getStartTime()) . ' (<span class="compoTimer' . 1 . '"></span>)</p>';
            $timerList[] = array("id" => 1, "when" => $compo->getStartTime());

            $participants = ClanHandler::getClansByCompo($compo);
            $qualified = 0;
            $notQualified = 0;
            $queued = 0;
            foreach($participants as $participant) {
                if($participant->isQualified($compo)) {
                    $qualified++;
                } else {
                    if(ClanHandler::isInQualificationQueue($participant)) {
                        $queued++;
                    } else {
                        $notQualified++;
                    }
                }
            }
            echo '<p>Deltagere: ' . $qualified . ($compo->getParticipantLimit() != 0 ? '/' . $compo->getParticipantLimit() : '') . ' (' . $qualified . ' kvalifiserte, ' . $queued . ' i kø, ' . $notQualified . ' uferdige)</p>';

            $pendingMatches = MatchHandler::getPendingMatchesByCompo($compo);
            $currentMatches = MatchHandler::getCurrentMatchesByCompo($compo);
            $finishedMatches = MatchHandler::getFinishedMatchesByCompo($compo);
            $totalCount = (count($pendingMatches) + count($currentMatches) + count($finishedMatches));

            echo '<p>Matcher: ' . $totalCount . ' (' . count($pendingMatches) . ' vendende matcher, ' . count($currentMatches) . ' nåværende matcher, ' . count($finishedMatches) . ' ferdige matcher)</p>';

            echo '<p>Tilkoblings-type: ';
            switch($compo->getConnectionType()) {
            case Compo::CONNECTION_TYPE_NONE:
                echo "<b>Ingen</b>";
                break;
            case Compo::CONNECTION_TYPE_SERVER:
                echo '<b>Server</b>';
                break;
            case Compo::CONNECTION_TYPE_CUSTOM:
                echo '<b>Egen(Håndtert av compo-plugin)</b>';
                break;
            }
            echo '</p>';
            echo '<h1>Plugin info</h1>';
            $pluginMeta = CompoPluginHandler::getPluginMetadata($compo->getPluginName());
            echo '<p>Internt navn: ' . $compo->getPluginName() . (CompoPluginHandler::pluginExists($compo->getPluginName()) ? '' : '<b>(finnes ikke)</b>') . '</p>';
            echo '<p>Fullt navn: ' . $pluginMeta["name"] . '</p>';
            echo '<p>Beskrivelse: <i>' . $pluginMeta["description"] . '</i></p>'; 
        } else {
            echo '<p>Compoen eksisterer ikke!</p>';
        }

	} else {
		echo '<p>Du har ikke rettigheter til dette!</p>';
	}
} else {
	echo '<p>Du er ikke logget inn!</p>';
}
?>
