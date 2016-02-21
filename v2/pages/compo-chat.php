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

	if ($user->hasPermission('compo.chat')) {
        $id = $_GET["id"];
        $compo = CompoHandler::getCompo($id);

        if($compo != null) {
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
            echo '<hr>';

	    echo '<script src="../api/scripts/websocket.js"></script>';
	    echo '<link rel="stylesheet" href="../api/styles/chat.css">';
            echo '<script src="../api/scripts/chat.js"></script>';
            echo '<script>Chat.init();</script>';
            echo '<h1>Hovedchat</h1>';
            echo '<div id="mainChat" class="compoChat"></div>';
            //Get current matches
            $currentMatches = MatchHandler::getCurrentMatchesByCompo($compo);
            if(count($currentMatches) > 0) {
                echo '<table>';
                $i = 0;
                echo '<tr>';
                while($i < count($currentMatches)) {
                    echo '<td>';
                    $participants = MatchHandler::getParticipantsJsonByMatch($currentMatches[$i]);
                    $first = true;
                    echo '<b>';
                    foreach($participants as $participant) {
                        if(!$first) {
                            echo " vs ";
                        }
                        if($participant["type"] != Settings::compo_match_participant_type_clan) {
                            echo '<i>' . $participant["value"] . '</i>';
                            $isReady = false;
                        } else {
                            echo $participant["value"];
                        }
                        $first = false;
                    }
                    echo '(' . $currentMatches[$i]->getId() . ')';
                    echo '</b>';
                    echo '<div id="compoChat' . $i . '" class="compoChat"></div></td>';
                    if($i%3==2) {
                        echo '</tr><tr>';
                    }
                    $i++;
                }
                echo '</tr>';
                echo '</table>';
            }

            echo '<script>$(document).ready(function(){Chat.bindChat("mainChat", ' . $compo->getChat()->getId() . ', 300);';
            $i = 0;
            while($i < count($currentMatches)) {
                echo 'Chat.bindChat("compoChat' . $i . '", ' . $currentMatches[$i]->getChat()->getId() . ', 300);';
                $i++;
            }
            echo '});</script>';
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
