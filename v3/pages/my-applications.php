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
require_once 'handlers/restrictedpagehandler.php';
require_once 'handlers/grouphandler.php';
require_once 'handlers/applicationhandler.php';
require_once 'utils/crewutils.php';
require_once 'page.php';

class ApplicationListPage extends Page {
    public function canAccess(User $user): bool {
        return !$user->isGroupMember();
    }
    public function isPublic(): bool {
        return false;
    }
	public function getTitle(): ?string {
		return 'Dine søknader';
	}

    public function getContent(User $user = null): string {
        $content = null;

        $applications = ApplicationHandler::getUserApplications($user);

            if(!empty($applications)) {
                $content = "";
                foreach ($applications as $application) {
                    $applicationCrew = $application->getGroup()->getTitle();
                    $applicationColor = "";
                    switch ($application->getState()) {
                        case ApplicationHandler::STATE_NEW:
                            $applicationColor = "box-primary";
                            break;
                        case ApplicationHandler::STATE_ACCEPTED:
                            $applicationColor = "box-success";
                            break;
                        case ApplicationHandler::STATE_REJECTED:
                            $applicationColor = "box-danger";
                            break;
                        case ApplicationHandler::STATE_CLOSED:
                            $applicationColor = "box-info";
                            break;
                        default:
                            $applicationColor = "box-warning";
                            break;
                    }
                    $applicationString = $application->getStateAsString();
                    $applicationContents = $application->getContent();
                    $content .= <<<EOD
<div class="col-md-6">
    <div class="box $applicationColor">
        <div class="box-header with-border">
          <h3 class="box-title">$applicationCrew</h3>
          <i> $applicationString </i>
        </div>
    <!-- /.box-header -->
    <!-- form start -->
        <div class="box-body">
            <p>$applicationContents</p>
EOD;
                    if($application->getComment() != null) {
                        $content .= "<label>Du har fått en kommentar fra saksbehandler</label><p><i>" . $application->getComment() . "</i></p>";
                    }
                    $content .= <<<EOD
        </div>
        <div class="box-footer">
            <button type="submit" class="btn btn-danger" onClick="deleteAvatar()">Slett søknad</button>
        </div>
    </div>
</div>
EOD;
                }
            } else {
                $content = <<<EOD
<div class="box box-warning">
<div class="box-header with-border">
  <h3 class="box-title">Ingen søknader</h3>
</div>
<!-- /.box-header -->
<!-- form start -->
    <div class="box-body">
        <p>Du har ikke søkt crew for dette året enda. Gå <a href="?page=new-application">hit</a> for å søke.</p>
    </div>
</div>
EOD;
            }

        
		return $content;
	}
}